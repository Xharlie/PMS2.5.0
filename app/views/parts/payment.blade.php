<div>
    <div ng-repeat="singleRoom in BookRoom | filter: {check:'true'}" ng-controller="singleRoomPayCtrl">
        <div class="row">
            <div class="form-group col-sm-6">
                <label xlabel ng-transclude checker="isNotEmpty|isNumber" checkee="singleRoom.payment.paymentRequest" btn-pass="payError">应收数目</label>
                <input class="form-control input-lg" ng-model="singleRoom.payment.paymentRequest" />
            </div>
            <div class="form-group col-sm-6">
                <label>账目类型</label>
                <select class="form-control input-lg"  ng-model="singleRoom.payment.paymentType"
                        ng-change="sourceChange()">
                    <option value="{{singleRoom.payment.paymentType}}">{{singleRoom.payment.paymentType}}</option>
                </select>
            </div>
        </div>
        <div class="row" ng-repeat="singlePay in singleRoom.payment.payByMethods" ng-controller="singlePayCtrl" >
            <div class="form-group col-sm-6">
                <label xlabel ng-transclude checker="isNotEmpty|isNumber" checkee="singlePay.payAmount" btn-pass="payError">实收数目</label>
                <input class="form-control input-lg" ng-model="singlePay.payAmount" />
            </div>
            <div class="form-group col-sm-6">
                <label>支付方式</label>
                <select class="form-control input-lg"  ng-model="singlePay.payMethod" >
                    <!---  only for resv check in -------->
                    <option ng-if="singlePay.payMethod=='预定金'" value="预定金" >预定金</option>
                    <!---  only for resv check in -------->
                    <option ng-if="singlePay.payMethod!='预定金'" value="{{payMethod}}" ng-repeat="payMethod in payMethodOptions">{{payMethod}}</option>
                </select>
            </div>
            <!--         only   for purchase model             -->
            <div class="form-group col-sm-6" ng-if="singlePay.payMethod == '房间挂账' ">
                <label xlabel ng-transclude checker="isNotEmpty" checkee="singlePay.roomId"
                       btn-pass="BookRoom[{{$parent.$index}}].payment.payByMethods[{{$index}}].roomNumError" >房间号</label>
                <input type="text" ng-model="singlePay.roomId" class="input-lg form-control"
                       ng-class="singlePay.rmIdClass"  ng-blur='roomIdClear(singlePay)'
                       typeahead="room.RM_ID as room.RM_ID for room in rooms | filter:{RM_CONDITION:'有人',RM_ID:$viewValue} | limitTo:8"
                       typeahead-on-select="roomIdHit(singlePay,$item)" typeahead-append-to-body="true"
                       typeahead-editable="true">
            </div>
            <!--         only   for purchase model             -->
        </div>
        <div class="row">
            <a class="pull-right btn btn-link btn-lg"   ng-if="singleRoom.payment.paymentType != '商品付款' "
               ng-click="addNewPayByMethod(singleRoom)">添加支付方式</a>
        </div>
        <div class="splitter"></div>
        <div class="row">
            <label>未收数目</label>
            <label class="text-danger text-lg">{{singleRoom.payment.payInDue}}元</label>
        </div>
    </div>
</div>