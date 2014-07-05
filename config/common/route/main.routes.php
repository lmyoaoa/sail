<?php
/**
 * 公共路由配置
 * @author limingyou
 * @since 2014-06-06
 *
 * 路由类型：
 * 1.   静态路由
 * 2.   通配符(暂定不实现)
 * 3.   正则
 * 4.   系统默认路由
 *
 * 路由规则: 
 * 1.   url结尾不需要加/
 * 2.   m(model)可为空，为空则直接在app目录下建立文件
 *      c(controller)第一个字母不要大写
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
    'c'=>'index',
    'a'=>'list',
    'args'=>array(
        'id', 'page',    //与上面参数一一对应
    ),
), 2);

Router::get('sail-(\d+).html', array(
    'm'=>'',
    'c'=>'index',
    'a'=>'index',
    'args'=>array(
        'id',        //与上面参数一一对应
    ),
), 2);

Router::get('sail\/(\d+).html', array(
    'm'=>'',
    'c'=>'index',
    'a'=>'index',
    'args'=>array(
        'sid',
    ),
), 2);



Router::get('sail-:num.html', array(
    'm'=>'',
    'c'=>'sail',
    'a'=>'index',
    'args'=>array(),
));

Router::get('bbb/:any.html', array(
    'm'=>'',
    'c'=>'sail',
    'a'=>'$any',
    'args'=>array(),
));



