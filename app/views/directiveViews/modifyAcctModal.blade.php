<div>
    <div class="modal-header">
        <span class="glyphicon glyphicon-send"></span>
        <label style="font-size: 15px;">新入住</label>{{}}
        <span class="pull-right btn" ng-click="cancel()">&#x2715</span>
    </div>
    <div class="col-sm-12" style="padding: 30px 60px 45px 60px;">
        <div class="col-sm-12"  >
            <div class="col-sm-4 ">
                <label>原账目类型</label>
                <label class="form-control">{{oriAcct.CLASS}}</label>
            </div>
            <div class="col-sm-4 ">
                <label>原账目金额</label>
                <label class="form-control">{{oriAmountShow}}</label>
            </div>
        </div>
        <div class="col-sm-12" style="padding: 20px 0px 0px 0px; ">
            <div class="col-sm-4 ">
                <label>{{labelMapping(modifyAcct.changeType)}}{{oriAcct.CLASS}}数目</label>
                <input class="form-control" ng-model="modifyAcct.payAmount" />
            </div>
            <div class="col-sm-4 ">
                <label>支付方式</label>
                <select class="form-control"  ng-model="modifyAcct.payMethod" ng-disabled="!oriAcct.PAY">
                    <option value="现金">现金</option>
                    <option value="银行卡">银行卡</option>
                    <option value="信用卡">信用卡</option>
                    <option value="" ng-if="!oriAcct.PAY">N/A</option>
                </select>
            </div>
        </div>
        <div class="col-sm-12" style="padding: 20px 0px 20px 0px; ">
            <div class="col-sm-4 ">
                <label>入账类型</label>
                <div>
                    <input type="radio" name="changeType" ng-model="modifyAcct.changeType" value="1" />增加
                    <input type="radio" name="changeType" ng-model="modifyAcct.changeType" value="-1" style="margin-left: 20px;"/>减少
                </div>
            </div>
        </div>
        <div class="form-group col-sm-12" style="border-top:1px solid lightgrey; padding-top: 20px;">
            <label>入账备注</label>
            <textarea class="form-control" ng-model="modifyAcct.RMRK"></textarea>
        </div>
        <button class="pull-right"
                style="margin-top:25px;padding: 10px 30px 10px 30px; background-color:grey; color: #ffffff"
                ng-click="submit()"
                btn-loading="submitLoading"
                reset-text = '确认并打印押金单'
                loading-text = '处理中请您稍后'
                loading-gif= 'assets/dummy/buttonProcessing.gif'
                >确认</button>
        <button class="pull-right"
                style="margin-top:25px;padding: 10px 30px 10px 30px; background-color:#69B4F5; color: #ffffff"
                ng-click="close()">取消</button>
    </div>
</div>