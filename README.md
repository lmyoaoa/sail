sail框架
====

一个php framework，一个简单，高效的php框架


项目简介
====

如何使用
====
1. 下载框架

2. 解压至需要的目录

3. 配置nginx/apache

3.1 放出个人的nginx配置供参考
    server {
        listen       8081;
        server_name  dev.sail.com;
        location / {
            root   /usr/htdocs/sail/sail;
            index  index.html index.htm index.php;
      
            #将所有请求rewrite到index.php
            #if (!-e $request_filename){
                rewrite ^/(.*)$ /index.php?_ac=$1 last;
            #}
            #rewrite ^/article/(\d+).html$          /article.php?id=$1 last;

        }
        error_log  /var/log/nginx/sail.cn.log;

        error_page  404              /404.html;
        location ~ \.php$ {
            root   /usr/htdocs/sail/sail;
            fastcgi_pass    127.0.0.1:9000;
            fastcgi_index index.php;
            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
            include        fastcgi_params;
        }

    }

 #将所有请求rewrite到index.php
 rewrite ^/(.*)$ /index.php?_ac=$1 last;

更新日志
====
    -2014-06-08
        -框架核心代码完成，并提交到github
        -view/controller层实现，并完成对应页面模板实例
        -自动解析模板位置功能实现，注重性能
    -2014-06-07
        -路由构建，静态路由，正则路由实现并做第一版优化，与性能测试
        -基础框架搭建，流程走通
    -2014-06-06
        -分发模块1.0完成
        -调研路由是否由PHP实现，还是由nginx/apache实现
    -2014-05-31
        -项目启动，完成框架初始化PPT
