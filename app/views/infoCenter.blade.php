<!doctype html>
<div class="infoCenter" ng-controller="infoCenterController">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4><div class="title-decor title-decor-md"></div>消息中心</h4>
        </div>
        <div class="card-body container-fluid">
            <ul>
                <li class="padded-row" ng-repeat="alert in alerts">
                    <div ng-if="alert.MSG_TP=='DPST_ALRT'">
                        <div class="col-md-12">
                            <div class="infoTitle pull-left">欠费</div>
                            <div class="infoTime pull-right">{{alert.MSG_ALRT_TSTMP}}</div>
                        </div>
                        <div class="col-md-12 infoContent">房间{{alert.RM_ID}}欠费{{alert.MSG_AMNT}}</div>
                    </div>
                    <div ng-if="alert.MSG_TP=='LEAVE_ALRT'">
                        <div class="col-md-12">
                            <div class="infoTitle pull-left">欲离</div>
                            <div class="infoTime pull-right">{{alert.MSG_ALRT_TSTMP}}</div>
                        </div>
                        <div class="col-md-12 infoContent">房间{{alert.RM_ID}}应于{{alert.MSG_SHOW_TSTMP}}收到提醒</div>
                    </div>
                    <div ng-if="alert.MSG_TP=='WKC_ALRT'">
                        <div class="col-md-12">
                            <div class="infoTitle pull-left">早叫</div>
                            <div class="infoTime pull-right">{{alert.MSG_ALRT_TSTMP}}</div>
                        </div>
                        <div class="col-md-12 infoContent">房间{{alert.RM_ID}}应于{{alert.MSG_ALRT_TSTMP}}收到电话</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>