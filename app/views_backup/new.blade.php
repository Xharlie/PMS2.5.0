<!doctype html>
<html>
	<head>
	    <base href="http://localhost/~Xharlie/BI_Dev_Shared/public/">
		<meta charset="UTF-8">
		<title></title>
        <!--   <link rel="stylesheet" type="text/css" href="css/temp_style.css">
            <link rel="stylesheet" type="text/css" href="css/CustomerAnimation.css">
              <link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
        -->

                    <!-- Bootstrap -->
            <link href="assets/stylesheets/application.css" rel="stylesheet">
         
            <!-- Javascript -->
            <script src="assets/javascripts/application.js"></script>


    </head>
	<body>
		<div class="main">
            <div class="content">
                <div class="contentArea formArea">
                    <?php if($pageType =='newCheckIn'){?>
                <div class="formArea" ng-app="newCheckInModule" ng-controller="newCheckInController">
    				@include('newCheckIn')
                </div>
                    <?php } elseif($pageType =='newResv'){?>
                <div class="formArea" ng-app="newResvModule" ng-controller="newResvController">
                    @include('newReservation')
                </div>
                    <?php } elseif($pageType =='newCheckOut'){?>
                <div class="formArea" ng-app="newCheckOutModule"  ng-controller="newCheckOutController">
                    @include('newCheckOut')
                </div>
                    <?php } elseif($pageType =='newModifyWindow'){?>
                <div class="formArea" ng-app="newModifyWindowModule"  ng-controller="newModifyWindowController">
                    @include('newModifyWindow')
                </div>
                    <?php } ?>
            </div>
        </div>
	</div>


        <!-- JS third party libraries-->
        <!--   <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.js"></script>

             <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.17/angular-animate.js"></script>

             <script src="https://code.angularjs.org/1.2.18/angular-route.js"></script>
          <script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.11.0.js"></script>
             <script src="http://code.jquery.com/jquery-1.11.1.js"></script>
             <script src="http://d3js.org/d3.v3.js"></script>
             -->
        <script src="Scripts/angularjs/angular.min.1.2.js"></script>
        <script src="Scripts/angularjs/ui-bootstrap-tpls-0.11.0.js"></script>
        <script src="Scripts/angularjs/angular-route.js"></script>
        <script src="Scripts/angularjs/angular-animate.js"></script>
        <script src="Scripts/jquery/jquery-1.11.1.js"></script>
        <script src="Scripts/d3/d3.v3.js"></script>
        <!--


                      -->




           <!-- JS Angular for front Desk
           <script src="js/Angular/module_frontDesk/controllers/frontDeskModals.js"></script>
           <script src="js/Angular/module_frontDesk/services/frontDeskModalServices.js"></script>      -->
        <script src="js/Angular/module_frontDesk/newCheckInModule.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/newCheckInController.js"></script>
        <script src="js/Angular/module_frontDesk/services/newCheckInServices.js"></script>

        <script src="js/Angular/module_frontDesk/directives/RoomAvaiLineChart.js"></script>

        <script src="js/Angular/module_frontDesk/newCheckOutModule.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/newCheckOutController.js"></script>
        <script src="js/Angular/module_frontDesk/services/newCheckOutServices.js"></script>

        <script src="js/Angular/module_frontDesk/newResvModule.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/newResvController.js"></script>
        <script src="js/Angular/module_frontDesk/services/newResvServices.js"></script>

        <script src="js/Angular/module_frontDesk/newModifyWindowModule.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/newModifyWindowController.js"></script>
        <script src="js/Angular/module_frontDesk/services/newModifyWindowServices.js"></script>

        <script src="js/Angular/module_frontDesk/module.js"></script>
        <script src="js/Angular/module_frontDesk/controllers/frontDeskModals.js"></script>
        <script src="js/Angular/module_frontDesk/services/frontDeskModalServices.js"></script>

	</body>

</html>