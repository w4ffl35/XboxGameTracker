<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title>Nerdery Xbox 360 Wish List</title>
        <link rel="stylesheet" href="<?= STATIC_DIR ?>css/xboxgames.css" type="text/css" media="screen" />
    </head>
    <body<?php if ($body_class) {?> class="<?= $body_class ?>"<?php } ?>>
        <div id="content">
            <h1><a href="/">Nerdery Xbox 360 Wish List</a></h1>
            <ul class="main_nav">
                <li class="wanted"><a href="/">Wanted</a></li>                
                <li class="gotit"><a href="/gotit/">Acquire games</a></li>
                <li class="add"><a href="/add/">Add game</a></li>
                <li class="owned"><a href="/owned/">Owned</a></li>
            </ul>
            <?php
            if ( isset($error) && $error ) {
            ?>
                <p class="error"><?= $error ?></p>
            <?php
            }
            ?>
