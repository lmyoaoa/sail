<?php
/**
 * 异常类
 * @author limingyou
 * 2014-06-06
 */
class BaseException extends Exception {

    public function errMsg() {
        return 'Framework ERROR: ' . $this->getMessage();
    }
}
