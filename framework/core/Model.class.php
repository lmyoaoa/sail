<?php
/**
 * Model基类，所有数据库操作都基于此类
 * 此类只为mysql服务，其他存储以插件形式介入
 * @author 李明友
 * @since 2014-06-20
 */

abstract class Model {
    protected $MasterConf;
    protected $SlaveConf;
    protected $dbName;

    //表名
    protected $tableName;

    //表字段
    protected $fields;

    //主从库变量
    protected $mdb;
    protected $sdb;

    function __construct() {
        $this->_init();

        $this->mdb = new Mysql($this->MasterConf, $this->dbName, $this->tableName);
        $this->sdb = new Mysql($this->SlaveConf, $this->dbName, $this->tableName);
    }


    public function getRows($fields='*', $where=array(), $page=1, $size=10, $orderBy='') {
        $db = $this->getDb();
        return $db->getRows($fields='*', $where, $page, $size, $orderBy);
    }

    public function getFields() {
        $db = $this->getDb();
        return $db->getFields();
    }

    private function getDb($master=false) {
        return $master ? $this->mdb : $this->sdb;
    }

    //子类中需初始化
    abstract protected function _init();

    
}
