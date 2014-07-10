<?php
/**
 * mysql配置类
 * @author limingyou
 * @since 2014-06-29
 */

class MysqlConf {

    //库名
    const MASTER = 'test';
    const SLAVE = 'test';

    /**
     * 主库配置
     */
    public static function Master() {
        return array(
            'host'=>'127.0.0.1',
            'port'=>3306,
            'dbUser'=>'root',
            'dbPasswd'=>'root',
        );
    }

    /**
     * 从库配置
     */
    public static function Slave() {
        return array(
            'host'=>'127.0.0.1',
            'port'=>3306,
            'dbUser'=>'root',
            'dbPasswd'=>'root',
        );
    }



}
