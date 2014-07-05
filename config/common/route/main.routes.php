<?php
/**
 * 公共路由配置
 * @author limingyou
 * @since 2014-06-06
 *
 * 路由类型：
 * 1.   静态路由
 * 2.   通配符
 * 3.   正则
 * 4.   默认路由
 *
 * 路由规则: 
 * 1.   url结尾不需要加/
 * 2.   m(model)可为空，为空则直接在app目录下建立文件
 *      c(controller)第一个字母要大写
 *      a(action)采用驼峰命名
 */

//hello world测试页
Router::get('sail', array(
    'm'=>'',
    'c'=>'index',
    'a'=>'index',
    'args'=>array(),
));

Router::get('sail-(\d+)-(\d+).html', array(
    'm'=>'',
    'c'=>'Index',
    'a'=>'list',
    'args'=>array(
        'id', 'page',    //与上面参数一一对应
    ),
), 2);

Router::get('sail-(\d+).html', array(
    'm'=>'',
    'c'=>'Index',
    'a'=>'Index',
    'args'=>array(
        'id',        //与上面参数一一对应
    ),
), 2);

Router::get('sail\/(\d+).html', array(
    'm'=>'',
    'c'=>'Index',
    'a'=>'Index',
    'args'=>array(
        'sid',
    ),
), 2);



Router::get('sail-:num.html', array(
    'm'=>'',
    'c'=>'sail',
    'a'=>'Index',
    'args'=>array(),
));

Router::get('bbb/:any.html', array(
    'm'=>'',
    'c'=>'sail',
    'a'=>'$any',
    'args'=>array(),
));



