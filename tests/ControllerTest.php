<?php
require_once('../settings.php');
require_once(ROOT_DIR.'/controllers/Controller.php');

class MockModel {
    var $error = 'mock error';
}

class ControllerTest extends PHPUnit_Framework_TestCase {
    var $filename = 'mocktemplate.php';
    var $data = array('foo'=>'world');
    
    protected function setUp() {
        parent::setUp();
        $this->template = new Template($this->filename, $this->data);
    }
    
    public function testMethods() {
        $methods = array('__construct',
                         'do_404',
                         'render_template',
                         'set_errors',);

        foreach ( $methods as $method ) {
            $this->assertTrue(method_exists('Controller', $method));
        }
    }
    
    public function testProperties() {
        $properties = array('template','model','data');
        foreach ( $properties as $property ) {
            $this->assertTrue(property_exists('Controller', $property));
        }
        
        $controller = new Controller();
        $this->assertFalse($controller->template);
        $this->assertFalse($controller->model);
        $this->assertEquals(array('error'=>''), $controller->data);
    }
    
    public function testDo404() {
        $controller = new Controller();
        ob_start();
        $controller->do_404();
        $rendered = ob_get_contents();
        ob_end_clean();
        
        $this->assertEquals('404.php', $controller->template);
        $this->assertEquals('error 404', $rendered);
    }
    
    public function testRenderTemplate() {
        $controller = new Controller();
        $controller->template = 'mocktemplate.php';
        $controller->data = array('foo'=>'test');
        
        ob_start();
        $controller->render_template();
        $rendered = ob_get_contents();
        ob_end_clean();
        $this->assertEquals('Hello, test!', $rendered);
    }
    
    public function testSetErrors() {
        $controller = new Controller();
        $controller->template = 'mocktemplate.php';
        $controller->data = array('foo'=>'test');
        $controller->model = new MockModel();
        
        ob_start();
        $controller->render_template();
        $rendered = ob_get_contents();
        ob_end_clean();
        
        $this->assertEquals('mock error', $controller->model->error);
    }
}
?>