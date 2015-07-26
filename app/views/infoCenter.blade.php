<!doctype html>
<div class="infoCenter" ng-controller="infoCenterController">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title"><span class="icon-chat"></span> 消息中心</h4>
        </div>
        <table class="table">
            <tr ng-repeat="alert in alerts">
                <td ng-if="alert.MSG_TP=='DPST_ALRT'">
                    <div class="col-md-12">
                        <div class="infoTitle"><b><span class="glyphicon glyphicon-usd"></span>欠费</b></div>
                        <div class="infoTime">{{alert.MSG_ALRT_TSTMP}}</div>
                    </div>
                    <div class="col-md-12 infoContent">房间{{alert.RM_ID}}欠费{{alert.MSG_AMNT}}</div>
                </td>
                <td ng-if="alert.MSG_TP=='LEAVE_ALRT'">
                    <div class="col-md-12">
                        <div class="infoTitle"><b><span class="icon-eject"></span>欲离</b></div>
                        <div class="infoTime">{{alert.MSG_ALRT_TSTMP}}</div>
                    </div>
                    <div class="infoContent col-md-12">房间{{alert.RM_ID}}应于{{alert.MSG_SHOW_TSTMP}}收到提醒</div>
                </td>
                <td ng-if="alert.MSG_TP=='WKC_ALRT'">
                    <div class="col-md-12">
                        <div class="infoTitle"><b><span class="icon-clock"></span>早叫</b></div>
                        <div class="infoTime">{{alert.MSG_ALRT_TSTMP}}</div>
                    </div>
                    <div class="col-md-12 infoContent">房间{{alert.RM_ID}}应于{{alert.MSG_ALRT_TSTMP}}收到电话</div>
                </td>
            </tr>
        </table>
    </div>
</div>