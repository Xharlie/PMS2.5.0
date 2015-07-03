<div class="col-sm-12">
    <form class="form-horizontal" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
          ng-switch on="viewClick">
        <div class="card card-default  animate-switch"  ng-switch-when ="checkIn">
            <div class="card-actions">
                <h3><div class="title-decor title-decor-lg"></div>入住信息</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="col-sm-1 control-label">客人来源</label>
                    <div class="col-sm-3">
                        <select class="form-control" name="sourceSelection" class="roomSourceSelection" ng-model="BookCommonInfo.roomSource"
                                ng-change="sourceChange()"
                                ng-init="BookCommonInfo.roomSource=0; ">
                            <option value="0">普通散客</option>
                            <option value="1">会员</option>
                            <option value="2">协议</option>
                            <option value="3">活动码</option>
                        </select>
                    </div>
                </div>
                <div>
                    <div collapse="BookCommonInfo.sourceCollapsed[0]" class="sourceCollap">
                        <!-- {{BookRoom.sourceCollapsed[2]}}   -->
                    </div>
                    <div collapse="BookCommonInfo.sourceCollapsed[1]" class="sourceCollap alert alert-info" style="overflow:hidden">
                        <div class="form-group">
                            <label class="col-sm-1 control-label">会员查询</label>
                            <div class="input-group col-sm-4">
                                <input ng-model="BookCommonInfo.checkMEM" class="form-control"/>
                                <div class="input-group-btn">
                                    <button class="btn btn-default" ng-click="checkMem(BookCommonInfo.checkMEM)" onclick="event.preventDefault();">查询</button>
                                </div>
                            </div>
                        </div>
        <!--                    <label>会员姓名:</label>-->
        <!--                    <label >{{BookCommonInfo.checkMEM_NM}};</label>-->
        <!--                    <label>{{BookCommonInfo.checkMEM_TP}}</label>-->
                        <div class="col-sm-10 col-sm-offset-1">
                            <table class="CrossTab table table-bordered">
                                <tr>
                                    <th>客人姓名</th>
                                    <th>会员号</th>
                                    <th>会员类型</th>
                                    <th>身份证号</th>
                                </tr>
                                <tr>
                                    <td>{{BookCommonInfo.checkMEM_NM}}</td>
                                    <td>{{BookCommonInfo.checkMEM_ID}}</td>
                                    <td>{{BookCommonInfo.checkMEM_TP}}</td>
                                    <td>{{BookCommonInfo.checkSSN}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div collapse="BookCommonInfo.sourceCollapsed[2]" class="sourceCollap alert alert-info" style="overflow:hidden">
                        <div class="form-group">
                            <label class="col-sm-1 control-label">协议号</label>
                            <div class="input-group col-sm-4">
                                <input ng-model="BookCommonInfo.checkTreaty" class="form-control"/>
                                <div class="input-group-btn">
                                    <button class="btn btn-default" ng-click="checkTREATY(BookCommonInfo.checkTreaty)" onclick="event.preventDefault();" >查询</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-10 col-sm-offset-1">
                            <table class="CrossTab table table-bordered">
                                <tr>
                                    <th>协议号</th>
                                    <th>公司协议名称</th>
                                    <th>协议类型</th>
                                    <th>联系人</th>
                                    <th>联系人电话</th>
                                    <th>折扣</th>
                                    <th>&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                </tr>
                                <tr ng-repeat = "treaty in BookCommonInfo.Treaties |  orderBy:TREATY_ID "
                                    popover="{{treaty.RMARK}}"
                                    popover-trigger="mouseenter"
                                    ng-init="BookCommonInfo.treatyChoose = BookCommonInfo.Treaties[0];treatyChange()">
                                    <td >
                                        {{treaty.TREATY_ID}}
                                    </td>
                                    <td>
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
                                    <td>
                                        &nbsp;<input ng-change="treatyChange()" type="radio" ng-model="BookCommonInfo.treatyChoose" ng-value="treaty" ><br/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="datePick" ng-controller="Datepicker" >
                    <div class="form-group">
                        <label class="col-sm-1 control-label">入住时间</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                 <input type="text" class="form-control" show-weeks="false" datepicker-popup="{{format}}"
                                           ng-model="BookCommonInfo.CHECK_IN_DT" is-open="opened1" min-date="minDate" max-date="'2020-06-22'"
                                           datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                                           ng-required="true " close-text="Close" ng-change="dateChange()"
                                           ng-init="dateChange()"
                                           ng-style="ngStyles.checkInDTStyle" disabled/>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default" disabled><i class="glyphicon glyphicon-calendar"></i></button>
                                </span>
                             </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">离店时间</label>
                        <div class="col-sm-3">
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
                    <label class="col-sm-1 control-label">房型选择</label>
                </div>



                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-1">
                        <table class="table table-bordered">
                            <tr>
                                <th>房型</th>
                                <th>预定数量</th>
                                <th>房型余量</th>
                                <th>折扣</th>
                                <th>门市价</th>
                                <th>协商价</th>
                            </tr>
                            <tr ng-repeat="(key, value) in roomQuanOBJRecorder">
                                <td><label> {{ key }}: </label></td>
                                <td class="col-sm-2">
                                    <div class="input-group">
                                        <input  class="form-control"
                                                ng-style ="( isNaN(roomTypeSelectQuan[key]) ||
                                                        roomTypeSelectQuan[key]<0 ||
                                                        roomTypeSelectQuan[key]>value)
                                                        ?{'color':'red'}:{'color':'black'}"
                                                ng-model="roomTypeSelectQuan[key]"
                                                ng-change="changeRoomNum(key)"
                                                placeholder=""/>
                                        <span class="input-group-addon">间</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped active" role="progressbar"
                                             aria-valuenow="5" aria-valuemin="0" aria-valuemax="1" style="width:{{value*100/roomQuanOBJ[key]}}%;">
                                            <span>{{ value }}间可选</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{(BookCommonInfo.treatyChoose =='')?'N/A':BookCommonInfo.treatyChoose.DISCOUNT/10}}</td>
                                <td>{{(BookCommonInfo.treatyChoose =='')?ID_SUGG_match[key]+'元'
                                    :ID_SUGG_match[key]+' X '+(BookCommonInfo.treatyChoose.DISCOUNT/100)+'='
                                    + Limit(ID_SUGG_match[key]*(BookCommonInfo.treatyChoose.DISCOUNT/100))+'元'}}</td>
                                <td class="col-sm-2">
                                    <input class="form-control" ng-model="roomNegoPriceOBJ[key]" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="ctrlArea">
                    <div class="ctrlRight">
                        <button class="btn btn-default" ng-click="switchView()">选房完毕</button>
                    </div>
                </div>
            </div>
        </div>

