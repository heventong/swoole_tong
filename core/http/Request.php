<?php
namespace  Core\http;
class Request{
    protected $server = [];
    protected $uri;

    /**
     * @return array
     */
    public function getServer(): array
    {
        return $this->server;
    }

    /**
     * @param array $server
     */
    public function setServer(array $server)
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
    public function getHeader(): array
    {
        return $this->header;
    }

    /**
     * @param array $header
     */
    public function setHeader(array $header)
    {
        $this->header = $header;
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
    protected $queryParams;
    protected $postParams;
    protected $method;
    protected $header=[];
    protected $body;
    protected $swooleRequest;

    /**
     * Request constructor.
     * @param array $server
     * @param $uri
     * @param $queryParams
     * @param $postParams
     * @param $method
     * @param array $header
     * @param $body
     */
    public function __construct(array $server, $uri, $queryParams, $postParams, $method, array $header, $body)
    {
        $this->server = $server;
        $this->uri = $uri;
        $this->queryParams = $queryParams;
        $this->postParams = $postParams;
        $this->method = $method;
        $this->header = $header;
        $this->body = $body;
    }
    public static function init(\Swoole\Http\Request $swooleRequest){

        $server = $swooleRequest->server;
        $method = $swooleRequest->server['request_method'] ?? 'GET';
        $uri =  $server['request_uri'];
        $body = $swooleRequest->rawContent();
        //关键点
        $request = new self($server, $uri, $swooleRequest->get,$swooleRequest->post,$method,$swooleRequest->header, $body);
        $request->swooleRequest = $swooleRequest;
        return $request;

    }


}