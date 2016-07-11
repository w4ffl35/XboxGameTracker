<?php
require_once('../settings.php');
require_once(ROOT_DIR.'/libraries/Cookie.php');

global $_COOKIE;
class MockCookie extends Cookie {
    public function set($name, $value='', $expire=false, $path='', $domain='', $secure=false, $httponly=false) {
        $_COOKIE[$name]=array('value'=>$value,
                              'expire'=>$expire);
        return true;
    }
}

class CookieTest extends PHPUnit_Framework_TestCase {
    protected function setUp() {
        parent::setUp();
        $this->cookie = new MockCookie();
    }
    
    public function testMethods() {
        $methods = array('set',
                         'get',);

        foreach ( $methods as $method ) {
            $this->assertTrue(method_exists('Cookie', $method));
        }
    }
    
    public function testSetGetCookie() {
        $cookie_name = 'fakecookie';
        $cookie_value = 'foo';
        $cookie_expire = time() + 3600;
        $this->cookie->set($cookie_name, $cookie_value, $cookie_expire);
        $cookie = $this->cookie->get($cookie_name);
        $this->assertEquals($cookie['value'], $cookie_value);
        $this->assertEquals($cookie['expire'], $cookie_expire);
    }
}
?>