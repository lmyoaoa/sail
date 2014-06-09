<?php

class Ip {

    const PREG_IP = "/\A((([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\.){3}(([0-9]?[0-9])|(1[0-9]{2})|(2[0-4][0-9])|(25[0-5]))\Z/";
    const ERROR_IP = "127.0.0.1";

    /**
     *获取本机外网ip地址
     */
    public static function getLocalIp() {
        exec("/sbin/ifconfig",$out,$stats);
        if(!empty($out)) {
            if(isset($out[1]) && strstr($out[1],'addr:')) {
                $tmpArray = explode(":", $out[1]);
                $tmpIp = explode(" ", $tmpArray[1]);
                if(preg_match(self::PREG_IP,trim($tmpIp[0]))) {
                    return trim($tmpIp[0]);
                }
            }
        }
        return self::ERROR_IP;
    }
}
