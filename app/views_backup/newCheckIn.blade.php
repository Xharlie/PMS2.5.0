
<link rel="stylesheet" type="text/css" href="css/newIn.css">
<form class="form" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" >
    <div class="wholeInfoBlock container-fluid" >
        <div class="sectionArea">
            <h3>新入住办理</h3>
            <div class="datePick" ng-controller="Datepicker" >
                <div class="form-group col-sm-6">
                    <label>入住日期</label>
                    <div class="datePicker">
                        <div class="input-group">
                            <input type="text" class="form-control" show-weeks="false" datepicker-popup="{{format}}"
                                   ng-model="BookCommonInfo.CHECK_IN_DT" is-open="opened1" min-date="minDate" max-date="'2020-06-22'"
                                   datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                                   ng-required="true " close-text="Close" ng-change="dateChange()"
                                   ng-style="BookCommonInfo.CheckInStyle"/>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" ng-click=""><i class="glyphicon glyphicon-calendar"></i></button> <!-- open1($event) -->
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label>离店日期</label>
                    <div class="datePicker">
                        <div class="input-group">
                            <input type="text" class="form-control" show-weeks="false" datepicker-popup="{{format}}"
                                   ng-model="BookCommonInfo.CHECK_OT_DT" is-open="opened2" min-date="BookCommonInfo.CHECK_IN_DT" max-date="'2020-06-22'"
                                   datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                                   ng-required="true" close-text="Close" ng-change="dateChange()"
                                   ng-style="BookCommonInfo.CheckOTStyle"
                                   ng-init=""/>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" ng-click="open2($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div ng-style='BookCommonInfo.memStyle'>
                    <div class="col-sm-6">
                        <label>卡号查询会员</label>
                        <div class="input-group">
                            <input class="form-control" ng-model="BookCommonInfo.checkMEM_ID" placeholder="会员卡号"/>
                            <div class="input-group-btn">
                                <button class="btn btn-default input-btn" ng-click="checkMEMbyID(BookCommonInfo.checkMEM_ID)" onclick="event.preventDefault();">查询</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label>合作协议</label>
                        <input class="form-control" placeholder="合作协议" ng-model="BookCommonInfo.Treaty" />
                    </div>
                </div>
            </div>
<!--Trump moved customer source to here , start here -->
            <div class="form-group col-sm-6">
                <label for="sourceSelection">客人来源</label>
                <select class="form-control" name="sourceSelection" class="roomSourceSelection" ng-model="BookCommonInfo.roomSource"
                        ng-change="sourceChange()"
                        ng-init="BookCommonInfo.roomSource=0; ">
                    <option value="0">散客</option>>
                    <option value="1">会员卡</option>
                    <option value="2">普通预定</option>
                    <option value="3">协议</option>
                </select>
            </div>
            <div class="form-group col-sm-12">
                    <div collapse="BookCommonInfo.sourceCollapsed[1]" class="sourceCollap">
                        <label>会员卡号</label>
                        <input ng-model="BookCommonInfo.checkMEM_ID" />
                        <button class="btn btn-default" ng-click="checkMEMbyID()" onclick="event.preventDefault();">查询</button>
                        <label>会员身份证号</label>
                        <input ng-model="BookCommonInfo.checkSSN" />
                        <button class="btn btn-default" ng-click="checkMEMbySSN()" onclick="event.preventDefault();">查询</button>
                        </br>
                        <label>会员姓名: </label>
                        <label >{{BookRoom.checkMEM_NM}}</label>
                        <label>会员卡级别</label>
                        <label>{{BookRoom.checkMEM_TP}}</label>
                    </div>
                    <div collapse="BookCommonInfo.sourceCollapsed[2]" class="sourceCollap">
                        <!-- {{BookRoom.sourceCollapsed[2]}}   -->
                    </div>
                    <div collapse="BookCommonInfo.sourceCollapsed[3]" class="sourceCollap">
                        <label>协议号</label>
                        <input ng-model="BookCommonInfo.checkTreatyId"/>
                        <button class="btn btn-default" ng-click="checkTREATYbyID()" onclick="event.preventDefault();" >查询</button>
                        <label>公司协议名称</label>
                        <input ng-model="BookCommonInfo.checkTreatyCorp" />
                        <button class="btn btn-default" ng-click="checkTREATYbyCorp()" onclick="event.preventDefault();">查询</button>
                        <table class="CrossTab">
                            <tr>
                                <th>&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                <th>协议号</th>
                                <th>公司协议名称</th>
                                <th>协议类型</th>
                                <th>联系人</th>
                                <th>联系人电话</th>
                                <th>折扣</th>
                            </tr>

                            <tr ng-repeat = "treaty in BookCommonInfo.Treaties |  orderBy:TREATY_ID "
                                popover="{{treaty.RMARK}}"
                                popover-trigger="mouseenter"
                                ng-init="BookCommonInfo.treatyChoose = BookCommonInfo.Treaties[0];treatyChange(BookRoom)">
                                <td >
                                    &nbsp;<input ng-change="treatyChange()" type="radio" ng-model="BookCommonInfo.treatyChoose" ng-value="treaty" ><br/>
                                </td>
                                <td >
                                    {{treaty.TREATY_ID}}
                                </td>
                                <td >
                                    {{treaty.CORP_NM}}
                                </td>
                                <td >
                                    {{treaty.TREATY_TP}}
                                </td>
                                <td >
                                    {{treaty.CONTACT_NM}}
                                </td>
                                <td >
                                    {{treaty.CONTACT_PHONE}}
                                </td>
                                <td >
                                    {{treaty.DISCOUNT}}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
<!--Trump moved customer source to here , end here -->
<!--Trump ng-repeat and ng-style sample(change check out date to see bar change..) , start here -->
                <div>
                    <table>
                        <tr ng-repeat="(key, value) in roomQuanOBJRecorder">
                            <td>
                                <input  ng-style ="( isNaN(roomTypeSelectQuan[key]) ||
                                                roomTypeSelectQuan[key]<0 ||
                                                roomTypeSelectQuan[key]>value)
                                                ?{'color':'red'}:{'color':'black'}"
                                        ng-model="roomTypeSelectQuan[key]"
                                        ng-change="changeRoomNum(key)"
                                        placeholder=""/>
                            </td>
                            <td>
                                <label> {{ key }}: </label>
                            </td>
                            <td>
                                <label>可选房间数{{ value }}/{{roomQuanOBJ[key]}}</label>
                            </td>
                            <td>
                                <div  style="margin-left: 10px; display: inline-block; width: 100px; height: 20px;position: relative;border: 1px solid blue; background: orange;">
                                    <span style="background-color:#f5f5f5;display:block;height: 100%; " ng-style="{'width':value/roomQuanOBJ[key]*100+'%'} "></span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
