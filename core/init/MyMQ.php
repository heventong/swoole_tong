<?php
namespace Core\init;
use Core\annotations\Bean;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Exception;

/**
 * @Bean
 * @method \Illuminate\Database\Query\Builder table(string  $table,string|null  $connection=null)
 */
class MyMQ{
    public $channel;         //信道
    public $exchange;   //交换机
    public $queueName;  //队列名称
    public $route        = 'routeKey';  //路由键
    public $exchangeType = 'direct';  //交换机类型

    protected $conn;

    static protected $connection;  //静态rabbitMq连接

    /**
     * RabbitMq constructor.
     *
     * @param $conf  array  Mq的默认连接配置
     *               @$conf['host']  rabbitMq配置的ip地址
     *               @$conf['port']  rabbitMq配置的端口号
     *               @$conf['user']  用户名
     *               @$conf['pwd']   密码
     *               @$conf['vhost'] 虚拟host
     */
    public function __construct()
    {
        global $GLOBAL_CONFIGS;
        $mqconf = $GLOBAL_CONFIGS['mq']['default'];
        try {
            $this->conn = new AMQPStreamConnection($mqconf['host'], $mqconf['port'], $mqconf['user'], $mqconf['pwd'],
                $mqconf['vhost']);
            $this->exchange = $mqconf['exchange'];
            $this->queueName = $mqconf['queue'];
            $this->getConnection();
        } catch (Exception $e) {
            throw new Exception('cannot connection rabbitMq:' . $e->getMessage());
        }

    }

    public function getConnection()
    {
        if (!isset($this->channel)) {
            $this->channel = $this->conn->channel();
        }

        $this->createExchange();
    }

    public function createExchange()
    {
        //passive: 消极处理， 判断是否存在队列，存在则返回，不存在直接抛出 PhpAmqpLib\Exception\AMQPProtocolChannelException 异常
        //durable：true、false true：服务器重启会保留下来Exchange。警告：仅设置此选项，不代表消息持久化。即不保证重启后消息还在
        //autoDelete:true、false.true:当已经没有消费者时，服务器是否可以删除该Exchange

        $this->channel->exchange_declare($this->exchange, $this->exchangeType, false, true, false);

        //passive: 消极处理，判断是否存在队列，存在则返回，不存在则直接抛出 PhpAmqpLib\Exception\AMQPProtocolChannelException 异常
        //durable: true/false true :在服务器重启时，能够存活
        //exclusive: 是否为当前连接的专用队列，在连接段开后，会自动删除该队列
        //autodelete: 当没有任何消费者使用时，自动删除该队列
        //arguments: 自定义规则
        $this->channel->queue_declare($this->queueName, false, true, false, false);
    }


    /**
     * 绑定消息队列
     * 博主个人看法：在创建交换机与队列的时候，可以手动在rabbitMq界面将二者绑定，没有必要每次进行发送或者消费队列时进行绑定；
     */
    public function bindQueue()
    {
        $this->channel->queue_bind($this->queueName, $this->exchange, $this->route);
    }

    /**
     * 发送消息
     *
     * @param $msgBody  string  消息类型
     */
    public function sendMsg($msgBody)
    {
        // content_type: 发送消息的类型
        // delivery_mode: 设置的属性，比如设置该消息持久化['delivery_mode' => 2]
        if (is_array($msgBody)) {
            $msgBody = json_encode($msgBody);
        }
        $msg = new AMQPMessage($msgBody, ['content_type' => 'text/plain', 'delivery_mode' => 2]); //生成消息
        $this->channel->basic_publish($msg, $this->exchange, $this->route); //推送消息到某个交换机
    }

    /**
     * 消费消息
     *
     * @param $callback callable|null  回调函数 在这里可以添加消费消息的具体逻辑
     */
    public function consumeMsg($callback)
    {
        $this->bindQueue();
        //1.队列名称
        //2.consumer_tag 消费者标签
        //3.no_local false 这个功能属于AMPQ的标准，但是rabbitMq并没有做实现
        //4.no_ack false 收到消息后，是否不需要回复确认即被认为是被消费
        //5.exclusive false 排他消费者，即这个队列只能有一个消费者消费，适用于人物不允许进行并打处理的情况下，比如系统对接
        //6.callback 回调函数
        try{
            $this->channel->basic_consume($this->queueName, '', false, false, false, false, $callback);
        }catch(Exception $e){

        }
        //监听消息
        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    public function __destruct()
    {
        $this->channel()->close();
        $this->conn->close();
    }

    //实例化该service时首先加载的方法：检测是否已经有rabbitMq连接【始终保持是同一连接】
    static public function instance($conf)
    {
        if (!self::$connection) {
            self::$connection = new self($conf);
        }

        return self::$connection;
    }

//    public function __call($methodName, $arguments)
//    {
//    }
}