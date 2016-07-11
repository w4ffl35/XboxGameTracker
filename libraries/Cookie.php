<?php
/*
Wrapper class for cookie function. Allows easy unit testing.
*/
class Cookie {
    /*
    Set a cookie.
    */
    public function set($name, $value='', $expire=false, $path='/', $domain='', $secure=false, $httponly=false) {
        return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }
    
    /*
    Get a cookie.
    */
    public function get($name) {
        if ( $_COOKIE && isset($_COOKIE[$name]) ) {
            return $_COOKIE[$name];
        }
        return false;
    }
}
?>