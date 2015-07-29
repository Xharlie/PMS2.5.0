<div id="wholeModal" >
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="icon-coffee"></span>
            <label>选取班次</label>
        </h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="col-md-4" ng-repeat="shift in shifts">
                    <button ng-click="submit(shift)" class="btn btn-shift btn-primary">{{shift.SHFT_NM}}</button>
                </div>
            </div>
        </div>
    </div>
</div>