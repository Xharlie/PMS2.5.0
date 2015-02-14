<div>
    <div class="modal-header col-sm-12">
        <span class="glyphicon glyphicon-arrow-left"></span>
        <label style="font-size: 15px;">退房办理</label>{{}}
        <span class="pull-right btn" ng-click="cancel()">&#x2715</span>
        <div class="col-sm-12 ">
            <div class="col-sm-3" >
                退房房号:全选 <input type="checkbox" ng-model="BookCommonInfo.selectAll"/>
            </div>
            <div class="col-sm-1" ng-repeat="singleRoom in BookRoom" ng-controller="checkOutSelectController">
                <input type="checkbox" ng-model="singleRoom.selected" ng-checked="singleRoom.selected"/>
                <label>{{singleRoom.RM_ID}}</label>
            </div>
        </div>
    </div>
    <div class="col-sm-12"  style="padding: 30px 20px 45px 25px;">
        <div class="col-sm-12" ng-show ="viewClick=='Info'" >
            <div class="col-sm-12 form-group" style="height: 300px; border-bottom:1px solid #D3D6DE; overflow: scroll">
                <table class="table table-striped table-acct" >
                    <tr >
                        <th >转入主房</th>
                        <th >时间</th>
                        <th >账单号</th>
                        <th >房间号</th>
                        <th >项目</th>
                        <th >金额(元)</th>
                    </tr >
                    <tr ng-repeat-start="singleRoom in BookRoom | filter: {selected : 'true'}" />
                        <tr ng-repeat = "depo in singleRoom.AcctDepo | orderBy: DEPO_TSTAMP  "  >
                            <td><input type="checkbox" ng-model="depo.transfer" ng-checked="depo.transfer"/></td>
                            <td>{{depo.DEPO_TSTMP}}</td>
                            <td>DEP-{{depo.RM_DEPO_ID}}</td>
                            <td>{{singleRoom.RM_ID}}</td>
                            <td>{{depo.RMRK}}</td>
                            <td>{{twoDigit(depo.DEPO_AMNT)}}元</td>
                        </tr>
                        <tr ng-repeat = "bill in singleRoom.AcctPay | orderBy: BILL_TSTMP  ">
                            <td><input type="checkbox" ng-model="bill.transfer" ng-checked="bill.transfer"/></td>
                            <td>{{bill.BILL_TSTMP}}</td>
                            <td>BIL-{{bill.RM_BILL_ID}}</td>
                            <td>{{singleRoom.RM_ID}}</td>
                            <td>房费</td>
                            <td>{{twoDigit(bill.RM_PAY_AMNT)}}元</td>
                        </tr>
                        <tr ng-repeat = "store in singleRoom.AcctStore | orderBy: STR_TRAN_TSTAMP  ">
                            <td><input type="checkbox" ng-model="store.transfer" ng-checked="store.transfer"/></td>
                            <td>{{store.STR_TRAN_TSTAMP}}</td>
                            <td>STR-{{store.STR_TRAN_ID}}</td>
                            <td>{{singleRoom.RM_ID}}</td>
                            <td>{{store.PROD_NM}}X{{store.PROD_QUAN}}</td>
                            <td>{{(store.PROD_PRICE!=undefined)?twoDigit(store.PROD_PRICE*store.PROD_QUAN):twoDigit(store.STR_PAY_AMNT)}}元</td>
                        </tr>
                        <tr ng-repeat = "Rstore in singleRoom.RoomConsume" >
                            <td><input type="checkbox" ng-model="Rstore.transfer" ng-checked="Rstore.transfer"/></td>
                            <td>{{Rstore.STR_TRAN_TSTAMP}}</td>
                            <td>(提交后分配)</td>
                            <td>{{singleRoom.RM_ID}}</td>
                            <td>{{Rstore.PROD_NM}}X{{Rstore.PROD_QUAN}}</td>
                            <td>{{twoDigit(Rstore.STR_PAY_AMNT)}}</td>
                        </tr>
                        <tr ng-repeat = "fee in singleRoom.penalty">
                            <td><input type="checkbox" ng-model="fee.transfer" ng-checked="fee.transfer"/></td>
                            <td>{{fee.FEE_TRAN_TSTAMP}}</td>
                            <td>(提交后分配)</td>
                            <td>{{singleRoom.RM_ID}}</td>
                            <td>{{fee.RMRK}}:{{fee.PAYER}}({{fee.PAYER_PHONE}})</td>
                            <td>{{twoDigit(fee.PAY_AMNT)}}元</td>
                        </tr >
                    <tr ng-repeat-end />
                </table>
            </div>
            <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px; border-bottom:1px solid #D3D6DE;">
                <label class="pull-right col-sm-2">{{BookCommonInfo.payment.paymentRequest}}元</label>
                <label class="pull-right col-sm-2">总计:</label>
            </div>

            <div class="col-sm-12 form-group" style="padding:10px 25px 0px 10px;" ng-repeat="item in addedItems" ng-controller="itemController">
                <div class="col-sm-2 ">
                    <label>房间号</label>
                    <select class="form-control" ng-model="item.RM_ID" >
                        <option ng-repeat="singleRoom in BookRoom | filter: {selected:true}"
                                value="{{singleRoom.RM_ID}}" ng-selected="singleRoom.RM_ID == item.RM_ID">
                            {{singleRoom.RM_ID}}
                        </option>
                    </select>
                </div>

                <div class="col-sm-2 ">
                    <label>入账金额</label>
                    <input class="form-control" ng-model="item.paymentRequest" />
                </div>

