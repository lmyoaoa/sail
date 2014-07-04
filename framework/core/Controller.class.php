<?php
/**
 * 控制器
 * @author limingyou
 * @since 2014-06-08
 */

class Controller {
    //全局变量
    public $cVars;

    //view
    protected $view;

    //模板目录
    protected $tplPath = '';

    public function __construct() {
        $this->view = new View();
    }

    /**
     * 赋值
     * $key mixed $key=string时直接设置页面key变量, $key=array时设置$key数组中的key变量值为$key中的$val
     * $value mixed 可以为字符串，也可为数组，在$key为数组时传空
     */
    protected function assign($key, $value) {
        $this->view->assign($key, $value);
    }

    /**
     * 获取页面内容
     * $param string $tplName 模板名，无需传具体路径
     */
    protected function fetch($tplName, $value=array()) {
        $tplFile = $this->getTplFile($tplName);
        $value['tplPath'] = $this->tplPath;
        return $this->view->fetch($tplFile, $value);
    }

    /**
     * 显示页面
     * $param string $tplName 模板名，无需传具体路径
     */
    protected function render($tplName, $value) {
        $tplFile = $this->getTplFile($tplName);
        $value['tplPath'] = $this->tplPath;
        $this->view->display($tplFile, $value);
    }

    protected function getTplFile($tplName) {
        $cParams = $this->cVars;
        $module = $cParams['module'] ? $cParams['module'] . '/' : '';
        $this->tplPath = ROOT_PATH . 'templates/' .  $cParams['app'] . '/' . $module;
        return $this->tplPath . $tplName;
    }

}
