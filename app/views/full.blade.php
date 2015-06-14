<!doctype html>
<html ng-app="Operationer" >
	<head>
		<meta charset="UTF-8">
		<title>Pantheo BI</title>

            <link href="assets/stylesheets/application.css" rel="stylesheet">
            <link href="assets/stylesheets/need2Add.css" rel="stylesheet">
            <link href="assets/stylesheets/loaders.css" rel="stylesheet">
        <!-- Javascript -->
        <script src="assets/javascripts/application.js"></script>


	</head>
	<body >
		<div class="sideNavArea" ng-controller="sideBarController">
			@include('sideNav')
		</div>
		<div class="main">
			<div class="infoCenterArea">
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

        <script src="Scripts/jquery/jquery-1.11.1.js"></script>
        <script src="Scripts/angularjs/angular.min.js"></script>
<!--        <script src="Scripts/angularjs/ui-bootstrap-tpls-0.11.0.js"></script>-->
        <script src="Scripts/angularjs/ui-bootstrap-tpls-0.12.1.js"></script>

        <script src="Scripts/angularjs/angular-route.js"></script>
        <script src="Scripts/angularjs/angular-animate-1.3.0.js"></script>
        <script src="Scripts/angularjs/angular-ui-0.4.0.min.js"></script>



        <!-- JS Angular for front Desk-->
        <script src="js/Angular/module_frontDesk/module.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/frontDeskController.js"></script>
        <!--  <script src="js/Angular/module_frontDesk/controllers/frontDeskModals.js"></script> -->
        <script src="js/Angular/module_frontDesk/controllers/menu/smallMenuController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/menu/largeMenuController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/menu/contentMenuController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/CheckInModalController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/CheckOutModalController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/MultiCheckInModalController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/reservationModalController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/buildInDirController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/addMemberModalController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/modifyAcctModalController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/purchaseModalController.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/xlabelController.js"></script>
        <script src="js/Angular/module_frontDesk/services/frontDeskServices.js"></script>
        <script src="js/Angular/module_frontDesk/services/frontDeskModalServices.js"></script>
        <script src="js/Angular/module_frontDesk/services/newCheckInServices.js"></script>
        <script src="js/Angular/module_frontDesk/services/newCheckOutServices.js"></script>
        <script src="js/Angular/module_frontDesk/services/newResvServices.js"></script>
        <script src="js/Angular/module_frontDesk/directives/sgDbClick.js"></script>
        <script src="js/Angular/module_frontDesk/directives/popMenu.js"></script>
        <script src="js/Angular/module_frontDesk/directives/autoScrollTo.js"></script>
        <script src="js/Angular/module_frontDesk/directives/datePickerBackUp.js"></script>
        <script src="js/Angular/module_frontDesk/directives/btnLoading.js"></script>
        <script src="js/Angular/module_frontDesk/directives/focusOn.js"></script>
        <script src="js/Angular/module_frontDesk/directives/xlabel.js"></script>
        <script src="js/Angular/module_frontDesk/pan_lib/util.js"></script>
        <script src="js/Angular/module_frontDesk/pan_lib/filter.js"></script>

    <script language="JavaScript" type="text/javascript">

    </script>
	</body>
</html>
