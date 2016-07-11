<?php
/*
Run this file from command line to
clear all games and votes.
*/
require_once('models/GameModel.php');
$m = new GameModel();
if ( $m->clear_all() ) {
    echo "All games and votes now cleared.\n";
}
else {
    echo $m->error . "\n";
}
?>