<!------------------------------------view separator ------------------------------------------------------------------------------------>

        <div class="card card-default  animate-switch" ng-switch-when ="modify">
            <div class="form-group" ng-repeat="singleRoom in BookRoom">
                <div class="card-actions">
                    <h4 style="display: inline-block"><div class="title-decor title-decor-md"></div>房间{{$index+1}}信息</h4>
                    <div ng-style="MasterRoomDisplay">
                        选为联房主房:<input type="checkbox" ng-model="singleRoom.MasterRoom" ng-change="changeMaster(singleRoom)" ng-click="clickMaster(singleRoom);">
                    </div>
                </div>
                <div class="card-body">
                    <div class="datePick" ng-controller="Datepicker" >
                        <div class="form-group">
                            <label class="col-sm-1 control-label">入住时间</label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" show-weeks="false" datepicker-popup="{{format}}"
                                           ng-model="singleRoom.CHECK_IN_DT" is-open="opened1" min-date="minDate" max-date="'2020-06-22'"
                                           datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                                           ng-required="true " close-text="Close" ng-change="dateChange()"
                                           ng-init="dateChange()"
                                           ng-style="ngStyles.checkInDTStyle"
                                           ng-disabled="singleRoom.checkInDtDisabled"/>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default"><i class="glyphicon glyphicon-calendar"></i></button>
                                </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-1 control-label">离店时间</label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" show-weeks="false" datepicker-popup="{{format}}"
                                           ng-model="singleRoom.CHECK_OT_DT" is-open="opened2" min-date="singleRoom.CHECK_IN_DT" max-date="'2020-06-22'"
                                           datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                                           ng-required="true" close-text="Close" ng-change="dateChange()"
                                           ng-style="BookCommonInfo.CheckOTStyle"
                                           ng-init=""
                                           ng-disabled="singleRoom.checkOtDtDisabled"
                                        />
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default" ng-click="open2($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-1 control-label">房型*</label>
                        <div class="col-sm-3">
                            <select class="form-control" name="roomTypeSelection" class="RoomTypeSelection"
                                    ng-model="singleRoom.roomType"
                                    ng-options="value.RM_TP as value.RM_TP for value in roomQuan"
                                    ng-change="roomTypeNumUpdate(singleRoom);treatyChange();singleRoom.Cards_num=cardNum(singleRoom.roomType)"
                                    disabled="{{rmTpDisabled}}" >
                                <option value="">选择房型</option>
                            </select>
                        </div>
                        <label class="col-sm-1 control-label">房间号*</label>
                        <div class="col-sm-2">
                            <select class="form-control" name="roomIdSelection"  ng-model="singleRoom.roomSelect" class="roomSelection"
                                    ng-change=" MaptoType(singleRoom); "
                                    ng-init="roomNmInit(singleRoom)"
                                    ng-style="singleRoom.roomNumStyle"
                                    ng-options="room.RM_ID as (room.RM_ID+'('+room.RM_CONDITION+')') for room in roomInType|
                                    filter: roomTypeFilter(singleRoom.roomType)| filter: roomDateFilter(singleRoom.CHECK_IN_DT) |
                                    orderBy: ['RM_CONDITION','RM_ID'] "
                                    ng-disabled="rmTpDisabled">
                            </select>
                        </div>
                        <div class="col-sm-3 ">
                            <div class="col-sm-4">
                                <label class="col-xs-offset-1">钟点房{{(plansFirst[singleRoom.roomType]!=undefined)?'':'(无)'}}</label>
                                <input type="checkbox" ng-model="singleRoom.tempSelected" ng-change="tempChecking(singleRoom)"
                                       ng-disabled="plansFirst[singleRoom.roomType]==undefined"/>
                            </div>
                            <div class="col-sm-8" >
                                <select class="form-control" ng-model="singleRoom.PLAN_ID" ng-if="singleRoom.tempSelected"
                                        ng-init="singleRoom.PLAN_ID = plansFirst[singleRoom.roomType]"
                                        ng-options="plan.PLAN_ID as (plan.RM_TP+': '+plan.PLAN_COV_MIN+'分钟 '+plan.PLAN_COV_PRCE+'元')
                                        for plan in plans|filter: singleRoom.roomType :true | orderBy: ['PLAN_COV_MIN','PLAN_COV_PRCE'] ">
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" ng-repeat=" singleGuest in singleRoom.GuestsInfo">
                        <label class="col-sm-1 control-label">客人{{$index+1}}</label>
                        <div class="col-sm-3">
                            <input class="form-control" ng-model="singleGuest.NameInput" placeholder="姓名">
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input class="form-control" ng-model="singleGuest.SSNinput" ng-style = "singleGuest.markStyle"
                                       placeholder="身份证号"/>
                                <span class="input-group-btn">
                                                <button id="checkButton1" class="btn btn-success checkButton" onclick="event.preventDefault();"
                                                        popover="{{smartIdentify(singleGuest)}}"
                                                        popover-trigger="mouseenter"
                                                        popover-append-to-body="true"
                                                        ng-mouseenter = "markStylechange(singleGuest);"
                                                        ng-mouseout= "singleGuest.markStyle = {'color':'black'}"
                                                        ng-click="showIdentity(singleGuest)">自动识别</button>
                                            </span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <input class="form-control" ng-model="singleGuest.Phone" placeholder="联系电话">
                        </div>
                        <div>
                            <div class="col-sm-1">
                                <button  ng-style="(singleRoom.GuestsInfo.length==1)?{'opacity':'0'}:{'opacity':'1'}"
                                         class="btn btn-default"
                                         onclick="event.preventDefault();"
                                         ng-click="Deletecustomer($parent.$index,$index);">删除</button>
                            </div>

                            <div class="col-sm-1">
                                <button ng-click="singleGuest.AddStyle={'display': 'none'}; Addcustomer($parent.$index);"
                                        ng-style="singleGuest.AddStyle"
                                        class="btn btn-default"
                                        onclick="event.preventDefault();">添加</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div ng-switch on="button2Show">
                <div class="ctrlArea" ng-switch-when="submit">
                    <div class="ctrlRight">
                        <button class="btn btn-default" ng-click="checkInSubmit()">入住办理</button>
                    </div>
                </div>

                <div class="ctrlArea" ng-switch-when="save">
                    <div class="ctrlRight">
                        <button class="btn btn-default" ng-click="saveModified()">保存修改</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>


<script type="text/ng-template" id="checkInModalContent">
    <div class="modal-header">
        <h3 class="modal-title">入住办理确认</h3>
    </div>
    <div class="modal-body container-fluid">
        <div class="col-sm-12" ng-repeat = "singleRoom in BookRoom" >
            <h4 class="PerRoom">{{singleRoom.roomSelect}}号房</h4>
            <div class="col-sm-5">
                <label class="control-label">押金</label>
                <input class="form-control" ng-model="singleRoom.fixedDeposit" />
            </div>
            <div class="col-sm-5">
                <label class="control-label">总共应交:</label>
                <input class="form-control" ng-model="singleRoom.expectedDeposit" disabled/>
            </div>
            <div class="col-sm-5">
                <label class="control-label">总共实交:</label>
                <input class="form-control" ng-model="singleRoom.deposit" ng-init = "singleRoom.deposit= singleRoom.expectedDeposit"/>
            </div>
            <div class="col-sm-5">
                <label class="control-label">付款方式:</label>
                <select class="form-control" ng-model="singleRoom.payMethod" ng-init="singleRoom.payMethod='现金'" >
                    <option value="现金">现金</option>
                    <option value="信用卡">信用卡</option>
                    <option value="银行卡">银行卡</option>
                    <option value="优惠券">优惠券</option>
                </select>
            </div>
        </div>
        <div class="ctrlRight">
            <button class="btn btn-default" ng-click="confirm()">确认办理</button>
        </div>
    </div>
</script>