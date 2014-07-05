<?php
/**
 * 
 */

class IndexPage extends Controller {

    //直接输出
    public function indexAction() {
        echo "hello world\r\n";
    }

    //加载模板
    public function listAction() {
        print_r($_GET);
        $m = new IndexInterface();
        exit;
        $this->render( 'list.html', array(
            'kk'=>22,
            'vv'=>'33',
            'test'=>'testPage',
            'desc'=>'页面变量都在这个$this里面',
        ));
    }

}
