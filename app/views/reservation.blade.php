<!doctype html>
<div class="col-sm-12" ng-show="ready">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-control">
                <button ng-click = "addNew()" class="btn btn-primary btn-lg">新预定</button>
                <div class="pull-right">
                    <input type="text"  ng-model = "resvName" placeholder="预订人" class="searchBox input-lg">
                    <!--       roomtype filter        -->
                    <div class="btn-group" dropdown is-open="roomType.isopen"
                         ng-init="selectTo('','所有房型',roomType)" dropdown-append-to-body>
                        <button type="button" class="btn btn-drop dropdown-toggle" dropdown-toggle>
                            {{roomType.caption}} <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href ng-click="selectTo('','所有房型',roomType)">所有房型</a></li>
                            <li ng-repeat=" type in allType">
                                <a href ng-click="selectTo(type.RM_TP,type.RM_TP,roomType)">{{type.RM_TP}}</a>
                            </li>
                        </ul>
                    </div>
                    <!--        sorter        -->
                    <div class="btn-group" dropdown is-open="sorter.isopen"
                         ng-init="selectTo('','排序',sorter)" dropdown-append-to-body>
                        <button type="button" class="btn btn-drop dropdown-toggle" dropdown-toggle>
                            {{sorter.caption}} <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href ng-click="selectTo('','排序',sorter)">排序</a></li>
                            <li><a href ng-click="selectTo('GUEST_NM','住客姓名',sorter)">住客姓名</a></li>
                            <li><a href ng-click="selectTo('RESVER_CARDNM','预定卡号',sorter)">预定卡号</a></li>
                            <li><a href ng-click="selectTo('PHONE','预定手机',sorter)">预定手机</a></li>
                            <li><a href ng-click="selectTo('RESVER_NAME','预定人',sorter)">预定人</a></li>
                            <li><a href ng-click="selectTo('RESV_WAY','支付方式',sorter)">支付方式</a></li>
                            <li><a href ng-click="selectTo('RESV_TMESTMP','下订日期',sorter)">下订日期</a></li>
                            <li><a href ng-click="selectTo('CHECK_IN_DT','预达日期',sorter)">预达日期</a></li>
                            <li><a href ng-click="selectTo('CHECK_OT_DT','预离日期',sorter)">预离日期</a></li>
                            <li><a href ng-click="selectTo('RM_TP','房型',sorter)">房型</a></li>
                            <li><a href ng-click="selectTo('RM_QUAN','房间数量',sorter)">房间数量</a></li>
                            <li><a href ng-click="selectTo('TREATY_ID','协议',sorter)">协议</a></li>
                            <li><a href ng-click="selectTo('MEMBER_ID','会员卡号',sorter)">会员卡号</a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    	<table class="table table-striped table-acct">
    		<tr>
                <th>订单号</th>
                <th>姓名</th>
                <th>来源协议</th>
    			<th>联系电话</th>
    			<th>预达日期</th>
                <th>保留至</th>
    			<th>房型</th>
                <th>房间数量</th>
                <th>每晚价格</th>
    			<th>备注</th>
                <th>状态</th>
    		</tr>
    			<tr ng-repeat = "reserve in resvInfo | filter : {RESVER_NAME: resvName, RM_TP: roomType.value} | orderBy:sorter.value "
                    ng-mouseenter="sameIDLightUp(reserve)"
                    ng-mouseleave = 'sameIDLightBack(reserve)'
                    ng-dblclick="fastAction(reserve)"
                    sglclick="open(reserve)" onclick="event.preventDefault();" ng-class="reserve.blockClass" block-class="blockClass"
                    not-show ="menuNoshow" pop-menu  menu-type="'small-menu'" owner="reserve"
                    icon-n-action="reserve.iconAndAction.resvIconAction" ng-transclude>
                    <td >
                        {{reserve.RESV_ID}}
                    </td>
                    <td >
                        {{reserve.RESVER_NAME}}
                    </td>
                    <td >
                        {{reserve.RESV_WAY}}
                    </td>
                    <td >
                        {{reserve.RESVER_PHONE}}
                    </td>
                    <td ng-class="reserve.timeOutClass">
                        {{reserve.CHECK_IN_DT}}
                    </td>
                    <td ng-class="reserve.timeOutClass">
                        {{reserve.RESV_LATEST_TIME}}
                    </td>
                    <td >
                        {{reserve.RM_TP}}
                    </td>
                    <td >
                        {{reserve.RM_QUAN}}
                    </td>
                    <td >
                        {{reserve.RESV_DAY_PAY}}元
                    </td>
                    <td >
                        {{reserve.RMRK}}
                    </td>
                    <td >
                        {{reserve.STATUS}}
                    </td>
                </tr>
    	</table>
    </div>
</div>
<div class="loader loader-main" ng-hide="ready">
    <div class="loader-inner ball-scale-multiple">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>