<!--                <div class="col-sm-4 ">-->
<!--                    <label>消费项目</label>-->
<!--                    <select class="form-control" ng-model="singleRoom.RM_ID" >-->
<!--                        <option ng-repeat="room in roomsAndRoomTypes[singleRoom.RM_TP]"-->
<!--                                ng-disabled= "roomsDisableList[room.RM_ID]" value="{{room.RM_ID}}" ng-selected="singleRoom.RM_ID==room.RM_ID">-->
<!--                            {{room.RM_ID}}{{(roomsDisableList[room.RM_ID])&&(singleRoom.RM_ID != room.RM_ID)?'(已选)':''}}-->
<!--                        </option>-->
<!--                    </select>-->
<!--                </div>-->
                <div class="col-sm-4" >
                    <label class="col-sm-12">消费项目</label>
                    <div class="btn-group dropup col-sm-12" dropdown is-open="item.isopen">
                        <button type="button" class="btn btn-default col-sm-10">{{item.showup}}</button>
                        <button type="button" class="btn btn-primary dropdown-toggle" dropdown-toggle>
                            <span class="caret "></span>
                        </button>
                        <div class="dropdown-menu " style="width: 220px; height: 200px; overflow: scroll" role="menu"
                             ng-click="$event.stopPropagation()">
                            <div class="col-sm-3 roomAction">
                                <table>
                                    <tr>
                                        <td><button class="btn btn-link col-sm-12" ng-click="changeCategory(item,'merchant')">商品</button></td>
                                    </tr>
                                    <tr>
                                        <td><button class="btn btn-link col-sm-12" ng-click="changeCategory(item,'penalty')">赔偿</button></td>
                                    </tr>
                                    <tr>
                                        <td><button class="btn btn-link col-sm-12" ng-click="changeCategory(item,'others')">其他</button></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-9" ng-show="item.itemCategory=='merchant'">
                                <table class="table table-striped">
                                    <col width="10%">
                                    <col width="60%">
                                    <col width="30%">
                                    <tr>
                                        <th></th>
                                        <th>产品名称</th>
                                        <th>数量</th>
                                    </tr>
                                    <tr ng-repeat="prod in item.prodInfo | filter: {ROOM_BAR : '!F'}">
                                        <td><input type="checkbox" ng-model="prod.PROD_SELECTED" /></td>
                                        <td><label >{{prod.PROD_NM}}</label</td>
                                        <td><input class="col-sm-12" ng-model="prod.PROD_QUAN" /></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-9"  style = "padding: 5px 0px 0px 10px;" ng-show="item.itemCategory=='penalty'" >
                                <div class="col-sm-6">
                                    <label>赔偿项目<label>
                                    <input style="width: 90%" ng-model="item.penalty.PENALTY_ITEM" />
                                </div>
                                <div class="col-sm-6 ">
                                    <label>金额<label>
                                    <input style="width: 90%" ng-model="item.penalty.PAY_AMNT" />
                                </div>
                                <div class="col-sm-12 ">
                                    <label>赔款人姓名<label>
                                    <input style="width: 90%" ng-model="item.penalty.PAYER" />
                                </div>
                                <div class="col-sm-12">
                                    <label>赔款人电话<label>
                                    <input style="width: 90%" ng-model="item.penalty.PAYER_PHONE" />
                                </div>

                            </div>
                            <div class="col-sm-9" ng-show="item.itemCategory=='others'">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 ">
                    <label>备注</label>
                    <input class="form-control" ng-model="item.RMRK" />
                </div>
            </div>

            <div class="col-sm-12 " style="padding-right:25px; padding-left: 25px;">
                <button class="pull-right" style="margin-top:10px; padding: 10px 30px 10px 30px; background-color:#69B4F5; color: #ffffff"
                        ng-click="confirm()">
                    确认办理</button>
            </div>
        </div>
