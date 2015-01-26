
<div class="popUp">
    <div class="card card-default">
        <div class="card-body">
            <form xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" >
                    <div class="oldAcct">
                        <h5>目标账目</h5>
                        <table class="table table-striped table-bordered">
                            <tr>
                                <td>类别</td>
                                <td>账单号</td>
                                <td>房号</td>
                                <td>房单号</td>
                                <td>金额</td>
                            </tr>
                            <tr>
                                <td>{{oldTarget.CLASS}}</td>
                                <td>{{oldTarget.ACCT_ID}}</td>
                                <td>{{oldTarget.RM_ID}}</td>
                                <td>{{oldTarget.RM_TRAN_ID}}</td>
                                <td>{{oldTarget.PAY_AMNT}}</td>
                            </tr>
                        </table>

                    </div>

                    <div class="offsetAcct container-fluid">
                        <h5>修改账目</h5>
                        <div class="form-group col-xs-8">
                            <div class="col-xs-6">
                                <select ng-model="changePoNe" class="form-control" ng-init="changePoNe='补加'">
                                    <option value="减少">减少</option>
                                    <option value="补加">补加</option>
                                </select>
                            </div>

                            <div class="input-group col-xs-6">
                                <input class="form-control" type="text" ng-model="Amount" ng-style="amountStyle" />
                                <span class="input-group-addon">元</span>
                            </div>
                        </div>
                            <div class="col-xs-4">
                                <select class="form-control" ng-model="payMethod" ng-init="payMethod='现金'">
                                    <option value="现金">现金</option>
                                    <option value="信用卡">信用卡</option>
                                    <option value="银行卡">银行卡</option>
                                    <option value="优惠券">优惠券</option>
                                </select>
                            </div>

                    </div>


                        <div class="form-group col-xs-12">
                            <label>备注</label>
                            <textarea class="form-control" ng-style="rmrkStyle" ng-model="RMRK"></textarea>
                        </div>
                    </div>

                    <div class="authorization form-group col-xs-12">
                        <div class="col-xs-7 col-xs-offset-2">
                            <input type="password"  class="form-control" ng-change='psCheck()' ng-model="password" ng-style='passwordStyle' placeholder="输入密码"/>
                        </div> 
                        <div class="col-xs-3">   
                            <button
                                class="btn btn-default"
                                ng-mouseenter="resetMarkedBorder(); checkInCheck();"
                                ng-click="submit()"
                                popover="{{err}}"
                                popover-trigger="mouseenter"
                            >提交</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
