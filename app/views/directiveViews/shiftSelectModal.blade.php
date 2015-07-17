<div id="wholeModal" >
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="glyphicon glyphicon-send"></span>
            <label>选取班次</label>
            <span class="pull-right close" ng-click="cancel()">&#x2715</span>
        </h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-4 form-group" ng-repeat="shift in shifts">
                <button ng-click="submit(shift)" class="btn btn-primary">{{shift.SHFT_NM}}</button>
            </div>
        </div>
        <!-- <div class="row modal-control">
            <button class="pull-right btn btn-primary btn-lg"
                    ng-click="confirm()">
                确认办理</button>
            <button class="pull-right btn btn-primary btn-lg"
                    ng-click="editConfirm()">
                确认修改</button>
        </div>  -->
    </div>
</div>