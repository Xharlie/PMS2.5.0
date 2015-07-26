<!doctype html>
<div class="col-sm-12" ng-show="ready">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-control" xmlns="http://www.w3.org/1999/html">
                <div class="col-sm-6">
                    <div class="col-sm-4 form-group">
                        <div class="input-group datePick" ng-controller="Datepicker" >
                            <input type="text" class="form-control input-lg" show-weeks="false" datepicker-popup="yyyy-MM-dd"
                                   ng-model="QueryDates.startDate" is-open="opened1" min-date="twoDaysBefore" max-date="QueryDates.endDate"
                                   datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                                   ng-required="true " close-text="Close" datepicker-append-to-body="true"/>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default btn-lg" ng-click="open1($event)"><i class="icon-calendar-outline"></i></button>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <label class="pull-right" style="vertical-align:middle;text-align:center;">_&nbsp;</label>
                    </div>
                    <div class="col-sm-4 form-group">
                        <div class="input-group datePick" ng-controller="Datepicker" >
                            <input type="text" class="form-control input-lg" show-weeks="false" datepicker-popup="yyyy-MM-dd"
                                   ng-model="QueryDates.endDate" is-open="opened2" min-date="QueryDates.startDate" max-date="QueryDates.today"
                                   datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                                   ng-required="true" close-text="Close"
                                   datepicker-append-to-body="true"/>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default btn-lg" ng-click="open2($event)"><i class="icon-calendar-outline"></i></button>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button class="pull-right btn btn-primary btn-lg" ng-click="refreshResult()"><span class="icon-arrows-cw"></span></button>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="pull-right">
                        <div class="btn-group" dropdown is-open="Type.isopen"
                             ng-init="selectTo('','费用与结算',Type)" dropdown-append-to-body>
                            <button type="button" class="btn btn-primary dropdown-toggle" dropdown-toggle>
                                {{Type.caption}} <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href ng-click="selectTo('','费用与结算',Type)">费用与结算</a></li>
                                <li><a href ng-click="selectTo('CON','仅费用',Type)">仅费用</a></li>
                                <li><a href ng-click="selectTo('PAY','仅结算',Type)">仅结算</a></li>
                            </ul>
                        </div>
                        <div class="btn-group" dropdown is-open="class.isopen"
                             ng-init="selectTo('','所有类别',class)" dropdown-append-to-body>
                            <button type="button" class="btn btn-primary dropdown-toggle" dropdown-toggle>
                                {{class.caption}} <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href ng-click="selectTo('','所有类别',class)">所有类别</a></li>
                                <li><a href ng-click="selectTo('存入押金','存入押金',class)">存入押金</a></li>
                                <li><a href ng-click="selectTo('退还押金','退还押金',class)">退还押金</a></li>
                                <li><a href ng-click="selectTo('夜核房费','夜核房费',class)">夜核房费</a></li>
                                <li><a href ng-click="selectTo('损坏罚金','损坏罚金',class)">损坏罚金</a></li>
                                <li><a href ng-click="selectTo('商品','商品',class)">商品</a></li>
                            </ul>
                        </div>
                        <div class="btn-group" dropdown is-open="payMethod.isopen"
                             ng-init="selectTo('','所有方式',payMethod)" dropdown-append-to-body>
                            <button type="button" class="btn btn-primary dropdown-toggle" dropdown-toggle>
                                {{payMethod.caption}} <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href ng-click="selectTo('','所有类别',payMethod)">所有方式</a></li>
                                <li><a href ng-click="selectTo('现金','现金',payMethod)">现金</a></li>
                                <li><a href ng-click="selectTo('信用卡','信用卡',payMethod)">信用卡</a></li>
                                <li><a href ng-click="selectTo('银行卡','银行卡',payMethod)">银行卡</a></li>
                                <li><a href ng-click="selectTo('损坏罚金','损坏罚金',payMethod)">损坏罚金</a></li>
                                <li><a href ng-click="selectTo('优惠券','优惠券',payMethod)">优惠券</a></li>
                            </ul>
                        </div>
                        <div class="btn-group" dropdown is-open="sorter.isopen"
                             ng-init="selectTo('','排序',sorter)" dropdown-append-to-body>
                            <button type="button" class="btn btn-primary dropdown-toggle" dropdown-toggle>
                                {{sorter.caption}} <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href ng-click="selectTo('','排序',sorter)">排序</a></li>
                                <li><a href ng-click="selectTo('ACCT_ID','帐单号',sorter)">帐单号</a></li>
                                <li><a href ng-click="selectTo('TSTMP','发生时间',sorter)">发生时间</a></li>
                                <li><a href ng-click="selectTo('RM_ID','房号',sorter)">房号</a></li>
                                <li><a href ng-click="selectTo('CLASS','类别',sorter)">类别</a></li>
                                <li><a href ng-click="selectTo('PAY_METHOD','付款方式',sorter)">付款方式</a></li>
                                <li><a href ng-click="selectTo('CONSUME_PAY_AMNT','消费金额',sorter)">消费金额</a></li>
                                <li><a href ng-click="selectTo('SUBMIT_PAY_AMNT','结算金额',sorter)">结算金额</a></li>
                            </ul>
                        </div>
                        <input class="input-lg" type="text"  ng-model = "searchAll" placeholder="智能搜索" />
                    </div>
                </div>
            </div>
        </div>

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
            <tr ng-repeat = "acct in acctInfo  | filter : {CLASS: class.value,PAY_METHOD: payMethod.value}
            | filter:TypeFilter  | filter:searchAll | orderBy:sorter.value:true as collections "
                ng-mouseenter="LightUp(acct)"
                ng-mouseleave = 'LightBack(acct)'
                ng-dblclick="open(acct)" ng-class="acct.blockClass"
                onclick="event.preventDefault();">
<!--                            <td >-->
<!--                                <button class="btn btn-default btn-xs" ng-click="modify(acct)">调整</button>-->
<!--                            </td>-->
                <td >
                    {{acct.ACCT_ID+((acct.ORGN_ACCT_ID!=null)?'(原账目:'+acct.ORGN_ACCT_ID+')':'')}}
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
<div class="loader loader-main" ng-hide="ready">
    <div class="loader-inner ball-scale-multiple">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>