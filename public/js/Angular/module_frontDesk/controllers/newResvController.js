/**
 * Created by Xharlie on 8/17/14.
 *
 *
 */
appResv.controller('newResvController', function($scope, $http, newResvFactory){
//    $locationProvider.html5Mode(true).hashPrefix('!');

        $scope.BookRoom = [];
        $scope.currentDate = new Date();
        $scope.dt1 =  new Date();
        $scope.ID_TP_match = {};
        $scope.ID_SUGG_match = {};
        $scope.singleGuest = {'NameInput':'','Phone':'','checkMEM_ID':'','checkMEM_TP':'',
                                'RemarkInput':'','Email':'','MemberId':'','Points':'',
                                 checkMEM_NM:'',checkSSN:''};
        $scope.resvInfo = [];
        newResvFactory.RoomUnAvail().success(function(data){
            $scope.RoomAllinfo =data;
            for (var i = 0; i <$scope.RoomAllinfo.length; i++ ){
                $scope.ID_SUGG_match[$scope.RoomAllinfo[i]["RM_TP"]] = $scope.RoomAllinfo[i]["SUGG_PRICE"];
            }
        });
        $scope.singleRoom = {RM_QUAN:'1',RMRK:'',roomType:"",CHECK_IN_DT:new Date(),Treaties:[],treatyChoose:'',
            CHECK_OT_DT:new Date(Number(new Date())+86400000),finalPrice:"0",'roomSource':'',
            sourceCollapsed:[true,true,true,true]};

        $scope.MemCollapse = true;

        var mappingTP = function(index){
            var tpObj = ["","会员卡","普通预定","协议"]
            return tpObj[index];
        }
        $scope.checkMEMbySSN = function(checkSSN){
            $scope.singleGuest.checkSSN = '';
            $scope.singleGuest.checkMEM_ID = '';
            $scope.singleGuest.checkMEM_TP = '';
            $scope.singleGuest.checkMEM_NM = '';
            $scope.singleGuest.TIMES = '';
            $scope.singleGuest.POINTS = '';

            newResvFactory.MemberBySSN(checkSSN).success(function(data){
                $scope.MemCollapse = false;
                $scope.singleGuest.checkSSN = data[0].SSN;
                $scope.singleGuest.checkMEM_ID = data[0].MEM_ID;
                $scope.singleGuest.checkMEM_TP = data[0].MEM_TP;
                $scope.singleGuest.checkMEM_NM = data[0].MEM_NM;
                $scope.singleGuest.TIMES = data[0].TIMES;
                $scope.singleGuest.POINTS = data[0].POINTS;
            });
        }

        $scope.checkMEMbyID = function(checkMEM_ID){
            $scope.singleGuest.checkSSN = '';
            $scope.singleGuest.checkMEM_ID = '';
            $scope.singleGuest.checkMEM_TP = '';
            $scope.singleGuest.checkMEM_NM = '';
            $scope.singleGuest.TIMES = '';
            $scope.singleGuest.POINTS = '';
            newResvFactory.MemberByID(checkMEM_ID).success(function(data){
                $scope.MemCollapse = false;
                $scope.singleGuest.checkSSN = data[0].SSN;
                $scope.singleGuest.checkMEM_ID = data[0].MEM_ID;
                $scope.singleGuest.checkMEM_TP = data[0].MEM_TP;
                $scope.singleGuest.checkMEM_NM = data[0].MEM_NM;
                $scope.singleGuest.TIMES = data[0].TIMES;
                $scope.singleGuest.POINTS = data[0].POINTS;
            });
        }

        $scope.checkTREATYbyID = function(){
            $scope.singleRoom.Treaties=[];
            $scope.singleRoom.treatyChoose="";
            newResvFactory.TreatyByID($scope.singleRoom.checkTreatyId).success(function(data){
                $scope.singleRoom.Treaties = data;
            });
        }

        $scope.checkTREATYbyCorp = function(){
            $scope.singleRoom.Treaties=[];
            $scope.singleRoom.treatyChoose="";
            newResvFactory.TreatyByCORP($scope.singleRoom.checkTreatyCorp).success(function(data){
                $scope.singleRoom.Treaties = data;
            });
        }

        $scope.RemarkisCollapsed = true;

        $scope.sourceChange = function(){
            $scope.singleRoom.sourceCollapsed = [true,true,true,true];
            $scope.singleRoom.sourceCollapsed[$scope.singleRoom.roomSource] = false;
            $scope.singleRoom.price='';
            $scope.singleRoom.finalPrice = $scope.ID_SUGG_match[$scope.singleRoom.roomType];
            if($scope.singleRoom.roomSource == '3'){
                if ($scope.singleRoom.treatyChoose != ''){
                    $scope.priceChange();
                }
            }
        }

        $scope.priceChange = function(){
            if ($scope.singleRoom.roomSource == 3){
                $scope.singleRoom.price = $scope.ID_SUGG_match[$scope.singleRoom.roomType]*($scope.singleRoom.treatyChoose.DISCOUNT)/100;
                $scope.singleRoom.finalPrice = $scope.singleRoom.price;
                $scope.singleRoom.price = '* '+($scope.singleRoom.treatyChoose.DISCOUNT/100).toString()
                    +'='+ $scope.singleRoom.price.toString();
            };
        }


        $scope.roomQuanOBJ = {};
        newResvFactory.RoomQuan().success(function(data){
            $scope.roomQuan =data;
            for (var k=0; k< $scope.roomQuan.length; k++){
                var key = $scope.roomQuan[k]["RM_TP"];
                var value = $scope.roomQuan[k]["RM_QUAN"];
                $scope.roomQuanOBJ[key]=value;
            }
            $scope.dateChange();
        });

        $scope.Limit = function(num){
            return parseFloat(num).toFixed(2);
        }
        $scope.dateFormat = function(date){
            var yyyy = date.getFullYear().toString();
            var mm = (date.getMonth()+1).toString();
            var dd  = date.getDate().toString();
            return yyyy+"-" + (mm[1]?mm:"0"+mm[0])+"-" + (dd[1]?dd:"0"+dd[0]);
        }

        $scope.birthDateFormat = function(date){
            var mm = (date.getMonth()+1).toString();
            var dd  = date.getDate().toString();
            return (mm[1]?mm:"0"+mm[0])+"-" + (dd[1]?dd:"0"+dd[0]);
        }

        $scope.TstmpFormat = function(date){
            var YYYY = (date.getFullYear()).toString();
            var mm = (date.getMonth()+1).toString();
            var dd  = date.getDate().toString();
            var hh = (date.getHours()).toString();
            var mm = (date.getMinutes()).toString();
            var ss = (date.getSeconds()).toString();
            return (YYYY+"-"+(mm[1]?mm:"0"+mm[0])+"-" + (dd[1]?dd:"0"+dd[0])+" "+hh+":"+mm+":"+ss);
        }

        $scope.dateChange = function(){
            if ($scope.singleRoom.CHECK_IN_DT != undefined &&($scope.singleRoom.CHECK_IN_DT instanceof Date) ){
                var dt1 = $scope.singleRoom.CHECK_IN_DT;
                if($scope.singleRoom.CHECK_OT_DT == undefined || $scope.singleRoom.CHECK_OT_DT==""){
                    var dt2 = dt1;
                }else{
                    var dt2 = $scope.singleRoom.CHECK_OT_DT;
                }

                $scope.singleRoom.soldRaw = [];
                newResvFactory.RoomSoldOut($scope.dateFormat(dt1), $scope.dateFormat(dt2)).then(function(data){
                    $scope.singleRoom.soldRaw =data;
                    var soldArray = [];
                    var dateOBJ ={};
                    var dt1num = Number(dt1);
                    var dt2num = Number(dt2);
                    var counter = 0;
                    var AvailQuanFlag = {};
                    for (var i = dt1num; i<= dt2num; i+=86400000){
                        var addDate = JSON.parse(JSON.stringify($scope.roomQuanOBJ));
                        addDate["DATE"] = $scope.dateFormat(new Date(i));
                        soldArray.push(addDate);
                        dateOBJ[addDate["DATE"]] = counter;
                        counter ++;
                    }

                    for (var i = 0; i< $scope.singleRoom.soldRaw.length; i++){
                        var index = dateOBJ[$scope.singleRoom.soldRaw[i]["DATE"]];
                        var availQuan =soldArray[index][$scope.singleRoom.soldRaw[i]['RM_TP']]
                            - $scope.singleRoom.soldRaw[i]['RESV_QUAN']- $scope.singleRoom.soldRaw[i]['CHECK_QUAN'];
                        soldArray[index][$scope.singleRoom.soldRaw[i]['RM_TP']] = availQuan;
                        if (availQuan <= 0){
                            AvailQuanFlag[$scope.singleRoom.soldRaw[i]['RM_TP'].toString()]=false;
                        }
                    }
                    $scope.singleRoom.soldArray = soldArray;
             //       $scope.singleRoom.AvailQuanFlag = AvailQuanFlag;
                });
            }
        }

        $scope.AddstyleMarked = {};

        $scope.resetAddMarkedBorder = function(){
            if ($scope.AddstyleMarked.border != undefined){
                $scope.AddstyleMarked.border = "default";
                $scope.AddstyleMarked={};
            }
        }

        $scope.addRoomCheck = function(){
            alert($scope.resvInfo[0].CHECK_IN_DT);
            $scope.addErr = function(){
                if(!($scope.singleRoom.CHECK_IN_DT instanceof Date)){
                    $scope.singleRoom.CheckInStyle={border:"2px solid red"};
                    $scope.AddstyleMarked = $scope.singleRoom.CheckInStyle;
                    return "请您正确输入新增预定的入住时间!"
                }else if(!($scope.singleRoom.CHECK_OT_DT instanceof Date)){
                    $scope.singleRoom.CheckOTStyle={border:"2px solid red"};
                    $scope.AddstyleMarked = $scope.singleRoom.CheckOTStyle;
                    return "请您正确输入新增预定的离店时间!"
                }else if($scope.singleRoom.CHECK_OT_DT < $scope.singleRoom.CHECK_IN_DT){
                    $scope.singleRoom.CheckOTStyle={border:"2px solid red"};
                    $scope.AddstyleMarked = $scope.singleRoom.CheckOTStyle;
                    return "新增预订的离店时间早于入住时间了。。。"
                }else if($scope.singleRoom.roomType ==""){
                    $scope.singleRoom.roomTPStyle={border:"2px solid red"};
                    $scope.AddstyleMarked = $scope.singleRoom.roomTPStyle;
                    return "请输入新增预订房型"
                }else if($scope.singleRoom.finalPrice == '' || isNaN($scope.singleRoom.finalPrice)
                            || parseFloat($scope.singleRoom.finalPrice) <= 0 ){
                    $scope.singleRoom.priceStyle={border:"2px solid red"};
                    $scope.AddstyleMarked = $scope.singleRoom.priceStyle;
                    return "请您输入新增预定每间每晚单价"
                }else if(!(!isNaN($scope.singleRoom.RM_QUAN) && parseFloat($scope.singleRoom.RM_QUAN)%1 === 0 &&
                    parseFloat($scope.singleRoom.RM_QUAN)>0
                    )){
                        $scope.singleRoom.quanStyle={border:"2px solid red"};
                        $scope.AddstyleMarked = $scope.singleRoom.priceStyle;
                        return "请您正确输入新增房间数量"
                }else{
                    if($scope.singleRoom.roomSource == '1' && $scope.singleGuest.checkMEM_NM == ''){
                        $scope.singleGuest.memStyle={border:"1px solid red"};
                        $scope.AddstyleMarked = $scope.singleGuest.memStyle;
                        return "请输入预定来源'会员卡'的信息并查询"
                    }else if($scope.singleRoom.roomSource == '3' && $scope.singleRoom.treatyChoose == ''){
                        $scope.singleRoom.treatyStyle={border:"1px solid red"};
                        $scope.AddstyleMarked = $scope.singleRoom.treatyStyle;
                        return "请输入预定来源'协议'的信息并查询"
                    }
                }
            }();
        }


        $scope.addRoom = function(){
                if($scope.AddstyleMarked.border != undefined){
                    return;
                }
                var newResv = {
                    "roomSource": mappingTP($scope.singleRoom.roomSource),
                    "RESV_TMESTMP": $scope.TstmpFormat(new Date()),
                    "CHECK_IN_DT":$scope.dateFormat($scope.singleRoom.CHECK_IN_DT),
                    "CHECK_OT_DT":$scope.dateFormat($scope.singleRoom.CHECK_OT_DT),
                    "RM_TP":$scope.singleRoom.roomType,
                    "RM_QUAN": $scope.singleRoom.RM_QUAN,
                    "RMRK":$scope.singleRoom.RMRK,
                    "RESV_DAY_PAY":$scope.singleRoom.finalPrice
                };

                if($scope.singleRoom.roomSource == '1'){
                    newResv['roomSourceID'] = $scope.singleGuest.checkMEM_ID;
                }else if($scope.singleRoom.roomSource == '3'){
                    newResv['roomSourceID'] = $scope.singleRoom.treatyChoose.TREATY_ID;
                }
                var newD = new Date();
                $scope.resvInfo.push(newResv);
                $scope.singleRoom.roomType = "";
                $scope.singleRoom.finalPrice = "";
                $scope.singleRoom.RM_QUAN = "1";

        }

        $scope.deleteRoom = function(room2delete){
                $scope.resvInfo.pop(room2delete);
        }

        $scope.markStylechange = function(singleGuest){
            if(singleGuest.Pass == true){
                singleGuest.markStyle = {"color":"#28C940"};
            }else{
                singleGuest.markStyle = {"color":"red"};
            }
        }



        $scope.styleMarked = {};

        $scope.resetMarkedBorder = function(){
            if ($scope.styleMarked.border != undefined){
                $scope.styleMarked.border = "default";
                $scope.styleMarked={};
            }
        }

        $scope.checkInCheck = function(){
            $scope.err = function(){
                    if($scope.resvInfo.length < 1){
                        $scope.addButtonStyle={border:"1px solid red"};
                        $scope.styleMarked = $scope.addButtonStyle;
                        return "请您添加预定房屋后办理！"
                    }else if($scope.singleGuest.NameInput == ''){
                        $scope.singleGuest.NameStyle={border:"1px solid red"};
                        $scope.styleMarked = $scope.singleGuest.NameStyle;
                        return "请您输入预订人姓名!"
                    }else if($scope.singleGuest.Phone =='' ){
                        $scope.singleGuest.phoneStyle = {border:"1px solid red"};
                        $scope.styleMarked = $scope.singleGuest.phoneStyle;
                        return "请您输入预订人电话!"
                    }
                return "通过智能信息检测,请点击进行办理";
            }();
        }


        $scope.checkInSubmit = function(){
            if ($scope.styleMarked.border != undefined){
                return;
            }
            $scope.SubmitInfo = JSON.parse(JSON.stringify($scope.resvInfo));
            for (var i = 0; i < $scope.SubmitInfo.length; i++){
                $scope.SubmitInfo[i]["name"] = $scope.singleGuest.NameInput;
                $scope.SubmitInfo[i]["Phone"] = $scope.singleGuest.Phone;
                $scope.SubmitInfo[i]["Email"] = $scope.singleGuest.Email;
            }
            newResvFactory.resvSubmit(JSON.stringify($scope.SubmitInfo)).success(function(data){
                alert(JSON.stringify(data));
                window.close();
            });
        }
    });



    appCheckIn.controller('Datepicker', function($scope){

        $scope.today = function() {
            $scope.minDate = new Date();
        };

        $scope.today();

        // Disable weekend selection
        $scope.disabled = function(date, mode) {
            return false; //( mode === 'day' && ( date.getDay() === 0 || date.getDay() === 6 ) );
        };

        $scope.open1 = function($event) {
            $event.preventDefault();
            $event.stopPropagation();
            $scope.opened1 = true;
        };

        $scope.open2 = function($event) {
            $event.preventDefault();
            $event.stopPropagation();
            $scope.opened2 = true;
        };

        $scope.dateOptions = {
            formatYear: 'yy',
            startingDay: 1
        };

        $scope.format = 'yyyy/MM/dd';


});




appResv.controller('Datepicker', function($scope){

    $scope.today = function() {
        $scope.minDate = new Date();
    };

    $scope.today();

    // Disable weekend selection
    $scope.disabled = function(date, mode) {
        return false; //( mode === 'day' && ( date.getDay() === 0 || date.getDay() === 6 ) );
    };

    $scope.open1 = function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.opened1 = true;
    };

    $scope.open2 = function($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.opened2 = true;
    };

    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };

    $scope.format = 'yyyy/MM/dd';



});
