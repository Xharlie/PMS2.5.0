<div id="wholeModal">
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="glyphicon glyphicon-credit-card"></span>
            <label>费用收取</label>
            <span class="pull-right close" ng-click="cancel()">&#x2715</span>
        </h4>
    </div>
    <div class="panel-body">
        <div ng-show ="viewClick=='Pay'">
            <!-- <div class="col-sm-12" style="border-bottom: 1px solid #D3D6DE; margin-bottom: 20px;"
                 ng-show ="(!Connected)" ng-repeat="singleRoom in BookRoom | filter: {check:'true'}" ng-controller="purchaseSingleRoomPayCtrl">
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
                </div>
                <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px;"
                     ng-repeat="singlePay in singleRoom.payment.payByMethods" ng-controller="purchaseSinglePayCtrl" >
                    <div class="col-sm-4 ">
                        <label>实收数目</label>
                        <input class="form-control" ng-model="singlePay.payAmount" />
                    </div>
                    <div class="col-sm-4 ">
                        <label>支付方式</label>
                        <select class="form-control"  ng-model="singlePay.payMethod"
                                ng-options="method as method for method in BookCommonInfo.Methods" />
                    </div>
                </div>
                <a class="pull-right btn" style="margin-top: 5px; margin-right:33%" ng-click="addNewPayByMethod(singleRoom)">添加支付方式</a>
                <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px;padding-top:10px;">
                    <label>未收数目</label>
                    <label style="display:block;color: red; font-size: 25px;">{{singleRoom.payment.payInDue}}元</label>
                </div>
            </div>   -->
            <div ng-show ="Connected">
                <!--<h4>{{$scope.BookCommonInfo.Master.CONN_RM_ID}}号房主房</h4></br>-->
                <div class="row">
                    <div class="col-sm-4 form-group">
                        <label xlabel ng-transclude checker="isNotEmpty|isNumber|isLargerEqualThan0" checkee="BookCommonInfo.Master.payment.paymentRequest" btn-pass="payError">应收数目</label>
                        <input class="form-control input-lg" ng-model="BookCommonInfo.Master.payment.paymentRequest"
                               ng-change="distributeMasterPay()"/>
                    </div>
                    <div class="col-sm-4 form-group">
                        <label>账目类型</label>
                        <select class="form-control input-lg"  ng-model="BookCommonInfo.Master.payment.paymentType"
                                ng-change="sourceChange()">
                            <option value="住房押金">住房押金</option>
                        </select>
                    </div>
                </div>
                <div class="row"
                     ng-repeat="singlePay in BookCommonInfo.Master.payment.payByMethods" ng-controller="purchaseSingleMasterPayCtrl" >
                    <div class="col-sm-4 form-group">
                        <label xlabel ng-transclude checker="isNotEmpty|isNumber|isLargerEqualThan0" checkee="singlePay.payAmount" btn-pass="payError">实收数目</label>
                        <input class="input-lg form-control" ng-model="singlePay.payAmount" />
                    </div>
                    <div class="col-sm-4 form-group">
                        <label>支付方式</label>
                        <select class="input-lg form-control"  ng-model="singlePay.payMethod"
                                ng-options="method as method for method in BookCommonInfo.Methods" />
                    </div>
                    <div class="col-sm-4 form-group" ng-show="singlePay.payMethod == '房间挂账' ">
                        <label>房间号</label>
                        <input type="text" ng-model="singlePay.roomId" class="input-lg form-control"
                               ng-class="singlePay.rmIdClass"  ng-blur='roomIdClear(singlePay)'
                               typeahead="room.RM_ID as room.RM_ID for room in rooms | filter:{RM_CONDITION:'有人'} | limitTo:8"
                               typeahead-on-select="roomIdHit(singlePay,$item)"
                               typeahead-editable="true">
                    </div>
                </div>
<!--    cannot fitinto the schema            <a class="pull-right btn" style="margin-top: 5px; margin-right:33%" ng-click="addNewPayByMethod(BookCommonInfo.Master)">添加支付方式</a>-->
                <div class="splitter"></div>
                <div class="row">
                    <label>未收数目</label>
                    <label class="text-lg text-danger">{{BookCommonInfo.Master.payment.payInDue}}元</label>
                </div>
            </div>
            <div class="row modal-control">
                <button class="pull-right btn btn-primary btn-lg"
                        ng-click="submit()"
                        btn-loading="submitLoading"
                        loading-text = '处理中请您稍后'
                        loading-gif= 'assets/dummy/buttonProcessing.gif'
                        ng-if="payError == '0' || payError == null ">确认并打印小票</button>
                <button class="pull-right btn btn-alert btn-lg" ng-if=" payError != '0' && payError != null ">请更正错误信息</button>
                {{payError}}
            </div>
        </div>
    </div>
</div>