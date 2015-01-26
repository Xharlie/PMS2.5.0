<div>
    <div class="modal-header">
        <span class="glyphicon glyphicon-send"></span>
        <label style="font-size: 15px;">新入住</label> {{}}
        <span class="pull-right btn" ng-click="cancel()">&#x2715</span>
    </div>
    <div class="col-sm-12" style="padding: 30px 20px 45px 25px;">
        <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px; ">
            <div class="col-sm-4 ">
                <label>到店日期</label>
                <div class="input-group datePick" ng-controller="Datepicker" >
                    <input type="text" class="form-control" show-weeks="false" datepicker-popup="yyyy-MM-dd"
                           ng-model="BookCommonInfo.CHECK_IN_DT" is-open="opened1" min-date="minDate" max-date="'2020-06-22'"
                           datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                           ng-required="true" close-text="Close"
                           ng-style="BookCommonInfo.CheckINStyle"/>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default" ng-click="open1($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                                        </span>
                </div>
            </div>
            <div class="col-sm-4">
                <label>留房时间</label>
                <div ng-controller="TimePickerDemoCtrl" class="removeArrow">
                    <timepicker ng-model="BookCommonInfo.arriveTime" show-meridian="true"
                                meridians="chineseM" mousewheel="false"></timepicker>
                </div>
            </div>
            <div class="col-sm-4 ">
                <label>离店日期</label>
                <div class="input-group datePick" ng-controller="Datepicker" >
                    <input type="text" class="form-control" show-weeks="false" datepicker-popup="yyyy-MM-dd"
                           ng-model="BookCommonInfo.CHECK_OT_DT" is-open="opened2" min-date="minDate2" max-date="'2020-06-22'"
                           datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                           ng-required="true" close-text="Close" ng-init="BookCommonInfo.CHECK_OT_DT = OT_DT"
                           ng-style="BookCommonInfo.CheckOTStyle"/>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-default" ng-click="open2($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                                        </span>
                </div>
            </div>
        </div>
        <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px; ">
            <div class="col-sm-4 ">
                <label>客人姓名</label>
                <input class="form-control"ng-model="reserver.Name" />
            </div>
            <div class="col-sm-4 ">
                <label>联系电话</label>
                <input class="form-control" ng-model="reserver.Phone" />
            </div>
        </div>
        <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px; ">
            <div class="col-sm-4" >
                <label>客人来源</label>
                <select class="form-control" name="sourceSelection" ng-model="BookCommonInfo.roomSource"
                        ng-change="sourceChange()" >
                    <option value="普通散客">普通预定</option>
                    <option value="会员">会员预定</option>
                    <option value="协议">协议预定</option>
                    <option value="活动码">活动码预定</option>
                </select>
            </div>
            <div class="col-sm-4 ">
                <label>预定金</label>
                <input class="form-control" ng-model="BookCommonInfo.pyament" />
            </div>
        </div>
        <div class="col-sm-12 form-group alert alert-info" style="padding-right:25px; padding-left: 25px; ">
            <div class="col-sm-5 ">
                <label>查询:{{caption.searchCaption}}</label>
                <div class="input-group">
                    <input class="form-control" ng-model="check.checkInput" ng-disabled="disable.searchDisable"/>
                    <span class="input-group-addon btn " ng-click="checkSource(BookCommonInfo.roomSource,check.checkInput)">查询</span>
                </div>
            </div>
            <div class="col-sm-7">
                <table  class="pull-right" ng-show="BookCommonInfo.roomSource =='会员'" class="ng-hide">
                    <tr>
                        <th></th>
                        <th>会员号&nbsp;&nbsp;</th>
                        <th>会员姓名</th>
                    </tr>
                    <tr  ng-repeat = "memberOption in Members |  orderBy:MEM_ID "
                         tooltip-html-unsafe="{{memberOption.summary}}"
                         tooltip-trigger="mouseenter"
                         tooltip-append-to-body="true">
                        <td><input type="radio" name="memberchoose" ng-model="BookCommonInfo.Member" ng-value="memberOption"
                                   ng-show="Members.length>1" class="ng-hide">&nbsp;</td>
                        <td>{{memberOption.MEM_ID}}&nbsp;</td>
                        <td>{{memberOption.MEM_NM}}</td>
                    </tr>
                </table>
                <table  class="pull-right" ng-show="BookCommonInfo.roomSource =='协议'" class="ng-hide">
                    <tr>
                        <th>&nbsp;</th>
                        <th>协议号&nbsp;&nbsp;</th>
                        <th>单位名称</th>
                    </tr>
                    <tr  ng-repeat = "treatyOption in Treaties |  orderBy:TREATY_ID "
                         tooltip-html-unsafe="{{treatyOption.summary}}"
                         tooltip-trigger="mouseenter"
                         tooltip-append-to-body="true">
                        <td><input  type="radio" name="treatychoose" ng-model="BookCommonInfo.Treaty" ng-value="treatyOption"
                                    ng-show="Treaties.length>1" class="ng-hide">&nbsp;</td>
                        <td>{{treatyOption.TREATY_ID}}&nbsp;</td>
                        <td>{{treatyOption.CORP_NM}}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-sm-12 " style="padding:10px 25px 0px 25px; border-top:1px solid #D3D6DE; ">
            <div class="col-sm-12"  ng-repeat="(TP,singleTP) in BookRoomByTP ">
                <div class="col-sm-3 ">
                    <label>房型</label>
                    <select class="form-control" ng-model="TP" ng-change="roomTypeChange(singleRoom)"
                            ng-options="rmTp as rmTp for (rmTp,rmList) in roomsAndRoomTypes " />
                </div>
                <div class="col-sm-3 ">
                    <label>数量</label>
                    <input class="form-control" ng-model="singleTP.roomAmount">
                </div>
                <div class="col-sm-3 ">
                    <label>每晚房价</label>
                    <input class="form-control" ng-model="singleTP.finalPrice" />
                </div>
                <div class="col-sm-3 ">
                    <label>折扣(%)</label>
                    <input class="form-control" ng-model="singleTP.discount" ng-change="discountChange4TP(singleTP)"/>
                </div>
            </div>
            <div class="col-sm-12 btn" >
                <a class="pull-right" ng-click="addCustomer(singleRoom.GuestsInfo)">添加房型</a>
            </div>
        </div>
        <div class="col-sm-12 " style="padding:10px 25px 20px 25px; border-top:1px solid #D3D6DE; ">
            <div class="col-sm-12 ">
                <label>预定备注</label>
                <textarea class="form-control" rows="4" cols="60" />
            </div>
        </div>
        <div class="col-sm-12 " style="padding-left: 25px;">
            <button class="pull-right" style="margin-top:10px;padding: 10px 30px 10px 30px; background-color:#69B4F5; color: #ffffff"
                    ng-click="confirm()">
                确认办理</button>
        </div>
    </div>
</div>