<?php

class IndexInterface {

    function __construct() {
        $mod = new indexModel();
        $rows = $mod->getRows('*', array(
            array('name', '=', 'lmyoaoa'),
        ));
        print_r($rows);

        /*
        $rows = $mod->getRowsCount(array(
            array('name', '=', 'lmyoaoa'),
        ));
        print_r($rows);
        */

        $id = $mod->add(array(
            'name'=>'a'
        ), true);
        var_dump('新增的ID为：' . $id);

        $up = $mod->update(array(
            array('name', '=', 'b'),
        ), array(
            'name'=>"c'",
        ));
        var_dump($up);
    }
}
