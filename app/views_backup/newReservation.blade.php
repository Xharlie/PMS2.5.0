
<div class="card">            

    <form class="form-horizontal" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" >
        <div class="wholeInfoBlock container-fluid" >

    <!-- Input dates of stay -->
            <h3>新预定</h3>
            <div class="datePick" ng-controller="Datepicker" >
                <div class="form-group col-sm-6">
                    <label>入住日期</label>
                    <div class="datePicker">
                        <div class="input-group">
                            <input type="text" class="form-control" show-weeks="false" datepicker-popup="{{format}}"
                                   ng-model="singleRoom.CHECK_IN_DT" is-open="opened1" min-date="minDate" max-date="'2020-06-22'"
                                   datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                                   ng-required="true " close-text="Close" ng-change="dateChange()"
                                   ng-init="dateChange()"
                                   ng-style="singleRoom.CheckInStyle"/>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" ng-click="open1($event)"><i class="glyphicon glyphicon-calendar"></i></button> <!-- open1($event) -->
                            </span>
                        </div>
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label>离店日期</label>
                    <div class="datePicker">
                        <div class="input-group">
                            <input type="text" class="form-control" show-weeks="false" datepicker-popup="{{format}}"
                                   ng-model="singleRoom.CHECK_OT_DT" is-open="opened2" min-date="singleRoom.CHECK_IN_DT" max-date="'2020-06-22'"
                                   datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                                   ng-required="true" close-text="Close" ng-change="dateChange()"
                                   ng-style="singleRoom.CheckOTStyle"/>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" ng-click="open2($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="roomResidue col-sm-12">
                <div class="d3Canvas" id="d3Canvas0">
                    <room-avilinechart ng-init ='dateChange()'
                                       num = "0" sold-array ="singleRoom.soldArray"
                                       room-type = "singleRoom.roomType">
                    </room-avilinechart>
                </div>
            </div>

            <div class="guest">
                <div class="form-group">
                    <div class="col-sm-6">
                        <label>预定人姓名*</label>
                        <input class="form-control" name="Nameinput" ng-model="singleGuest.NameInput" ng-style="singleGuest.NameStyle" placeholder="姓名"/>                
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label>联系电话*</label>
                        <input class="clientInput form-control" ng-model="singleGuest.Phone" ng-style="singleGuest.phoneStyle" placeholder="联系电话"/>
                    </div>
                    <div class="col-sm-6">
                        <label>电子邮箱</label>
                        <input class="clientInput form-control" ng-model="singleGuest.Email" type="email" placeholder="电子邮箱"/>
                    </div>
                </div>
                <div class="form-group">
                    <div ng-style='singleGuest.memStyle'>
                        <div class="col-sm-6">
                            <label>卡号查询会员</label>
                            <div class="input-group">
                                <input class="form-control" ng-model="singleGuest.checkMEM_ID" placeholder="会员卡号"/>
                                <div class="input-group-btn">
                                    <button class="btn btn-default input-btn" ng-click="checkMEMbyID(singleGuest.checkMEM_ID)" onclick="event.preventDefault();">查询</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label>身份证号查询会员</label>
                            <div class="input-group">
                                <input class="form-control" placeholder="身份证号" ng-model="singleGuest.checkSSN" />
                                <div class="input-group-btn">
                                    <button class="btn btn-default" ng-click="checkMEMbySSN(singleGuest.checkSSN)" onclick="event.preventDefault();">查询</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div collapse="MemCollapse">
                        <label class="gustCaption">会员姓名: </label>
                        <label class="" >{{singleGuest.checkMEM_NM}}</label>
                        <label class="gustCaption">会员卡级别</label>
                        <label >{{singleGuest.checkMEM_TP}}</label>
                        <label class="gustCaption">会员积分</label>
                        <input ng-model="singleGuest.POINTS" />

                    <label class="timesCaption"  ng-style = "(singleGuest.TIMES!='')? {'opacity':1}:{'opacity':0}">曾经来访{{singleGuest.TIMES}}次</label>
                    </div>
                </div>



            <div class="addRoom ">

                <div>
                    <label for="roomTypeSelection" class="RoomCaption" >房型</label>

                    <select name="roomTypeSelection" class="RoomTypeSelection" ng-model="singleRoom.roomType"
                            ng-change="priceChange();"
                            ng-style="singleRoom.roomTPStyle">
                        <option value="">所有房型</option>>
                        <option value="Single">单人房</option>
                        <option value="Double">双人房</option>
                        <option value="Kingbed">大床房</option>
                        <option value="SingleSupreme">元帅单人</option>
                        <option value="DoubleSupreme">伯爵双房</option>
                        <option value="KingbedSupreme">总统大床房</option>
                    </select>

                    <label class="gustCaption">标准房价(元/天): {{ID_SUGG_match[singleRoom.roomType]}}</label>
                    <label ng-init="singleRoom.price = '' ">{{singleRoom.price}}</label>


                    <input class="floatRightInput"
                           ng-model="singleRoom.finalPrice"
                           placeholder="{{ID_SUGG_match[singleRoom.roomType]}}"
                           ng-style="singleRoom.priceStyle"
                           />
                    <label class="floatRightLabel" >预定价(元/夜):</label>

                    </br>

                    <label class="RoomCaption">预定天数: {{ (singleRoom.CHECK_OT_DT.getTime() - singleRoom.CHECK_IN_DT.getTime())/86400000  }}天</label>
                    <label class="RoomCaption">房间数:</label>
                    <input ng-model="singleRoom.RM_QUAN"
                           ng-style="singleRoom.quanStyle"
                           placeholder="{{1}}" />
                    <label class="RoomCaption">总花费: {{
                        Limit((singleRoom.CHECK_OT_DT.getTime()
                        - singleRoom.CHECK_IN_DT.getTime())/86400000
                        * singleRoom.RM_QUAN * singleRoom.finalPrice) }}元</label>

                    <button class="btn btn-default RemarkButton" onclick="event.preventDefault();"
                            ng-click="addRoom()"
                            ng-mouseenter="resetAddMarkedBorder(); addRoomCheck()"
                            popover="{{addErr}}"
                            popover-trigger="mouseenter"
                            ng-click="checkInSubmit()"
                            ng-style = 'addButtonStyle'
                            >添加预定</button>

                    <button class="btn btn-default RemarkButton"
                            ng-click="RemarkisCollapsed = !RemarkisCollapsed; " onclick="event.preventDefault();
                            this.innerHTML=((this.innerHTML=='添加备注')?'收起备注':'添加备注') ">添加备注</button>

                    <div collapse="RemarkisCollapsed" >
                        <textarea class="RemarkInput" ng-model="singleRoom.RMRK" rows="3" cols="90">
                        </textarea>
                    </div>

                </div>
    <!--
                <div class="guestSource"  >
                    <label class="hugeCaption">预订优惠途径</label>
                    <select name="sourceSelection" class="RoomSourceSelection" ng-model="singleRoom.roomSource"
                            ng-change="sourceChange()"
                            ng-init="singleRoom.roomSource=2; ">
                        <option value="1">会员卡</option>
                        <option value="2">普通预定</option>
                        <option value="3">协议</option>
                    </select>

                    <div collapse="singleRoom.sourceCollapsed[0]">
                    </div>


                    <div collapse="singleRoom.sourceCollapsed[2]" class="sourceCollap">
    -->                    <!-- {{singleRoom.sourceCollapsed[2]}}   -->
    <!--                </div>

                    <div collapse="singleRoom.sourceCollapsed[3]" class="sourceCollap">
                        <div ng-style='singleRoom.treatyStyle'>
                            <label class="gustCaption">协议号</label>
                            <input ng-model="singleRoom.checkTreatyId"/>
                            <button ng-click="checkTREATYbyID()" onclick="event.preventDefault();" >查询</button>
                            <label class="gustCaption">公司协议名称</label>
                            <input ng-model="singleRoom.checkTreatyCorp" />
                            <button ng-click="checkTREATYbyCorp()" onclick="event.preventDefault();">查询</button>
                        </div>
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
                                ng-init="singleRoom.treatyChoose = singleRoom.Treaties[0];priceChange()">
                                <td >
                                    &nbsp;<input ng-change="priceChange()" type="radio" ng-model="singleRoom.treatyChoose" ng-value="treaty" ><br/>
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
                <div class="CrossTab">
                    <table>
                        <col width="20px" />
                        <col width="70px" />
                        <col width="80px" />
                        <col width="90px" />
                        <col width="90px" />
                        <col width="50px" />
                        <col width="50px" />
                        <col width="80px" />
                        <col width="100px" />
                        <tr>
                            <th></th>
                            <th>预定来源</th>
                            <th>来源号码</th>
                            <th>预达日期</th>
                            <th>预离时间</th>
                            <th>房型</th>
                            <th>房数</th>
                            <th>每晚价格</th>
                            <th>备注</th>
                        </tr>

                        <tr ng-repeat = "reserve in resvInfo | orderBy: RESV_TMESTMP">
                            <td><button ng-click="deleteRoom(reserve)" onclick="event.preventDefault();">-</button></td>
                            <td >
                                {{reserve.roomSource}}
                            </td>
                            <td >
                                {{reserve.roomSourceID}}
                            </td>
                            <td >
                                {{reserve.CHECK_IN_DT}}
                            </td>
                            <td >
                                {{reserve.CHECK_OT_DT}}
                            </td>
                            <td >
                                {{reserve.RM_TP}}
                            </td>
                            <td >
                                {{reserve.RM_QUAN}}
                            </td>
                            <td >
                                {{reserve.FINAL_PRICE}}
                            </td>
                            <td >
                                {{reserve.RMRK}}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    -->
        <input class="btn btn-primary"
               type="submit" ng-mouseenter="resetMarkedBorder(); checkInCheck()"
               popover="{{err}}"
               popover-trigger="mouseenter"
               value = "确认办理"
               ng-click="checkInSubmit()"
               />

    </form>

    {{singleRoom.treatyChoose.TREATY_ID}}

</div>

