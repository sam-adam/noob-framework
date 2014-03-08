<?php

namespace Noob\Http\Request;

use Noob\Core\Exception\InvalidArgumentTypeException;

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

    /** @var ParameterCollection ($_GET) */
    protected $queryCollection;

    /** @var ParameterCollection ($_POST) */
    protected $postCollection;

    /** @var FileCollection ($_FILES) */
    protected $fileCollection = [];

    /** @var  ParameterCollection ($_COOKIE) */
    protected $cookieCollection = [];

    /** @var ParameterCollection ($_SERVER) */
    protected $serverCollection = [];

    /** @var  HeaderCollection taken from $_SERVER */
    protected $headerCollection = [];

    /** @var  httpVersion */
    protected $httpVersion = 'HTTP/1.1';

    /** @var  content */
    protected $content = null;

    public function __construct(
        array $queryParams = [],
        array $postParams = [],
        array $cookieParams = [],
        array $fileParams = [],
        array $serverParams = [],
        $content = null) {
        $this->queryCollection = new ParameterCollection($queryParams);
        $this->postCollection = new ParameterCollection($postParams);
        $this->cookieCollection = new ParameterCollection($cookieParams);
        $this->fileCollection = new FileCollection($fileParams);
        $this->serverCollection = new ServerCollection($serverParams);
        $this->headerCollection = new HeaderCollection($this->serverCollection->getHeaders());
        $this->content = $content;
    }

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

    /**
     * Add a new query item to the request
     *
     * @param string $key
     * @param array $value
     * @return Request
     * @throws \Noob\Core\Exception\InvalidArgumentTypeException
     */
    public function addQuery($key, array $value) {
        if(!is_string($key)) {
            throw new InvalidArgumentTypeException('string', $key);
        }

        if(!is_array($value)) {
            throw new InvalidArgumentTypeException('array', $value);
        }

        return $this;
    }
}