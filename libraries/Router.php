<?php
/*
Static class.
Handles all URL requests with dispatch method.
*/
class Router {
    var $urls;

    private function __construct() {}

    /*
    Handle url cases passed into dispatch.
    */
    public function dispatch($urls) {
        $requested_url = ( $_GET && isset($_GET['url']) ) ? $_GET['url'] : false;

        
        $controller = false;
        foreach ( $urls as $pattern=>$controller_method ) {
            if ( preg_match( $pattern, $requested_url ) ) {
                // Set controller and method if valid url pattern found.
                list($controller,$method) = explode('::', $controller_method);

                // Attempt to create a new controller
                try {
                    $controller = new $controller();
                }
                catch(Excption $e) {}

                break;
            }
        }
        
        // Choose method to display based on url passed.
        if ( $controller && method_exists($controller, $method) ) {
            $controller->$method();
            return $controller->render_template();
        }
        
        // If no method or class is found, will display a 404 template.
        $controller = new Controller();
        return $controller->do_404();
    }
}
?>