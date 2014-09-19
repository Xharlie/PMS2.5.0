<!doctype html>
<html ng-app="Operationer" >
	<head>
		<meta charset="UTF-8">
		<title>Laravel PHP Framework</title>
	    <link rel="stylesheet" type="text/css" href="css/temp_style.css">
       <!-- <link rel="stylesheet" type="text/css" href="css/shape.css">   shape css for fun
        <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">-->
        <link rel="stylesheet" type="text/css" href="css/CustomerAnimation.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrapModalPart.css">
        <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">
	</head>
	<body >
		<div class="sideNavArea" >
			@include('sideNav')
		</div>
		<div class="main">
			<div class="infoCenterArea">
				@include('infoCenter')
			</div>

			<div class="contentArea" ng-view ng-style="contentNgStyle = {'min-height': '500px'}">

			</div>
		</div>

        <!-- JS third party libraries
                <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.18/angular.js"></script>
                <script src="https://code.angularjs.org/1.2.18/angular-route.js"></script>
                <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.17/angular-animate.js"></script>
                <script src="http://code.jquery.com/jquery-1.11.1.js"></script>
                <script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.11.0.js"></script>     -->
        <script src="Scripts/jquery/jquery-1.11.1.js"></script>
        <script src="Scripts/jquery/jquery-ui-1.11.0.min.js"></script>
        <script src="Scripts/angularjs/angular.min.js"></script>
        <script src="Scripts/angularjs/ui-bootstrap-tpls-0.11.0.js"></script>
        <script src="Scripts/angularjs/angular-route.js"></script>
        <script src="Scripts/angularjs/angular-animate-1.3.0.js"></script>
        <script src="Scripts/angularjs/angular-ui-0.4.0.min.js"></script>


        <!-- JS Angular for front Desk-->
        <script src="js/Angular/module_frontDesk/module.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/frontDeskController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/frontDeskModals.js"></script>
        <script src="js/Angular/module_frontDesk/services/frontDeskServices.js"></script>
        <script src="js/Angular/module_frontDesk/services/frontDeskModalServices.js"></script>

    <script language="JavaScript" type="text/javascript">

    </script>
	</body>
</html>
