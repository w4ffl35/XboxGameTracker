<?php
require_once('../settings.php');
require_once(ROOT_DIR.'/controllers/GameController.php');
require_once(ROOT_DIR.'/tests/mock_objects.php');

class MockModel {
    var $error = 'mock error';
    
    public function __construct() {
        $this->sc = new MockSoapClient();
    }
    
    public function get_wanted() {
        $games_wanted = array(
            new MockGame(array('id'=>124,
                  'title'=>'Out of This World',
                  'votes'=>22,
                  'status'=>'wantit',
                  'ip'=>'111.111.111.111',
                  'votetime'=>'2012-01-18 23:37:31',
                  'key'=>'fakekey')),
            new MockGame(array('id'=>125,
                  'title'=>'Flashback',
                  'votes'=>20,
                  'status'=>'wantit',
                  'ip'=>'111.111.111.111',
                  'votetime'=>'2012-01-17 23:37:31',
                  'key'=>'fakekey')),
            new MockGame(array('id'=>123,
                  'title'=>'Sonic the Hedgehog',
                  'votes'=>2,
                  'status'=>'wantit',
                  'ip'=>'111.111.111.111',
                  'votetime'=>'2012-01-19 23:37:31',
                  'key'=>'fakekey')),
        );
        return $games_wanted;
    }
    
    public function set_vote($id) {
        echo 'success';
    }
    
    public function set_got_it($id) {
        echo 'success';
    }
    
    public function get_owned() {
        $games_wanted = array(
            new MockGame(array('id'=>126,
                  'title'=>'Call of Duty',
                  'votes'=>40,
                  'status'=>'gotit',
                  'ip'=>'111.111.111.111',
                  'votetime'=>'2012-01-16 23:37:31',
                  'key'=>'fakekey')),
            new MockGame(array('id'=>126,
                  'title'=>'Skyrim',
                  'votes'=>200,
                  'status'=>'gotit',
                  'ip'=>'111.111.111.111',
                  'votetime'=>'2012-01-15 23:37:31',
                  'key'=>'fakekey')),
        );
        return $games_wanted;
    }
    
    public function add() {
        echo 'success';
    }
}

class ControllerTest extends PHPUnit_Framework_TestCase {
    var $filename = 'mocktemplate.php';
    var $data = array('foo'=>'world');
    
    protected function setUp() {
        parent::setUp();
        $this->template = new Template($this->filename, $this->data);
    }
    
    public function testMethods() {
        $methods = array('__construct',
                         'games_wanted',
                         'got_it',
                         'games_owned',
                         'add_game',);

        foreach ( $methods as $method ) {
            $this->assertTrue(method_exists('GameController', $method));
        }
    }
    
    public function testProperties() {
        $properties = array('template','model','data');
        foreach ( $properties as $property ) {
            $this->assertTrue(property_exists('Controller', $property));
        }
    }
    
    public function testGamesWanted() {
        $controller = new GameController();
        $controller->model = new MockModel();
        $controller->games_wanted();
        $this->assertEquals('games_wanted.php', $controller->template);
        $this->assertEquals('wanted', $controller->data['body_class']);
        $this->assertEquals(MockModel::get_wanted(), $controller->data['games']);
    }
    
    public function testGamesWantedAction() {
        $controller = new GameController();
        $controller->model = new MockModel();
        $_GET['action'] = 'vote';
        
        // Test without _GET['id']
        ob_start();
        $controller->games_wanted();
        $result = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('', $result);
        
        // Test with _GET['id']
        $_GET['id'] = 1;
        ob_start();
        $controller->games_wanted();
        $result = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('success', $result);
    }
    
    public function testGotIt() {
        $controller = new GameController();
        $controller->model = new MockModel();
        $controller->got_it();
        $this->assertEquals('games_gotit.php', $controller->template);
        $this->assertEquals('gotit', $controller->data['body_class']);
        $this->assertEquals(MockModel::get_wanted(), $controller->data['games']);
    }
    
    public function testGotItAction() {
        $controller = new GameController();
        $controller->model = new MockModel();
        $_GET['action'] = 'gotit';
        
        // Test without _GET['id']
        ob_start();
        $controller->got_it();
        $result = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('', $result);
        
        // Test with _GET['id']
        $_GET['id'] = 1;
        ob_start();
        $controller->got_it();
        $result = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('success', $result);
    }
    
    public function testGamesOwned() {
        $controller = new GameController();
        $controller->model = new MockModel();
        $controller->games_owned();
        $this->assertEquals('games_owned.php', $controller->template);
        $this->assertEquals('owned', $controller->data['body_class']);
        $this->assertEquals(MockModel::get_owned(), $controller->data['games']);
    }
    
    public function testAddGame() {
        $controller = new GameController();
        $controller->model = new MockModel();
        $controller->add_game();
        $this->assertEquals('games_add.php', $controller->template);
        $this->assertEquals('add', $controller->data['body_class']);
        
        $_POST['gametitle']='';
        $controller->add_game();
        $this->assertEquals('Sorry, but the title field cannot be blank.', $controller->data['error']);
        
        $_POST['gametitle']='foo';
        ob_start();
        $controller->add_game();
        $result = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('success', $result);
    }
}
?>