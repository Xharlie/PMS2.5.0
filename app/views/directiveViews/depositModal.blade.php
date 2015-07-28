<div id="wholeModal">
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="icon-star"></span>
            <label>入押金</label>
            <span class="pull-right close" ng-click="cancel()">&#x2715</span>
        </h4>
    </div>
    <div class="panel-body">
        <div payment  book-room="BookRoomMaster" pay-method-options="payMethodOptions" pay-error="payError"></div>
        <div class="row modal-control">
            <button class="pull-right btn btn-primary btn-lg"
                    ng-if="initialString == 'singleDepositIn' && (payError == '0' || payError == null) "
                    ng-click="submit()"
                    btn-loading="submitLoading"
                    loading-text = '处理中请您稍候...'
                    loading-gif= 'assets/dummy/buttonProcessing.gif'>确认入住并打印押金单</button>
            <button class="pull-right btn btn-disabled btn-lg" ng-if=" payError != '0' && payError != null ">请更正错误信息</button>
        </div>
    </div>
</div>