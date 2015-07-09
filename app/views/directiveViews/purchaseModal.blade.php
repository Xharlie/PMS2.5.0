<div id="wholeModal">
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="glyphicon glyphicon-credit-card"></span>
            <label>费用收取</label>
            <span class="pull-right close" ng-click="cancel()">&#x2715</span>
        </h4>
    </div>
    <div class="panel-body">
        <div ng-show ="viewClick=='Pay'">
            <div  payment  book-room="BookRoomMaster" pay-method-options="payMethodOptions" pay-error="payError" rooms="rooms"></div>
            <div class="row modal-control">
                <button class="pull-right btn btn-primary btn-lg"
                        ng-click="submit()"
                        btn-loading="submitLoading"
                        loading-text = '处理中请您稍后'
                        loading-gif= 'assets/dummy/buttonProcessing.gif'
                        ng-if="payError == '0' || payError == null ">确认并打印小票</button>
                <button class="pull-right btn btn-alert btn-lg" ng-if=" payError != '0' && payError != null ">请更正错误信息</button>
            </div>
        </div>
    </div>
</div>