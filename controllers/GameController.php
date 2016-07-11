<?php
require_once(ROOT_DIR.'/controllers/Controller.php');
require_once(ROOT_DIR.'/utils/Sort.php');
require_once(ROOT_DIR.'/libraries/Cookie.php');
require_once(ROOT_DIR.'/models/GameModel.php');

/*
Custom controller for game app.
Displays wanted, owned and add views.
*/
class GameController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->model = new GameModel();
    }
    
    /*
    Gets games wanted from model and displays in template.
    */
    public function games_wanted() {
        $this->template = 'games_wanted.php';
        $this->data['body_class'] = 'wanted';
        
        // Do vote action if requested.
        if ( $_GET && isset($_GET['action']) && $_GET['action'] == 'vote' ) {
            // No errors, action was successful. Forward user to home to avoid refreshing on action.
            if ( isset($_GET['id']) && $this->model->set_vote($_GET['id'], Cookie::get('xbox_voter')) ) {
                try {
                    Cookie::set('xbox_voter',time(),time()+3600*24*30);
                    header('Location:/');
                    exit;
                }
                catch( Exception $e) {}
            }
        }

        if ( $this->data['games'] = $this->model->get_wanted() ) {
            $this->data['games']  = Sort::sort_by_vote($this->data['games']); // Sort games by vote descending.
        }
    }
    
    /*
    Gets games wanted from model and displays list along with "got it" controls.
    */
    public function got_it() {
        $this->template = 'games_gotit.php';
        $this->data['body_class'] = 'gotit';
        
        // Do gotit action if requested.
        if ( $_GET && isset($_GET['action']) && $_GET['action'] == 'gotit' ) {
            if ( isset( $_GET['id']) && $this->model->set_got_it($_GET['id']) ) {
                try {
                    header('Location:/');
                    exit;
                }
                catch( Exception $e ) {}
            }
        }
        
        if ( $this->data['games'] = $this->model->get_wanted() ) {
            $this->data['games'] = sort::sort_by_vote($this->data['games']); // Sort games by vote descending.
        }
    }

    /*
    Gets games owned from model and displays in template.
    */
    public function games_owned() {
        $this->template = 'games_owned.php';
        $this->data['body_class'] = 'owned';
        if ( $this->data['games'] = $this->model->get_owned() ) {
            $this->data['games'] = Sort::sort_by_name($this->data['games']); // Sort games alphabetically
        }
    }
    
    /*
    Displays form which allows user to add game titles.
    Adds game title if POST with gametitle is set.
    */
    public function add_game() {
        $this->template = 'games_add.php';
        $this->data['body_class'] = 'add';

        if ( $_POST && isset($_POST['gametitle'] ) ) {
            $game_title = $_POST['gametitle'];

            if ( $game_title == '') {
                $this->data['error'] = 'Sorry, but the title field cannot be blank.';
            }
            // If save was successful, forward user to homepage.
            elseif ( $this->model->add($game_title, Cookie::get('xbox_voter')) ) {
                try {
                    Cookie::set('xbox_voter',time(),time()+3600*24*30);
                    header('Location:/');
                    exit;
                }
                catch(Exception $e) {}
            }
        }
    }
}
?>