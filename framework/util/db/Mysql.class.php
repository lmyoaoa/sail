<?php
/**
 * mysql操作基类，静态类，多次使用场景下效率更高
 * @author 李明友
 * @since 2014-06-20
 */

class Mysql {

    //是否长连接，默认false
    public static $pconnect = false;

    //是否开启debug模式
    public static $debug = false;

    protected static $dbConfig;
    protected static $dbName;
    protected static $tableName;

    //pdo object
    protected static $_conn;

    protected static $_pcon;

    /**
     * 查询数据表
     */
    public static function getRows() {
        $conn = self::getConnect();
        $sql = 'select * from ' . self::$tableName;
        echo $sql;
        var_dump($conn);
    }

    public static function getConnect() {
        if( self::$_conn ) {
            return self::$_conn;
        }
        return self::connect(self::$dbConfig, self::$dbName);
    }

     /**
     * 数据库初始化
     * @param array $dbValues
     */
    private static function connect($dbConfig, $dbName) {
        self::$dbConfig = $dbConfig;
        self::$dbName = $dbName;

/*
        $this->host     = $dbConfig['host'];
        $this->port     = $dbConfig['port'];
        $this->dbUser   = $dbConfig['dbUser'];
        $this->dbPasswd = $dbConfig['dbPasswd'];

        $this->dbName   = $dbName;
        */

        try {
            self::$_pcon = array(PDO::ATTR_PERSISTENT => self::$pconnect);
            self::$_conn = new PDO(
                'mysql:host=' . self::$dbConfig['host'] . ';port=' . self::$dbConfig['port'] . 
                ';dbname=' . self::$dbConfig['dbName'] . ';', 
                self::$dbConfig['dbUser'],
                self::$dbConfig['dbPasswd']);
            return self::$_conn;
        }catch( Exception $e ) {
            echo $e->getMessage();
        }
    }
}
