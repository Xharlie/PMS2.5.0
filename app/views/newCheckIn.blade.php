
<link rel="stylesheet" type="text/css" href="css/newIn.css">
<form xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" >
    <div class="wholeInfoBlock" ng-repeat="singleRoom in BookRoom">
        <div class="headDiv">
        <h1 class="PerRoom">选择第{{$index+1}}间房</h1>

        <button  ng-click="singleRoom.AddStyle={'display': 'none'}; Addroom();"
                 ng-style="singleRoom.AddStyle"  class="AddRoombutton" onclick="event.preventDefault();">+</button>
        <button  ng-style="(BookRoom.length==1)?{'opacity':'0'}:{'opacity':'1'}"  class="DeleteRoombutton"
                 onclick="event.preventDefault();"
                 ng-click="DeleteRoom($index);">-</button>
        <div ng-style="MasterRoomDisplay" style="display: inline-block; margin-left:10px;">
            选为联房主房:<input type="checkbox" ng-model="singleRoom.MasterRoom" ng-change="changeMaster(singleRoom)" ng-click="clickMaster(singleRoom);"
                >
        </div>
        </div>
        <div class="ChooseRoom">
            <h2>房间信息</h2>
            <label style="margin-left: 20px; font-size: large;">入住日期:</label>
            <label style="float:right; margin-right: 40%; font-size: large;">预离日期:</label>
            <div class="datePick" ng-controller="Datepicker" >
                <div class="datePicker">
                    <p class="input-group">
                        <input type="text" class="form-control" show-weeks="false" datepicker-popup="{{format}}"
                               ng-model="singleRoom.CHECK_IN_DT" is-open="opened1" min-date="minDate" max-date="'2020-06-22'"
                               datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                               ng-required="true " close-text="Close" ng-change="dateChange($index)"
                                ng-init="dateChange($index)"
                                ng-style="singleRoom.CheckInStyle"/>

                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default" ng-click="open1($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                        </span>
                    </p>
                </div>
                <div class="datePicker">
                    <p class="input-group">
                        <input type="text" class="form-control" show-weeks="false" datepicker-popup="{{format}}"
                               ng-model="singleRoom.CHECK_OT_DT" is-open="opened2" min-date="singleRoom.CHECK_IN_DT" max-date="'2020-06-22'"
                               datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                               ng-required="true" close-text="Close" ng-change="dateChange($index)"
                               ng-style="singleRoom.CheckOTStyle"/>

                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default" ng-click="open2($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                        </span>
                    </p>
                </div>
            </div>

            <label for="roomTypeSelection" class="RoomCaption" >房型</label>
            <select name="roomTypeSelection" class="RoomTypeSelection" ng-model="singleRoom.roomType"
                    ng-change="treatyChange(singleRoom);" >
                <option value="">所有房型</option>>
                <option value="Single">单人房</option>
                <option value="Double">双人房</option>
                <option value="Kingbed">大床房</option>
                <option value="SingleSupreme">元帅单人</option>
                <option value="DoubleSupreme">伯爵双房</option>
                <option value="KingbedSupreme">总统大床房</option>
            </select>
            <label for="roomIdSelection" class="RoomCaption">房号</label>
            <!--           <select name="roomIdSelection"  ng-model="singleRoom.roomSelect" class="RoomSelection"
                          ng-change="removeremovable(); MaptoType(singleRoom); "
                          ng-init="singleRoom.roomSelect"
                          ng-style="singleRoom.roomNumStyle">
                 <option value={{singleRoom.roomSelect}}  id="removable">{{singleRoom.roomSelect}}</option>
                <option value ={{room.RM_ID}}  ng-repeat="room in roomInType   | filter: roomTypeFilter(singleRoom.roomType) | filter: roomDateFilter(singleRoom.CHECK_IN_DT) | orderBy: RM_ID ">
                       {{room.RM_ID}}</option>
            </select>-->
            <select name="roomIdSelection"  ng-model="singleRoom.roomSelect" class="RoomSelection"
                    ng-change=" MaptoType(singleRoom); "
                    ng-init="roomNmInit(singleRoom) "
                    ng-style="singleRoom.roomNumStyle"
                    ng-options="room.RM_ID as room.RM_ID for room in roomInType| filter: roomTypeFilter(singleRoom.roomType)| filter: roomDateFilter(singleRoom.CHECK_IN_DT) ">
            </select>
            <label style="margin-left: 20px;">标准房价(元/天): {{ID_SUGG_match[singleRoom.roomType]}}</label>
            <label style="margin-left: 0px;" ng-init="singleRoom.price = '' ">{{singleRoom.price}}</label>
            </br>
            <select name="sourceSelection" class="RoomSourceSelection" ng-model="singleRoom.roomSource"
                    ng-change="sourceChange(singleRoom)"
                    ng-init="singleRoom.roomSource=0; ">
                <option value="0">散客</option>>
                <option value="1">会员卡</option>
                <option value="2">普通预定</option>
                <option value="3">协议</option>
            </select>


            <input class="floatRightInput"
                   ng-model="singleRoom.finalPrice"
                   placeholder="{{ID_SUGG_match[singleRoom.roomType]}}"
                   ng-init="singleRoom.finalPrice = ID_SUGG_match[singleRoom.roomType]"
            />
            <label class="floatRightLabel" >房价:</label>


            <input class="floatRightInput"
                   ng-model="singleRoom.deposit"
                   placeholder="300"
                   ng-style="singleRoom.depositStyle"/>
            <select  class="floatRightLabel" ng-model="singleRoom.payMethod"
                     ng-init="singleRoom.payMethod = 'cash'">
                <option value="cash">现金</option>>
                <option value="debit">银行卡</option>
                <option value="credit">信用卡</option>
            </select>
            <label class="floatRightLabel" >押金:</label>

            <div collapse="singleRoom.sourceCollapsed[0]">
            </div>

            <div collapse="singleRoom.sourceCollapsed[1]" class="sourceCollap">
                <label class="gustCaption">会员卡号</label>
                <input ng-model="singleRoom.checkMEM_ID" />
                <button ng-click="checkMEMbyID(singleRoom)" onclick="event.preventDefault();">查询</button>
                <label class="gustCaption">会员身份证号</label>
                <input ng-model="singleRoom.checkSSN" />
                <button ng-click="checkMEMbySSN(singleRoom)" onclick="event.preventDefault();">查询</button>
                </br>
                <label class="gustCaption">会员姓名: </label>
                <label class="gustCaption" >{{singleRoom.checkMEM_NM}}</label>
                <label class="gustCaption">会员卡级别</label>
                <label class="gustCaption">{{singleRoom.checkMEM_TP}}</label>
            </div>

            <div collapse="singleRoom.sourceCollapsed[2]" class="sourceCollap">
               <!-- {{singleRoom.sourceCollapsed[2]}}   -->
            </div>

            <div collapse="singleRoom.sourceCollapsed[3]" class="sourceCollap">
                <label class="gustCaption">协议号</label>
                <input ng-model="singleRoom.checkTreatyId"/>
                <button ng-click="checkTREATYbyID(singleRoom)" onclick="event.preventDefault();" >查询</button>
                <label class="gustCaption">公司协议名称</label>
                <input ng-model="singleRoom.checkTreatyCorp" />
                <button ng-click="checkTREATYbyCorp(singleRoom)" onclick="event.preventDefault();">查询</button>
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

                    <tr ng-repeat = "treaty in singleRoom.Treaties |  orderBy:TREATY_ID "
                        popover="{{treaty.RMARK}}"
                        popover-trigger="mouseenter"
                        ng-init="singleRoom.treatyChoose = singleRoom.Treaties[0];treatyChange(singleRoom)">
                        <td >
                            &nbsp;<input ng-change="treatyChange(singleRoom)" type="radio" ng-model="singleRoom.treatyChoose" ng-value="treaty" ><br/>
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


            <div class="d3Canvas" id="d3Canvas{{$index}}">
                <room-avilinechart num = "$index" sold-array ="singleRoom.soldArray" room-type = "singleRoom.roomType"></room-avilinechart>
            </div>
        </div>



        <div class="checkInCustomer" >
                <h2>客人信息</h2>
            {{singleRoom.memberCheck.MEM_NM}}
                <div class="guest" ng-repeat=" singleGuest in singleRoom.GuestsInfo">
                    <span class="GuestNum">{{$index+1}}</span>
                    <button  ng-style="(singleRoom.GuestsInfo.length==1)?{'opacity':'0'}:{'opacity':'1'}"  class="Deletebutton"
                             onclick="event.preventDefault();"
                             ng-click="Deletecustomer($parent.$index,$index);">-</button>

                    <label class="gustCaption" >证件号码</label>
                    <input  ng-model="singleGuest.SSNinput" ng-style = "singleGuest.markStyle"/>
                    <select name="SSNType1" class="SSNType" ng-model="singleGuest.SSNType">
                        <option value="SSN18">二代18位身份证</option>>
                        <option value="PassPort">护照</option>
                        <option value="other">其他</option>
                    </select>
                    <button id="checkButton1" class="btn btn-default checkButton" onclick="event.preventDefault();"
                            popover="{{smartIdentify(singleGuest)}}"
                            popover-trigger="mouseenter"
                            ng-mouseenter = "markStylechange(singleGuest);"
                            ng-mouseout= "singleGuest.markStyle = {'color':'black'}"
                            ng-click="showIdentity(singleGuest)">智能身份识别</button>
                    </br>
                    </br>
                    <label class="gustCaption">客人姓名</label>
                    <input name="Nameinput"  ng-model="singleGuest.NameInput"/>
                    <label  class="gustCaption">出生日期</label>
                    <input ng-model="singleGuest.BirthInput"
                        ng-style="(singleGuest.BirthInput.substring(5) == birthDateFormat(currentDate))?{'border':'2px solid gold'}:{'border':'default'}"/>
                    <form >
                        <label class="gustCaption">性别</label>
                        <input type="radio" value="M"  ng-model="singleGuest.Gender">男
                        <input type="radio" value="F" ng-model="singleGuest.Gender">女
                    </form>
                    </br>
                    <label class="gustCaption">会员卡号</label>
                    <input ng-model="singleGuest.MemberId"/>
                    <label class="gustCaption"  >合作协议</label>
                    <input ng-model="singleGuest.Treaty" />
                    </br>

                    <button class="btn btn-default RemarkButton" ng-init="singleGuest.cusInfoCollapsed=true"
                            ng-click="singleGuest.cusInfoCollapsed = !singleGuest.cusInfoCollapsed; " onclick="event.preventDefault();
                           this.innerHTML=((this.innerHTML=='顾客详细信息')?'收起顾客信息':'顾客详细信息') ">收起顾客信息</button>

                    <button class="btn btn-default RemarkButton"
                            ng-click="RemarkisCollapsed = !RemarkisCollapsed; " onclick="event.preventDefault();
                           this.innerHTML=((this.innerHTML=='记录备注')?'收起备注':'记录备注') ">记录备注</button>

                    <div collapse="singleGuest.cusInfoCollapsed" class="sourceCollap" >
                        <label class="gustCaption">省份</label>
                        <input class="clientInput" ng-model="singleGuest.Province"/>
                        <label class="gustCaption">电话</label>
                        <input class="clientInput" ng-model="singleGuest.Phone"/>
                        <label class="gustCaption">会员积分</label>
                        <input class="clientInput" ng-model="singleGuest.Points"/>
                    </div>
                    <div collapse="RemarkisCollapsed" >
                        <textarea class="RemarkInput" ng-model="singleGuest.RemarkInput" rows="3" cols="90">
                        </textarea>
                    </div>
                    <label class="timesCaption" ng-model="singleGuest.TIMES" ng-style = "(singleGuest.TIMES!='')? {'opacity':1}:{'opacity':0}">曾经来访{{singleGuest.TIMES}}次</label>
                    <button  ng-click="singleGuest.AddStyle={'display': 'none'}; Addcustomer($parent.$index);" ng-style="singleGuest.AddStyle"  class="Addbutton" onclick="event.preventDefault();">+</button>
                </div>
        </div>
    </div>


        <div class="reservationNext">

        </div>
    <input type="submit" ng-mouseenter="resetMarkedBorder(); checkInCheck()"
           popover="{{err}}"
           popover-trigger="mouseenter"
           value = "确认办理"
           ng-click="checkInSubmit()"
           style="margin-left: 90%"/>

</form>
