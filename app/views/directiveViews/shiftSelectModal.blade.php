<div id="wholeModal" >
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="icon-coffee"></span>
            <label>选取班次</label>
        </h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-4 form-group" ng-repeat="shift in shifts">
                <button ng-click="submit(shift)" class="btn btn-primary">{{shift.SHFT_NM}}</button>
            </div>
        </div>
    </div>
</div>