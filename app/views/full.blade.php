<!doctype html>
<html ng-app="Operationer" >
	<head>
		<meta charset="UTF-8">
        <meta http-equiv="X-UA-COMPATIBLE" content="IE-edge">
		<title>Pantheo BI</title>

            <link href="assets/stylesheets/application.css" rel="stylesheet">
            <link href="assets/stylesheets/loaders.css" rel="stylesheet">
            <link href="assets/fonts/fontello/css/fontello.css" rel="stylesheet">

        <!-- Javascript -->
        <script src="assets/javascripts/application.js"></script>

        <!----      IE detection          --->
        <!--[if lt IE 9]><script src="js/Angular/module_frontDesk/pan_lib/browserDetection.js"></script><![endif]-->
	</head>
	<body>
        <script>
            var uni ={userInfo : JSON.parse('<?php echo json_encode($userInfo); ?>')};
        </script>
		<div class="sideNavArea" ng-controller="sideBarController" >
                @include('sideNav')
        </div>
		<div class="main">
            <div class="infoCenterArea" hidden>
				@include('infoCenter')
			</div>
            <div class="content">
    			<div class="contentArea container-fluid" ng-view ng-style="contentNgStyle = {'min-height': '500px'}">
    			</div>
            </div>
		</div>

        <!-- JS third party libraries
                <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.18/angular.js"></script>
                <script src="https://code.angularjs.org/1.2.18/angular-route.js"></script>
                <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.17/angular-animate.js"></script>
                <script src="http://code.jquery.com/jquery-1.11.1.js"></script>
                <script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.11.0.js"></script>     -->
        <!-- <script src="Scripts/angularjs/angular-ui-0.4.0.min.js"></script> -->


        <script src="Scripts/jquery/jquery-1.11.1.js"></script>
        <script src="Scripts/angularjs/angular_1.3.0/angular.min.js"></script>
        <!--  <script src="Scripts/angularjs/ui-bootstrap-tpls-0.11.0.js"></script>     any problem, try change back to this version-->
                <script src="Scripts/angularjs/angular_1.3.0/ui-bootstrap-tpls.min.js"></script>
                <script src="Scripts/angularjs/angular_1.3.0/angular-route.min.js"></script>
                <script src="Scripts/angularjs/angular_1.3.0/angular-animate.min.js"></script>
                <script src="Scripts/angularjs/angular_1.3.0/angular-cookies.min.js"></script>

                <!-- JS Angular for front Desk-->
        <script src="js/Angular/module_frontDesk/module.js"></script>

        <script src="js/Angular/module_frontDesk/controllers/frontDeskController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/menu/smallMenuController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/menu/largeMenuController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/menu/contentMenuController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/checkInModalController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/checkOutModalController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/multiCheckInModalController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/reservationModalController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/addMemberModalController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/modifyAcctModalController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/purchaseModalController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/depositModalController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/shiftSelectModalController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/wakeUpCallModalController.js"></script>

        <script src="js/Angular/module_frontDesk/controllers/directiveControllers/buildInDirController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/directiveControllers/xlabelController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/partControllers/paymentController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/partControllers/roomSourceController.js"></script>

        <script src="js/Angular/module_frontDesk/services/frontDeskServices.js"></script>
        <script src="js/Angular/module_frontDesk/services/frontDeskModalServices.js"></script>
        <script src="js/Angular/module_frontDesk/services/newCheckInServices.js"></script>
        <script src="js/Angular/module_frontDesk/services/newCheckOutServices.js"></script>
        <script src="js/Angular/module_frontDesk/services/newResvServices.js"></script>
        <script src="js/Angular/module_frontDesk/services/sessionNcookies.js"></script>

        <script src="js/Angular/module_frontDesk/services/internalLogic/paymentService.js"></script>
        <script src="js/Angular/module_frontDesk/services/internalLogic/roomService.js"></script>
        <script src="js/Angular/module_frontDesk/services/internalLogic/roomStatusService.js"></script>


        <script src="js/Angular/module_frontDesk/directives/sgDbClick.js"></script>
        <script src="js/Angular/module_frontDesk/directives/popMenu.js"></script>
        <script src="js/Angular/module_frontDesk/directives/autoScrollTo.js"></script>
        <script src="js/Angular/module_frontDesk/directives/datePickerBackUp.js"></script>
        <script src="js/Angular/module_frontDesk/directives/btnLoading.js"></script>
        <script src="js/Angular/module_frontDesk/directives/focusOn.js"></script>
        <script src="js/Angular/module_frontDesk/directives/xlabel.js"></script>
        <script src="js/Angular/module_frontDesk/directives/paymentModule.js"></script>
        <script src="js/Angular/module_frontDesk/directives/roomSourceModule.js"></script>

        <script src="js/Angular/module_frontDesk/pan_lib/util.js"></script>
        <script src="js/Angular/module_frontDesk/pan_lib/filter.js"></script>
        <script src="js/Angular/module_frontDesk/pan_lib/structure.js"></script>
        <script src="js/Angular/module_frontDesk/pan_lib/icons.js"></script>

        <script src="js/Angular/module_frontDesk/hardwareAPI/tinyPrinter.js"></script>

    <script language="JavaScript" type="text/javascript">

    </script>
	</body>
</html>
