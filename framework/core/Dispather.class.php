<?php
/**
 * 流程分发，分发到具体控制器
 * @author: limingyou
 * @since 2014-06-05
 */

class Dispather {
    const EXT = 'Page.class.php';
    const CLASS_EXT = 'Page';
    
    /**
     * 分发程序
     * @author limingyou
     * @param array $cParams 项目参数
     * app  string 控制器主目录
     * module string 模块
     * controller string 控制器
     * action string 动作
     * @param array $args
     */
    public static function run($cParams) {
        self::_checkCParams($cParams);
        $controllerFile = self::_controllerPath($cParams);

        if( !file_exists($controllerFile) ) {
            exit('未找到对应模块文件: ' . $controllerFile);
        }
        
        include $controllerFile;
        $controllerName = $cParams['controller'] . self::CLASS_EXT;
        $c = new $controllerName();

        if ( method_exists($c, 'init') ) {
            $c->init();
        }

        $action = $cParams['action'] . 'Action';
        if ( !method_exists($c, $action) ) {
            exit('未找到对应action: ' . $action);
        }

        $c->cVars = $cParams;
        $_GET = array_merge($_GET, $cParams['args']);
        /*
        if ( isset($cParams['args']) && !empty($cParams['args']) ) {
            call_user_func_array( array(&$c, $action), $cParams['args'] );
        }else{
        */
            $c->$action();
        //}
    }

    /**
     * 控制器主目录
     * @author limingyou
     */
    private static function _controllerPath($cParams) {
        return ROOT_PATH . $cParams['app'] . '/controller/' . $cParams['module'] . $cParams['controller'] . self::EXT;
    }

    private static function _checkCParams(&$cParams) {
        if( !isset($cParams['app']) || ( isset($cParams['app']) && empty($cParams['app']) ) ) {
            $cParams['app'] = ROOT_PATH . 'app/';
        }
        if( !isset($cParams['module']) || ( isset($cParams['module']) && empty($cParams['module']) ) ) {
            $cParams['module'] = ''; // xxoo/
        }
        if( !isset($cParams['controller']) || ( isset($cParams['controller']) && empty($cParams['controller']) ) ) {
            $cParams['controller'] = 'index';
        }
        if( !isset($cParams['action']) || ( isset($cParams['action']) && empty($cParams['action']) ) ) {
            $cParams['action'] = 'run';
        }
    }
}
