<?php

namespace Noob\Http\Request;
use Noob\Core\InvalidArgumentTypeException;

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
    protected $uri = '/';

    /** @var ParameterCollection */
    protected $queryParams;

    /** @var ParameterCollection */
    protected $postParams;

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

    /**
     * Get the query for this request
     *
     * Return ParameterCollection if key is not defined,
     * otherwise return the item with defined key
     *
     * @param mixed $key
     * @param mixed $default
     * @return mixed|ParameterCollection
     */
    public function getQuery($key = null, $default = null) {
        if($this->queryParams === null) {
            $this->queryParams = new ParameterCollection();
        }

        if($key === null) {
            return $this->queryParams;
        }

        return $this->queryParams->get($key, $default);
    }

    /**
     * Set the whole query collection for this request
     *
     * @param ParameterCollection $query
     * @return Request
     */
    public function setQuery(ParameterCollection $query) {
        $this->queryParams = $query;

        return $this;
    }

    public function addQuery($key, $value) {
        if(!is_string($key)) {
            throw new InvalidArgumentTypeException('string', $key);
        }

        if(!is_array($value)) {
            throw new InvalidArgumentTypeException('array', $value);
        }

        return $this;
    }
}