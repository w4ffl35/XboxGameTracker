<?php
require_once('../settings.php');
require_once(ROOT_DIR.'/utils/Sort.php');
require_once(ROOT_DIR.'/tests/mock_objects.php');

class TemplateTest extends PHPUnit_Framework_TestCase {
    protected function setUp() {
        parent::setUp();
        $this->games = array(
            new MockGame(array('id'=>124,
                  'title'=>'Out of This World',
                  'votes'=>22,
                  'status'=>'wantit',
                  'ip'=>'111.111.111.111',
                  'votetime'=>'2012-01-18 23:37:31',
                  'key'=>'fakekey')),
            new MockGame(array('id'=>125,
                  'title'=>'Flashback',
                  'votes'=>2,
                  'status'=>'wantit',
                  'ip'=>'111.111.111.111',
                  'votetime'=>'2012-01-17 23:37:31',
                  'key'=>'fakekey')),
            new MockGame(array('id'=>123,
                  'title'=>'Sonic the Hedgehog',
                  'votes'=>200,
                  'status'=>'wantit',
                  'ip'=>'111.111.111.111',
                  'votetime'=>'2012-01-19 23:37:31',
                  'key'=>'fakekey'))
        );
    }
    
    public function testMethods() {
        $methods = array('sort_by_vote',
                         'sort_by_name',);

        foreach ( $methods as $method ) {
            $this->assertTrue(method_exists('Sort', $method));
        }
    }
    
    public function testSortByVote() {
        $games = Sort::sort_by_vote($this->games);
        $this->assertEquals(200, $games[0]->votes);
        $this->assertEquals(22, $games[1]->votes);
        $this->assertEquals(2, $games[2]->votes);
    }
    
    public function testSortByName() {
        $games = Sort::sort_by_name($this->games);
        $this->assertEquals('Flashback', $games[0]->title);
        $this->assertEquals('Out of This World', $games[1]->title);
        $this->assertEquals('Sonic the Hedgehog', $games[2]->title);
    }
}
?>