<!--Trump ng-repeat and ng-style sample(change check out date to see bar change..)  , end here -->
            </div>



        <div class="sectionArea" ng-repeat="singleRoom in BookRoom">

            <div class="chooseRoom" >
<!--Trump enable add room and delete room button start here -->
                <div class="form-group">
                    <h4 style="display: inline-block">房间{{$index+1}}信息</h4>
                    <button  ng-click="singleRoom.AddStyle={'display': 'none'}; Addroom('');"
                             ng-style="singleRoom.AddStyle"  class="btn btn-default AddRoombutton" onclick="event.preventDefault();">添加房间</button>
                    <button  ng-style="(BookRoom.length==1)?{'opacity':'0'}:{'opacity':'1'}"  class="btn btn-default DeleteRoombutton"
                             onclick="event.preventDefault();"
                             ng-click="DeleteRoom($index);">删除房间</button>
                    <div ng-style="MasterRoomDisplay">
                        选为联房主房:<input type="checkbox" ng-model="singleRoom.MasterRoom" ng-change="changeMaster(singleRoom)" ng-click="clickMaster(singleRoom);">
                    </div>
                </div>
<!--Trump enable add room and delete room button end here -->
                <div class="form-group col-sm-4">
                    <label for="roomTypeSelection">房型*</label>
                    <select class="form-control" name="roomTypeSelection" class="RoomTypeSelection"
                            ng-model="singleRoom.roomType"
                            ng-change="roomTypeNumUpdate(singleRoom);treatyChange(singleRoom);singleRoom.Cards_num=cardNum(singleRoom.roomType)" >
                        <option value="">所有房型</option>
                        <option value="Single">单人房</option>
                        <option value="Double">双人房</option>
                        <option value="Kingbed">大床房</option>
                        <option value="SingleSupreme">元帅单人</option>
                        <option value="DoubleSupreme">伯爵双房</option>
                        <option value="KingbedSupreme">总统大床房</option>
                    </select>
                </div>
                <div class="form-group col-sm-4">
                    <label for="roomIdSelection" class="RoomCaption">房间号*</label>
                    <select class="form-control" name="roomIdSelection"  ng-model="singleRoom.roomSelect" class="roomSelection"
                            ng-change=" MaptoType(singleRoom); "
                            ng-init="roomNmInit(singleRoom) "
                            ng-style="singleRoom.roomNumStyle"
                            ng-options="room.RM_ID as room.RM_ID for room in roomInType| filter: roomTypeFilter(singleRoom.roomType)| filter: roomDateFilter(singleRoom.CHECK_IN_DT) ">
                    </select>
                </div>

     <!--           <label>标准房价(元/天): {{ID_SUGG_match[singleRoom.roomType]}}</label>
                <label>{{singleRoom.price}}</label> 
                <label>房卡数量</label>
                <input ng-model="singleRoom.Cards_num"
                       ng-init="singleRoom.Cards_num=cardNum(singleRoom.roomType)"
                       /> -->
                <div class="form-group col-sm-5">
                    <label>每晚房价*</label>
                    <div class="input-group">
                        <input class="form-control" ng-model="singleRoom.finalPrice" placeholder="{{ID_SUGG_match[singleRoom.roomType]}}" ng-init="singleRoom.finalPrice = (singleRoom.finalPrice != '')? singleRoom.finalPrice:ID_SUGG_match[singleRoom.roomType]"/>
                        <span class="input-group-addon">元</span>
                    </div>
                </div>
                <div class="form-group col-sm-5">
                    <label>押金*</label>
                    <div class="input-group">
                        <input class="form-control" ng-model="singleRoom.deposit" placeholder="300" ng-style="singleRoom.depositStyle"/>
                        <span class="input-group-addon">元</span>
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label>支付方式</label>
                    <select class="form-control" ng-model="singleRoom.payMethod" ng-init="singleRoom.payMethod = '现金'">
                        <option value="现金">现金</option>>
                        <option value="银行卡">银行卡</option>
                        <option value="信用卡">信用卡</option>
                    </select>
                </div>



     <!--           <div class="d3Canvas" id="d3Canvas{{$index}}">
                    <room-avilinechart num = "$index" sold-array ="singleRoom.soldArray" room-type = "singleRoom.roomType"></room-avilinechart>
                </div> -->
            </div>

            <div class="checkInCustomer col-sm-12">

                    <div ng-repeat=" singleGuest in singleRoom.GuestsInfo">
                        <h4>房间{{$index+1}}客人</h4>
                        {{singleRoom.memberCheck.MEM_NM}}
                    <!--                   <span>{{$index+1}}</span>-->
                        <div >
                            <button  ng-style="(singleRoom.GuestsInfo.length==1)?{'opacity':'0'}:{'opacity':'1'}"
                                     class="btn btn-default Deletebutton"
                                     onclick="event.preventDefault();"
                                     ng-click="Deletecustomer($parent.$index,$index);">删除客人</button>
                        </div>
                        <div class="form-group col-sm-2">

                            <label>客人姓名</label>
                            <input name="Nameinput" class="form-control" ng-model="singleGuest.NameInput"/>
                        </div>
                        <div class="form-group col-sm-2">
                            <label>证件类型</label>
                            <select name="SSNType1" class="SSNType form-control" ng-model="singleGuest.SSNType">
                                <option value="SSN18">身份证</option>>
                                <option value="PassPort">护照</option>
                                <option value="other">其他</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-4">
                            <label>证件号码</label>
                            <div class="input-group">
                                <input class="form-control" ng-model="singleGuest.SSNinput" ng-style = "singleGuest.markStyle"/>
                                <span class="input-group-btn">
                                    <button id="checkButton1" class="btn btn-default checkButton" onclick="event.preventDefault();"
                                            popover="{{smartIdentify(singleGuest)}}"
                                            popover-trigger="mouseenter"
                                            popover-append-to-body="true"
                                            ng-mouseenter = "markStylechange(singleGuest);"
                                            ng-mouseout= "singleGuest.markStyle = {'color':'black'}"
                                            ng-click="showIdentity(singleGuest)">识别</button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-sm-1">
                            <label>性别</label>
                            <select class="form-control" ng-model="singleGuest.Gender">
                                <option value="M">男</option>>
                                <option value="F">女</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-3">
                            <label>出生日期</label>
                            <input class="form-control"
                                   ng-model="singleGuest.BirthInput"
                                   ng-style="(singleGuest.BirthInput.substring(5) == birthDateFormat(currentDate))?{'border':'2px solid gold'}:{'border':'default'}"/>
                        </div>
