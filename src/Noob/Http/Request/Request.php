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

    /** @var  httpVersion */
    protected $httpVersion = 'HTTP/1.1';

    /** @var string */
    protected $method = Request::GET;

    /** @var string */
    protected $uri = '/';

    /** @var ParameterCollection ($_GET) */
    protected $queryCollection;

    /** @var ParameterCollection ($_POST) */
    protected $postCollection;

    /** @var FileCollection ($_FILES) */
    protected $fileCollection;

    /** @var  ParameterCollection ($_COOKIE) */
    protected $cookieCollection;

    /** @var ParameterCollection ($_SERVER) */
    protected $serverCollection;

    /** @var  HeaderCollection taken from $_SERVER */
    protected $headerCollection;

    /** @var  body */
    protected $body = [];

    public function __construct(
        array $query = [],
        array $post = [],
        array $cookie = [],
        array $files = [],
        array $server = []) {
        $this->queryCollection = new ParameterCollection($query);
        $this->postCollection = new ParameterCollection($post);
        $this->cookieCollection = new ParameterCollection($cookie);
        $this->fileCollection = new FileCollection($files);
        $this->serverCollection = new ServerCollection($server);
        $this->headerCollection = new HeaderCollection($this->serverCollection->getHeaders());

        $this->method = $this->serverCollection['REQUEST_METHOD'];
        $this->uri = $this->serverCollection['REQUEST_URI'];

        if ($this->method === Request::POST
            ||
            $this->method === Request::PUT) {
            parse_str(file_get_contents('php://input'), $body);
        }
    }

    /**
     * Return the method of this request
     * or
     * Method from setMethod
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

    /**
     * @return string An uri string
     */
    public function getUri() {
        return $this->uri;
    }

    /**
     * @param $uri Set uri string
     */
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
        if($this->queryCollection === null) {
            $this->queryCollection = new ParameterCollection();
        }

        if($key === null) {
            return $this->queryCollection;
        }

        return $this->queryCollection->get($key, $default);
    }

    /**
     * Set the whole query collection for this request
     *
     * @param ParameterCollection $query
     * @return Request
     */
    public function setQuery(ParameterCollection $query) {
        $this->queryCollection = $query;

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

    /**
     * @return Host
     */
    public function getHost() {
        return $this->headerCollection['Host'];
    }

    /**
     * @return UserAgent
     */
    public function getUserAgent() {
        return $this->headerCollection['User-Agent'];
    }

    /**
     * @return REMOTE_ADDR
     */
    public function getRemoteAddr() {
        return $this->serverCollection['REMOTE_ADDR'];
    }

    /**
     * @return bool Check if the request is ajax
     */
    public function isAjax() {
        return
            isset($this->serverCollection['HTTP_X_REQUEST_WITH'])
            &&
            strtolower($this->serverCollection['HTTP_X_REQUEST_WITH']) === 'xmlhttprequest';
    }


}