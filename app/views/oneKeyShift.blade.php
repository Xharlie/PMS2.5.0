
<!doctype html>

<div class="col-sm-12" ng-show="ready">
    <div class="col-sm-12 card card-default">
        <div class="col-sm-12" style="background-color: #3399FF; padding: 10px 0px 10px 20px;">
            <h5 style=" color: #ffffff; font-size: 14px">班次内数据核对</h5>
        </div>
        <div class="col-sm-12" style="background-color: #03a9f4; padding: 30px 0px 30px 100px; color: #ffffff;font-size: 14px">
            <div class="col-sm-3">
                <label>准备金余额</label>
                <h1 style=" color: #ffffff; font-weight: bold">{{twoDigit(cashSum)}}元</h1>
            </div>
            <div class="col-sm-3">
                <label>房卡发放</label>
                <h1 style=" color: #ffffff;font-weight: bold">{{roomCardSum}}张</h1>
            </div>
            <div class="col-sm-3">
                <label>押金单打印</label>
                <h1 style=" color: #ffffff;font-weight: bold">{{depositReceiptSum}}份</h1>
            </div>
            <div class="col-sm-3">
                <label>会员卡发放</label>
                <h1 style=" color: #ffffff;font-weight: bold">{{memberCardSum}}张</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="leftPart card card-default">
            <div class="card-actions">
                <h4><span class="glyphicon glyphicon-shopping-cart" style="padding: 0px 15px 0px 15px;"/>商品库存核对</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped table-acct">
                    <tr>
                        <th><label>商品号</label></th>
                        <th><label>商品名称</label></th>
                        <th><label>出售量</label></th>
                        <th><label>剩余量</label></th>
                    </tr>
                    <tr ng-repeat = "product in productSellSum | orderBy:PROD_ID ">
                        <td>{{product.PROD_ID}}</td>
                        <td>{{product.PROD_NM}}</td>
                        <td>{{product.PROD_SUM_QUAN}}</td>
                        <td>{{product.PROD_AVA_QUAN}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="rightPart oneKeyCrossTab">
            <div class="card card-default">
                <div class="card-actions">
                    <h4><span class="glyphicon glyphicon-refresh" style="padding: 0px 15px 0px 15px;"/>班次交换信息</h4>
                </div>
                <div class="card-body">
                    <ul class="col-sm-6" style="margin: 40px 25% 60px 25%;">
                        <li class="col-sm-12" style="list-style: none; margin-bottom: 10px;">
                            <label>封包金额</label>
                            <input class="form-control" type="text" ng-model="ShiftInfo.SHFT_CSH_BOX"/>
                        </li>
                        <li class="col-sm-12" style="list-style: none; margin-bottom: 10px;">
                            <label>准备金交接金额</label>
                            <input class="form-control" type="text" ng-model="ShiftInfo.SHFT_END_PSS2_CSH"/>
                        </li>
                        <li class="col-sm-12" style="list-style: none; margin-bottom: 10px;">
                            <label>交班员工号</label>
                            <input class="form-control" type="text" ng-model="ShiftInfo.SHFT_PSS_EMP_ID"/>
                        </li>
                        <li class="col-sm-12" style="list-style: none; margin-bottom: 10px;">
                            <label>交班员工密码</label>
                            <input class="form-control" type="password"  ng-model="ShiftInfo.pssEmpPw" />
                        </li>
                    </ul>
                    <input class="col-md-8 btn btn-primary" type="submit" style="margin: 0px 17% 20px 17%"
                        value = "确认交班" ng-click="changeShiftSubmit()" />
<!--                        <table class="table table-bordered table-striped">-->
<!--                            <tr>-->
<!--                                <td><label>交班班次</label></td>-->
<!--                                <td>-->
<!--                                    <select class="form-control"-->
<!--                                            ng-model="oldShiftNM" class=""-->
<!--                                            ng-change=""-->
<!--                                            ng-style=""-->
<!--                                            ng-options="shiftTP as shiftTP for shiftTP in shiftTPs">-->
<!--                                    </select>-->
<!--                                </td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td><label>交班员工号码</label></td>-->
<!--                                <td><input class="form-control" ng-model="pssEmpUn" ng-style = "pssEmpUnStyle"/></td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td><label>交班员工密码</label></td>-->
<!--                                <td><input class="form-control" type="password"  ng-model="pssEmpPw" ng-style = "pssEmpPwStyle"/></td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td><label>备用金剩余金额</label></td>-->
<!--                                <td><input  class="form-control" ng-change="cashCalculate(cashleft)" ng-model="cashleft" ng-style = "cashleftStyle"/></td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td><label>待补备用金</label></td>-->
<!--                                <td><label>{{cashNeedAdd}}</label></td>-->
<!--                            </tr>-->
<!--                        </table>-->
                </div>
            </div>
        </div>
    </div>
</div>
<div style="margin-top: 20%; margin-left: 50%" ng-hide="ready">
    <img src="assets/dummy/pageloading.gif" />
</div>
