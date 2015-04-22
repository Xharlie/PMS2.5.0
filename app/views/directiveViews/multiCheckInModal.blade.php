<div>
    <div class="modal-header">
        <span class="glyphicon glyphicon-send"></span>
        <label style="font-size: 15px;">新入住</label>
        <span class="pull-right btn" ng-click="cancel()">&#x2715</span>
    </div>
    <div class="col-sm-12" style="padding: 30px 20px 45px 25px;">
        <div class="col-sm-12" ng-show ="viewClick=='Info'"  >
            <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px; ">
                <div class="col-sm-4 ">
                    <label>离店日期</label>
                    <div class="input-group datePick" ng-controller="Datepicker" >
                        <input type="text" class="form-control" show-weeks="false" datepicker-popup="yyyy-MM-dd"
                               ng-model="BookCommonInfo.CHECK_OT_DT" is-open="opened2" min-date="minDate" max-date="'2020-06-22'"
                               datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                               ng-required="true" close-text="Close"
                               ng-style="BookCommonInfo.CheckOTStyle"/>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default" ng-click="open2($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                                    </span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <label>离店时间</label>
                    <div ng-controller="TimePickerDemoCtrl" class="removeArrow">
                        <timepicker ng-model="BookCommonInfo.leaveTime" show-meridian="true"
                                    meridians="chineseM" mousewheel="false"></timepicker>
                    </div>
                </div>
                <div class="col-sm-4 ">
                    <label>出租类型</label>
                    <select class="form-control" ng-model="BookCommonInfo.rentType">
                        <option value="全日租">全日租</option>
                        <option ng-repeat=" plan in plans | filter:{RM_TP: BookRoom[0].RM_TP}:true  | orderBy: ['PLAN_COV_MIN','PLAN_COV_PRCE'] "
                                value="{{plan.PLAN_ID}}" >
                            {{(plan.RM_TP+': '+plan.PLAN_COV_MIN+'分钟 '+plan.PLAN_COV_PRCE+'元')}}
                        </option>
                    </select>
                </div>
                <div class="col-sm-4" style="margin-top: 10px;">
                    <label>客人来源</label>
                    <select class="form-control" name="sourceSelection" ng-model="BookCommonInfo.roomSource"
                            ng-change="sourceChange()">
                        <option value="普通散客">普通散客</option>
                        <option value="会员">会员</option>
                        <option value="协议">协议</option>
                        <option value="活动码">活动码</option>
                        <option value="预订">预订</option>
                    </select>
                </div>
                <div class="col-sm-4" style="margin-top: 10px;">
                    </br>
                    <label >连房入住&nbsp;</label>
                    <input type="checkbox" ng-click="toggleMaster()" ng-checked="Connected"/>
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
            <div class="col-sm-12 " style="padding:10px 25px 0px 10px; border-top:1px solid #D3D6DE; ">
                <div class="col-sm-12 form-group" ng-repeat="(TP,singleTP) in BookRoomByTP " ng-controller="multiSingleTPCtrl">
                    <div class="col-sm-3 ">
                        <label>房型</label>
                        <label class="form-control" >{{TP}}</label>
                    </div>
                    <div class="col-sm-3 ">
                        <label>数量</label>
                        <input class="form-control" ng-model="singleTP.roomAmount" ng-disabled="initialString=='multiWalkIn'">
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
            </div>
            <div class="col-sm-12 " style="padding-right:25px; padding-left: 25px;">
                <button class="pull-right" style="margin-top:10px;padding: 10px 30px 10px 30px; background-color:#69B4F5; color: #ffffff"
                        ng-click="editRoomAndGuest()">
                    下一步: 添加客人</button>
            </div>
        </div>

        <form ng-show ="viewClick=='roomInfo'" class="col-sm-12">
            <div class="col-sm-12 form-group" style="padding:10px 25px 0px 10px; border-bottom:1px solid #D3D6DE; "
                 ng-repeat="singleRoom in BookRoom | filter: {check:'true'}" ng-controller="multiSingleRoomCtrl">
                <div class="col-sm-4 ">
                    <label>房间号</label>
                    <select class="form-control" ng-model="singleRoom.RM_ID" ng-disabled="initialString=='multiWalkIn'">
                        <option ng-repeat="room in roomsAndRoomTypes[singleRoom.RM_TP]" ng-selected="{{room.RM_ID == singleRoom.RM_ID}}"
                                ng-disabled= "roomsDisableList[room.RM_ID]" value="{{room.RM_ID}}">
                            {{room.RM_ID}}
                            {{(roomsDisableList[room.RM_ID])&&(singleRoom.RM_ID != room.RM_ID)?'('+singleRoom.RM_TP+' 已选)':'('+singleRoom.RM_TP+')'}}
                        </option>
                    </select>
                </div>
                <div class="col-sm-4 ">
                    <label>房型</label>
                    <select class="form-control" ng-model="singleRoom.RM_TP" ng-change="roomTypeChange(singleRoom)"
                            ng-options="rmTp as rmTp for (rmTp,rmList) in roomsAndRoomTypes " disabled/>
                </div>
                <div class="col-sm-4 " ng-show="Connected">
                    </br>
                    <label class="pull-right">&nbsp;选为主房</label>
                    <input class="pull-right" type="radio" name="MasterRoom" ng-model="BookCommonInfo.Master.CONN_RM_ID" ng-value="singleRoom.RM_ID" />
                </div>

                <div class="col-sm-12 " style="padding-top:10px;"
                     ng-repeat=" singleGuest in singleRoom.GuestsInfo">
                    <div class="col-sm-2 ">
                        <label>客人姓名</label>
                        <input class="form-control"ng-model="singleGuest.Name" />
                    </div>
                    <div class="col-sm-2 ">
                        <label>证件类型</label>
                        <select class="form-control"  ng-model="singleGuest.SSNType"
                                ng-init="singleGuest.SSNType='二代身份证'">
                            <option value="二代身份证">二代身份证</option>
                            <option value="护照">护照</option>
                        </select>
                    </div>
                    <div class="col-sm-5 ">
                        <label>证件号码</label>
                        <div class="input-group" >
                            <input class="form-control" ng-model="singleGuest.SSN"
                                   id="guest{{$index}}SSN"
                                   popover="{{singleGuest.notFindWarning}}"
                                   popover-trigger="openEvent"
                                   popover-append-to-body="true"/>
                                    <span class="input-group-addon btn" ng-click="showIdentity(singleGuest,$index)"
                                          ng-mouseleave="closePopover('guest'+$index+'SSN')" >识别</span>
                        </div>
                    </div>
                    <div class="col-sm-3 ">
                        <label class="col-sm-6">联系电话</label>
<!--                        <button class="col-sm-6 btn-xs btn-danger ng-hide" ng-click="deleteCustomer(singleRoom.GuestsInfo,$index)"-->
<!--                                ng-show="singleRoom.GuestsInfo.length < -1">删除客人</button>-->
                        <input class="form-control" ng-model="singleGuest.Phone" />
                    </div>
                </div>
                <div class="col-sm-12 btn" style="margin-top: 20px">
                    <a class="pull-right" ng-click="addCustomer(singleRoom.GuestsInfo)">添加更多客人</a>
                </div>
            </div>
            <div class="col-sm-12 " style="padding-right:25px; padding-left: 25px;">
                <button class="pull-right" style="margin-top:10px;padding: 10px 30px 10px 30px; background-color:#69B4F5; color: #ffffff"
                        ng-click="confirm()">
                    确认办理</button>
                <button class="pull-right" style="margin-top:10px;padding: 10px 30px 10px 30px; background-color:#69B4F5; color: #ffffff"
                        ng-click="backward('Info')">
                    上一步: 选择房型</button>
            </div>
        </form>
        <div ng-show ="viewClick=='Pay'" class="col-sm-12">
            <div class="col-sm-12" style="border-bottom: 1px solid #D3D6DE; margin-bottom: 20px;"
                 ng-show ="(!Connected)" ng-repeat="singleRoom in BookRoom | filter: {check:'true'}" ng-controller="multiSingleRoomPayCtrl">
                <h4>{{singleRoom.RM_ID}}号房</h4></br>
                <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px; padding-bottom:20px; ">
                    <div class="col-sm-4 ">
                        <label>应收数目</label>
                        <input class="form-control"ng-model="singleRoom.payment.paymentRequest" />
                    </div>
                    <div class="col-sm-4 ">
                        <label>账目类型</label>
                        <select class="form-control"  ng-model="singleRoom.payment.paymentType"
                                ng-change="sourceChange()">
                            <option value="住房押金">住房押金</option>
                        </select>
                    </div>
                    <!--                <div class="col-sm-4 ">-->
                    <!--                    <label>账单号</label>-->
                    <!--                    <input class="form-control"ng-model="b" />-->
                    <!--                </div>-->
                </div>
                <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px;"
                     ng-repeat="singlePay in singleRoom.payment.payByMethods" ng-controller="multiSinglePayCtrl" >
                    <div class="col-sm-4 ">
                        <label>实收数目</label>
                        <input class="form-control" ng-model="singlePay.payAmount" />
                    </div>
                    <div class="col-sm-4 ">
                        <label>支付方式</label>
                        <select class="form-control"  ng-model="singlePay.payMethod" >
                            <option value="现金">现金</option>
                            <option value="银行卡">银行卡</option>
                            <option value="信用卡">信用卡</option>
                        </select>
                    </div>
                </div>
                <a class="pull-right btn" style="margin-top: 5px; margin-right:33%" ng-click="addNewPayByMethod(singleRoom)">添加支付方式</a>
                <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px;padding-top:10px;">
                    <label>未收数目</label>
                    <label style="display:block;color: red; font-size: 25px;">{{singleRoom.payment.payInDue}}元</label>
                </div>
            </div>
            <div class="col-sm-12" ng-show ="Connected"  style="border-bottom: 1px solid #D3D6DE; margin-bottom: 20px;">
                <h4>{{$scope.BookCommonInfo.Master.CONN_RM_ID}}号房主房</h4></br>
                <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px; padding-bottom:20px; ">
                    <div class="col-sm-4 ">
                        <label>应收数目</label>
                        <input class="form-control"ng-model="BookCommonInfo.Master.payment.paymentRequest"
                            ng-change="distributeMasterPay()"/>
                    </div>
                    <div class="col-sm-4 ">
                        <label>账目类型</label>
                        <select class="form-control"  ng-model="BookCommonInfo.Master.payment.paymentType"
                                ng-change="sourceChange()">
                            <option value="住房押金">住房押金</option>
                        </select>
                    </div>
                    <!--                <div class="col-sm-4 ">-->
                    <!--                    <label>账单号</label>-->
                    <!--                    <input class="form-control"ng-model="b" />-->
                    <!--                </div>-->
                </div>
                <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px;"
                     ng-repeat="singlePay in BookCommonInfo.Master.payment.payByMethods" ng-controller="multiSingleMasterPayCtrl" >
                    <div class="col-sm-4 ">
                        <label>实收数目</label>
                        <input class="form-control" ng-model="singlePay.payAmount" />
                    </div>
                    <div class="col-sm-4 ">
                        <label>支付方式</label>
                        <select class="form-control"  ng-model="singlePay.payMethod" >
                            <option value="现金">现金</option>
                            <option value="银行卡">银行卡</option>
                            <option value="信用卡">信用卡</option>
                        </select>
                    </div>
                </div>
                <a class="pull-right btn" style="margin-top: 5px; margin-right:33%" ng-click="addNewPayByMethod(BookCommonInfo.Master)">添加支付方式</a>
                <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px;padding-top:10px;">
                    <label>未收数目</label>
                    <label style="display:block;color: red; font-size: 25px;">{{BookCommonInfo.Master.payment.payInDue}}元</label>
                </div>
            </div>

            <button class="pull-right"
                    style="margin-top:25px;padding: 10px 30px 10px 30px; background-color:grey; color: #ffffff"
                    ng-click="submit()"
                    btn-loading="submitLoading"
                    reset-text = '确认并打印押金单'
                    loading-text = '处理中请您稍后'
                    loading-gif= 'assets/dummy/buttonProcessing.gif'
                    >确认并打印押金单</button>
            <button class="pull-right"
                    style="margin-top:25px;padding: 10px 30px 10px 30px; background-color:#69B4F5; color: #ffffff"
                    ng-click="backward('roomInfo')">返回修改</button>
        </div>
    </div>
</div>