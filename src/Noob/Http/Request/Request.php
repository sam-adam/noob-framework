<?php

namespace Noob\Http\Request;

/**
 * Class Request
 * @package Noob\Http\Request
 */
class Request {
    /**
     * @const HTTP Request method constants
     */
    const OPTIONS   = 'OPTIONS';
    const GET       = 'GET';
    const HEAD      = 'HEAD';
    const POST      = 'POST';
    const PUT       = 'PUT';
    const DELETE    = 'DELETE';
    const TRACE     = 'TRACE';
    const CONNECT   = 'CONNECT';

    /** @var string */
    protected $method = Request::GET;

    /** @var string */
    protected $uri = null;

    /** @var array */
    protected $query = array();

    /**
     * Return the method of this request
     *
     * @return string
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * Set the method of this request
     *
     * @param string $method
     * @return Request $this
     * @throws \InvalidArgumentException
     */
    public function setMethod($method) {
        $method = strtoupper($method);

        if(!defined('static::'.$method)) {
            throw new \InvalidArgumentException('Invalid HTTP request method');
        }

        $this->method = $method;

        return $this;
    }

    public function getUri() {
        return $this->uri;
    }

    public function setUri($uri) {
        $this->uri = $uri;
    }

    public function getQuery() {
        return $this->query;
    }

    public function addQuery($key, $value) {
        if(!is_string($key)) {
            throw new \InvalidArgumentException('Invalid');
        }
    }
}