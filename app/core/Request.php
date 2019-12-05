<?php
namespace App\core;
class Request{
    protected $server = [];
    protected $uri;
    protected $queryParams;
    protected $postParams;
    protected $method;
    protected $hearder = [];
    protected $body;
    protected $swooleRequest;

    /**
     * @return array
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @param array $server
     */
    public function setServer($server)
    {
        $this->server = $server;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param mixed $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return mixed
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * @param mixed $queryParams
     */
    public function setQueryParams($queryParams)
    {
        $this->queryParams = $queryParams;
    }

    /**
     * @return mixed
     */
    public function getPostParams()
    {
        return $this->postParams;
    }

    /**
     * @param mixed $postParams
     */
    public function setPostParams($postParams)
    {
        $this->postParams = $postParams;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return array
     */
    public function getHearder()
    {
        return $this->hearder;
    }

    /**
     * @param array $hearder
     */
    public function setHearder($hearder)
    {
        $this->hearder = $hearder;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getSwooleRequest()
    {
        return $this->swooleRequest;
    }

    /**
     * @param mixed $swooleRequest
     */
    public function setSwooleRequest($swooleRequest)
    {
        $this->swooleRequest = $swooleRequest;
    }

    /**
     * Request constructor.
     * @param array $server
     * @param $uri
     * @param $queryParams
     * @param $postParams
     * @param $method
     * @param array $hearder
     * @param $body
     */
    public function __construct(array $server, $uri, $queryParams, $postParams, $method, array $hearder, $body)
    {
        $this->server = $server;
        $this->uri = $uri;
        $this->queryParams = $queryParams;
        $this->postParams = $postParams;
        $this->method = $method;
        $this->hearder = $hearder;
        $this->body = $body;
    }

    public static function init(\Swoole\Http\Request $swooleRequest){
        $server = $swooleRequest->server;
        $method = $swooleRequest->server['request_method'] ?? 'GET';
        $uri =  $server['request_uri'];
        $body = $swooleRequest->rawContent();
        $request = new self($server, $uri, $swooleRequest->get,$swooleRequest->post,$method,$swooleRequest->header, $body);
        $request->swooleRequest = $swooleRequest;
        return $request;
    }

}