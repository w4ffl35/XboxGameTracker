<?php
require_once(ROOT_DIR.'/libraries/Template.php');

/*
Base controller class.
Extend this to create custom controllers.
*/
class Controller {
    var $template = false;
    var $model = false;
    var $data = array('error'=>'');

    public function __construct() {}
    
    /*
    Handles 404 view
    */
    public function do_404() {
        try{
            header('HTTP/1.0 404 Not Found');
        }
        catch(Exception $e) {}

        $this->template = '404.php';
        return $this->render_template();
    }
    
    /*
    Does template rendering using Template class.
    */
    public function render_template() {
        // Get errors from model (if any)
        $this->set_errors();

        // Create and render template set in controller method.
        $template = new Template($this->template, $this->data);
        return $template->render($template);
    }
    
    /*
    Add any model error to template variables for display.
    */
    private function set_errors() {
        if ( $this->model && $this->model->error ) {
            $this->data['error'] = $this->model->error;
        }
    }
}
?>