<!doctype html>

<div class="col-sm-12" ng-show="ready">
    <div class="card card-default">
        <div class="card-actions">
            <div class="ctrlArea" xmlns="http://www.w3.org/1999/html">
            <!--    <div id = "statusDiv">
                    <label>今日欲达: 5</label>
                    <label>预定状态: 满房</label><br>
                </div> -->
                <div class="ctrlRight">
                    <input class="input-sm searchBox" type="text"  ng-model = "searchAll" placeholder="智能搜索">
                    <select class="btn btn-default" ng-model="Type" ng-init="Type='';">
                        <option value="">消费或结算</option>
                        <option value="CON">消费</option>
                        <option value="PAY">结算</option>
                    </select>
                    <select class="btn btn-default" ng-model="class" >
                        <option value="">所有类别</option>
                        <option value="存入押金">存入押金</option>
                        <option value="现金支出">现金支出</option>
                        <option value="夜核房费">夜核房费</option>
                        <option value="损坏罚金">损坏罚金</option>
                        <option value="商品">商品</option>
                    </select>
                    <select class="btn btn-default" ng-model="payMethod" >
                        <option value="">所有付费方法</option>
                        <option value="现金">现金</option>
                        <option value="信用卡">信用卡</option>
                        <option value="银行卡">银行卡</option>
                        <option value="优惠券">优惠券</option>
                    </select>
                    <select class="btn btn-default" ng-model="sorter" >
                        <option value="">排序</option>
                        <option value="TSTMP">下订日期</option>
                        <option value="CLASS">类别</option>
                        <option value="ACCT_ID">帐单号</option>
                        <option value="RM_ID">房号</option>
                        <option value="RM_TRAN_ID">房单号</option>
                        <option value="PAYER_NM">付款人姓名</option>
                        <option value="PAYER_PHONE">付款人电话</option>
                        <option value="PAY_METHOD">付款方式</option>
                        <option value="CONSUME_PAY_AMNT">消费金额</option>
                        <option value="SUBMIT_PAY_AMNT">结算金额</option>
                        <option value="RMRK">备注</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="acctList CrossTab tableArea">
            	<table class="table table-striped table-bordered">
            		<tr>
                        <th>调账</th>
                        <th>下订日期</th>
                        <th>类别</th>
                        <th>帐单号</th>
            			<th>房号</th>
            			<th>房单号</th>
                        <th>付款人姓名</th>
            			<th>付款人电话</th>
                        <th>付款方式</th>
                        <th>消费</th>
                        <th>结算</th>
            			<th>备注</th>
            		</tr>

                    <tr ng-repeat = "acct in acctInfo  | filter : {CLASS: class,PAY_METHOD: payMethod}
                    | filter:TypeFilter  | filter:searchAll | orderBy:sorter as collections ">
                            <td >
                                <button class="btn btn-default btn-xs" ng-click="modify(acct)">调整</button>
                            </td>
                            <td >
                                {{acct.TSTMP}}
                            </td>
                            <td >
                                {{acct.CLASS}}
                            </td>
                            <td >
                                {{acct.ACCT_ID}}
                            </td>
                            <td >
                                {{acct.RM_ID}}
                            </td>
                            <td >
                                {{acct.RM_TRAN_ID}}
                            </td>
                            <td >
                                {{acct.PAYER_NM}}
                            </td>
                            <td >
                                {{acct.PAYER_PHONE}}
                            </td>
                            <td >
                                {{acct.PAY_METHOD}}
                            </td>
                            <td >
                                {{toFixed(acct.CONSUME_PAY_AMNT)}}
                            </td>
                            <td >
                                {{toFixed(acct.SUBMIT_PAY_AMNT)}}
                            </td>
                            <td >
                                {{acct.RMRK}}
                            </td>
                    </tr>
                    <tr>
                             <td >
                             </td>
                             <td >
                             </td>
                             <td >
                             </td>
                             <td>
                             </td>
                             <td >
                             </td>
                             <td >
                             </td>
                             <td >
                             </td>
                            <td >
                            </td>
                             <td style="font-weight:bold; font-size: medium">
                                 总计
                             </td>
                             <td style="font-weight: bold;font-size: medium">
                                 消费: {{toFixed(Conaddup)}}
                             </td>
                             <td style="font-weight: bold;font-size: medium">
                                 结算入账: {{toFixed(Payaddup)}}
                             </td>
                             <td >
                             </td>


                    </tr>
            	</table>

            </div>

        </div>
    </div>
</div>
<div style="margin-top: 20%; margin-left: 50%" ng-hide="ready">
    <img src="assets/dummy/pageloading.gif" />
</div>