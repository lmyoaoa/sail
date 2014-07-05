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
     * 往数据表插入一条记录
     * @param array $array 插入数据库的数组，array( '字段名'=>'值' )
     * @param bool $returnID 是否返回自增主键值
     * @return bool | id
     */
    public function add($array, $returnID=false) {
        $conn = $this->getConnect();
        $data = $this->_formatValue($array);

        $sql = 'insert into `' . $this->tableName . '` set ' . $data['str'];
        $sth = $conn->prepare($sql);
        $res = $sth->execute($data['data']);
        //$arr = $sth->errorInfo(); print_r($arr);

        return $returnID ? $conn->lastInsertId() : $res;
    }

    /**
     * 更新数据库数据，不支持库更新
     * @param array $where 查询数组，与getRows等函数用法相同
     * @param array $update 更新的数组 array('字段名'=>'值', )
     * @param int rray $limit 限制操作条数
     * @return bool
     */
    public function update(array $where, array $update, $limit=1) {
        /*
        if( empty($where) || empty($update) ) {
            throw new BaseException('不允许对全库进行更新，或者更新内容为空'); return false;
        }
        */

        //更新
        $conn = $this->getConnect();
        $value = $this->_formatValue($update, true);
        $formatData = $this->formatWhere($where);
        //$data = array_merge($value['data'], $formatData['data']);

        $sql = 'update ' . $this->tableName . ' set ' . $value['str'] . ' where ' . $formatData['where'] 
                . ' limit ' . $limit;
        $sth = $conn->prepare($sql);
        return $sth->execute($formatData['data']);
    }

    /**
     * 对数据进行物理删除，一般不对数据进行物理删除，任何数据都是宝贵的...
     * @param array $where
     * @param int $limit 限制操作条数
     * @return bool
     */
    public function del(array $where, $limit=1) {
        /*
        if( empty($where) || empty($update) ) {
            throw new BaseException('不允许对全库进行删除操作'); return false;
        }
        */

        //更新
        $conn = $this->getConnect();
        $formatData = $this->formatWhere($where);

        $sql = 'delete from ' . $this->tableName . ' where ' . $formatData['where'] . ' limit ' . $limit;
        $sth = $conn->prepare($sql);
        return $sth->execute($formatData['data']);
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
    public function getRows($fields='', $where=array(), $page=1, $size=10, $orderBy='', $isCount=false) {
        $fields = $fields=='' ? '*' : $fields;
        $formatData = $this->formatWhere($where);
        $where = $formatData['where']=='' ? '' : ' where ' . $formatData['where'];
        $start = ($page -1) * $size;

        $conn = $this->getConnect();
        $sth = $conn->prepare('SELECT ' . $fields . ' FROM ' . $this->tableName . $where 
            . ' ' . $orderBy . ' limit ' . $start . ','.$size );
        //var_dump($sth);
        //print_r($formatData['data']);
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
     * 查询数据表，一条记录
     * @param string $fields
     * @param array $where 查询条件数组
     * array(
            array('name', '=', 'lmyoaoa'),
            array('number', '>', 15),
            array('id', 'in', array(1,2,3), false),     //此处false/0代表是否给数组加上单引号
            array('id', 'between', ''),

            'xxx=0 and oo=9 or jj=3' //自定义sql
       )
       @param string $orderBy etc: 'order by id desc'
     */
    public function getOne($fields='', $where=array(), $orderBy='') {
        $fields = $fields=='' ? '*' : $fields;
        $formatData = $this->formatWhere($where);
        $where = $formatData['where']=='' ? '' : ' where ' . $formatData['where'];

        $conn = $this->getConnect();
        $sth = $conn->prepare('SELECT ' . $fields . ' FROM ' . $this->tableName . $where 
            . ' ' . $orderBy);
        //var_dump($sth);
        $res = $sth->execute($formatData['data']);
        //$arr = $sth->errorInfo(); print_r($arr);
        $result = $sth->fetch( $this->resultMode );
        return $result;
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
            //$this->_conn->query('SET NAMES UTF8');
            return $this->_conn;
        }
        $conn = $this->connect();
        $conn->query('SET NAMES UTF8');
        return $conn;
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
            if( is_array($v) && $v[2] !== '' ) {
                switch( $v[1] ) {
                    case '=':
                        $formatData['where'][] = $v[0] . $v[1] . $key;
                        $formatData['data'][$key] = $v[2];
                        break;
                    case '>=':
                        $key1 = $key . '_s';
                        $formatData['where'][] = $v[0] . $v[1] . $key1;
                        $formatData['data'][$key1] = $v[2];
                        break;
                    case '<=':
                        $key1 = $key . '_e';
                        $formatData['where'][] = $v[0] . $v[1] . $key1;
                        $formatData['data'][$key1] = $v[2];
                        break;
                    case 'in':
                        $quot = isset($v[3]) && $v[3] ? "'" : '';
                        foreach( $v[2] as $val ) {
                            $in[] = $quot . $val . $quot;
                        }
                        $formatData['where'][] = $v[0] . ' in (' . implode(',', $in) . ')';
                        /*
                        $quot = isset($v[3]) && $v[3] ? "'" : '';
                        foreach( $v[2] as $val ) {
                            $in[] = $quot . $val . $quot;
                        }
                        $formatData['data'][$key] = implode(',', $in);
                        $formatData['where'][] = $v[0] . ' in (' . $key . ')';
                        */
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

        //print_r($formatData);
        $formatData['where'] = implode(' and ', $formatData['where']);
        return $formatData;
    }

    /**
     * 将数组格式化为逗号隔开的字符串
     */
    protected function _formatValue($array, $returnString=false) {
        if( $returnString) {
            foreach( $array as $k => $v ) {
                $ret[] = $k . '=\'' . addslashes($v) . '\'';
            }
            $val = array();
        }else{
            foreach( $array as $k => $v ) {
                $key = ':' . $k;
                $ret[] = $k . '=' . $key;
                $val[$key] = $v;
            }
        }
        
        return array(
            'str'   =>implode(',', $ret),
            'data'  =>$val,
        );
    }
}
