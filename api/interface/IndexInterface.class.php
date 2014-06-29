<?php

class IndexInterface {

    function __construct() {
        $mod = new indexModel();
        $rows = $mod->getRows('*', array(
            array('name', '=', 'lmyoaoa'),
        ));
        print_r($rows);
    }
}
