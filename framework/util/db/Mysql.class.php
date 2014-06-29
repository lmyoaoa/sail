<?php
/**
 * mysql操作基类
 * @author 李明友
 * @since 2014-06-20
 */

class Mysql {

    //是否长连接，默认false
    public $pconnect = false;

    //是否开启debug模式
    public $debug = false;

    protected $host;
    protected $port;
    protected $dbName;
    protected $dbUser;
    protected $dbPasswd;

    //pdo object
    protected $_conn;

    protected $_pcon;

    /**
     * 数据库初始化
     * @param array $dbValues
     */
    function __construct($dbConfig, $dbName) {

        $this->host     = $dbConfig['host'];
        $this->port     = $dbConfig['port'];
        $this->dbUser   = $dbConfig['dbUser'];
        $this->dbPasswd = $dbConfig['dbPasswd'];

        $this->dbName   = $dbName;
    }

    /**
     * 查询数据表
     */
    public function getRows() {
        $conn = $getConnect();
        var_dump($conn);
    }

    public function getConnect() {
        if( $this->_conn ) {
            return $this->_conn;
        }
        return $this->connect();
    }
    
    private function connect() {
        try {
            $this->_pcon = array(PDO::ATTR_PERSISTENT => $this->pconnect);
            $this->_conn = new PDO("mysql:host={$this->host};port={$this->port};dbname={$this->dbName};", 
                $this->dbUser,
                $this->dbPasswd);
            var_dump($this->_conn);
        }catch( Exception $e ) {
            echo $e->getMessage();
        }
    }
}
