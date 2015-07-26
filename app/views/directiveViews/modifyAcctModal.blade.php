<div id="wholeModal">
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="icon-wrench-outline"></span>
            <label>账目修改</label>
            <span class="pull-right close" ng-click="cancel()">&#x2715</span>
        </h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-6 form-group">
                <label>原账目类型</label>
                <label class="form-control input-lg">{{oriAcct.CLASS}}</label>
            </div>
            <div class="col-sm-6 form-group">
                <label>原账目金额</label>
                <label class="form-control input-lg">{{oriAmountShow}}</label>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 form-group">
                <label xlabel ng-transclude checker="isNotEmpty|isNumber|isLargerEqualThan0" checkee="modifyAcct.payAmount" btn-pass="payError">
                    {{labelMapping(modifyAcct.changeType)}}{{oriAcct.CLASS}}数目
                </label>
                <input class="form-control input-lg" ng-model="modifyAcct.payAmount" />
            </div>
            <div class="col-sm-6 form-group">
                <label>支付方式</label>
                <select class="form-control input-lg"  ng-model="modifyAcct.payMethod" ng-disabled="!oriAcct.PAY">
                    <option value="现金">现金</option>
                    <option value="银行卡">银行卡</option>
                    <option value="信用卡">信用卡</option>
                    <option value="" ng-if="!oriAcct.PAY">N/A</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 form-group">
                <label>入账类型</label>
                <div>
                    <input type="radio" name="changeType" ng-model="modifyAcct.changeType" value="1" /><label>&nbsp;增加&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <input type="radio" name="changeType" ng-model="modifyAcct.changeType" value="-1"/><label>&nbsp;减少</label> 
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-12">
                <label>入账备注</label>
                <textarea rows="5" class="form-control" ng-model="modifyAcct.RMRK"></textarea>
            </div>
        </div>
        <div class="row modal-control">
            <button class="pull-right btn btn-primary btn-lg"
                    ng-click="submit()"
                    btn-loading="submitLoading"
                    loading-gif= 'assets/dummy/buttonProcessing.gif'
                    ng-if="payError == '0' || payError == null "
                    >确认帐目修改</button>
            <button class="pull-right btn btn-disabled btn-lg" ng-if=" payError != '0' && payError != null ">请更正错误信息</button>
            <button class="pull-right btn btn-primary btn-lg"
                    ng-click="cancel()">取消修改</button>
        </div>
    </div>
</div>