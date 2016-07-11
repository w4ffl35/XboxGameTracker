<?php
class MockGame {
    var $id;
    var $title;
    var $votes;
    var $status;
    var $ip;
    var $votetime;
    var $key;
    
    public function __construct($data) {
        foreach ( $data as $key=>$val) {
            $this->$key = $val;
        }
    }
}

class MockSoapClient {
    var $results = false;
    var $boolean_result = true;
    
    public function __construct() {
        $this->set_results();
    }
    
    public function getGames() {
        return $this->results;
    }
    
    public function addGame() {
        return $this->boolean_result;
    }
    
    public function addVote() {
        return $this->boolean_result;
    }
    
    public function setGotIt() {
        return $this->boolean_result;
    }
    
    public function clearGames() {
        return $this->boolean_result;
    }
    
    public function set_results() {
        $data = array(
            array('id'=>123,
                  'title'=>'Sonic the Hedgehog',
                  'votes'=>2,
                  'status'=>'wantit',
                  'ip'=>'111.111.111.111',
                  'votetime'=>'2012-01-19 23:37:31',
                  'key'=>'fakekey'),
            array('id'=>124,
                  'title'=>'Out of This World',
                  'votes'=>22,
                  'status'=>'wantit',
                  'ip'=>'111.111.111.111',
                  'votetime'=>'2012-01-18 23:37:31',
                  'key'=>'fakekey'),
            array('id'=>125,
                  'title'=>'Flashback',
                  'votes'=>20,
                  'status'=>'wantit',
                  'ip'=>'111.111.111.111',
                  'votetime'=>'2012-01-17 23:37:31',
                  'key'=>'fakekey'),
            array('id'=>126,
                  'title'=>'Call of Duty',
                  'votes'=>40,
                  'status'=>'gotit',
                  'ip'=>'111.111.111.111',
                  'votetime'=>'2012-01-16 23:37:31',
                  'key'=>'fakekey'),
            array('id'=>126,
                  'title'=>'Skyrim',
                  'votes'=>200,
                  'status'=>'gotit',
                  'ip'=>'111.111.111.111',
                  'votetime'=>'2012-01-15 23:37:31',
                  'key'=>'fakekey'),
        );
        $this->results = array();
        foreach ( $data as $d ) {
            $this->results[] = new MockGame($d);
        }
    }
    
    public function clear_results() {
        $this->results = false;
    }
}
?>