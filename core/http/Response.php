<?php
namespace Core\http;
class Response {
    /**
     * @var \Swoole\Http\Response
     */
    protected $swooleReponse;


    protected $body;

    /**
     * Response constructor.
     * @param \Swoole\Http\Response $swooleReponse
     */
    public function __construct($swooleReponse)
    {
        $this->swooleReponse = $swooleReponse;
        $this->writeHeader("Content-type","text/html; charset=utf-8");
    }

    public static function init(\Swoole\Http\Response $swooleReponse){
        return new self($swooleReponse);
    }
    public function writeHttpStatus(int $code){
        $this->swooleReponse->status($code);
    }
    public function writeHeader($key,$value){
        $this->swooleReponse->header($key,$value);
    }
    public function writeHtml($html){
        $this->swooleReponse->write($html);
    }
    public function redirect($url,$code=301){
        $this->writeHttpStatus($code);
        $this->writeHeader("Location",$url);
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

    public function end(){
        $json_convert = ['array'];
        $ret = $this->getBody();
        if(in_array(gettype($ret),$json_convert)){
            $this->writeHeader("Content-type","application/json;charset=utf-8");
            $this->swooleReponse->write(json_encode($ret));
        }else {
            $this->swooleReponse->write($this->body);
        }
        $this->swooleReponse->end();
    }
}