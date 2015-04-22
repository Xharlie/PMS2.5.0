<div>
    <div ng-hide="ready">
        <img src="assets/dummy/pageloading.gif" />
    </div>
    <div class="fluid-container" ng-show="ready">
        <div class="modal-header card-actions" id="go">
            <h4 class="pull-left"><span class="glyphicon glyphicon-arrow-left"></span>退房办理</h4>{{}}
            <span class="pull-right btn" ng-click="cancel()">&#x2715</span>
            <!--        <div class="col-sm-12 ">-->
            <!--            <div class="col-sm-3" >-->
            <!--                退房房号:全选 <input type="checkbox" ng-model="BookCommonInfo.selectAll"/>-->
            <!--            </div>-->
            <!--            <div class="col-sm-1" ng-repeat="singleRoom in BookRoom" ng-controller="checkOutSelectController">-->
            <!--                <input type="checkbox" ng-model="singleRoom.selected" ng-checked="singleRoom.selected"/>-->
            <!--                <label>{{singleRoom.RM_ID}}</label>-->
            <!--            </div>-->
            <!--        </div>-->
        </div>
        <div class="modal-body">
            <div class="col-sm-12" ng-show ="viewClick=='RoomChoose'">
                <div class="card-body tableArea">
                    <table class="table table-striped table-acct" >
                        <tr class="padded-block">
                            <th>房间号</th>
                            <th>房型</th>
                            <th>客人姓名</th>
                            <th>房间消费</th>
                            <th><input type="checkbox" ng-model="BookCommonInfo.selectAll"/>全选</th>
                        </tr>
                        <tr class="padded-block" ng-repeat="singleRoom in BookRoom" ng-controller="checkOutSelectController">
                            <td>{{singleRoom.RM_ID}}</td>
                            <td>{{singleRoom.RM_TP}}</td>
                            <td>{{singleRoom.Customers[0].CUS_NAME}}</td>
                            <td>{{singleRoom.spendSumation}}</td>
                            <td><input type="checkbox" ng-model="singleRoom.selected" ng-checked="singleRoom.selected"/></td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer padded-block">
                    <button class="pull-right btn btn-primary"
                            ng-click="confirmRoom('Info')">
                        办理退房</button>
                </div>
            </div>
            <div class="col-sm-12" ng-show ="viewClick=='Info'" >
                <div class="card-body">
                    <div ng-transclude class="col-sm-12 form-group tableArea"
                         scroll-anchor="ItemScrollTo" fade-class="greenOut" new-ids="newAddedIds">
                        <table class="table table-striped table-acct" >
                            <tr >
                                <th></th>
                                <th ng-if="BookRoom.length>1" >转入主房</th>
                                <th >时间</th>
                                <th >账单号</th>
                                <th >房间号</th>
                                <th >项目</th>
                                <th >金额(元)</th>
                            </tr >
                            <tr ng-repeat = "bill in acct.exceedPay | filter: {show : 'true'} | orderBy: BILL_TSTMP  ">
                                <td><span class="glyphicon glyphicon-remove-circle btn gly-spin" ng-click="deleteItem(bill,acct.exceedPay,$index)"/></td>
                                <td ng-if="BookRoom.length>1"><input  type="checkbox" ng-model="bill.transfer"
                                           ng-checked="bill.transfer" ng-click="updateSumation()"/></td>
                                <td>{{bill.BILL_TSTMP}}</td>
                                <td>(提交后分配)</td>
                                <td>{{bill.RM_ID}}</td>
                                <td>超时{{bill.exceedTime}}分钟</td>
                                <td><input class="form-control"
                                           ng-model="bill.RM_PAY_AMNT" /></td>
                            </tr>
                            <tr ng-repeat = "bill in acct.AcctPay | filter: {show : 'true'} | orderBy: BILL_TSTMP  ">
                                <td></td>
                                <td ng-if="BookRoom.length>1"><input type="checkbox" ng-model="bill.transfer" ng-checked="bill.transfer" ng-click="updateSumation()"/></td>
                                <td>{{bill.BILL_TSTMP}}</td>
                                <td>BIL-{{bill.RM_BILL_ID}}</td>
                                <td>{{bill.RM_ID}}</td>
                                <td>房费</td>
                                <td>{{twoDigit(bill.RM_PAY_AMNT)}}元</td>
                            </tr>
                            <tr ng-repeat = "depo in acct.AcctDepo | filter: {show : 'true'} | orderBy: DEPO_TSTAMP  "  >
                                <td></td>
                                <td ng-if="BookRoom.length>1" ><input type="checkbox" ng-model="depo.transfer" ng-checked="depo.transfer" ng-click="updateSumation()"/></td>
                                <td>{{depo.DEPO_TSTMP}}</td>
                                <td>DEP-{{depo.RM_DEPO_ID}}</td>
                                <td>{{depo.RM_ID}}</td>
                                <td>{{depo.RMRK}}</td>
                                <td>{{twoDigit(depo.DEPO_AMNT)}}元</td>
                            </tr>
                            <tr ng-repeat = "store in acct.AcctStore | filter: {show : 'true'} | orderBy: STR_TRAN_TSTAMP  ">
                                <td></td>
                                <td input ng-if="BookRoom.length>1">< type="checkbox" ng-model="store.transfer" ng-checked="store.transfer" ng-click="updateSumation()"/></td>
                                <td>{{store.STR_TRAN_TSTAMP}}</td>
                                <td>STR-{{store.STR_TRAN_ID}}</td>
                                <td>{{store.RM_ID}}</td>
                                <td>{{store.PROD_NM}}X{{store.PROD_QUAN}}</td>
                                <td>{{(store.PROD_PRICE!=undefined)?twoDigit(store.PROD_PRICE*store.PROD_QUAN):twoDigit(store.STR_PAY_AMNT)}}元</td>
                            </tr>
                            <tr ng-repeat = "item in addedItems" id="newItem{{item.ID}}" >
                                <td><span class="glyphicon glyphicon-remove-circle btn gly-spin" ng-click="deleteItem(item,addedItems,$index)" ng-click="updateSumation()"/></td>
                                <td input ng-if="BookRoom.length>1">< type="checkbox" ng-model="item.transfer" ng-checked="item.transfer"/></td>
                                <td>{{item.TSTMP}}</td>
                                <td>(提交后分配)</td>
                                <td>{{item.RM_ID}}</td>
                                <td>{{item.showUp}}</td>
                                <td>{{twoDigit(item.PAY_AMNT)}}元</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-12 form-group">
                        <label class="pull-right col-sm-2">{{twoDigit(BookCommonInfo.Master.payment.paymentRequest)}}元</label>
                        <label class="pull-right col-sm-2">总计:</label>
                    </div>
                </div>
                <div class="card-footer padded-block">
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-2 ">
                            <label>房间号</label>
                            <select class="form-control" ng-model="newItem.RM_TRAN_ID" >
                                <option ng-repeat="singleRoom in BookRoom | filter: {selected:true}"
                                        value="{{singleRoom.RM_TRAN_ID}}" ng-selected="singleRoom.RM_TRAN_ID == newItem.RM_TRAN_ID">
                                    {{singleRoom.RM_ID}}
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-2 ">
                            <label>入账金额</label>
                            <input class="form-control" ng-model="newItem.paymentRequest" />
                        </div>

                        <div class="col-sm-4" >
                            <label class="col-sm-12">消费项目</label>
                            <div class="btn-group dropup col-sm-12" dropdown is-open="newItem.isopen">
                                <button type="button" class="btn btn-default col-sm-10">{{newItem.showup}}</button>
                                <button type="button" class="btn btn-default dropdown-toggle" dropdown-toggle>
                                    <span class="caret "></span>
                                </button>
                                <div class="dropdown-menu" role="menu"
                                     ng-click="$event.stopPropagation()">
                                    <div class="col-sm-3 roomAction">
                                        <table>
                                            <tr>
                                                <td><button class="btn btn-link col-sm-12" ng-click="changeCategory(newItem,'others')">其他</button></td>
                                            </tr>
                                            <tr>
                                                <td><button class="btn btn-link col-sm-12" ng-click="changeCategory(newItem,'merchant')">商品</button></td>
                                            </tr>
                                            <tr>
                                                <td><button class="btn btn-link col-sm-12" ng-click="changeCategory(newItem,'penalty')">赔偿</button></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-sm-9" ng-show="newItem.itemCategory=='merchant'"
                                            >
                                        <table class="table table-striped">
                                            <col width="10%">
                                            <col width="60%">
                                            <col width="30%">
                                            <tr>
                                                <th></th>
                                                <th>产品名称</th>
                                                <th>数量</th>
                                            </tr>
                                            <tr ng-repeat="prod in newItem.prodInfo | filter: {ROOM_BAR : '!F'}">
                                                <td><input type="checkbox" ng-model="prod.PROD_SELECTED" /></td>
                                                <td><label >{{prod.PROD_NM}}</label></td>
                                                <td><input class="col-sm-12" ng-model="prod.PROD_QUAN" /></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-sm-9" ng-show="newItem.itemCategory=='penalty'" >
                                        <div class="col-sm-6">
                                            <label>赔偿项目</label>
                                                    <input ng-model="newItem.penalty.PENALTY_ITEM" />
                                        </div>
                                        <div class="col-sm-6 ">
                                            <label>金额</label>
                                                    <input ng-model="newItem.penalty.PAY_AMNT" />
                                        </div>
                                        <div class="col-sm-12 ">
                                            <label>赔款人姓名</label>
                                                    <input ng-model="newItem.penalty.PAYER" />
                                        </div>
                                        <div class="col-sm-12">
                                            <label>赔款人电话</label>
                                                    <input ng-model="newItem.penalty.PAYER_PHONE" />
                                        </div>

                                    </div>
                                    <div class="col-sm-9" ng-show="newItem.itemCategory=='others'">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 ">
                            <label>备注</label>
                            <input class="form-control" ng-model="newItem.RMRK" />
                        </div>
                    </div>
                    <div class="col-sm-12 btn">
                        <button class="pull-right btn btn-link" ng-click="addItem(newItem)">添加到账目</button>
                    </div>
                    <div class="col-sm-12">
                        <button class="pull-right btn btn-primary"
                                ng-click="confirm('Pay')">
                            确认办理</button>
                        <button class="pull-right btn btn-primary" ng-if="BookRoom.length>1"
                                ng-click="backward('RoomChoose')">
                            上一步:选择房间</button>
                    </div>
                </div>
            </div>
            <div class="col-sm-12" ng-show ="viewClick=='Pay'">
                <div class="col-sm-12">
                    <div class="col-sm-12 form-group" >
                        <h4>退房账款:
                            <p ng-repeat="singleRoom in BookRoom | filter: {selected:true}">
                                {{singleRoom.RM_ID}}</p>
                        </h4>
                    </div>
                    <div class="col-sm-12 form-group">
                        <div class="col-sm-4 ">
                            <label>应收数目</label>
                            <input class="form-control"ng-model="BookCommonInfo.Master.payment.paymentRequest"
                                   disabled/>
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
                    <div class="col-sm-12 form-group"
                         ng-repeat="singlePay in BookCommonInfo.Master.payment.payByMethods" ng-controller="chotSingleMasterPayCtrl" >
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
                    <a class="pull-right btn" ng-click="addNewPayByMethod(BookCommonInfo.Master)">添加支付方式</a>
                    <div class="col-sm-12 form-group">
                        <label>未收数目</label>
                        <label>{{BookCommonInfo.Master.payment.payInDue}}元</label>
                    </div>
                </div>

                <button class="pull-right btn btn-primary"
                        btn-loading="submitLoading"
                        reset-text = '确认并打印押金单'
                        loading-text = '处理中请您稍后'
                        loading-gif= 'assets/dummy/buttonProcessing.gif'
                        ng-click="submit()" >确认并打印押金单</button>
                <button class="pull-right btn btn-primary"
                        ng-click="backward('Info')">返回修改</button>
            </div>
        </div>
    </div>
</div>