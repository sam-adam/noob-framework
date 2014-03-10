<?php

use \Noob\Http\Request\Request;

class RequestTest extends PHPUnit_Framework_TestCase {
    public function testCanConstruct() {
        $request = new Request();

        $this->assertNotNull($request);
    }
} 