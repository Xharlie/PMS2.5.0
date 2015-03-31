
<!doctype html>
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

<div class="leftPart">
    <div style="margin-left: 30px;">
        <table>
            <col width="100px" />
            <col width="120px" />
            <tr>
                <td><label style="font-size: medium;">现金</label></td>
                <td><label style="font-size: medium;">合计{{cashSum}}元</label></td>
            </tr>
            <tr>
                <td><label style="font-size: medium;">房卡</label></td>
                <td><label style="font-size: medium;">合计{{roomCardSum}}张</label></td>
            </tr>
            <tr>
                <td><label style="font-size: medium;">押金单</label></td>
                <td><label style="font-size: medium;">{{depositSum}}份</label></td>
            </tr>

        </table>
       </br>
        <table>
            <col width="100px" />
            <col width="120px" />
            <col width="100px" />
            <tr>
                <th><label style="font-size: medium;">产品序号</label></th>
                <th><label style="font-size: medium;">产品名称</label></th>
                <th><label style="font-size: medium;"">共计售出</label></th>
            </tr>
            <tr ng-repeat = "product in productSellSum | orderBy:PROD_ID ">
                <td >{{product.PROD_ID}}</td>
                <td>{{product.PROD_NM}}</td>
                <td>{{product.PROD_SUM_QUAN}}</td>
            </tr>
        </table>
    </div>
</div>

<div class="rightPart oneKeyCrossTab">
    <div class="innerDiv">
        <h4>交班</h4>
        <table>
            <col width="170px" />
            <col width="120px" />
            <tr>
                <td><label style="font-size: medium;font-weight: lighter">交班班次</label></td>
                <td>
                    <select ng-model="oldShiftNM" class=""
                            ng-change=""
                            ng-style=""
                            ng-options="shiftTP as shiftTP for shiftTP in shiftTPs">
                    </select>
                </td>
            </tr>
            <tr>
                <td><label style="font-size: medium;font-weight: lighter">交班员工号码</label></td>
                <td><input class="gustContent" ng-model="pssEmpUn" ng-style = "pssEmpUnStyle"/></td>
            </tr>
            <tr>
                <td><label style="font-size: medium;font-weight: lighter">交班人密码</label></td>
                <td><input type="password" class="gustContent" ng-model="pssEmpPw" ng-style = "pssEmpPwStyle"/></td>
            </tr>
            <tr>
                <td><label style="font-size: medium;font-weight: lighter">备用金剩余金额</label></td>
                <td><input class="gustContent" ng-change="cashCalculate(cashleft)" ng-model="cashleft" ng-style = "cashleftStyle"/></td>
            </tr>
            <tr>
                <td><label style="font-size: medium;font-weight: lighter">待补备用金</label></td>
                <td><label style="font-size: medium;font-weight: lighter">{{cashNeedAdd}}</label></td>
            </tr>
        </table>
    </div>
    <div class="innerDiv">
        <h4>接班</h4>
        <table>
            <col width="190px" />
            <col width="120px" />
            <tr>
                <td><label style="font-size: medium;font-weight: lighter">接班班次</label></td>
                <td>
                    <select name=""  ng-model="newShiftNM" class=""
                            ng-change=""
                            ng-style=""
                            ng-options="shiftTP as shiftTP for shiftTP in shiftTPs">
                    </select>
                </td>
            </tr>
            <tr>
                <td><label style="font-size: medium;font-weight: lighter">接班员工号码</label></td>
                <td><input class="gustContent" ng-model="rEmpUn" ng-style = "rEmpUnStyle"/></td>
            </tr>
            <tr>
                <td><label style="font-size: medium;font-weight: lighter">接班人密码</label></td>
                <td><input type="password" class="gustContent" ng-model="rEmpPw" ng-style = "rEmpPwStyle"/></td>
            </tr>
        </table>
    </div>
    <input type="submit"
           value = "确认交班"
           ng-click="changeShiftSubmit()"
           style="float: right;"/>

</div>

