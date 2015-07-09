<div id="wholeModal" >
    {{payError}}

    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="glyphicon glyphicon-send"></span>
            <label>新预订</label>
            <span class="pull-right close" ng-click="cancel()">&#x2715</span>
        </h4>
    </div>
    <div class="panel-body">
        <div ng-show ="viewClick=='Info'" >
            <div class="row">
                <div class="col-sm-4 form-group">
                    <label xlabel ng-transclude checker="isDate" checkee="BookCommonInfo.CHECK_IN_DT" btn-pass="infoError" >到店日期</label>
                    <div class="input-group datePick" ng-controller="Datepicker" >
                        <input type="text" class="form-control input-lg" show-weeks="false" datepicker-popup="yyyy-MM-dd"
                               ng-model="BookCommonInfo.CHECK_IN_DT" is-open="opened1" min-date="minDate" max-date="'2020-06-22'"
                               datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                               ng-required="true" close-text="Close"
                               ng-style="BookCommonInfo.CheckINStyle"
                               datepicker-append-to-body="true" />
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default btn-lg" ng-click="open1($event)"><i class="glyphicon glyphicon-calendar" style="font-size:17px;"></i></button>
                        </span>
                    </div>
                </div>
                <div class="col-sm-4 form-group">
                    <label xlabel ng-transclude checker="isDate" checkee="BookCommonInfo.arriveTime" btn-pass="infoError">留房时间</label>
                    <div ng-controller="TimePickerDemoCtrl" class="removeArrow">
                        <timepicker ng-model="BookCommonInfo.arriveTime" show-meridian="true"
                                    meridians="chineseM" mousewheel="false"></timepicker>
                    </div>
                </div>
                <div class="col-sm-4 form-group">
                    <label xlabel ng-transclude checker="isDate|isLargerEqualThan" comparer="BookCommonInfo.CHECK_IN_DT" checkee="BookCommonInfo.CHECK_OT_DT" btn-pass="infoError" >离店日期</label>
                    <div class="input-group datePick" ng-controller="Datepicker" >
                        <input type="text" class="form-control input-lg" show-weeks="false" datepicker-popup="yyyy-MM-dd"
                               ng-model="BookCommonInfo.CHECK_OT_DT" is-open="opened2" min-date="minDate2" max-date="'2020-06-22'"
                               datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                               ng-required="true" close-text="Close" datepicker-append-to-body="true"
                               ng-style="BookCommonInfo.CheckOTStyle"/>
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default btn-lg" ng-click="open2($event)"><i class="glyphicon glyphicon-calendar" style="font-size:17px;"></i></button>
                                            </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 form-group">
                    <label xlabel ng-transclude checker="isNotEmpty|isChineseOrEnglishOrSpace" checkee="reserver.Name" btn-pass="infoError">姓名</label>
                    <input class="form-control input-lg" ng-model="reserver.Name" />
                </div>
                <div class="col-sm-4 form-group">
                    <label xlabel ng-transclude checker="isNotEmpty|isPhoneNum" checkee="reserver.Phone" btn-pass="infoError" >联系电话</label>
                    <input class="form-control input-lg" ng-model="reserver.Phone" />
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 form-group" >
                    <label>客人来源</label>
                    <select class="form-control input-lg" name="sourceSelection" ng-model="BookCommonInfo.roomSource"
                            ng-change="sourceChange()" >
                        <option value="普通预定">普通预定</option>
                        <option value="会员">会员预定</option>
                        <option value="协议">协议预定</option>
                        <option value="活动码">活动码预定</option>
                    </select>
                </div>
                <div class="col-sm-4 form-group ">
                    <label xlabel ng-transclude checker="isNotEmpty|isNumber|isLargerEqualThan0" checkee="BookCommonInfo.payment.paymentRequest" btn-pass="payError">预定金</label>
                    <input class="form-control input-lg" ng-model="BookCommonInfo.payment.paymentRequest" />
                </div>
                <div class="col-sm-3 form-group ">
                    <label class="padded-label">预付
                        <input  type="checkbox" ng-model="BookCommonInfo.paymentFlag" />
                    </label>
                </div>
            </div>
            <div class="row alert alert-info">
                <div class="col-sm-5 form-group ">
                    <label>查询:{{caption.searchCaption}}</label>
                    <div class="input-group">
                        <input class="form-control input-lg" ng-model="check.checkInput" ng-disabled="disable.searchDisable"/>
                        <span class="input-group-addon btn " ng-click="checkSource(BookCommonInfo.roomSource,check.checkInput)">查询</span>
                    </div>
                </div>
                <div class="col-sm-7 form-group">
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
            <div class="row" style="margin-bottom: 0px" >
                <div ng-repeat="(TP,singleTP) in BookRoomByTP " ng-controller="resvSingleTPCtrl">
                    <div class="col-sm-3 form-group">
                        <label>房型</label>
                        <select class="form-control input-lg" ng-model="TP">
                            <option ng-repeat="(rmTp,rmList) in roomsAndRoomTypes"
                                    ng-disabled= "roomTpsDisableList[rmTp]"
                                    ng-selected="rmTp == TP"
                                    value="{{rmTp}}">
                                {{rmTp}}{{(roomTpsDisableList[rmTp])&&(TP != rmTp)?'(已选)':''}}
                            </option>
                        </select>
                    </div>
                    <div class="col-sm-3 form-group">
                        <label xlabel ng-transclude checker="isNotEmpty|isNumber|isInt|isLargerEqualThan0" checkee="singleTP.roomAmount" btn-pass="infoError">数量({{singleTP.AVAIL_QUAN}}间可订)</label>
                        <input class="form-control input-lg" ng-model="singleTP.roomAmount" >
                    </div>
                    <div class="col-sm-3 form-group">
                        <label xlabel ng-transclude checker="isNotEmpty|isNumber|isLargerEqualThan0" checkee="singleTP.finalPrice" btn-pass="infoError" >每晚房价</label>
                        <input class="form-control input-lg" ng-model="singleTP.finalPrice" />
                    </div>
                    <div class="col-sm-3 form-group">
                        <label xlabel ng-transclude checker="isNumber|isLargerEqualThan0|isLessEqualThan100" checkee="singleTP.discount" btn-pass="infoError">折扣(%)</label>
                        <input class="form-control input-lg" ng-model="singleTP.discount" />
                    </div>
                </div>
                <div class="row">
                    <a class="pull-right btn btn-link btn-lg" ng-click="addRmTp()">添加房型</a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group">
                    <label>预定备注</label>
                    <textarea class="form-control" rows="5" cols="60" ng-model="BookCommonInfo.comment"/>
                </div>
            </div>
            <div class="row modal-control">
                <button class="pull-right btn btn-primary btn-lg"
                            ng-click="confirm()"
                            ng-if="initialString=='newReservation' && (infoError == '0' || infoError == null) ">
                        确认办理</button>
                <button class="pull-right btn btn-primary btn-lg"
                        ng-click="editConfirm()"
                        ng-if="initialString=='editReservation' && (infoError == '0' || infoError == null) ">
                    确认修改</button>
                <button class="pull-right btn btn-disabled btn-lg" ng-if="infoError != '0' && infoError != null ">
                    请修改错误信息</button>
            </div>
        </div>
        <div ng-if ="viewClick=='Pay'">
            <div payment  book-room="BookRoomMaster" pay-method-options="payMethodOptions" pay-error="payError"></div>
            <div class="row modal-control">
                <button class="pull-right btn btn-primary btn-lg" ng-if="initialString=='newReservation' && (payError == '0' || payError == null) "
                        ng-click="submit()"
                        btn-loading="submitLoading"
                        reset-text = '预订已确认'
                        loading-text = '处理中请您稍候...'
                        loading-gif= 'assets/dummy/buttonProcessing.gif'>确认预订</button>
                <button class="pull-right btn btn-primary btn-lg" ng-if="initialString=='editReservation' && (payError == '0' || payError == null) "
                        ng-click="editSubmit(BookCommonInfo.payment.paymentRequest)"
                        btn-loading="submitLoading"
                        reset-text = '预订已确认'
                        loading-text = '处理中请您稍候...'
                        loading-gif= 'assets/dummy/buttonProcessing.gif'>确认预订</button>
                <button class="pull-right btn btn-disabled btn-lg" ng-if=" payError != '0' && payError != null ">请更正错误信息</button>
                <button class="pull-right btn btn-primary btn-lg"
                        ng-click="backward('Info')">返回修改</button>
            </div>
        </div>
    </div>
</div>