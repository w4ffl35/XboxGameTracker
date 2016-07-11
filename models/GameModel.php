<?php
/*
Communicates with SOAP server to pull wanted and owned games,
set votes, add new games to the wanted list and set games as owned.
*/
class GameModel {
    var $api_key = '463a02b00aaaab2bac9d516343ff598f';
    var $wsdl = 'http://xbox.sierrabravo.net/v2/xbox.wsdl';
    var $error = false;
    var $sc = false;

    /*
    Creates a SOAP client for use throughout the this class.
    */
    public function __construct() {
        // Set timestamp for use in overriding date functions in unit tests.
        $this->now = time();
        $this->current_ip = ($_SERVER && isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : false;

        // Set up soap client
        try {
            $this->sc = new SoapClient($this->wsdl);
        }
        catch( Exception $e ) {
            $this->error = $e->getMessage();
        }
    }
    
    /*
    Get all games.
    */
    public function get() {
        return $this->request('getGames');
    }
    
    /*
    Get wanted games. Consumes get() and filters out acquired games.
    */
    public function get_wanted() {
        $wanted_games = array();
        if ( $games = $this->get() ) {
            foreach ( $games as $game ) {
                if ( $game->status == 'wantit' ) {
                    array_push($wanted_games, $game);
                }
            }
            return $wanted_games;
        }
        return false;
    }
    
    /*
    Get acquired games. Consumes get() and filters out wanted games.
    */
    public function get_owned() {
        $games_owned = array();
        if ( $games = $this->get() ) {
            foreach ( $games as $game ) {
                if ( $game->status == 'gotit' ) {
                    array_push($games_owned, $game);
                }
            }
            return $games_owned;
        }
        return false;
    }
    
    /*
    Adds a game title.
    */
    public function add($game_title, $time_added) {
        $game_title = trim($game_title);

        if ( $this->valid_date() && $this->valid_action($time_added) && $this->valid_title($game_title) ) {
            if ( ! $this->request('addGame', $game_title) ) { // Add title failed. Set error.
                $this->error = 'Sorry, I was unable to add the game title you chose.';
                return false;
            }
            return true;
        }
        return false;
    }
    
    /*
    Sets a vote on a game title.
    */
    public function set_vote($id, $time_added) {
        if ( $this->valid_id($id) && $this->valid_date() && $this->valid_action($time_added) ) {
            if ( ! $this->request('addVote', $id) ) { // Add vote failed. Set error.
                $this->error = 'Sorry, I was unable to add your vote.';
                return false;
            }
            return true;
        }
        return false;
    }
    
    /*
    Marks game title as acquired.
    */
    public function set_got_it($id) {
        $id = intval($id);
        if ( $this->valid_id($id) ) {
            if ( ! $this->request('setGotIt', $id) ) { // Set acquired failed. Set error.
                $this->error = 'Sorry, I was unable to add that game to our collection.';
                return false;
            }
            return true;
        }
        return false;
    }
    
    /*
    Clears all game titles.
    */
    public function clear_all() {
        if ( ! $this->request('clearGames') ) {
            $this->error = 'I was unable to clear game titles and votes.';
            return false;
        }
        return true;
    }
    
    /*
    Validates title being added
    */
    private function valid_title($title) {
        if ( $title == '' ) {
            $this->error = 'Game title cannot be blank.';
            return false;
        }

        $games = $this->get();
        foreach ( $games as $game ) {
            // compare titles with same case
            if ( strtolower($game->title) == strtolower($title) ) {
                $this->error = 'Duplicate titles are not allowed.';
                return false;
            }
        }
        return true;
    }
    
    /*
    Validates id being used to vote
    */
    private function valid_id($id) {
        $id = intval( $id );
        if ( ! $id ) {
            $this->error = 'Sorry, id is required for this action.';
            return false;
        }
        return true;
    }
    
    /*
    Performs checks to restrict users from voting / adding games on the weekend
    */
    private function valid_date() {
        if ( date('N', $this->now) > 5 ) {
            $this->error = 'Sorry, no votes or game adding allowed on the weekend';
            return false;
        }
        return true;
    }

    /*
    Performs checks to restrict users from adding more than one vote or game per day.
    */
    private function valid_action($time) {
        if ( date('z-Y', $this->now) == date('z-Y', $time) ) {
            $this->error = 'Sorry, only one vote OR game addition per day.';
            return false;
        }
        return true;
    }
    
    /*
    Runs a soap request. Passes api_key. Passes value (id or title).
    */
    private function request($request, $value=false) {
        if ( $this->sc ) {
            try {
                return $this->sc->$request($this->api_key, $value);
            }
            catch( Exception $e ) {
                $this->error = $e->getMessage();
            }
        }
        return false;
    }
}
?>