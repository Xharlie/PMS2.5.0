

<div id="wholeModal" >
    <div ng-hide="ready" class="loader loader-main">
        <div class="loader-inner ball-scale-multiple">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <div ng-show="ready">
        <div class="panel-heading" id="go">
            <h4 class="panel-title">
                <span ng-if="initialString =='checkOut'" class="icon-eject-outline"></span>
                <span ng-if="initialString =='checkLedger'" class="icon-sort-numeric-outline"></span>
                <label ng-if="initialString =='checkOut'" >退房办理</label>
                <label ng-if="initialString =='checkLedger'" >房间账目</label>
                <span class="pull-right close" ng-click="cancel()">&#x2715</span>
            </h4>
        </div>
        <div ng-show ="viewClick=='RoomChoose'">
            <table class="table table-striped table-acct" >
                <tr class="padded-block">
                    <th>房间号</th>
                    <th>房型</th>
                    <th>客人姓名</th>
                    <th>房间消费</th>
                    <th><input type="checkbox" ng-model="BookCommonInfo.selectAll"/> 全选</th>
                </tr>
                <tr class="padded-block" ng-repeat="singleRoom in BookRoom" ng-controller="checkOutSelectController">
                    <td>{{singleRoom.RM_ID}}</td>
                    <td>{{singleRoom.RM_TP}}</td>
                    <td>{{singleRoom.Customers[0].CUS_NAME}}</td>
                    <td>{{singleRoom.spendSumation}}</td>
                    <td><input type="checkbox" ng-model="singleRoom.selected" ng-checked="singleRoom.selected"/></td>
                </tr>
            </table>
            <div class="panel-body">
                <div class="row modal-control">
                    <button class="pull-right btn btn-primary btn-lg"
                            ng-click="confirmRoom('Info');checkTransferable();"
                            ng-if="initialString =='checkOut'" >
                        办理退房</button>
                    <button class="pull-right btn btn-primary btn-lg"
                            ng-click="confirmRoom('Info');checkTransferable();"
                            ng-if="initialString =='checkLedger'" >
                        账目查看</button>
                </div>
            </div>
        </div>
        <div ng-show ="viewClick=='Info'" >
            <div ng-transclude scroll-anchor="{{ItemScrollTo}}" fade-class="greenOut" new-ids="{{newAddedIds}}">
                <table class="table table-striped table-acct" >
                    <tr >
                        <th ><span ng-if="BookCommonInfo.transferable" >转入主房</span></th>
                        <th >时间</th>
                        <th >账单号</th>
                        <th >房间号</th>
                        <th >项目</th>
                        <th >金额(元)</th>
                        <th></th>
                    </tr>
                    <tr ng-repeat = "bill in acct.exceedPay | filter: {show : 'true'} | orderBy: BILL_TSTMP " ng-if="initialString =='checkOut'">
                        <td><input ng-if="BookCommonInfo.transferable" class="input-lg" type="checkbox" ng-model="bill.transfer"
                                   ng-checked="bill.transfer" ng-change="updateSumation()"/></td>
                        <td>{{bill.BILL_TSTMP}}</td>
                        <td>(提交后分配)</td>
                        <td>{{bill.RM_ID}}</td>
                        <td>超时{{bill.exceedTime}}分钟</td>
                        <td><input class="form-control input-lg"
                                   ng-model="bill.RM_PAY_AMNT" /></td>
                        <td><span class="icon-cancel btn gly-spin" ng-click="deleteItem(bill,acct.exceedPay,$index)"/></td>
                    </tr>
                    <tr ng-repeat = "bill in acct.AcctPay | filter: {show : 'true'} | orderBy: BILL_TSTMP  ">
                        <td><input ng-if="BookCommonInfo.transferable" class="input-lg" type="checkbox" ng-model="bill.transfer" ng-checked="bill.transfer" ng-change="updateSumation()"/></td>
                        <td>{{bill.BILL_TSTMP}}</td>
                        <td>BIL-{{bill.RM_BILL_ID}}</td>
                        <td>{{bill.RM_ID}}</td>
                        <td>{{bill.SUB_CAT}}房费</td>
                        <td>{{twoDigit(bill.RM_PAY_AMNT)}}元</td>
                        <td></td>
                    </tr>
                    <tr ng-repeat = "depo in acct.AcctDepo | filter: {show : 'true'} | orderBy: DEPO_TSTAMP  "  >
                        <td><input ng-if="BookCommonInfo.transferable" class="input-lg" type="checkbox" ng-model="depo.transfer" ng-checked="depo.transfer" ng-change="updateSumation()"/></td>
                        <td>{{depo.DEPO_TSTMP}}</td>
                        <td>DEP-{{depo.RM_DEPO_ID}}</td>
                        <td>{{depo.RM_ID}}</td>
                        <td>{{depo.PAY_METHOD}}{{depo.SUB_CAT}}押金</td>
                        <td>{{twoDigit(depo.DEPO_AMNT)}}元</td>
                        <td></td>
                    </tr>
                    <tr ng-repeat = "store in acct.AcctStore | filter: {show : 'true'} | orderBy: STR_TRAN_TSTMP  ">
                        <td><input ng-if="BookCommonInfo.transferable" type="checkbox" ng-model="store.transfer" ng-checked="store.transfer" ng-change="updateSumation()"/></td>
                        <td>{{store.STR_TRAN_TSTMP}}</td>
                        <td>STR-{{store.STR_TRAN_ID}}</td>
                        <td>{{store.RM_ID}}</td>
                        <td>{{store.PROD_NM}}＊{{store.PROD_QUAN}}</td>
                        <td>{{(store.PROD_PRICE!=undefined)?twoDigit(store.PROD_PRICE*store.PROD_QUAN):twoDigit(store.STR_PAY_AMNT)}}元</td>
                        <td></td>
                    </tr>
                    <tr ng-repeat = "pen in acct.AcctPenalty | filter: {show : 'true'} | orderBy: BILL_TSTMP  ">
                        <td><input ng-if="BookCommonInfo.transferable" type="checkbox" ng-model="pen.transfer" ng-checked="pen.transfer" ng-change="updateSumation()"/></td>
                        <td>{{pen.BILL_TSTMP}}</td>
                        <td>PEN-{{pen.PEN_BILL_ID}}</td>
                        <td>{{pen.RM_ID}}</td>
                        <td>赔偿费</td>
                        <td>{{twoDigit(pen.PNLTY_PAY_AMNT)}}元</td>
                        <td></td>
                    </tr>
                    <tr ng-repeat = "item in addedItems" id="newItem{{item.ID}}" >
                        <td><input ng-if="BookCommonInfo.transferable" type="checkbox" ng-model="item.transfer" ng-checked="item.transfer" ng-change="updateSumation()"/></td>
                        <td>{{item.TSTMP}}</td>
                        <td>(提交后分配)</td>
                        <td>{{item.RM_ID}}</td>
                        <td>{{item.showUp}}</td>
                        <td>{{twoDigit(item.PAY_AMNT)}}元</td>
                        <td><a ng-click="deleteItem(item,addedItems,$index)"><span class="icon-cancel gly-spin text-info"></span></a></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><label>{{(BookCommonInfo.Master.payment.paymentRequest >= 0)?'应补缴':'应退还'}}</label></td>
                        <td><label class="text-danger">{{twoDigit(abs(BookCommonInfo.Master.payment.paymentRequest))}}元</label></td>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div class="panel-body enableOverFlow">
                <div class="row enableOverFlow">
                    <div class="col-sm-6 form-group">
                        <label>房间号</label>
                        <select class="form-control input-lg" ng-model="newItem.RM_TRAN_ID" >
                            <option ng-repeat="singleRoom in BookRoom | filter: {selected:true}"
                                    value="{{singleRoom.RM_TRAN_ID}}" ng-selected="singleRoom.RM_TRAN_ID == newItem.RM_TRAN_ID">
                                {{singleRoom.RM_ID}}
                            </option>
                        </select>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label>消费项目</label>
                        <select class="form-control input-lg" name="sourceSelection" ng-model="newItem.itemCategory" >
                            <option value="merchant">商品</option>
                            <option value="newAcct">房费</option>
                            <option value="penalty">赔偿</option>
                        </select>
                    </div>
                    <div ng-include="'../app/views/parts/accountType.blade.php'">
                    </div>
                    <div class="row">
                        <a class="pull-right btn btn-link btn-lg" ng-click="addItem(newItem)">添加到账目</a>
                    </div>
                    <div class="modal-control row" >
                        <button class="pull-right btn btn-primary btn-lg" ng-if="initialString =='checkOut'"
                                ng-click="confirm('Pay')">
                            确认办理
                        </button>
                        <button class="pull-right btn btn-primary btn-lg" ng-if="initialString =='checkLedger'"
                                btn-loading="submitLoading"
                                loading-gif= 'assets/dummy/buttonProcessing.gif'
                                loading-text = '处理中请您稍候...'
                                ng-click="checkLedgerSubmit()">
                            确认调整</button>
                        <button class="pull-right btn btn-primary btn-lg" ng-if="BookRoom.length>1"
                                ng-click="backward('RoomChoose')">
                            上一步:选择房间</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div ng-show="viewClick=='Pay'">
                <div  payment  book-room="BookRoomMaster" pay-method-options="payMethodOptions" pay-error="payError"></div>
                <div class="row modal-control">
                    <button class="pull-right btn btn-primary btn-lg"
                            btn-loading="submitLoading"
                            loading-gif= 'assets/dummy/buttonProcessing.gif'
                            loading-text = '处理中请您稍候...'
                            ng-click="checkOTSubmit()" >确认并打印帐单</button>
                    <button class="pull-right btn btn-primary btn-lg"
                            ng-click="backward('Info')">返回修改</button>
                </div>
            </div>
        </div>
    </div>
</div>