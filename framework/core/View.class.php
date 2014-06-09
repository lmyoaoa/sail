<?php
/**
 * 视图
 * @author limingyou
 * @since 2014-06-08
 */

class View {
   
    public function __construct($config=array()) {
    }

    /**
     * 赋值
     * $key mixed $key=string时直接设置页面key变量, $key=array时设置$key数组中的key变量值为$key中的$val
     * $value mixed 可以为字符串，也可为数组，在$key为数组时传空
     */
    public function assign($key, $value='') {
        if( is_array($key) ) {
            foreach( $key as $k => $v ) {
                $this->$k = $v;
            }
        }else{
            $this->$key = $value;
        }
    }

    /**
     * 获取模板内容
     */
    public function fetch($tplFile, $value=array()) {
        if( !empty($value) ) {
            $this->assign($value);
        }

        //获取模板输出
        ob_start();

        if( !file_exists($tplFile) ) {
            throw new BaseException('找不到模板文件：' . $tplFile);
        }
        require $tplFile;

        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    public function display($tplFile, $value=array()) {
        $content = $this->fetch($tplFile, $value);

        echo $content;
    }

    public function setContentType($contentType='') {
        header('Content-Type:'.$contentType.'; charset=utf-8');
    }
}
