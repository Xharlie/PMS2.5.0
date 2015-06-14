<!doctype html>
<div class="col-sm-12" ng-show="ready">
    <div class="card card-default">
        <div class="card-actions">
            <div class="ctrlArea" xmlns="http://www.w3.org/1999/html">
                <div class="col-sm-6">
                    <div class="col-sm-4">
                        <div class="input-group datePick" ng-controller="Datepicker" >
                            <input type="text" class="form-control input-lg" show-weeks="false" datepicker-popup="yyyy-MM-dd"
                                   ng-model="QueryDates.startDate" is-open="opened1" min-date="twoDaysBefore" max-date="QueryDates.endDate"
                                   datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                                   ng-required="true " close-text="Close" datepicker-append-to-body="true"/>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default btn-lg" ng-click="open1($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <label style="margin:10px 0 0 20%">至</label>
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group datePick" ng-controller="Datepicker" >
                            <input type="text" class="form-control input-lg" show-weeks="false" datepicker-popup="yyyy-MM-dd"
                                   ng-model="QueryDates.endDate" is-open="opened2" min-date="QueryDates.startDate" max-date="QueryDates.today"
                                   datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                                   ng-required="true" close-text="Close"
                                   datepicker-append-to-body="true"/>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default btn-lg" ng-click="open2($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-primary btn-lg" ng-click="refreshResult()">
                            刷新
                        </button>
                    </div>
                </div>
                <div class="ctrlRight col-sm-6">
                    <div class="col-sm-2">
                        <select class="btn btn-default form-control btn-lg" ng-model="Type" ng-init="Type='';">
                            <option value="">费用或结算</option>
                            <option value="CON">费用</option>
                            <option value="PAY">结算</option>
                        </select>
                    </div>
                    <div class=" col-sm-2">
                        <select class="btn btn-default btn-lg form-control" ng-model="class" >
                            <option value="">所有类别</option>
                            <option value="存入押金">存入押金</option>
                            <option value="退还押金">退还押金</option>
                            <option value="夜核房费">夜核房费</option>
                            <option value="损坏罚金">损坏罚金</option>
                            <option value="商品">商品</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select class="btn btn-default btn-lg form-control" ng-model="payMethod" >
                            <option value="">所有付费法</option>
                            <option value="现金">现金</option>
                            <option value="信用卡">信用卡</option>
                            <option value="银行卡">银行卡</option>
                            <option value="优惠券">优惠券</option>
                        </select>
                    </div>
                    <div class=" col-sm-2">
                        <select class="btn btn-default btn-lg form-control" ng-model="sorter" >
                            <option value="">排序</option>
                            <option value="ACCT_ID">帐单号</option>
                            <option value="TSTMP">发生时间</option>
                            <option value="RM_ID">房号</option>
                            <option value="CLASS">类别</option>
                            <option value="PAY_METHOD">付款方式</option>
                            <option value="CONSUME_PAY_AMNT">消费金额</option>
                            <option value="SUBMIT_PAY_AMNT">结算金额</option>
                            <option value="RMRK">备注</option>
                        </select>
                    </div>
                    <div class=" col-sm-3">
                        <input class="form-control searchBox input-lg" type="text"  ng-model = "searchAll" placeholder="智能搜索">
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class=" tableArea CrossTab">
                <table class="table table-striped table-acct">
            		<tr>
<!--                    <th>调账</th>-->
                        <th>帐单号</th>
                        <th>发生时间</th>
                        <th>房号</th>
                        <th>账目类型</th>
                        <th>付款方式</th>
                        <th>产生费用</th>
                        <th>结算金额</th>
            			<th>备注</th>
            		</tr>
                    <tr ng-repeat = "acct in acctInfo  | filter : {CLASS: class,PAY_METHOD: payMethod}
                    | filter:TypeFilter  | filter:searchAll | orderBy:sorter:true as collections "
                        ng-mouseenter="LightUp(acct)"
                        ng-mouseleave = 'LightBack(acct)'
                        ng-dblclick="open(acct)" ng-class="acct.blockClass"
                        onclick="event.preventDefault();">
<!--                            <td >-->
<!--                                <button class="btn btn-default btn-xs" ng-click="modify(acct)">调整</button>-->
<!--                            </td>-->
                        <td >
                            {{acct.ACCT_ID}}
                        </td>
                        <td>
                            {{acct.adjustedTSTMP}}
                        </td>
                        <td >
                            {{acct.RM_ID}}
                        </td>
                        <td >
                            {{acct.CLASS}}
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
                         <td >
                         </td>
                         <td>
                             总计
                         </td>
                         <td>
                             消费: {{toFixed(Conaddup)}}
                         </td>
                         <td>
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
<div class="loader loader-main" ng-hide="ready">
    <div class="loader-inner ball-scale-multiple">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>