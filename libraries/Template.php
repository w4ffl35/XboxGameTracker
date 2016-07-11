<?php
/*
Basic template class used.
Used to serve a template file (expects these to be stored in folder "views").
Pass it a filename (foo.php) and an optional array set of key=>value pairs ($data)
to be used in the view as variables (ie: foo=>bar will make $foo accessible in view)
*/
class Template {
    var $file = false;
    var $data = false;

    /*
    Requires filename string on creation.
    Accepts optional data array which is used to pass variables to the template.
    */
    public function __construct($file, $data = false) {
        $this->file = $file;
        $this->data = $data;
    }
    
    /*
    Extracts variables from data array and displays template.
    */
    public function render() {
        if ( $this->file && $this->file != '') {
            if ( is_array($this->data) ) {
                extract($this->data);
            }
            return include( 'views/' . $this->file );
        }
        return false;
    }
}
?>