<!--Trump Province,Phone,Membership Points,Remark，times visited; 如果是会员且在 上一行 输入身份证号后点击‘识别’(例如 110108199006136017)，则这些信息会被提取
                                            angular 戴一个collapse，如果信息太多了可以用这个 展开信息button控制这些信息collapse， start here -->

<!-- 带 collapse效果的
                        <div >
                            <div class="form-group col-sm-2">
                                <label class="timesCaption" ng-model="singleGuest.TIMES"
                                       ng-style = "(singleGuest.TIMES!='')? {'opacity':1}:{'opacity':0}">曾经来访{{singleGuest.TIMES}}次</label>
                                <button class="form-control" ng-init="singleGuest.cusInfoCollapsed=true"
                                        ng-click="singleGuest.cusInfoCollapsed = !singleGuest.cusInfoCollapsed; " onclick="event.preventDefault();
                               this.innerHTML=((this.innerHTML=='展开详细信息')?'收起顾客信息':'展开详细信息') ">展开详细信息</button>
                            </div>

                            <div collapse="singleGuest.cusInfoCollapsed" class="form-group col-sm-2">
                                <label>省份</label>
                                <input class="form-control" ng-model="singleGuest.Province"/>
                            </div>
                            <div collapse="singleGuest.cusInfoCollapsed" class="form-group col-sm-2">
                                <label>电话</label>
                                <input class="form-control" ng-model="singleGuest.Phone"/>
                            </div>
                            <div collapse="singleGuest.cusInfoCollapsed" class="form-group col-sm-2">
                                <label>会员卡号</label>
                                <input class="form-control" ng-model="singleGuest.MemberId"/>
                            </div>
                            <div collapse="singleGuest.cusInfoCollapsed" class="form-group col-sm-2">
                                <label>会员积分</label>
                                <input class="form-control" ng-model="singleGuest.Points"/>
                            </div>
                            <div collapse="singleGuest.cusInfoCollapsed" class="form-group col-sm-12">
                                <label>备注</label>
                                <textarea class="form-control" ng-model="singleGuest.RemarkInput" rows="3" cols="90">
                                </textarea>
                            </div>
                        </div>
