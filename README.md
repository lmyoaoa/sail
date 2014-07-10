sail框架
====

天下武功，唯快不破。这是一个php framework，一个简单，高效的php框架。


项目简介
====
这个项目目前暂时是由我一个人维护，做这个项目的原因很简单，希望将来这个sail框架能够随我一同成长，我到哪，用到哪。命名为sail可能是凑巧想到，恰好我十分喜欢大航海，导致十分喜欢这个富有航海味的名字。而且现在在一家新的公司从头开始做起，代表启航，我启航，它亦在启航。

这个框架是在每天下班，周末的时候抽时间写出来的，目前功能还非常简单。虽然已经运用在我自己的私人项目上，难免还有一些框架级的逻辑疏忽。希望大家有兴趣多多指正，也欢迎做些技术交流～

本着高效的原则进行功能增强。将来会扩展到单元测试，以及前端UI框架，前端框架的引入。做一个开发简单，迅速，并且对效率有高要求的一套框架。而不是牺牲效率来换取开发速度！“一切”以牺牲程序效率换取开发速度的框架，送两个字“呵，呵”。当然，只是做个企业站之类没什么流量的站就请无视我上面一句啦。^_^

对框架效率有任何疑问或者质疑，欢迎邮件来猛砸：lmyoaoa#gmail.com。

目录说明
====
        api:            所有的数据都从api中输入输出
            ├─interface 接口文件均放在这下面
            ├─model     与数据表一一对应
            
        app:            app项目所有的controller以及model都放在这里
            ├─controller 控制器
            ├─model     模型所在，与数据表一一对应
            
        cache:          缓存文件夹

        sail.php        框架主入口文件
        index.php       默认项目入口文件
        framerowk:      框架主目录
            ├─core      框架核心类，mvc分层，路由，流程分发等核心都放在这
            ├─lib       第三方类库存放位置
            ├─util      通用工具类存放在这里，如http相关操作，图片处理，默认mysql库操作类等。。。

        config:         全站配置
            ├─appConfig 项目配置，所有项目配置都放置在这，appName.conf.php方式命名，主项目为main.conf.php
            ├─common 全站公用配置，不用区分开发、测试、模拟、生产环境的配置都放在这
                ├─route 路由配置，以项目拆分，防止项目过大，初始化加载浪费
            ├─pro 生产环境的配置都放在这
            ├─test 测试环境的配置都放在这

        static:         静态文件

        templates:      模板文件夹
            ├─app       项目app的模板存放处
            ├─....
        


如何使用
====
1. 下载框架

2. 解压至需要的目录

3. 配置nginx/apache

  3.1 nginx:放出个人的nginx配置供参考

        #nginx配置文件
        server {
            listen       8081;  #这个端口改成默认80
            server_name  dev.sail.com;  #配置访问url
            location / {
                root   /usr/htdocs/sail;   #框架目录
                index  index.html index.htm index.php;
          
                #将所有请求rewrite到index.php
                if (!-e $request_filename){
                    rewrite ^/(.*)$ /index.php?_ac=$1 last;
                }
            }
            error_log  /var/log/nginx/sail.cn.log;

            error_page  404              /404.html;
            location ~ \.php$ {
                root   /usr/htdocs/sail;
                fastcgi_pass    127.0.0.1:9000;
                fastcgi_index index.php;
                fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
                include        fastcgi_params;
            }
        }

4. 默认的page controller放在app/controller/下，打开可以看到app/controller/下有indexPage.class.php文件。一般我们默认页面都使用index命名，当然在sail里面也可以换成其他的，在config/appConfig中的对应配置修改DEF_CONTROLLER常量。（本例中可通过修改main.conf.php文件实现）。

5. 路由的配置：默认indexPage实现了一个index（首页），以及一个列表页，列表页通过配置路由实现。路由的配置可在config/common/route/下找到。具体规则可以看配置文件的注释。或者可看之后更新的路由详解小节。下面是一个正则路由配置的例子：

        Router::get('sail-(\d+)-(\d+).html', array(
            'm'=>'',
            'c'=>'index',
            'a'=>'list',
            'args'=>array(
                'id', 'page',    //与上面参数一一对应
            ),
        ), 2);
通过以上可以设置一个/sail-1-2.html的页面，将访问controller下的indexPage中的listAction方法，并且在页面可以获取到$_GET['id'], $_GET['page']两个变量


更新日志
====
    -2014-07-05
        -成功运用到项目[事件拆解系统](http://www.ichaichai.com)上，将原有的框架抛弃，改造完成
    -2014-07-04
        -着手将sail框架运用到我自己的项目：[事件拆解系统](http://www.ichaichai.com)
    -2014-07-02
        -mysql功能函数，model基本功能完成
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
