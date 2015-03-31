
<!doctype html>

<div class="col-sm-12" ng-show="ready">
    <div class="datePick" ng-controller="Datepicker" xmlns="http://www.w3.org/1999/html">
    <!--    <label >查看从</label>
            <div class="datePicker">
                <p class="input-group">
                    <input type="text" class="form-control" show-weeks="false" datepicker-popup="{{format}}"
                           ng-model="checkStart" is-open="opened1" min-date="minDate" max-date="checkEnd"
                           datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                           ng-required="true " close-text="Close" ng-change="dateChange($index)"
                           ng-style="singleRoom.CheckInStyle"
                           ng-init="checkStart = toDay"/>

                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default" ng-click="open1($event)"><i class="glyphicon glyphicon-calendar"></i></button> <!-- open1($event)
                                </span>
                </p>
            </div>
            <label >到</label>
            <div class="datePicker">
                <p class="input-group">
                    <input type="text" class="form-control" show-weeks="false" datepicker-popup="{{format}}"
                           ng-model="checkEnd" is-open="opened2" min-date="checkStart" max-date="toDay"
                      datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                           ng-required="true" close-text="Close" ng-change="dateChange($index)"
                           ng-style="singleRoom.CheckOTStyle"
                           ng-init="checkEnd = toDay"
                        />

                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default" ng-click="open2($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                                </span>
                </p>
            </div> -->
    </div>

    <div class="col-sm-6">
        <div class="leftPart card card-default">
            <div class="card-actions">
                <h4><div class="title-decor title-decor-md"></div>班次统计</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <tr>
                        <td><label>现金</label></td>
                        <td><label>合计{{twoDigit(cashSum)}}元</label></td>
                    </tr>
                    <tr>
                        <td><label>房卡</label></td>
                        <td><label>合计{{roomCardSum}}张</label></td>
                    </tr>
                    <tr>
                        <td><label>押金单</label></td>
                        <td><label>{{depositSum}}份</label></td>
                    </tr>

                </table>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th><label>产品序号</label></th>
                        <th><label>产品名称</label></th>
                        <th><label>共计售出</label></th>
                    </tr>
                    <tr ng-repeat = "product in productSellSum | orderBy:PROD_ID ">
                        <td >{{product.PROD_ID}}</td>
                        <td>{{product.PROD_NM}}</td>
                        <td>{{product.PROD_SUM_QUAN}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="rightPart oneKeyCrossTab">
                <div class="innerDiv card card-default">
                    <div class="card-actions">
                        <h4><div class="title-decor title-decor-md"></div>交班</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <td><label>交班班次</label></td>
                                <td>
                                    <select class="form-control"
                                            ng-model="oldShiftNM" class=""
                                            ng-change=""
                                            ng-style=""
                                            ng-options="shiftTP as shiftTP for shiftTP in shiftTPs">
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><label>交班员工号码</label></td>
                                <td><input class="form-control" ng-model="pssEmpUn" ng-style = "pssEmpUnStyle"/></td>
                            </tr>
                            <tr>
                                <td><label>交班人密码</label></td>
                                <td><input class="form-control" type="password"  ng-model="pssEmpPw" ng-style = "pssEmpPwStyle"/></td>
                            </tr>
                            <tr>
                                <td><label>备用金剩余金额</label></td>
                                <td><input  class="form-control" ng-change="cashCalculate(cashleft)" ng-model="cashleft" ng-style = "cashleftStyle"/></td>
                            </tr>
                            <tr>
                                <td><label>待补备用金</label></td>
                                <td><label>{{cashNeedAdd}}</label></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="innerDiv card card-default">
                    <div class="card-actions">
                        <h4><div class="title-decor title-decor-md"></div>接班</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <td><label>接班班次</label></td>
                                <td>
                                    <select class="form-control"
                                            name=""  ng-model="newShiftNM"
                                            ng-change=""
                                            ng-style=""
                                            ng-options="shiftTP as shiftTP for shiftTP in shiftTPs">
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><label>接班员工号码</label></td>
                                <td><input class="form-control" ng-model="rEmpUn" ng-style = "rEmpUnStyle"/></td>
                            </tr>
                            <tr>
                                <td><label>接班人密码</label></td>
                                <td><input class="form-control"ntype="password" ng-model="rEmpPw" ng-style = "rEmpPwStyle"/></td>
                            </tr>
                        </table>
                         <input 
                         class="col-md-12 btn btn-primary"
                         type="submit"
                           value = "确认交班"
                           ng-click="changeShiftSubmit()"
                          />
                    </div>
                </div>
        </div>
    </div>
</div>
<div style="margin-top: 20%; margin-left: 50%" ng-hide="ready">
    <img src="assets/dummy/pageloading.gif" />
</div>
