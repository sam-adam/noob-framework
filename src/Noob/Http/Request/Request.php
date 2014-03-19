<?php

namespace Noob\Http\Request;

use Noob\Core\Exception\InvalidArgumentTypeException;
use Noob\Http\Request\Collection\ParameterCollection;
use Noob\Http\Request\Collection\HeaderCollection;
use Noob\Http\Request\Collection\FileCollection;
use Noob\Http\Request\Collection\ServerCollection;

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
    protected $method;

    /** @var string */
    protected $uri;

    /** @var  httpVersion */
    protected $httpVersion = 'HTTP/1.1';

    /** @var ParameterCollection ($_GET) */
    protected $queryCollection = [];

    /** @var ParameterCollection ($_POST) */
    protected $postCollection = [];

    /** @var FileCollection ($_FILES) */
    protected $fileCollection = [];

    /** @var  ParameterCollection ($_COOKIE) */
    protected $cookieCollection = [];

    /** @var ParameterCollection ($_SERVER) */
    protected $serverCollection = [];

    /** @var  HeaderCollection taken from $_SERVER */
    protected $headerCollection = [];

    /** @var  body */
    protected $body = null;

    public function __construct(
        $method,
        $uri,
        $httpVersion = 'HTTP/1.1',
        array $query = [],
        array $post = [],
        array $cookie = [],
        array $files = [],
        array $server = []) {
        $this->httpVersion = $httpVersion;
        $this->method = $method;
        $this->uri = $uri;

        $this->queryCollection = new ParameterCollection($query);
        $this->postCollection = new ParameterCollection($post);
        $this->cookieCollection = new ParameterCollection($cookie);
        $this->fileCollection = new FileCollection($files);
        $this->serverCollection = new ServerCollection($server);
        $this->headerCollection = new HeaderCollection($this->serverCollection->getHeaders());

        if ($this->getMethod() === Request::POST
            ||
            $this->getMethod() === Request::PUT) {
            $this->body = file_get_contents('php://input');
        }
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

    /**
     * @param $uri Set uri string
     */
    public function setUri($uri) {
        $this->uri = $uri;
    }

    /**
     * @return string An uri string
     */
    public function getUri() {
        return $this->uri;
    }

    public function setVersion($httpVersion) {
        $this->httpVersion = $httpVersion;
    }

    public function getVersion() {
        return $this->httpVersion;
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
     * Get query for this request
     *
     * @return ParameterCollection
     */
    public function getQuery() {
        return $this->queryCollection;
    }

    /**
     * Get Header for this request
     *
     * @return HeaderCollection
     */
    public function getHeader() {
        return $this->headerCollection;
    }

    /**
     * Get Post for this request
     *
     * @return ParameterCollection
     */
    public function getPost() {
        return $this->postCollection;
    }

    /**
     * Get Cookie for this request
     *
     * @return ParameterCollection
     */
    public function getCookie() {
        return $this->cookieCollection;
    }

    /**
     * Get File for this request
     *
     * @return FileCollection
     */
    public function getFile() {
        return $this->fileCollection;
    }

    /**
     * Get Body for this request
     *
     * @return string
     */
    public function getBody() {
        return $this->body;
    }

    /**
     * Set Body for this request
     *
     * @param string
     */
    public function setBody($body) {
        if(is_string($body)) {
            $this->body = $body;
        }
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

    /**
     * Create request from global variable
     *
     * @return Request An Request created from globals variable
     */
    public static function createFromGlobals() {
        $httpVersion = 'HTTP/1.1';
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = '/';

        return new Request(
            $method,
            $uri,
            $httpVersion,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES,
            $_SERVER
        );
    }

    public static function createFromString($stringRequest) {
        $requestCore = explode("\r\n", $stringRequest);

        $matches = null;
        $methods = implode('|', [
            Request::OPTIONS,
            Request::HEAD,
            Request::GET,
            Request::POST,
            Request::PUT,
            Request::DELETE,
            Request::TRACE,
            Request::CONNECT,
        ]);

        /**
         * Make sure first line is a valid string
         */
        $pattern = '#^(?P<method>'.$methods.')\s(?P<uri>[^ ]*)(?:\sHTTP\/(?P<version>\d+\.\d+){0,1})#';
        $firstLine = array_shift($requestCore);
        if(count($requestCore) === 0 || !preg_match($pattern, $firstLine, $matches)) {
            throw new \InvalidArgumentException('A valid request line was not found in the provided string');
        }

        $request = new Request($matches['method'], $matches['uri']);

        if(isset($matches['version'])) {
            $request->setVersion($matches['version']);
        }

        /**
         * Extract request lines
         * separate between header and body
         */
        $isHeader = true;
        $rawHeader = $rawBody = null;
        while($requestCore) {
            $nextLine = ltrim(rtrim(array_shift($requestCore)));

            if($nextLine === '') {
                $isHeader = false;
                continue;
            }

            $isHeader ? ($rawHeader = $nextLine) : ($rawBody = $nextLine);
        }

        if($rawHeader) {
            $headers = [];

            $rawHeader = preg_replace("/\r|\n/", "\r\n", $rawHeader);
            $headerList = explode("\r\n", $rawHeader);
            foreach($headerList as $list) {
                $exploded = explode(':', $list);
                $headers[$exploded[0]] = $exploded[1];
            }

            $request->getHeader()->addParameters($headers);
        }

        if($rawBody) {
            $request->setBody($rawBody);
        }

        return $request;
    }
}