下面不带 collapse效果    -->

                        <div >
                            <div class="form-group col-sm-2">
                                <label>省份</label>
                                <input class="form-control" ng-model="singleGuest.Province"/>
                            </div>
                            <div class="form-group col-sm-2">
                                <label>电话</label>
                                <input class="form-control" ng-model="singleGuest.Phone"/>
                            </div>
                            <div class="form-group col-sm-2">
                                <label>会员卡号</label>
                                <input class="form-control" ng-model="singleGuest.MemberId"/>
                            </div>
                            <div class="form-group col-sm-2">
                                <label>会员积分</label>
                                <input class="form-control" ng-model="singleGuest.Points"/>
                            </div>
                            <div class="form-group col-sm-2">
                                <label class="timesCaption" ng-model="singleGuest.TIMES"
                                       ng-style = "(singleGuest.TIMES!='')? {'opacity':1}:{'opacity':0}">曾经来访{{singleGuest.TIMES}}次</label>
                            </div>
                            <div class="form-group col-sm-12">
                                <label>备注</label>
                                <textarea class="form-control" ng-model="singleGuest.RemarkInput" rows="3" cols="90">
                                </textarea>
                            </div>
                        </div>
<!--Trump Province,Phone,Membership Points,Remark，times visited; 如果是会员且在 上一行 输入身份证号后点击‘识别’(例如 110108199006136017)，则这些信息会被提取
                                            angular 戴一个collapse，如果信息太多了可以用这个 展开信息button控制这些信息collapse， end here -->

                        <button ng-click="singleGuest.AddStyle={'display': 'none'}; Addcustomer($parent.$index);"
                                ng-style="singleGuest.AddStyle"
                                class="btn btn-default Addbutton col-sm-12"
                                onclick="event.preventDefault();">添加客人</button>
                    </div>
            </div>
        </div>
    </div>

    <input class="btn btn-primary"
            type="submit" ng-mouseenter="resetMarkedBorder(); checkInCheck()"
           popover="{{err}}"

           popover-trigger="mouseenter"
           value = "确认办理"
           ng-click="checkInSubmit()"
           />
</form>
