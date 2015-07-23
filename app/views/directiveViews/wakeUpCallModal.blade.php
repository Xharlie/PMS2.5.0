<div id="wholeModal">
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="glyphicon glyphicon-user"></span>
            <label>早叫设置</label>
            <span class="pull-right close" ng-click="cancel()">&#x2715</span>
        </h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="form-group col-sm-4">
                <label xlabel ng-transclude checker="isDate" checkee="BookCommonInfo.WKC_DT" btn-pass="infoError" >设定日期</label>
                <div class="input-group datePick" ng-controller="Datepicker" >
                    <input type="text" class="form-control input-lg" show-weeks="false" datepicker-popup="yyyy-MM-dd"
                           ng-model="BookCommonInfo.WKC_DT" is-open="opened2" min-date="minDate" max-date="'2020-06-22'"
                           datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                           ng-required="true" close-text="Close"
                           datepicker-append-to-body="true" />
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default btn-lg" ng-click="open2($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                        </span>
                </div>
            </div>
            <div class="form-group col-sm-4">
                <label xlabel ng-transclude checker="isDate" checkee="BookCommonInfo.WKC_TM" btn-pass="infoError" >叫醒时间</label>
                <div ng-controller="TimePickerDemoCtrl" class="removeArrow">
                    <timepicker ng-model="BookCommonInfo.WKC_TM" show-meridian="true"
                                meridians="chineseM" mousewheel="false"></timepicker>
                </div>
            </div>
            <div class=" modal-control col-sm-4" >
                <button class="pull-right btn btn-primary btn-lg"
                        ng-click="submit('set')"
                        ng-if="infoError == '0' || infoError == null ">
                    确认办理</button>
                <button class="pull-right btn btn-disabled btn-lg" ng-if="infoError != '0' && infoError != null ">
                    请补全信息</button>
                <button class="pull-right btn btn-warning btn-lg"
                        ng-click="submit('cancel')">
                    取消早叫</button>
            </div>
        </div>
    </div>
</div>
