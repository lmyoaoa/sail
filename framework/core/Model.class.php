<?php
/**
 * Model基类，所有数据库操作都基于此类
 * 此类只为mysql服务，其他存储以插件形式介入
 * @author 李明友
 * @since 2014-06-20
 */

abstract class Model {
    protected function __before_save() {}
    protected function __after_save() {}

    protected function __before_insert() {}
    protected function __after_insert() {}

    protected function __before_update() {}
    protected function __after_update() {}

    protected function __before_delete() {}
    protected function __after_delete() {}
    
    
}
