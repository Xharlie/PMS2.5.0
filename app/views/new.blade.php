<!doctype html>
<html>
	<head>
	    <base href="http://localhost/~Xharlie/Hotel_Dev0/public/">
		<meta charset="UTF-8">
		<title>Laravel PHP Framework</title>
        <!--   <link rel="stylesheet" type="text/css" href="css/temp_style.css">
            <link rel="stylesheet" type="text/css" href="css/CustomerAnimation.css">
              <link href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
        -->

        <link href="css/bootstrap/bootstrap.min.css" rel="stylesheet">


    </head>
	<body>
		<div class="main">
                <?php if($pageType =='newCheckIn'){?>
            <div class="formArea" ng-app="newCheckInModule" ng-controller="newCheckInController">
				@include('newCheckIn')
            </div>
                <?php } elseif($pageType =='newReservation'){?>
            <div class="formArea">
                @include('newReservation')
			</div>
                <?php } elseif($pageType =='newCheckOut'){?>
            <div class="formArea" ng-app="newCheckOutModule"  ng-controller="newCheckOutController">
                @include('newCheckOut')
                <?php } ?>
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
        <script src="Scripts/angularjs/angular.min.js"></script>
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

	</body>

</html>