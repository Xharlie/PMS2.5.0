<div id="wholeModal">  <!-- id cannot change here, children focus identifier   -->
    <div ng-hide="ready" class="loader loader-main">
        <div class="loader-inner ball-scale-multiple">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <div ng-show="ready">
        <div class="card-actions">
            <h4>
                <span class="glyphicon glyphicon-send"></span>
                <label>{{(initialString == 'singleWalkIn')?'新入住':'入住修改'}}</label>
                <span class="pull-right close" ng-click="cancel()">&#x2715</span>
            </h4>
        </div>
        <div class="card-body">
            <div class=" padded-form" ng-show ="viewClick=='Info'">
                <div class="form-group" style="overflow: inherit">
                    <div class="col-sm-6">
                        <label xlabel ng-transclude checker="isDate" checkee="BookCommonInfo.CHECK_OT_DT" btn-pass="infoError">离店日期</label>
                        <div class="input-group datePick" ng-controller="Datepicker" >
                            <input type="text" class="form-control input-lg" show-weeks="false" datepicker-popup="yyyy-MM-dd"
                                   ng-model="BookCommonInfo.CHECK_OT_DT" is-open="opened2" min-date="minDate" max-date="'2020-06-22'"
                                   datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                                   ng-required="true" close-text="Close" ng-style="BookCommonInfo.CheckOTStyle"
                                   datepicker-append-to-body="true"
                                   />
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default btn-lg" ng-click="open2($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label xlabel ng-transclude checker="isDate" checkee="BookCommonInfo.leaveTime" btn-pass="infoError">离店时间</label>
                        <div ng-controller="TimePickerDemoCtrl" class="removeArrow">
                            <timepicker ng-model="BookCommonInfo.leaveTime" show-meridian="true"
                                        meridians="chineseM" mousewheel="false"></timepicker>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label>客人来源</label>
                        <select class="form-control input-lg" name="sourceSelection" ng-model="BookCommonInfo.roomSource"
                                ng-change="sourceChange()" >
                            <option value="普通散客">普通散客</option>
                            <option value="会员">会员</option>
                            <option value="协议">协议</option>
                            <option value="活动码">活动码</option>
                            <option value="预定">预定</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label>出租类型</label>
                        <select class="form-control input-lg" ng-model="BookCommonInfo.rentType">
                            <option value="全日租">全日租</option>
                            <option ng-repeat=" plan in plans | filter:{RM_TP: BookRoom[0].RM_TP}:true  | orderBy: ['PLAN_COV_MIN','PLAN_COV_PRCE'] "
                                value="{{plan.PLAN_ID}}" ng-selected="plan.PLAN_ID == rentType" >
                                {{(plan.RM_TP+': '+plan.PLAN_COV_MIN+'分钟 '+plan.PLAN_COV_PRCE+'元')}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group alert alert-info">
                    <div class="col-sm-5 ">
                        <label>查询:{{caption.searchCaption}}</label>
                        <div class="input-group">
                            <input class="form-control input-lg" ng-model="check.checkInput" ng-disabled="disable.searchDisable"/>
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
                                           ng-show="Members.length>1" class="ng-hide input-lg">&nbsp;</td>
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
                                            ng-show="Treaties.length>1" class="ng-hide input-lg">&nbsp;</td>
                                <td>{{treatyOption.TREATY_ID}}&nbsp;</td>
                                <td>{{treatyOption.CORP_NM}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div ng-repeat="singleRoom in BookRoom" ng-controller="scheckSingleRoomCtrl">
                    <div class="form-group" id="editRoom">
                        <div class="col-sm-4">
                            <label>房型</label>
                            <select class="form-control input-lg" ng-model="singleRoom.RM_TP" ng-change="roomTypeChange(singleRoom)"
                                    ng-options="rmTp as rmTp for (rmTp,rmList) in roomsAndRoomTypes " />
                        </div>
                        <div class="col-sm-4">
                            <label>房间号</label>
                            <select class="form-control input-lg" ng-model="singleRoom.RM_ID" >
                                <option ng-repeat="room in roomsAndRoomTypes[singleRoom.RM_TP]"
                                        ng-disabled= "roomsDisableList[room.RM_ID]" value="{{room.RM_ID}}" ng-selected="singleRoom.RM_ID==room.RM_ID">
                                    {{room.RM_ID}}{{(roomsDisableList[room.RM_ID])&&(singleRoom.RM_ID != room.RM_ID)?'(已选)':''}}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label xlabel checker="isNotEmpty|isNumber|isLargerEqualThan0" checkee="singleRoom.finalPrice" ng-transclude btn-pass="infoError">每晚房价</label>
                            <input class="form-control input-lg" ng-model="singleRoom.finalPrice" />
                        </div>
                        <div class="col-sm-4 hidden">
                            <label>折扣(%)</label>
                            <input class="form-control input-lg" ng-model="singleRoom.discount" ng-change="discountChange(singleRoom)"/>
                        </div>
                    </div>
                    <div class="form-group" id="editCustomer"
                         ng-repeat=" singleGuest in singleRoom.GuestsInfo" >
                        <div class="col-sm-2">
                            <label xlabel ng-transclude checker="isChineseOrEnglishOrSpace" checkee="singleGuest.Name" btn-pass="infoError">姓名</label>
                            <input class="form-control input-lg" ng-model="singleGuest.Name" />
                        </div>
                        <div class="col-sm-2 ">
                            <label >证件类型</label>
                            <select class="form-control input-lg"  ng-model="singleGuest.SSNType"
                                    ng-init="singleGuest.SSNType='二代身份证'">
                                <option value="二代身份证">二代身份证</option>
                                <option value="护照">护照</option>
                            </select>
                        </div>
                        <div class="col-sm-5 ">
                            <label xlabel ng-transclude checker="isNotEmpty|isSSN" checkee="singleGuest.SSN" btn-pass="infoError">证件号码</label>
                            <div class="input-group" >
                                <input class="form-control input-lg" ng-model="singleGuest.SSN"
                                       id="guest{{$index}}SSN"
                                       popover="{{singleGuest.notFindWarning}}"
                                       popover-trigger="openEvent"
                                       popover-append-to-body="true"/>
                                <span class="input-group-addon btn" ng-click="showIdentity(singleGuest,$index)"
                                      ng-mouseleave="closePopover('guest'+$index+'SSN')" >识别</span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label xlabel ng-transclude checker="isPhoneNum" checkee="singleGuest.Phone" btn-pass="infoError">联系电话</label>
                            <button class="col-sm-6 btn-lg btn-danger ng-hide" ng-click="deleteCustomer(singleRoom.GuestsInfo,$index)"
                                ng-show="singleRoom.GuestsInfo.length < -1">删除客人</button>
                            <input class="form-control input-lg" ng-model="singleGuest.Phone" />
                        </div>
                    </div>
                    <div class="form-group">
                        <a class="pull-right btn btn-link btn-lg" ng-click="addCustomer(singleRoom.GuestsInfo)">添加更多客人</a>
                    </div>
                </div>
                <div class="form-group">
                    <button class="pull-right btn btn-primary btn-lg"
                            btn-loading="submitLoading"
                            loading-gif= 'assets/dummy/buttonProcessing.gif'
                            ng-click="confirm()"
                            ng-if="infoError == '0' || infoError == null">
                        确认办理</button>
                    <button class="pull-right btn btn-alert btn-lg" ng-if="infoError != '0' && infoError != null ">
                        请修改错误信息</button>
                </div>
            </div>
            <div class="padded-form" ng-show ="viewClick=='Pay'">
                <div ng-repeat="singleRoom in BookRoom" ng-controller="scheckSingleRoomPayCtrl">
                    <div class="form-group">
                        <div class="col-sm-6">
                            <label xlabel ng-transclude checker="isNotEmpty|isNumber" checkee="singleRoom.payment.paymentRequest" btn-pass="payError">应收数目</label>
                            <input class="form-control input-lg" ng-model="singleRoom.payment.paymentRequest" />
                        </div>
                        <div class="col-sm-6">
                            <label>账目类型</label>
                            <select class="form-control input-lg"  ng-model="singleRoom.payment.paymentType"
                                    ng-change="sourceChange()">
                                <option value="住房押金">住房押金</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group"
                            ng-repeat="singlePay in singleRoom.payment.payByMethods" ng-controller="scheckSinglePayCtrl" >
                        <div class="col-sm-6">
                            <label xlabel ng-transclude checker="isNotEmpty|isNumber" checkee="singlePay.payAmount" btn-pass="payError">实收数目</label>
                            <input class="form-control input-lg" ng-model="singlePay.payAmount" />
                        </div>
                        <div class="col-sm-6">
                            <label>支付方式</label>
                            <select class="form-control input-lg"  ng-model="singlePay.payMethod" >
                                <option value="现金">现金</option>
                                <option value="银行卡">银行卡</option>
                                <option value="信用卡">信用卡</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <a class="pull-right btn btn-link btn-lg" ng-click="addNewPayByMethod(singleRoom)">添加支付方式</a>
                    </div>
                    <div class="form-group">
                        <label>未收数目</label>
                        <label class="text-danger text-lg">{{singleRoom.payment.payInDue}}元</label>
                    </div>
                    </div>
                    <div class="form-group">
                        <button class="pull-right btn btn-primary btn-lg"
                                ng-if="initialString == 'singleWalkIn' && (payError == '0' || payError == null) "
                                ng-click="submit()"
                                btn-loading="submitLoading"
                                loading-text = '处理中请您稍候...'
                                loading-gif= 'assets/dummy/buttonProcessing.gif'>确认入住并打印押金单</button>
                        <button class="pull-right btn btn-primary btn-lg"
                                ng-if="initialString != 'singleWalkIn' && (payError == '0' || payError == null) "
                                ng-click="editSubmit('true')"
                                btn-loading="submitLoading"
                                loading-text = '处理中请您稍候...'
                                loading-gif= 'assets/dummy/buttonProcessing.gif'>确认修改并打印押金单</button>
                        <button class="pull-right btn btn-alert btn-lg" ng-if=" payError != '0' && payError != null ">请更正错误信息</button>

                        <button class="pull-right btn btn-primary btn-lg"
                                ng-click="backward()">返回修改</button>
                    </div>
             </div>
        </div>
    </div>
</div>