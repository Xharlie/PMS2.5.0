
<link rel="stylesheet" type="text/css" href="css/newModifyWindow.css">
<form xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" >
    <div style="margin-left: 10px;">
        <div class="oldAcct">
            <h4>目标账目</h4>
            <label class="gustCaption">类别:</label>
            <label class="gustContent">{{oldTarget.CLASS}}</label>

            <label class="gustCaption" >账单号:</label>
            <label class="gustContent">{{oldTarget.ACCT_ID}}</label>

            <label  class="gustCaption">房号:</label>
            <label class="gustContent">{{oldTarget.RM_ID}}</label>

            <label class="gustCaption">房单号:</label>
            <label class="gustContent">{{oldTarget.RM_TRAN_ID}}</label>

            <label class="gustCaption">金额:</label>
            <label class="gustContent">{{oldTarget.PAY_AMNT}}</label>

        </div>

        <div class="offsetAcct">
            <h4>修改账目</h4>

            <select ng-model="changePoNe" class="gustCaption" ng-init="changePoNe='补加'">
                <option value="减少">减少</option>
                <option value="补加">补加</option>
            </select>

            <label class="gustCaption" style="margin-left:20px;">{{changePoNe}}金额:</label>
            <input type="text" ng-model="Amount" style="width: 80px" ng-style="amountStyle"/>元
            <select ng-model="payMethod" ng-init="payMethod='现金'">
                <option value="">所有付费方法</option>
                <option value="现金">现金</option>
                <option value="信用卡">信用卡</option>
                <option value="银行卡">银行卡</option>
                <option value="优惠券">优惠券</option>
            </select>

            <div style="margin-top: 10px; margin-left:20px;">
                <label style="vertical-align: top; margin-right: 10px;">备注:</label>
                <textarea ng-style="rmrkStyle" ng-model="RMRK" rows="3" cols="60">
                </textarea>
            </div>
        </div>

        <div class="authorization">
            <label class="gustCaption" style="margin-left:20px;">密码:</label>
            <input type="password" ng-change='psCheck()' ng-model="password" ng-style='passwordStyle' style="width: 90px; margin-right: 15px;"/>
            <button
                style="width: 60px;"
                ng-mouseenter="resetMarkedBorder(); checkInCheck();"
                ng-click="submit()"
                popover="{{err}}"
                popover-trigger="mouseenter"
            >提交</button>
        </div>
    </div>
</form>
