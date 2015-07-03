<!doctype html>
<html lang="zh-Hans">
	<head>
		<meta charset="utf-8">
		<title>Pantheo Intelligence</title>
		<link rel="stylesheet" href="assets/stylesheets/application.css">
	</head>
	<body class="fluid-container" style="width:100%;height:1000px;
			background-image: -o-linear-gradient(-41deg, #03A9F4 0%, #37DDD0 100%);
			background-image: -moz-linear-gradient(-41deg, #03A9F4 0%, #37DDD0 100%);
			background-image: -ms-linear-gradient(-41deg, #03A9F4 0%, #37DDD0 100%);
			background-image: linear-gradient(-131deg, #03A9F4 0%, #37DDD0 100%);
		  background-size:cover;">
        <?php  echo Form::open(array('url' => 'logonPost','method' => 'post')); ?>
		<div class="form-horizontal">
			<div class="col-sm-4 col-sm-offset-4" style="top:100px;">
				<div class="card card-default">
					<div class="card-actions">
					</div>
					<div class="card-body">
                        <?php echo $err; ?>
						<div class="form-group" style="text-align:center;">
							<img src="assets/dummy/logo.png" style="height:64px;"/>
						</div>
						<div class="form-group"></div>

						<div class="form-group">
							<div class="col-sm-10 col-sm-offset-1">
								<input class="form-control input-lg" name="usr" placeholder="用户名/电子邮箱">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-10 col-sm-offset-1">
								<input type="password" class="form-control input-lg" name="pwd" placeholder="密码">
							</div>
						</div>
						<div class="form-group"></div>
						<div class="form-group">
                            <!-- inline style change!!!!!!! -->
							<input type="submit" value="登陆" class="btn btn-primary btn-lg col-sm-10 col-sm-offset-1" style="margin:0 36px"/>
                            <!-- inline style change!!!!!!! -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>