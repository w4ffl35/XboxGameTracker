<?php
include('../settings.php');
require_once(ROOT_DIR . '/libraries/Router.php');
require_once(ROOT_DIR . '/controllers/GameController.php');

// Set url patterns. example: "pattern" => "Controller::method"
$urls = array( '/^$/'=>'GameController::games_wanted',
               '/^gotit\/$/'=>'GameController::got_it',
               '/^add\/$/'=>'GameController::add_game',
               '/^owned\/$/'=>'GameController::games_owned' );

/*
Create a Router which will handle all url routing.
*/
Router::dispatch( $urls );
?>