<!--        <div ng-show ="viewClick=='Pay'" class="col-sm-12">-->
<!--            <div class="col-sm-12" ng-repeat="singleRoom in BookRoom" ng-controller="scheckSingleRoomPayCtrl">-->
<!--                <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px; padding-bottom:20px; border-bottom: 1px solid #D3D6DE">-->
<!--                    <div class="col-sm-4 ">-->
<!--                        <label>应收数目</label>-->
<!--                        <input class="form-control"ng-model="singleRoom.payment.paymentRequest" />-->
<!--                    </div>-->
<!--                    <div class="col-sm-4 ">-->
<!--                        <label>账目类型</label>-->
<!--                        <select class="form-control"  ng-model="singleRoom.payment.paymentType"-->
<!--                                ng-change="sourceChange()">-->
<!--                            <option value="住房押金">住房押金</option>-->
<!--                        </select>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px;"-->
<!--                     ng-repeat="singlePay in singleRoom.payment.payByMethods" ng-controller="scheckSinglePayCtrl" >-->
<!--                    <div class="col-sm-4 ">-->
<!--                        <label>实收数目</label>-->
<!--                        <input class="form-control" ng-model="singlePay.payAmount" />-->
<!--                    </div>-->
<!--                    <div class="col-sm-4 ">-->
<!--                        <label>支付方式</label>-->
<!--                        <select class="form-control"  ng-model="singlePay.payMethod" >-->
<!--                            <option value="现金">现金</option>-->
<!--                            <option value="银行卡">银行卡</option>-->
<!--                            <option value="信用卡">信用卡</option>-->
<!--                        </select>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <a class="pull-right btn" style="margin-top: 5px; margin-right:33%" ng-click="addNewPayByMethod(singleRoom)">添加支付方式</a>-->
<!--                <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px;padding-top:20px;  border-top: 1px solid #D3D6DE">-->
<!--                    <label>未收数目</label>-->
<!--                    <label style="display:block;color: red; font-size: 25px;">{{singleRoom.payment.payInDue}}元</label>-->
<!--                </div>-->
<!--            </div>-->
<!--            <button class="pull-right" ng-if="initialstring == 'singleWalkIn'"-->
<!--                    style="margin-top:25px;padding: 10px 30px 10px 30px; background-color:grey; color: #ffffff"-->
<!--                    ng-click="submit()">确认入住并打印押金单</button>-->
<!--            <button class="pull-right"-->
<!--                    style="margin-top:25px;padding: 10px 30px 10px 30px; background-color:#69B4F5; color: #ffffff"-->
<!--                    ng-click="backward()">返回修改</button>-->
<!--        </div>-->
    </div>
</div>