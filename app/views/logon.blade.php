<!doctype html>
<html lang="zh-Hans">
    <head>
        <meta charset="utf-8">
        <title>斑鸠PMS登陆</title>
        <link rel="stylesheet" href="assets/stylesheets/application.css">
    </head>
    <body class="fluid-container" style="background-color:#32383B;margin:0;font-family:'hiragino sans gb">
        <!-- Nav Bar -->
        <nav calss="navbar navbar-default navbar-fixed-top" style="height:80px;background-color:#282A2A;">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">
                    <img alt="Brand" src="assets/dummy/logo.png" style="margin:5px 0 0 80px;">
                </a>
            </div>
            <div class="navbar-right" style="margin:18px 80px 0 0">
                <button class="btn btn-primary btn-lg">用户登录</button>
            </div>
        </nav>
        <?php  echo Form::open(array('url' => 'logonPost','method' => 'post')); ?>
        <!-- Main Content -->

        <div class="container" style="width:1280px;background:url('assets/dummy/logon-back.jpg');background-size:cover;height:720px;)">
            <div class="title-text col-sm-offset-1 col-sm-3" style="margin-top:400px;">
                <img src="assets/dummy/logon-word.png"/>
            </div>
            <div class="form-horizontal">
                <div class="col-sm-4 col-sm-offset-3" style="margin-top:100px;">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <?php echo $err; ?>
                            <div class="form-group"></div>
                            <div class="form-group"></div>
                            <div class="form-group"></div>
                            <div class="form-group"></div>
                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <label>用户名/手机号/电子邮箱</label>
                                    <input class="form-control input-lg" name="usr" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <label>登录密码</label>
                                    <input type="password" class="form-control input-lg" name="pwd" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <a class="pull-right btn btn-lg btn-link">忘记用户名或密码</a>
                                </div>
                            </div>
                            <div class="form-group"></div>
                            <div class="form-group"></div>
                            <div class="form-group"></div>
                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <input type="submit" value="立即登录" class="btn btn-primary btn-lg btn-block" style="margin:0"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <button class="btn btn-default btn-lg btn-block" style="margin:0">新用户注册</button>
                                </div>
                            </div>
                            <div class="form-group"></div>
                            <div class="form-group"></div>
                            <div class="form-group"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <div class="footer" style="height:180px; background-color:#282A2A; border-bottom:5px solid #3575E0; width:100%; overflow:hidden; margin-top:20px;">
            <div class="content">
                <ul style="margin-left:80px; font-size:1.2em; margin-top:15px;">
                    <li style="float:left;margin-left:50px;">媒体信息</li>
                    <li style="float:left;margin-left:50px;">求职信息</li>
                    <li style="float:left;margin-left:50px;">宾智博客</li>
                    <li style="float:left;margin-left:50px;">技术支持</li>
                </ul>
            </div>
            <p style="width:400px;margin-top:135px;font-size:0.8em;float:right;color:#666;">京ICP备19000101	2015 Pantheo Intelligence Inc. All Rights Reserved</p>
        </div>
    </body>
</html>