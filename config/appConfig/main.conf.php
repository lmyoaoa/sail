<?php
/**
 * 主项目配置
 * 默认的主项目配置都放在这里
 * 其他项目（如后台，其他子域名项目等）可以通过拷贝此文件修改使用
 * @author limingyou
 * @since 2014-06-06
 */

class appConfig {
    //项目代号，项目名
    const APP_NAME = 'app';

    //项目默认页
    const DEF_CONTROLLER = 'Index';

    //404页面
    const PAGE_NOT_FOUND = '/404.html';

    //是否开启默认路由规则，默认TRUE
    const DEFAULT_ROUTE_RULE = TRUE;

    //public static $otherConf = array('xxoo', 'xx', 'oo');

}
