<div>
    <div class="modal-header">
        <span class="glyphicon glyphicon-send"></span>
        <label style="font-size: 15px;">新预订</label>
        <span class="pull-right btn" ng-click="cancel()">&#x2715</span>
    </div>
    <div class="col-sm-12" style="padding: 10px 20px 45px 25px;" >
            <div class="col-sm-12" ng-show ="viewClick=='Info'"  >
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
                               ng-required="true" close-text="Close"
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
                        <option value="普通预定">普通预定</option>
                        <option value="会员">会员预定</option>
                        <option value="协议">协议预定</option>
                        <option value="活动码">活动码预定</option>
                    </select>
                </div>
                <div class="col-sm-4 ">
                    <label>预定金</label>
                    <input class="form-control" ng-model="BookCommonInfo.payment.paymentRequest" />
                </div>
                <div class="col-sm-3 ">
                    </br>
                    <label>预付</label>
                    <input  type="checkbox" ng-model="BookCommonInfo.paymentFlag" />
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
                <div class="col-sm-12"  ng-repeat="(TP,singleTP) in BookRoomByTP " ng-controller="resvSingleTPCtrl">
                    <div class="col-sm-3 ">
                        <label>房型</label>
                        <select class="form-control" ng-model="TP">
                            <option ng-repeat="(rmTp,rmList) in roomsAndRoomTypes"
                                    ng-disabled= "roomTpsDisableList[rmTp]"
                                    ng-selected="rmTp == TP"
                                    value="{{rmTp}}">
                                {{rmTp}}{{(roomTpsDisableList[rmTp])&&(TP != rmTp)?'(已选)':''}}
                            </option>
                        </select>
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
                        <input class="form-control" ng-model="singleTP.discount" />
                    </div>
                </div>
                <div class="col-sm-12 btn" >
                    <a class="pull-right" ng-click="addRmTp()">添加房型</a>
                </div>
            </div>
            <div class="col-sm-12 " style="padding:10px 25px 20px 25px; border-top:1px solid #D3D6DE; ">
                <div class="col-sm-12 ">
                    <label>预定备注</label>
                    <textarea class="form-control" rows="4" cols="60" ng-model="BookCommonInfo.comment"/>
                </div>
            </div>
            <div class="col-sm-12 " style="padding-left: 25px;" ng-if="initialString=='newReservation'">
                <button class="pull-right" style="margin-top:10px;padding: 10px 30px 10px 30px; background-color:#69B4F5; color: #ffffff"
                        ng-click="confirm()">
                    确认办理</button>
            </div>
            <div class="col-sm-12 " style="padding-left: 25px;"  ng-if="initialString=='editReservation'">
                <button class="pull-right" style="margin-top:10px;padding: 10px 30px 10px 30px; background-color:#69B4F5; color: #ffffff"
                        ng-click="editConfirm()">
                    确认修改</button>
            </div>
        </div>
        <div ng-show ="viewClick=='Pay'" class="col-sm-12">
            <div class="col-sm-12" >
                <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px; padding-bottom:20px; border-bottom: 1px solid #D3D6DE">
                    <div class="col-sm-4 " >
                        <label ng-if="initialString=='newReservation'">应收数目</label>
                        <label ng-if="initialString=='editReservation'">补交数目</label>
                        <input class="form-control"ng-model="BookCommonInfo.payment.paymentRequest" />
                    </div>
                    <div class="col-sm-4 ">
                        <label>账目类型</label>
                        <select class="form-control"  ng-model="BookCommonInfo.payment.paymentType"
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
                     ng-repeat="singlePay in BookCommonInfo.payment.payByMethods" ng-controller="resvSinglePayCtrl" >
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
                <a class="pull-right btn" style="margin-top: 5px; margin-right:33%" ng-click="addNewPayByMethod(BookCommonInfo)">添加支付方式</a>
                <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px;padding-top:20px;  border-top: 1px solid #D3D6DE">
                    <label>未收数目</label>
                    <label style="display:block;color: red; font-size: 25px;">{{BookCommonInfo.payment.payInDue}}元</label>
                </div>
            </div>
            <button class="pull-right"  ng-if="initialString=='newReservation'"
                    style="margin-top:25px;padding: 10px 30px 10px 30px; background-color:grey; color: #ffffff"
                    ng-click="submit()"
                    btn-loading="submitLoading"
                    reset-text = '确认并打印押金单'
                    loading-text = '处理中请您稍后'
                    loading-gif= 'assets/dummy/buttonProcessing.gif'>确认并打印押金单</button>
            <button class="pull-right" ng-if="initialString=='editReservation'"
                    style="margin-top:25px;padding: 10px 30px 10px 30px; background-color:grey; color: #ffffff"
                    ng-click="editSubmit(BookCommonInfo.payment.paymentRequest)"
                    btn-loading="submitLoading"
                    reset-text = '确认并打印押金单'
                    loading-text = '处理中请您稍后'
                    loading-gif= 'assets/dummy/buttonProcessing.gif'>确认差价并提交</button>
            <button class="pull-right"
                    style="margin-top:25px;padding: 10px 30px 10px 30px; background-color:#69B4F5; color: #ffffff"
                    ng-click="backward('Info')">返回修改</button>
        </div>
    </div>
</div>