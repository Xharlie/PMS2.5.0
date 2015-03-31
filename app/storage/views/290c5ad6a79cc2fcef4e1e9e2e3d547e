<div class="col-sm-12">
    <form class="form-horizontal" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" >
        <div class="card card-default">
            <div class="card-actions">
                <h3><div class="title-decor title-decor-lg"></div>预定信息</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="col-sm-1 control-label">预定人</label>
                    <div class="col-sm-3">
                        <input id="nameTarget" class="form-control"  ng-model="singleGuest.nameInput"
                               ng-change="nameCheck()" ng-style="ngStyles.nameStyle" placeholder="姓名"
                               popover-append-to-body="true"
                               popover="{{errMessage.nameErr}}"
                               popover-trigger="wrong"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-1 control-label">联系电话</label>
                    <div class="col-sm-3">
                        <input id="phoneTarget" class="clientInput form-control" ng-model="singleGuest.phone"
                               ng-change="phoneCheck()" ng-style="ngStyles.phoneStyle" placeholder="联系电话"
                               popover-append-to-body="true"
                               popover="{{errMessage.phoneErr}}"
                               popover-trigger="wrong"/>
                    </div>
                </div>
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
                                       ng-style="ngStyles.checkInDTStyle"

                                    />
                                <span class="input-group-btn">
                                    <button id="checkInDTTarget"
                                            popover-append-to-body="true"
                                            popover="{{errMessage.checkInDTErr}}"
                                            popover-placement="right"
                                            popover-trigger="wrong"
                                            type="button" class="btn btn-default" ng-click="open1($event)">
                                        <i class="glyphicon glyphicon-calendar"></i></button> <!-- open1($event) -->
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">离店时间</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input
                                    type="text" class="form-control" show-weeks="false" datepicker-popup="{{format}}"
                                    ng-model="BookCommonInfo.CHECK_OT_DT" is-open="opened2" min-date="BookCommonInfo.CHECK_IN_DT" max-date="'2020-06-22'"
                                    datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                                    ng-required="true" close-text="Close" ng-change="dateChange()"
                                    ng-style="ngStyles.checkOutDTStyle"
                                    />
                                <span class="input-group-btn">
                                    <button id="checkOutDTTarget"
                                            popover-append-to-body="true"
                                            popover="{{errMessage.checkOutDTErr}}"
                                            popover-trigger="wrong"
                                            popover-placement="right"
                                            type="button" class="btn btn-default" ng-click="open2($event)"><i class="glyphicon glyphicon-calendar"></i></button>
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
                                        <input  id="{{key}}roomNumTarget"
                                                class="form-control"
                                                ng-style ="( isNaN(BookType[key].roomTypeSelectQuan) ||
                                                        BookType[key].roomTypeSelectQuan<0 ||
                                                        BookType[key].roomTypeSelectQuan>value)
                                                        ?{'color':'red'}:{'color':'black'}"
                                                ng-model="BookType[key].roomTypeSelectQuan"
                                                ng-change="changeRoomNum(key)"
                                                popover-append-to-body="true"
                                                popover="{{roomTypeErrMsg[key].roomQuanErr}}"
                                                popover-trigger="wrong"/>
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
                                    <input id="{{key}}priceTarget"
                                           class="form-control"
                                           ng-model="BookType[key].roomNegoPriceOBJ"
                                           ng-change="changePrice(key)"
                                           popover-append-to-body="true"
                                           popover="{{roomTypeErrMsg[key].priceErr}}"
                                           popover-trigger="wrong"
                                        />
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-3 form-group pull-right">
                        <label>总计(元):</label>
                        <input class="form-control"
                               ng-model="BookCommonInfo.totalPrice" disabled />
                    </div>
                </div>
                <div class="ctrlArea">
                    <div class="ctrlLeft">
                        <button class="btn btn-default" ng-click="checkInSubmit()">保存并关闭</button>
                    </div>
                    <div class="ctrlRight">
                        <button class="btn btn-default">入住办理</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>



