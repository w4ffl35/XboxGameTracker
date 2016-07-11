<?php
require_once('../settings.php');
require_once(ROOT_DIR.'/models/GameModel.php');
require_once(ROOT_DIR.'/tests/mock_objects.php');

class GameModelTest extends PHPUnit_Framework_TestCase {
    protected function setUp() {
        parent::setUp();
        $this->model = new \GameModel();
        $this->model->sc = new MockSoapClient();
    }
    
    public function testMethods() {
        $methods = array('__construct',
                         'get',
                         'get_wanted',
                         'get_owned',
                         'add',
                         'set_vote',
                         'set_got_it',
                         'clear_all',
                         'valid_title',
                         'valid_id',
                         'valid_date',
                         'valid_action',
                         'request',);

        foreach ( $methods as $method ) {
            $this->assertTrue(method_exists($this->model, $method));
        }
    }
    
    public function testDefaultParameters() {
        // Model params are as expected
        $this->assertEquals('463a02b00aaaab2bac9d516343ff598f', $this->model->api_key);
        $this->assertEquals('http://xbox.sierrabravo.net/v2/xbox.wsdl', $this->model->wsdl);
    }
    
    public function testGet() {
        $games = $this->model->get();

        // get() returns expected results
        $this->assertTrue(is_array($games));
    }
    
    public function testGetWanted() {
        $games = $this->model->get_wanted();
        
        // get_wanted() returns results with "wantit" status only
        foreach ( $games as $game ) {
            $this->assertEquals('wantit', $game->status);
        }
        
        // If get() returns no results, get_wanted() should return false.
        $this->model->sc->clear_results();
        $this->assertFalse($this->model->get_wanted());
        
        echo $this->model->error;
    }
    
    public function testGetOwned() {
        $games = $this->model->get_owned();
        
        // get_owned() returns results with "gotit" status only
        foreach ( $games as $game ) {
            $this->assertEquals('gotit', $game->status);
        }
        
        // If get() returns no results, get_owned() should return false.
        $this->model->sc->clear_results();
        $this->assertFalse($this->model->get_owned());
    }
    
    public function testDuplicateAdd() {
        // Check that title is not duplicated
        $this->model->now = strtotime('2012-01-18 00:00:00');
        $this->assertFalse($this->model->add('Skyrim', strtotime('2012-01-17 00:00:01')));
        $this->assertEquals($this->model->error, 'Duplicate titles are not allowed.');
        
        $this->assertFalse($this->model->add('skyrim', strtotime('2012-01-17 00:00:01')));
        $this->assertEquals($this->model->error, 'Duplicate titles are not allowed.');
    }
    
    public function testAdd() {
        // Add game works (returns true)
        $this->model->now = strtotime('2012-01-18 00:00:00');
        $this->assertTrue($this->model->add('foo', strtotime('2012-01-17 00:00:01')));
        
        // Add game works when no cookie date is passed(returns true)
        $this->model->now = strtotime('2012-01-18 00:00:00');
        $this->assertTrue($this->model->add('foo', false));
        
        // Add game fails when title is not set (returns false)
        $this->assertFalse($this->model->add('', false));
        $this->assertEquals($this->model->error, 'Game title cannot be blank.');

        // Add game fails when weekend (returns false)
        $this->model->now = strtotime('2012-01-22 00:00:00');
        $this->assertFalse($this->model->add('foo', false));
        $this->assertEquals($this->model->error, 'Sorry, no votes or game adding allowed on the weekend');
        
        // Add game fails when user has already added or voted.
        $this->model->now = strtotime('2012-01-19 23:37:26');
        $this->assertFalse($this->model->add('foo', strtotime('2012-01-19 10:10:10')));
        $this->assertEquals($this->model->error, 'Sorry, only one vote OR game addition per day.');
        
        // Check error message when add fails at server level
        $this->model->sc->boolean_result = false;
        $this->model->now = strtotime('2012-01-18 00:00:00');
        $this->model->add('foo', false);
        $this->assertEquals($this->model->error, 'Sorry, I was unable to add the game title you chose.');
    }
    
    public function testSetVote() {
        // Voting works (return true)
        $this->model->now = strtotime('2012-01-18 00:00:00');
        $this->assertTrue($this->model->set_vote(1, strtotime('2012-01-14 10:10:10')));
        
        // Voting works when no cookie date is passed (return true)
        $this->model->now = strtotime('2012-01-18 00:00:00');
        $this->assertTrue($this->model->set_vote(1, false));
        
        // Voting fails when id is not set
        $this->assertFalse($this->model->set_vote(0, false));
        $this->assertEquals($this->model->error, 'Sorry, id is required for this action.');
        
        // Voting fails when weekend
        $this->model->now = strtotime('2012-01-22 00:00:00');
        $this->assertFalse($this->model->set_vote(1, false));
        $this->assertEquals($this->model->error, 'Sorry, no votes or game adding allowed on the weekend');
        
        // Voting fails when user has already added or voted.
        $this->model->now = 1327367103;
        $this->assertFalse($this->model->set_vote(1, 1327366508));#strtotime('2012-01-19 10:10:10')));
        $this->assertEquals($this->model->error, 'Sorry, only one vote OR game addition per day.');
        
        // Check error message when add fails at server level
        $this->model->sc->boolean_result = false;
        $this->model->now = strtotime('2012-01-18 00:00:00');
        $this->model->set_vote(1, false);
        $this->assertEquals($this->model->error, 'Sorry, I was unable to add your vote.');
    }
    
    public function testSetGotIt() {
        // Set got it works
        $this->assertTrue($this->model->set_got_it(1));
        
        // Set got it fails when id is not set
        $this->assertFalse($this->model->set_got_it(0));
        
        // Check error message when set got it fails at server level
        $this->model->sc->boolean_result = false;
        $this->model->set_got_it(1);
        $this->assertEquals($this->model->error, 'Sorry, I was unable to add that game to our collection.');
    }
    
    public function testClearAll() {
        // Clear all works
        $this->assertTrue($this->model->clear_all());
        
        // Check error message when clear all fails at server level
        $this->model->sc->boolean_result = false;
        $this->model->clear_all();
        $this->assertEquals($this->model->error, 'I was unable to clear game titles and votes.');
    }
}
?>