<?php
/*********
	功能：自动加载类
	作者：limingyou
	日期：2012-06-01
*********/

//set_include_path ( LIB_PATH . get_include_path());

class Autoloader {
	protected static $classes = null;
	protected static function classArray() {
		if(is_null(self::$classes)||!is_array(self::$classes)) {
			self::$classes = require_once COMMON_PATH . '/autoload.conf.php';
		}
	}

	public static function autoload($classname) {
		self::classArray();

		if ( array_key_exists( $classname, self::$classes ) ) {
			require self::$classes[$classname];
			return true;
		}
		return false;
	}

}

spl_autoload_register( array( 'Autoloader', 'autoload' ) );
