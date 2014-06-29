<?php

class IndexInterface {

    function __construct() {
        $mod = new indexModel();
        $mod->getRows();
        $f = $mod->getFields();
        print_r($f);
    }
}
