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
    protected $dbUser;
    protected $dbPasswd;

    protected $dbName;
    protected $tableName;

    //pdo object
    protected $_conn;

    protected $_pcon;

    /**
     * 数据库初始化
     * @param array $dbValues
     */
    function __construct($dbConfig, $dbName, $tableName) {

        $this->host     = $dbConfig['host'];
        $this->port     = $dbConfig['port'];
        $this->dbUser   = $dbConfig['dbUser'];
        $this->dbPasswd = $dbConfig['dbPasswd'];

        $this->dbName   = $dbName;
        $this->tableName = $tableName;
    }

    /**
     * 查询数据表
     */
    public function getRows() {
        $conn = $this->getConnect();
        $sql = "select * from {$this->tableName}";
        $cq = $conn->query($sql);
        $cq->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $cq->fetchAll();
        return $rows;
    }

    /**
     * 直接执行sql语句，一般情况下请勿使用
     * @param string $sql
     */
    public function query($sql) {
        $conn = $this->getConnect();
        $cq = $conn->query($sql);
        $cq->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $cq->fetchAll();
        return $rows;
    }

    /**
     * 获取数据库字段
     */
    public function getFields($table='') {
        $table = $table ? $table : $this->tableName;
        $conn = $this->getConnect();
        $cq = $conn->query("DESCRIBE $table");
        $result = $cq->fetchAll( PDO::FETCH_ASSOC );
        unset($cq);
        return $result;
    }

    /**
     * 获得数据库连接
     */
    public function getConnect() {
        if( $this->_conn ) {
            return $this->_conn;
        }
        return $this->connect();
    }
    
    /**
     * 连接数据库
     */
    private function connect() {
        try {
            $this->_pcon = array(PDO::ATTR_PERSISTENT => $this->pconnect);
            $this->_conn = new PDO("mysql:host={$this->host};port={$this->port};dbname={$this->dbName};", 
                $this->dbUser,
                $this->dbPasswd);
            return $this->_conn;
        }catch( Exception $e ) {
            echo $e->getMessage();
        }
        return false;
    }
}
