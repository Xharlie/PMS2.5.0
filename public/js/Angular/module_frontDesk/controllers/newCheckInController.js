appCheckIn.controller('newCheckInController', function($scope, $http, newCheckInFactory){
    /* Database version */
//    $locationProvider.html5Mode(true).hashPrefix('!');
    $scope.BookRoom = [];
    $scope.CONN_RM_ID = "";
    $scope.currentDate = new Date();
    $scope.dt1 =  new Date();
    var pathArray = window.location.href.split("/");
    $scope.RoomNumArray = pathArray.slice(pathArray.indexOf('newCheckIn')+1);
    $scope.roomInType = [];
    $scope.ID_TP_match = {};
    $scope.ID_SUGG_match = {};
    newCheckInFactory.RoomUnAvail().success(function(data){
            $scope.RoomAllinfo =data;
            for (var i = 0; i <$scope.RoomAllinfo.length; i++ ){
                $scope.roomInType.push({RM_ID : $scope.RoomAllinfo[i]["RM_ID"],
                    RM_TP : $scope.RoomAllinfo[i]["RM_TP"],
                    RM_CONDITION: $scope.RoomAllinfo[i]["RM_CONDITION"],
                    CHECK_OT_DT :   $scope.RoomAllinfo[i]["CHECK_OT_DT"]
                });
                $scope.ID_SUGG_match[$scope.RoomAllinfo[i]["RM_TP"]] = $scope.RoomAllinfo[i]["SUGG_PRICE"];
                $scope.ID_TP_match[$scope.RoomAllinfo[i]["RM_ID"]] = $scope.RoomAllinfo[i]["RM_TP"];
            }
            for (var i =0; i< $scope.RoomNumArray.length; i++){
                var newRoom = {roomType:"",roomSelect:"",CHECK_IN_DT:new Date(),CHECK_OT_DT:new Date(Number(new Date())+86400000),
                    sourceCollapsed:[true,true,true,true],finalPrice:"",deposit:"300",
                    GuestsInfo:[{MEM_TP:"",Points:"",Phone:"",SSNinput:"",SSNType:"SSN18",NameInput:"",BirthInput:"", Gender:"",
                        MemberId:"",Treaty:"",RemarkInput:"",Province:"",markStyle:"",Pass:false,TIMES:""}]};
                $scope.BookRoom.push(newRoom);
                $scope.BookRoom[i].roomSelect = $scope.RoomNumArray[i];
                $scope.BookRoom[i].roomType = $scope.ID_TP_match[$scope.RoomNumArray[i]];
            }
            if($scope.RoomNumArray.length < 2){
                $scope.MasterRoomDisplay = {"display":"none"};
            }else{
                $scope.BookRoom[0]["MasterRoom"] = true;
                $scope.CONN_RM_ID = $scope.BookRoom[0].roomSelect;
            }
        });

    $scope.clickMaster = function(singleRoom){
        if(!singleRoom.MasterRoom){
            for (var i = 0; i< $scope.BookRoom.length; i++){
                $scope.BookRoom[i].MasterRoom = false;
            }
            singleRoom.MasterRoom = true;
            $scope.CONN_RM_ID = singleRoom.roomSelect;
        }else{
            alert("联房主房不可不选");
            singleRoom.MasterRoomFlag = "true";
            $scope.$apply();
        }
    };
    $scope.changeMaster = function(singleRoom){
        if (singleRoom.MasterRoomFlag == "true"){
            singleRoom.MasterRoomFlag ="";
            singleRoom.MasterRoom = true;
        }
    }
    $scope.roomTypeFilter = function(RM_TP){
            return function(room){
                 return (RM_TP == "" || room.RM_TP == RM_TP);
            };
    }

    $scope.roomDateFilter = function(IN){
        return function(room){
            if (IN > new Date()){
                return (new Date(room.CHECK_OT_DT) <  IN);
            }else {
                return (room.RM_CONDITION == "Empty");
            }
        };
    };


    $scope.roomNmInit = function(singleRoom){
        for (var i =0; i<$scope.roomInType.length; i++){
            if ($scope.roomInType[i].RM_ID == singleRoom.roomSelect){
            singleRoom.roomSelect = $scope.roomInType[i].RM_ID;
            return;
            }
        }
    }


    $scope.MaptoType = function(singleRoom){
        singleRoom.roomType = $scope.ID_TP_match[singleRoom.roomSelect];
        //room.roomType = $scope.ID_TP_match[room.roomSelect];
    }

    $scope.roomQuanOBJ = {};
    newCheckInFactory.RoomQuan().success(function(data){
        $scope.roomQuan =data;
        for (var k=0; k< $scope.roomQuan.length; k++){
            var key = $scope.roomQuan[k]["RM_TP"];
            var value = $scope.roomQuan[k]["RM_QUAN"];
            $scope.roomQuanOBJ[key]=value;
        }

    });

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

    $scope.dateChange = function($index){
        if ($scope.BookRoom[$index].CHECK_IN_DT != undefined &&($scope.BookRoom[$index].CHECK_IN_DT instanceof Date) ){
                var dt1 = $scope.BookRoom[$index].CHECK_IN_DT;
                if($scope.BookRoom[$index].CHECK_OT_DT == undefined || $scope.BookRoom[$index].CHECK_OT_DT==""){
                    var dt2 = dt1;
                }else{
                    var dt2 = $scope.BookRoom[$index].CHECK_OT_DT;
                }

                $scope.BookRoom[$index].soldRaw = [];
                newCheckInFactory.RoomSoldOut($scope.dateFormat(dt1), $scope.dateFormat(dt2)).then(function(data){
                    $scope.BookRoom[$index].soldRaw =data;
                    var soldArray = [];
                    var dateOBJ ={};
                    var dt1num = Number(dt1);
                    var dt2num = Number(dt2)+86400000;
                    var counter = 0;
                    var AvailQuanFlag = {};
                    for (var i = dt1num; i<= dt2num; i+=86400000){
                        var addDate = JSON.parse(JSON.stringify($scope.roomQuanOBJ));
                        addDate["DATE"] = $scope.dateFormat(new Date(i));
                        soldArray.push(addDate);
                        dateOBJ[addDate["DATE"]] = counter;
                        counter ++;
                    }

                    for (var i = 0; i< $scope.BookRoom[$index].soldRaw.length; i++){
                        var index = dateOBJ[$scope.BookRoom[$index].soldRaw[i]["DATE"]];
                        var availQuan =soldArray[index][$scope.BookRoom[$index].soldRaw[i]['RM_TP']]
                            - $scope.BookRoom[$index].soldRaw[i]['RESV_QUAN']- $scope.BookRoom[$index].soldRaw[i]['CHECK_QUAN'];
                        soldArray[index][$scope.BookRoom[$index].soldRaw[i]['RM_TP']] = availQuan;
                        if (availQuan <= 0){
                            AvailQuanFlag[$scope.BookRoom[$index].soldRaw[i]['RM_TP'].toString()]=false;
                        }
                    }
                    $scope.BookRoom[$index].soldArray = soldArray;
                    $scope.BookRoom[$index].AvailQuanFlag = AvailQuanFlag;
                });
        }
    }

    $scope.checkMEMbySSN = function(singleRoom){
        newCheckInFactory.MemberBySSN(singleRoom.checkSSN).success(function(data){
            singleRoom.checkSSN = data[0].SSN;
            singleRoom.checkMEM_ID = data[0].MEM_ID;
            singleRoom.checkMEM_TP = data[0].MEM_TP;
            singleRoom.checkMEM_NM = data[0].MEM_NM;

        });
    }

    $scope.checkMEMbyID = function(singleRoom){
        newCheckInFactory.MemberByID(singleRoom.checkMEM_ID).success(function(data){
            singleRoom.checkSSN = data[0].SSN;
            singleRoom.checkMEM_ID = data[0].MEM_ID;
            singleRoom.checkMEM_TP = data[0].MEM_TP;
            singleRoom.checkMEM_NM = data[0].MEM_NM;
        });
    }

    $scope.checkTREATYbyID = function(singleRoom){
        newCheckInFactory.TreatyByID(singleRoom.checkTreatyId).success(function(data){
            singleRoom.Treaties = data;

        });
    }

    $scope.checkTREATYbyCorp = function(singleRoom){
        newCheckInFactory.TreatyByCORP(singleRoom.checkTreatyCorp).success(function(data){
            singleRoom.Treaties = data;
        });
    }

    $scope.RemarkisCollapsed = true;

    $scope.sourceChange = function(singleRoom){
        singleRoom.sourceCollapsed = [true,true,true,true];
        singleRoom.sourceCollapsed[singleRoom.roomSource] = false;
        singleRoom.price='';
        singleRoom.finalPrice = $scope.ID_SUGG_match[singleRoom.roomType];
        if(singleRoom.roomSource == 3){
            if (singleRoom.treatyChoose!=undefined){
                $scope.treatyChange(singleRoom);
            }
        }
    }

    $scope.treatyChange = function(singleRoom){
        if (singleRoom.roomSource == 3){
            singleRoom.price = $scope.ID_SUGG_match[singleRoom.roomType]*(singleRoom.treatyChoose.DISCOUNT)/100;
            singleRoom.finalPrice = singleRoom.price;
            singleRoom.price = '* '+(singleRoom.treatyChoose.DISCOUNT/100).toString()+'='+singleRoom.price.toString();
        };
    }


    $scope.GuestsCheckInfo={BirthInput:"", Gender:"",Province:""};


//    $scope.BookRoom[0].GuestsInfo.push({MEM_TP:"",Points:"",Phone:"",SSNinput:"",SSNType:"SSN18",NameInput:"",BirthInput:"", Gender:"",MemberId:"",Treaty:"",RemarkInput:"",Province:"",markStyle:"",Pass:false, TIMES:""});


    $scope.smartIdentify = function(singleGuest){

        singleGuest.SSNinput.replace(/^\s+|\s+$/g,"");
        if(singleGuest.SSNinput == ""){
            return "请您输入客人证件号以查询";
        } else if(singleGuest.SSNType=="SSN18"){
                var sIdCard = JSON.parse(JSON.stringify(singleGuest.SSNinput));
                if (sIdCard.match(/^\d{17}(\d|X)$/gi)==null) {//判断是否全为18或15位数字，最后一位可以是大小写字母X
                    singleGuest.Pass=false;
                    return "身份证号码须为18位数字";      //允许用户输入大小写X代替罗马数字的Ⅹ
                }
                else if (sIdCard.length==18) {
                    if (CheckIdCard.province(sIdCard) && CheckIdCard.birthday18(sIdCard) &&CheckIdCard.validate(sIdCard)) {
                       singleGuest.Pass=true;
                       $scope.GuestsCheckInfo.Gender= CheckIdCard.gender18(sIdCard);
                       return "身份证号码合法";
                    }
                    else{
                        singleGuest.Pass=false;
                        if(!CheckIdCard.province(sIdCard)){
                            return "证件前二位省份代码错误";
                        }else if(!CheckIdCard.birthday18(sIdCard)){
                            return "证件出生日期部分错误";
                        }else if(!CheckIdCard.validate(sIdCard)){
                            return "证件末位验证位错误";
                        }
                    }
                }

        }else{
              return "检测功能更新中";
        }
    }
    $scope.markStylechange = function(singleGuest){
        if(singleGuest.Pass == true){
            singleGuest.markStyle = {"color":"#28C940"};
        }else{
            singleGuest.markStyle = {"color":"red"};
        }
    }
//
    $scope.showIdentity = function(singleGuest){
        singleGuest.TIMES='';
        if (singleGuest.Pass == true){
            singleGuest.BirthInput=$scope.GuestsCheckInfo.BirthInput;
            singleGuest.Gender=$scope.GuestsCheckInfo.Gender;
            singleGuest.Province=$scope.GuestsCheckInfo.Province;
            var $SSN =singleGuest.SSNinput;
            newCheckInFactory.HistoCustomer($SSN).success(function(data){
                var history  = data;
                if (data!= undefined && data.length>0){
                    singleGuest.NameInput = history[0].NM;
                    singleGuest.MemberId = history[0].MEM_ID;
                    singleGuest.TIMES = history[0].TIMES;
                    if (singleGuest.MemberId != undefined && singleGuest.MemberId != ""){

                            newCheckInFactory.MemberByID(singleGuest.MemberId).success(function(d){
                                singleGuest.cusInfoCollapsed = false;
                                singleGuest.Points=d[0].POINTS;
                                singleGuest.Province=d[0].PROV;
                                if(singleGuest.Phone.trim() == ""){
                                    singleGuest.Phone=d[0].PHONE;
                                }
                            });
                    }
                }
            });

        }
    }




// check Card
    var CheckIdCard={
        //Wi 加权因子 Xi 余数0~10对应的校验码 Pi省份代码
        Wi:[7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2],
        Xi:[1,0,"X",9,8,7,6,5,4,3,2],
        Pi:[11,12,13,14,15,21,22,23,31,32,33,34,35,36,37,41,42,43,44,45,46,50,51,52,53,54,61,62,63,64,65,71,81,82,91],

        //检验18位身份证号码出生日期是否有效
        //parseFloat过滤前导零，年份必需大于等于1900且小于等于当前年份，用Date()对象判断日期是否有效。
        birthday18:function(sIdCard){
            var year=parseFloat(sIdCard.substr(6,4));
            var month=parseFloat(sIdCard.substr(10,2));
            var day=parseFloat(sIdCard.substr(12,2));
            var checkDay=new Date(year,month-1,day);
            var nowDay=new Date();
            if (1900<=year && year<=nowDay.getFullYear() && month==(checkDay.getMonth()+1) && day==checkDay.getDate()) {
                $scope.GuestsCheckInfo.BirthInput=$scope.dateFormat(checkDay);
                return true;
            };
            return false;
        },

        //检验15位身份证号码出生日期是否有效
        birthday15:function(sIdCard){
            var year=parseFloat(sIdCard.substr(6,2));
            var month=parseFloat(sIdCard.substr(8,2));
            var day=parseFloat(sIdCard.substr(10,2));
            var checkDay=new Date(year,month-1,day);
            if (month==(checkDay.getMonth()+1) && day==checkDay.getDate()) {
                return true;
            };
            return false;
        },

        gender18:function(sIdCard){
        var genderChar= parseInt(sIdCard.substr(16,1));
            if (genderChar%2 == 1) {
                return "M";
            }
            else {
                return "F";
            }
        },
        //检验校验码是否有效
        validate:function(sIdCard){
            var aIdCard=sIdCard.split("");
            var sum=0;
            for (var i = 0; i < CheckIdCard.Wi.length; i++) {
                sum+=CheckIdCard.Wi[i]*aIdCard[i]; //线性加权求和
            };
            var index=sum%11;//求模，可能为0~10,可求对应的校验码是否于身份证的校验码匹配
            if (CheckIdCard.Xi[index]==aIdCard[17].toUpperCase()) {
                return true;
            };
            return false;
        },

        //检验输入的省份编码是否有效
        province:function(sIdCard){
            var p2=sIdCard.substr(0,2);
            for (var i = 0; i < CheckIdCard.Pi.length; i++) {
                if(CheckIdCard.Pi[i]==p2){
                    return true;
                };
            };
            return false;
        }

//                else if (sIdCard.length==15) {
//                      if (CheckIdCard.province(sIdCard)&&CheckIdCard.brithday15(sIdCard)) {
//                            return "身份证号码合法";
//                      }
//                      else{
//                            return "请输入有效的身份证号码";
//                      };
//                };


    };

    $scope.Addroom =  function(){
        var newRoom = {roomType:"",roomSelect:"",CHECK_IN_DT:new Date(),CHECK_OT_DT:new Date(Number(new Date())+86400000),
            sourceCollapsed:[true,true,true,true],finalPrice:"",deposit:"300",
            GuestsInfo:[{MEM_TP:"",Points:"",Phone:"",SSNinput:"",SSNType:"SSN18",NameInput:"",BirthInput:"", Gender:"",
            MemberId:"",Treaty:"",RemarkInput:"",Province:"",markStyle:"",Pass:false,TIMES:""}]};
        $scope.BookRoom.push(newRoom);
    }

    $scope.Addcustomer = function(parentIndex){
        var newGuest = {MEM_TP:"",Points:"",Phone:"",SSNinput:"",SSNType:"SSN18",NameInput:"",BirthInput:"", Gender:"",
            MemberId:"",Treaty:"",RemarkInput:"",Province:"",markStyle:"",Pass:false,TIMES:""};

        $scope.BookRoom[parentIndex].GuestsInfo.push(newGuest);
    }

    $scope.Deletecustomer = function(parentIndex,index){
        if($scope.BookRoom[parentIndex].GuestsInfo.length!=1){
            $scope.BookRoom[parentIndex].GuestsInfo.splice(index,1);
            $scope.BookRoom[parentIndex].GuestsInfo[$scope.BookRoom[parentIndex].GuestsInfo.length-1].AddStyle={'display': 'inline'};
        }
    }

    $scope.DeleteRoom = function($index){
        if($scope.BookRoom.length!=1){
            $scope.BookRoom.splice($index,1);
            $scope.BookRoom[$scope.BookRoom.length-1].AddStyle={'display': 'inline'};
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
              var roomDuplicateCheckOBJ = {};
              var SSNDuplicateCheckOBJ={};
              for (var i = 0; i<$scope.BookRoom.length; i++){
                  //alert(JSON.stringify($scope.BookRoom[i].AvailQuanFlag));
                  if($scope.BookRoom[i].roomSelect =="请选房号" || $scope.BookRoom[i].roomSelect == undefined){
                      $scope.BookRoom[i].roomNumStyle={border:"2px solid red"};
                      $scope.styleMarked = $scope.BookRoom[i].roomNumStyle;
                      return "请您选择第" + (i+1).toString()+"间房的房间号码!"
                  }else if(roomDuplicateCheckOBJ[$scope.BookRoom[i].roomSelect] == "1"){
                      return $scope.BookRoom[i].roomSelect+"号房被重复选择";
                  }else if (!($scope.BookRoom[i].CHECK_IN_DT instanceof Date)){
                      $scope.BookRoom[i].CheckInStyle={border:"2px solid red"};
                      $scope.styleMarked = $scope.BookRoom[i].CheckInStyle;
                      return "请您正确输入第" + (i+1).toString()+"间房的入住时间!"
                  }else {
                      if ($scope.BookRoom[i].CHECK_OT_DT !="" ){
                         if(!($scope.BookRoom[i].CHECK_OT_DT instanceof Date)){
                             $scope.BookRoom[i].CheckOTStyle={border:"2px solid red"};
                             $scope.styleMarked = $scope.BookRoom[i].CheckOTStyle;
                             return "请您正确输入第" + (i+1).toString()+"间房的离店时间!"
                         }else if($scope.BookRoom[i].CHECK_OT_DT < $scope.BookRoom[i].CHECK_IN_DT){
                             $scope.BookRoom[i].CheckOTStyle={border:"2px solid red"};
                             $scope.styleMarked = $scope.BookRoom[i].CheckOTStyle;
                             return "第" + (i+1).toString()+"间房的离店时间早于入住时间了。。。"
                         }
                      }
                      if($scope.BookRoom[i].AvailQuanFlag[$scope.ID_TP_match[$scope.BookRoom[i].roomSelect]] == false){
                          $scope.BookRoom[i].roomNumStyle={border:"2px solid red"};
                          $scope.styleMarked = $scope.BookRoom[i].roomNumStyle;
                          return "第" + (i+1).toString()+"间房的房型在所选的某些天内已被订满!"
                      }else if($scope.BookRoom[i].deposit < 300+ parseFloat($scope.BookRoom[i].finalPrice) *
                       Math.round(($scope.BookRoom[i].CHECK_OT_DT.getTime() - $scope.BookRoom[i].CHECK_IN_DT.getTime())/86400000)){

                              $scope.BookRoom[i].depositStyle={border:"2px solid red"};
                              $scope.styleMarked = $scope.BookRoom[i].depositStyle;

                              return "第" + (i+1).toString()+"间房需要至少"
                                  +( 300+ parseFloat($scope.BookRoom[i].finalPrice) *
                                Math.round(
                                            (
                                                $scope.BookRoom[i].CHECK_OT_DT.getTime()
                                                - $scope.BookRoom[i].CHECK_IN_DT.getTime()
                                            )
                                            /86400000
                                           )
                                   )+ "元";
                      }else{
                          for (var j = 0; j<$scope.BookRoom[i].GuestsInfo.length; j++){
                              var singleGuest = $scope.BookRoom[i].GuestsInfo[j];
                              singleGuest.SSNinput.replace(/^\s+|\s+$/g,"");
                              if(singleGuest.SSNinput == ""){
                                  $scope.BookRoom[i].GuestsInfo[j].markStyle={border:"2px solid red"};
                                  $scope.styleMarked =  $scope.BookRoom[i].GuestsInfo[j].markStyle;
                                  return "请您输入第" + (i+1).toString()+"间房第" + (j+1).toString()+"位客人的证件号";
                              } else if(SSNDuplicateCheckOBJ[singleGuest.SSNinput] == "1"){
                                  return "证件号"+singleGuest.SSNinput+"被重复使用";
                              }else if(singleGuest.SSNType=="SSN18"){
                                  var sIdCard = JSON.parse(JSON.stringify(singleGuest.SSNinput));
                                  if (sIdCard.match(/^\d{17}(\d|X)$/gi)==null || sIdCard.length!=18 || !CheckIdCard.province(sIdCard) || !CheckIdCard.birthday18(sIdCard) || !CheckIdCard.validate(sIdCard)){
                                      $scope.BookRoom[i].GuestsInfo[j].markStyle={border:"2px solid red"};
                                      $scope.styleMarked =  $scope.BookRoom[i].GuestsInfo[j].markStyle;
                                      return "第" + (i+1).toString()+"间房第" + (j+1).toString()+"位客人的证件号不正确";
                                  }
                              }
                              SSNDuplicateCheckOBJ[singleGuest.SSNinput] = "1";
                          }
                      }
                  }
                  roomDuplicateCheckOBJ[$scope.BookRoom[i].roomSelect]="1";
              }
              return "通过智能信息检测,请点击进行办理";
        }();
    }


    $scope.checkInSubmit = function(){
        if ($scope.styleMarked.border != undefined){
            return;
        }
        $scope.SubmitInfo =[];
        for (var i = 0; i<$scope.BookRoom.length; i++){
            var room = $scope.BookRoom[i];
            if (room.finalPrice ==""){
                room.finalPrice = $scope.ID_SUGG_match[room.roomType];
            }
            $scope.SubmitInfo.push({roomSelect:room.roomSelect, roomType:room.roomType, CHECK_IN_DT:$scope.dateFormat(room.CHECK_IN_DT)
                ,CHECK_OT_DT:$scope.dateFormat(room.CHECK_OT_DT),finalPrice: room.finalPrice, roomSource:room.roomSource,
                deposit:room.deposit, payMethod: room.payMethod, GuestsInfo: room.GuestsInfo});
            if (room.roomSource ==3 && room.treatyChoose != null){
                $scope.SubmitInfo[i]["TREATY_ID"]= room.treatyChoose.TREATY_ID;
            }else if(room.roomSource ==1 && room.checkMEM_TP != undefined){
                $scope.SubmitInfo[i]["MEM_ID"]= room.checkMEM_ID;
            }

            if ($scope.CONN_RM_ID != ""){
                $scope.SubmitInfo[i]["Conn_RM_ID"]=$scope.CONN_RM_ID;
                if( i!=0 && $scope.SubmitInfo[i]["roomSelect"] == $scope.CONN_RM_ID){
                    var temp = JSON.parse(JSON.stringify($scope.SubmitInfo[0]));
                    $scope.SubmitInfo[0]= JSON.parse(JSON.stringify($scope.SubmitInfo[i]));
                    $scope.SubmitInfo[i] = temp;
                }
            }
        }

        //alert(JSON.stringify($scope.SubmitInfo));
        newCheckInFactory.submit(JSON.stringify($scope.SubmitInfo)).success(function(data){
            alert(JSON.stringify(data));
            window.close();
        });
    }

    //$scope.myData = [10,20,30,40,60];
    /* static verion
     $scope.resvInfo = resrvFactory.resvShowStatic(); */
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

