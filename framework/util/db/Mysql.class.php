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

    //返回数据格式
    protected $resultMode = PDO::FETCH_ASSOC;

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
            array('id', 'in', array(1,2,3), false),     //此处false/0代表是否给数组加上单引号
            array('id', 'between', ''),

            'xxx=0 and oo=9 or jj=3' //自定义sql
       )
       @param int $page
       @param int $size
       @param string $orderBy etc: 'order by id desc'
     */
    public function getRows($fields='*', $where=array(), $page=1, $size=10, $orderBy='', $isCount=true) {
        $formatData = $this->formatWhere($where);
        $where = $formatData['where']=='' ? '' : ' where ' . $formatData['where'];
        $start = ($page -1) * $size;

        $conn = $this->getConnect();
        $sth = $conn->prepare('SELECT ' . $fields . ' FROM ' . $this->tableName . $where 
            . ' ' . $orderBy . ' limit ' . $start . ','.$size );
        //var_dump($sth);
        $res = $sth->execute($formatData['data']);
        $result = $sth->fetchAll( $this->resultMode );

        //获得总行数
        $ttl = 0;
        if( $isCount ) {
            $ttl = $this->getRowsCount(array(), $formatData);
        }

        return array(
            'rows'=>$result,
            'ttl'=>$ttl,
        );
    }

    /**
     * 获取总行数
     */
    public function getRowsCount($where=array(), $formatData=false) {
        $conn = $this->getConnect();
        if( !$formatData ) {
            $formatData = $this->formatWhere($where);
        }

        $where = $formatData['where']=='' ? '' : ' where ' . $formatData['where'];

        $sth = $conn->prepare('SELECT count(*) n FROM ' . $this->tableName . $where);
        $res = $sth->execute($formatData['data']);
        $count = $sth->fetch( $this->resultMode );

        return $count ? $count['n'] : 0;
    }

    /**
     * 将array数组格式化成数据库查询的格式
     * @param array $where 查询条件数组
     * array(
            array('name', '=', 'lmyoaoa'),
            array('number', '>', 15),
            array('id', 'in', array(1,2,3), false),     //此处false/0代表是否给数组加上单引号
            array('id', 'between', array(1,2)),

            'xxx=0 and oo=9 or jj=3' //自定义sql
       )
     * key string 键=>值（字符串
     */
    public function formatWhere($array) {
        $formatData = array(
            'where'=>'',
            'data'=>array(),
        );

        if( empty($array) ) {
            return $formatData;
        }

        foreach( $array as $k => $v ) {
            $preBra = $endBra = '';
            $key = ':' . $v[0];
            if( is_array($v) && !empty($v[2]) ) {
                switch( $v[1] ) {
                    case '=':
                        $formatData['where'][] = $v[0] . $v[1] . $key;
                        $formatData['data'][$key] = $v[2];
                        break;
                    case 'in':
                        /*
                        $quot = isset($v[3]) && $v[3] ? "'" : '';
                        foreach( $v[2] as $val ) {
                            $in[] = $quot . $val . $quot;
                        }
                        $formatData['where'][] = $v[0] . ' in (' . implode($in) . ')';
                        */
                        $quot = isset($v[3]) && $v[3] ? "'" : '';
                        foreach( $v[2] as $val ) {
                            $in[] = $quot . $val . $quot;
                        }
                        $formatData['data'][$key] = implode($in);
                        $formatData['where'][] = $v[0] . ' in (' . $key . ')';
                        break;
                    case 'between':
                        $formatData['data'][$key . '_start']    = $v[2][0];
                        $formatData['data'][$key . '_end']      = $v[2][1];
                        $formatData['where'][] = $v[0] . ' between ' . $key . '_start' . ' and ' . $key . '_end';
                        break;
                }
            }else{
                $formatData['where'][] = $v;
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
        //$cq->setFetchMode(PDO::FETCH_ASSOC);
        $rows = $cq->fetchAll( $this->resultMode );
        return $rows;
    }

    /**
     * 获取数据库字段
     */
    public function getFields($table='') {
        $table = $table ? $table : $this->tableName;
        $conn = $this->getConnect();
        $cq = $conn->query("DESCRIBE $table");
        $result = $cq->fetchAll( $this->resultMode );
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

    /***
     * 设置数据模式
     * $this->setResultMode(PDO::FETCH_UNIQUE);
     */
    public function setResultMode($mode) {
        $this->resultMode = $mode;
    }
}
