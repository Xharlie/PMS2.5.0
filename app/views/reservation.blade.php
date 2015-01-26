<!doctype html>
<div class="col-sm-12" ng-show="ready">
    <div class="card card-default">
        <div class="card-actions">
            <div class="ctrlArea">
                <div class="ctrlLeft">
                    <button ng-click = "addNew()" class="btn btn-primary btn-md">新预定</button>
                </div>
                <div class="ctrlRight">
                    <input type="text"  ng-model = "resvName" placeholder="预订人" class="searchBox input-sm">
                    <select ng-model="roomType" class="btn btn-default btn-md">
                        <option value="">所有房型</option>>
                        <option value="Single">单人床房</option>
                        <option value="Double">双人床房</option>
                        <option value="Kingbed">大床房</option>
                    </select>
                    <select ng-model="sorter" class="btn btn-default btn-md" >
                        <option value="">排序</option>
                        <option value="GUEST_NM">住客姓名</option>
                        <option value="RESVER_CARDNM">预定卡号</option>
                        <option value="PHONE">预定手机</option>
                        <option value="RESVER_NAME">预定人</option>
                        <option value="RESV_WAY">支付方式</option>
                        <option value="RESV_TMESTMP">下订日期</option>
                        <option value="CHECK_IN_DT">预达日期</option>
                        <option value="CHECK_OT_DT">预离日期</option>
                        <option value="RM_TP">房型</option>
                        <option value="RM_QUAN">房间数量</option>
                        <option value="TREATY_ID">协议</option>
                        <option value="MEMBER_ID">会员卡号</option>
                        <option value="RMRK">备注</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="reservationList tableArea CrossTab">
            	<table class="table table-striped table-bordered">
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
            			<tr ng-repeat = "reserve in resvInfo | filter : {RESVER_NAME: resvName, RM_TP: roomType} | orderBy:sorter "
                            ng-mouseenter="sameIDLightUp(reserve)"
                            ng-mouseleave = 'sameIDLightBack(reserve)'
                            ng-dblclick="fastAction(reserve)"
                            sglclick="open(reserve)" onclick="event.preventDefault();" ng-class="reserve.blockClass" block-class="blockClass"
                            not-show ="menuNoshow" pop-menu  menu-type="menuType" owner="reserve" icon-n-action="iconAndAction.resvIconAction" ng-transclude>
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
                            <td >
                                {{reserve.CHECK_IN_DT}}
                            </td>
                            <td >
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
    </div>
</div>
<div style="margin-top: 20%; margin-left: 50%" ng-hide="ready">
    <img src="assets/dummy/pageloading.gif" />
</div>
