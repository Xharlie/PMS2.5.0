<link rel="stylesheet" type="text/css" href="css/newOut.css">
<form xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" >
    <div class="wholeInfoBlock" ng-repeat="singleRoom in BookRoom">
        <div class="headDiv">
            <h1 class="PerRoom">{{}}号房</h1>
        </div>
        <div class="ChooseRoom">
            <div>
                <h4>住房帐</h4>
                <div class="CrossTab">
                    <label>押金</label>
                    <table>
                        <tr>
                            <th>时间</th>
                            <th>房单号</th>
                            <th>存入金额</th>
                            <th>存入方式</th>
                        </tr>
                        <tr  ng-repeat = "depo in singleRoom.AcctDepo | orderBy: DEPO_TSTAMP  ">
                            <td>{{depo.DEPO_TSTMP}}</td>
                            <td>{{depo.RM_TRAN_ID}}</td>
                            <td>{{depo.DEPO_AMNT}}</td>
                            <td>{{PayMethod(depo.PAY_METHOD)}}</td>
                        </tr>
                    </table>
                </div>
                <div class="CrossTab">
                    <label>房费</label>
                    <table>
                        <tr>
                            <th>时间</th>
                            <th>房单号</th>
                            <th>付费金额</th>
                            <th>付费方式</th>
                        </tr>
                        <tr  ng-repeat = "bill in singleRoomAcctPay | orderBy: BILL_TSTMP  ">
                            <td>{{bill.BILL_TSTMP}}</td>
                            <td>{{bill.RM_TRAN_ID}}</td>
                            <td>{{bill.RM_PAY_AMNT}}</td>
                            <td>{{PayMethod(bill.RM_PAY_METHOD)}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div>
                <div class="CrossTab">
                    <h4>商品帐</h4>
                    <table>
                        <tr>
                            <th>时间</th>
                            <th>房单号</th>
                            <th>付费金额</th>
                            <th>付费方式</th>
                        </tr>
                        <tr  ng-repeat = "store in singleRoom.AcctStore | orderBy: STR_TRAN_TSTAMP  ">
                            <td>{{store.STR_TRAN_TSTAMP}}</td>
                            <td>{{store.RM_TRAN_ID}}</td>
                            <td>{{store.STR_PAY_AMNT}}</td>
                            <td>{{PayMethod(store.STR_PAY_METHOD)}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="checkInCustomer">
        </div>
    </div>
    {{test}}
</form>