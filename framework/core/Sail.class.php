<?php
/**
 * 主引导类
 * @author limingyou
 * @since 2014-06-06
 */

class Sail {
    //扩展名
    const CONF_EXT = '.conf.php';
    const ROUTES_EXT = '.routes.php';

    //当前页面url
    private static $url = '';

    /**
     * 引导程序
     */
    public static function run() {
        try {
            self::appInit();
            $cParams = Router::getParams();
            //print_r($cParams);
            //print_r(Router::getRoutes());

            Dispather::run($cParams);
        }catch( BaseException $e) {
            echo $e->errMsg();
        }catch( Exception $e ) {
            echo $e->getMessage();
        }
    }

    /**
     * 初始化项目
     */
    public static function appInit() {
        $appConfFile = APP_CONF_PATH . APP_NAME . self::CONF_EXT;
        if( !file_exists($appConfFile) ) {
            throw new BaseException('没有找到' . $appConfFile);
        }
        $routeConfFile = ROUTE_PATH . APP_NAME . self::ROUTES_EXT;
        if( !file_exists($routeConfFile) ) {
            throw new BaseException('没有找到' . $routeConfFile);
        }
        require $appConfFile;
        require $routeConfFile;
    }

}
