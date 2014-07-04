<?php
/*********
	功能：自动加载类
	作者：limingyou
	日期：2012-06-01
*********/

class Autoloader {

	protected static $classes = null;

	protected static function classArray() {
		if( is_null(self::$classes) || !is_array(self::$classes) ) {
			self::$classes = require COMMON_PATH . '/autoload.conf.php';
            $appClassList = appConfig::getAutoloadClass();
            if( !empty($appClassList) ) {
                self::$classes = array_merge(self::$classes, $appClassList);
            }
		}
	}

	public static function autoload($className) {
		self::classArray();

        //判断是否在配置中存在
        $isExists = array_key_exists( $className, self::$classes );

		if ( $isExists ) {
			require self::$classes[$className];
			return true;
		}else{
            return self::_setInterfaceAndModelClass($className);
        }
		return false;
	}

    private static function _setInterfaceAndModelClass($className) {
        //try {
            //判断是否model
            $isModel = strpos($className, 'Model');
            if( $isModel === false ) {
                $file = dirname(FRAMEWORK_PATH) . '/api/interface/' . $className . '.class.php';
            }else{
                $file = dirname(FRAMEWORK_PATH) . '/api/model/' . $className . '.class.php';
            }

            if( !file_exists($file) ) {
                throw new BaseException('没有找到类：' . $className . '，请确认文件是否存在');
            }
            require $file;
            /*
        }catch(Exception $e) {
            echo $e->getMessage();
        }
        */

        return true;
    }
}

spl_autoload_register( array( 'Autoloader', 'autoload' ) );
