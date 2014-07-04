<?php
/**
 * 通用接口基类
 * @author limingyou
 * @since 2014-07-04
 */

class BaseInterface {
    protected static $MODELS;

    public static function getModel($modelName, $param='') {
        if( isset(self::$MODELS[$modelName]) ) {
            return self::$MODELS[$modelName];
        }

        $file = ROOT_PATH . 'api/model/' . $modelName . '.class.php';;
        require $file;
        $className = basename($modelName);
        return self::$MODELS[$modelName] = new $className($param);
    }
}
