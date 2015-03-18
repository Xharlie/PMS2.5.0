/**
 * Created by Xharlie on 8/17/14.
 *
 *
 */
appResv.controller('newResvController', function($scope, $http, $timeout, newResvFactory){



//  $locationProvider.html5Mode(true).hashPrefix('!');
    $scope.currentDate = new Date();
    $scope.dt1 =  new Date();
    $scope.ID_TP_match = {};
    $scope.ID_SUGG_match = {};
    $scope.BookType ={};
//    $scope.roomTypeSelectQuan = {};
//    $scope.roomNegoPriceOBJ={};
    $scope.roomQuanOBJ = {};
    $scope.singleGuest = {'nameInput':'','phone':'','email':''};

    $scope.BookCommonInfo ={CHECK_IN_DT:new Date(),CHECK_OT_DT:new Date(Number(new Date())+86400000),sourceCollapsed:[true,true,true],
                            CheckInStyle:{},CheckOTStyle:{},memStyle:{},roomSource:'',checkMEM:'',checkMEM_ID:'',checkSSN:'',checkMEM_NM:'',
                            checkMEM_TP:'',checkTreaty:'', checkTreatyId:'',checkTreatyCorp:'',Treaties:[],treatyChoose:'',soldRaw:[],RMRK:'',
                            stayLength:Math.round((new Date(Number(new Date())+86400000).getTime()
                                      -new Date().getTime())/86400000), totalPrice:0};


    $scope.errMessage={checkInDTErr:'',checkOutDTErr:'',nameErr:'',phoneErr:'',submitErr:''};
    $scope.roomTypeErrMsg={};
    $scope.ngStyles = {checkInDTStyle:'',checkOutDTStyle:'',nameStyle:'',phoneStyle:''};


    newResvFactory.RoomUnAvail().success(function(data){
        $scope.RoomAllinfo =data;
        for (var i = 0; i <$scope.RoomAllinfo.length; i++ ){
            $scope.ID_SUGG_match[$scope.RoomAllinfo[i]["RM_TP"]] = $scope.RoomAllinfo[i]["SUGG_PRICE"];
        }

        newResvFactory.RoomQuan().success(function(data){
            $scope.roomQuan =data;
            for (var k=0; k< $scope.roomQuan.length; k++){
                var key = $scope.roomQuan[k]["RM_TP"];
                var value = $scope.roomQuan[k]["RM_QUAN"];
                $scope.roomQuanOBJ[key]=value;
                $scope.BookType[key]={'roomTypeSelectQuan':0,'roomNegoPriceOBJ':$scope.ID_SUGG_match[key]};
                $scope.roomTypeErrMsg[key]={'priceErr':'','roomQuanErr':''};
            }
            $scope.refreshRoomAvailability($scope.BookCommonInfo.CHECK_IN_DT,$scope.BookCommonInfo.CHECK_OT_DT);
        });
    });


    $scope.refreshRoomAvailability = function(dt1,dt2){
        $scope.BookCommonInfo.soldRaw = [];
        newResvFactory.RoomSoldOut($scope.dateFormat(dt1), $scope.dateFormat(dt2)).then(function(data){
            $scope.BookCommonInfo.soldRaw =data;
            var soldArray = [];
            var dateOBJ ={};
            var dt1num = Number(dt1);
            var dt2num = Number(dt2);
            var counter = 0;
            var roomQuanOBJRecorder = JSON.parse(JSON.stringify($scope.roomQuanOBJ));
            for (var i = 0; i< $scope.BookCommonInfo.soldRaw.length; i++){
                var roomleft = $scope.BookCommonInfo.soldRaw[i]['RM_QUAN']
                    -$scope.BookCommonInfo.soldRaw[i]['RESV_QUAN']
                    - $scope.BookCommonInfo.soldRaw[i]['CHECK_QUAN'];
                if(roomleft<roomQuanOBJRecorder[$scope.BookCommonInfo.soldRaw[i]['RM_TP']]){
                    roomQuanOBJRecorder[$scope.BookCommonInfo.soldRaw[i]['RM_TP']] = roomleft;
                }
            }
            $scope.roomQuanOBJRecorder = roomQuanOBJRecorder;
        });
    }



        $scope.sourceChange = function(){
            $scope.BookCommonInfo.sourceCollapsed = [true,true,true,true];
            $scope.BookCommonInfo.sourceCollapsed[$scope.BookCommonInfo.roomSource] = false;
        }


/*----------------------------------------------------- check Membership method -------------------------------------------------------------*/

        $scope.checkMem = function(MEM){
            if (MEM.length == 18){
                $scope.checkMEMbySSN(MEM);
            }else{
                $scope.checkMEMbyID(MEM);
            }
        }

        $scope.checkMEMbySSN = function(checkSSN){
            $scope.BookCommonInfo.checkSSN = '';
            $scope.BookCommonInfo.checkMEM_TP = '';
            $scope.BookCommonInfo.checkMEM_ID = '';
            $scope.BookCommonInfo.checkMEM_NM = '';
            newResvFactory.MemberBySSN(checkSSN).success(function(data){
                if (data.length<1){
                    alert("查不到");
                    return;
                }
                $scope.BookCommonInfo.checkSSN = data[0].SSN;
                $scope.BookCommonInfo.checkMEM_ID = data[0].MEM_ID;
                $scope.BookCommonInfo.checkMEM_TP = data[0].MEM_TP;
                $scope.BookCommonInfo.checkMEM_NM = data[0].MEM_NM;
            });
        }

        $scope.checkMEMbyID = function(checkMEM_ID){
            $scope.BookCommonInfo.checkSSN = '';
            $scope.BookCommonInfo.checkMEM_TP = '';
            $scope.BookCommonInfo.checkMEM_ID = '';
            $scope.BookCommonInfo.checkMEM_NM = '';
            newResvFactory.MemberByID(checkMEM_ID).success(function(data){
                if (data.length<1){
                    alert("查不到");
                    return;
                }
                $scope.BookCommonInfo.checkSSN = data[0].SSN;
                $scope.BookCommonInfo.checkMEM_ID = data[0].MEM_ID;
                $scope.BookCommonInfo.checkMEM_TP = data[0].MEM_TP;
                $scope.BookCommonInfo.checkMEM_NM = data[0].MEM_NM;
            });
        }

/*----------------------------------------------------- check Treaty method -------------------------------------------------------------*/

        $scope.checkTREATY = function(treaty){
            if(isNaN(treaty)){
                $scope.checkTREATYbyCorp(treaty);
            }else{
                $scope.checkTREATYbyID(treaty);
            }
        }

        $scope.checkTREATYbyID = function(treaty){
            $scope.BookCommonInfo.Treaties=[];
            $scope.BookCommonInfo.treatyChoose="";
            newResvFactory.TreatyByID(treaty).success(function(data){
                if (data.length<1){
                    alert("查不到");
                    return;
                }
                $scope.BookCommonInfo.Treaties = data;
            });
        }

        $scope.checkTREATYbyCorp = function(treaty){
            $scope.BookCommonInfo.Treaties=[];
            $scope.BookCommonInfo.treatyChoose="";
            newResvFactory.TreatyByCORP(treaty).success(function(data){
                if (data.length<1){
                    alert("查不到");
                    return;
                }
                $scope.BookCommonInfo.Treaties = data;
            });
        }



        $scope.dateChange = function(){
            if ($scope.BookCommonInfo.CHECK_IN_DT == "" || !($scope.BookCommonInfo.CHECK_IN_DT instanceof Date)){
                $scope.ngStyles.checkInDTStyle={border:"2px solid red"};
                $scope.errMessage.checkInDTErr='请您正确输入入住时间';
                $timeout(function() {
                    angular.element('#checkInDTTarget').trigger('wrong');
                }, 0);
                $scope.BookCommonInfo.stayLength = ''
                $scope.BookCommonInfo.totalPrice='';
                return false;
            }else if ($scope.BookCommonInfo.CHECK_OT_DT == "" || !($scope.BookCommonInfo.CHECK_OT_DT instanceof Date)){
                $scope.ngStyles.checkOutDTStyle={border:"2px solid red"};
                $scope.errMessage.checkOutDTErr='请您正确输入离店时间';
                $timeout(function() {
                    angular.element('#checkOutDTTarget').trigger('wrong');
                }, 0);
                $scope.BookCommonInfo.stayLength = ''
                $scope.BookCommonInfo.totalPrice='';
                return false;
            }else if($scope.BookCommonInfo.CHECK_OT_DT < $scope.BookCommonInfo.CHECK_IN_DT){
                $scope.ngStyles.checkOutDTStyle={border:"2px solid red"};
                $scope.errMessage.checkOutDTErr = "离店时间早于入住时间";
                $timeout(function() {
                    angular.element('#checkOutDTTarget').trigger('wrong');
                }, 0);
                $scope.BookCommonInfo.stayLength = ''
                $scope.BookCommonInfo.totalPrice='';
                return false;
            }else{
                $timeout(function() {
                    angular.element('#checkOutDTTarget').trigger('right');
                    angular.element('#checkInDTTarget').trigger('right');
                }, 0);
                $scope.ngStyles.checkInDTStyle='';
                $scope.ngStyles.checkOutDTStyle='';
                var dt1 = $scope.BookCommonInfo.CHECK_IN_DT;
                var dt2 = $scope.BookCommonInfo.CHECK_OT_DT;
                $scope.refreshRoomAvailability(dt1,dt2);
                $scope.BookCommonInfo.stayLength = Math.round( (new Date(dt2).getTime()
                    -new Date(dt1).getTime())/86400000);
                $scope.showTotalPrice();
                return true;
            }
          };

          $scope.changeRoomNum = function(roomType){
              if (isNaN($scope.BookType[roomType].roomTypeSelectQuan) || $scope.BookType[roomType].roomTypeSelectQuan<0){
//                  $scope.ngStyles.roomNumStyle = {color:'red'};
                  $scope.roomTypeErrMsg[roomType].roomQuanErr ='房间数输入错误';
                  $timeout(function() {
                      angular.element('#'+roomType+'roomNumTarget').trigger('wrong');
                  }, 0);
                  $scope.BookCommonInfo.totalPrice='';
                  return false;
              }else if($scope.BookType[roomType].roomTypeSelectQuan>$scope.roomQuanOBJRecorder[roomType]){
//                  $scope.ngStyles.roomNumStyle = {color:'red'};
                  $scope.roomTypeErrMsg[roomType].roomQuanErr ='预定房间数量超过该时段空房数';
                  $timeout(function() {
                      angular.element('#'+roomType+'roomNumTarget').trigger('wrong');
                  }, 0);
                  $scope.BookCommonInfo.totalPrice='';
                  return false;
              }else{
//                  $scope.ngStyles.roomNumStyle = {color:'green'};
                  $scope.roomTypeErrMsg[roomType].roomQuanErr ='';
                  $timeout(function() {
                      angular.element('#'+roomType+'roomNumTarget').trigger('right');
                  }, 0);
                  $scope.changePrice(roomType);
                  return true;
              }
          };


          $scope.changePrice = function(rmTpNm){

                if (isNaN($scope.BookType[rmTpNm].roomNegoPriceOBJ) || $scope.BookType[rmTpNm].roomNegoPriceOBJ<0){
                   // $scope.BookRoom[rmTpNm].tpNgStyle.priceStyle = {color:'red'};
                    $scope.roomTypeErrMsg[rmTpNm].priceErr ='价格输入错误';
                    $timeout(function() {
                        angular.element('#'+rmTpNm+'priceTarget').trigger('wrong');
                    }, 0);
                    $scope.BookCommonInfo.totalPrice='';
                    return false;
                }else if( $scope.BookType[rmTpNm].roomTypeSelectQuan >0
                    && $scope.BookType[rmTpNm].roomNegoPriceOBJ ==''){
                    $scope.roomTypeErrMsg[rmTpNm].priceErr ='请输入每晚价格';
                    $timeout(function() {
                        angular.element('#'+rmTpNm+'priceTarget').trigger('wrong');
                    }, 0);
                    $scope.BookCommonInfo.totalPrice='';
                    return false;
                }else{
                 //   $scope.BookRoom[rmTpNm].tpNgStyle.priceStyle = {color:'green'};
                    $scope.roomTypeErrMsg[rmTpNm].priceErr ='';
                    $timeout(function() {
                        angular.element('#'+rmTpNm+'priceTarget').trigger('right');
                    }, 0);
                    $scope.showTotalPrice();
                    return true;
                }
          }



        $scope.showTotalPrice = function(){
            var totalPrice = 0;
            for (var rmTpNm in $scope.BookType){
                if ($scope.roomTypeErrMsg[rmTpNm].priceErr !='' || $scope.roomTypeErrMsg[rmTpNm].roomQuanErr !='' ||
                    isNaN($scope.BookCommonInfo.stayLength) || $scope.BookCommonInfo.stayLength <=0){
                    $scope.BookCommonInfo.totalPrice='';
                    return;
                }else if ($scope.BookType[rmTpNm].roomTypeSelectQuan >0 ){
                    totalPrice  += $scope.BookCommonInfo.stayLength
                                  *$scope.BookType[rmTpNm].roomTypeSelectQuan
                                  *$scope.BookType[rmTpNm].roomNegoPriceOBJ;
                }
            }
            $scope.BookCommonInfo.totalPrice = $scope.Limit(totalPrice);
        };


        $scope.nameCheck = function(){
            if ($scope.singleGuest.nameInput == ""){
                $scope.errMessage.nameErr='请输入预订人姓名';
                $timeout(function() {
                    angular.element('#nameTarget').trigger('wrong');
                }, 0);
                return false;
            }else{
                $scope.errMessage.nameErr ='';
                $timeout(function() {
                    angular.element('#nameTarget').trigger('right');
                }, 0);
                return true;
            }
        }


        $scope.phoneCheck = function(){
            if ($scope.singleGuest.phone == ""){
                $scope.errMessage.phoneErr='请输入预订人电话';
                $timeout(function() {
                    angular.element('#phoneTarget').trigger('wrong');
                }, 0);
                return false;
            }else{
                $scope.errMessage.phoneErr ='';
                $timeout(function() {
                    angular.element('#phoneTarget').trigger('right');
                }, 0);
                return true;
            }
        }


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
            var MM = (date.getMonth()+1).toString();
            var DD  = date.getDate().toString();
            var hh = (date.getHours()).toString();
            var mm = (date.getMinutes()).toString();
            var ss = (date.getSeconds()).toString();
            return (YYYY+"-"+(MM[1]?MM:"0"+MM[0])+"-" + (DD[1]?DD:"0"+DD[0])+" "+hh+":"+mm+":"+ss);
        }

        $scope.mappingTP = function(index){
            var tpObj = ["普通预定","会员","协议","活动码"];
            return tpObj[index];
        }



        $scope.checkInSubmit = function(){
            var BookRoom = {};
            for (RmTp in $scope.BookType){
                if ($scope.roomTypeErrMsg[RmTp].priceErr != '' || $scope.roomTypeErrMsg[RmTp].roomQuanErr != ''){
                    alert('请根据提示修改错误信息');
                    return;
                }else if($scope.BookType[RmTp].roomTypeSelectQuan>0){
                    BookRoom[RmTp]=$scope.BookType[RmTp];
                }
            }
            var keys = Object.keys(BookRoom);
            if (keys.length<1){
                alert("请至少预定一间房");
                return;
            };

            if (!$scope.nameCheck() || !$scope.phoneCheck()){
                alert('请根据提示修改错误信息');
                return;
            }else{
                var newResv = {
                    "roomSource": $scope.mappingTP($scope.BookCommonInfo.roomSource),
                    "RESV_TMESTMP": $scope.TstmpFormat(new Date()),
                    "CHECK_IN_DT":$scope.dateFormat($scope.BookCommonInfo.CHECK_IN_DT),
                    "CHECK_OT_DT":$scope.dateFormat($scope.BookCommonInfo.CHECK_OT_DT),
                    "BookRoom":BookRoom,
                    "email":'',
                    "RMRK":'',
                    "name":$scope.singleGuest.nameInput,
                    "phone":$scope.singleGuest.phone          //,"email":$scope.singleGuest.email
                };
                if($scope.BookCommonInfo.roomSource == '1'){
                    newResv['roomSourceID'] = $scope.BookCommonInfo.checkMEM_ID;
                }else if($scope.BookCommonInfo.roomSource == '2'){
                    newResv['roomSourceID'] = $scope.BookCommonInfo.treatyChoose.TREATY_ID;
                };
//                alert(JSON.stringify(newResv));
                newResvFactory.resvSubmit(JSON.stringify(newResv)).success(function(data){
                    if(JSON.stringify(data)!= null){
                        alert("预定成功！");
                        window.close();
                    }else{
                        alert("数据库出错")
                    }
                });
            }
        }
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
