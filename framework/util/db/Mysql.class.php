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
     * @param string $fields
     * @param array $where 查询条件数组
     * array(
            array('name', '=', 'lmyoaoa'),
            array('number', '>', 15),
            array('id', 'in', ''),
            array('id', 'between', ''),
        )
       @param int $page
       @param int $size
       @param string $orderBy etc: 'order by id desc'
     */
    public function getRows($fields='*', $where=array(), $page=1, $size=10, $orderBy='') {
        $formatData = $this->_formatWhere($where);
        $where = $formatData['where']=='' ? '' : ' where ' . $formatData['where'];
        $start = ($page -1) * $size;

        $conn = $this->getConnect();
        $sth = $conn->prepare('SELECT ' . $fields . ' FROM ' . $this->tableName . $where 
            . ' ' . $orderBy . ' limit ' . $start . ','.$size );
            var_dump($sth);
        $res = $sth->execute($formatData['data']);
        $result = $sth->fetchAll();
        return $result;
    }

    /**
     * 将array数组格式化成数据库查询的格式
     * @param array $array
     * key string 键=>值（字符串
     */
    private function _formatWhere($array) {
        $formatData = array(
            'where'=>'',
            'data'=>array(),
        );
        if( empty($array) ) {
            return $formatData;
        }

        foreach( $array as $k => $v ) {
            $preBra = $endBra = '';
            if( !empty($v[2]) ) {
                $key = ':' . $v[0];
                $formatData['where'][] = $v[0] . $v[1] . $key;
                $formatData['data'][$key] = $v[2];
            }
        }

        $formatData['where'] = implode(' and ', $formatData['where']);
        return $formatData;
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
