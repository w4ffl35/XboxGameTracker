<?php
require_once('../settings.php');
require_once(ROOT_DIR.'/libraries/Template.php');

class TemplateTest extends PHPUnit_Framework_TestCase {
    var $filename = 'mocktemplate.php';
    var $data = array('foo'=>'world');
    
    protected function setUp() {
        parent::setUp();
        $this->template = new Template($this->filename, $this->data);
    }
    
    public function testMethods() {
        $methods = array('__construct',
                         'render',);

        foreach ( $methods as $method ) {
            $this->assertTrue(method_exists('Template', $method));
        }
    }
    
    public function testProperties() {
        $this->assertEquals($this->filename, $this->template->file);
        $this->assertEquals($this->data, $this->template->data);
    }
    
    public function testNoFile() {
        $this->template->file = '';
        $this->assertFalse($this->template->render());
    }
    
    public function testRender() {
        ob_start();
        $this->template->render();
        $rendered = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('Hello, world!', $rendered);
    }
}
?>