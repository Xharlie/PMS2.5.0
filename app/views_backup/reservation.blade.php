<!doctype html>
<div class="card-group">
    <div class="card-header">
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
    <div class="card">
        <div class="reservationList tableArea CrossTab">
        	<table class="table table-striped table-bordered">
        		<tr>
                    <th>入住</th>
                    <th>预定人</th>
        			<th>预定手机</th>
        			<th>支付方式</th>
        			<th>下订时间</th>
        			<th>预达日期</th>
                    <th>预离日期</th>
        			<th>房型</th>
                    <th>房间数量</th>
        			<th>协议</th>
        			<th>会员卡号</th>
                    <th>每晚价格</th>
        			<th>备注</th>
        		</tr>

        			<tr ng-repeat = "reserve in resvInfo | filter : {RESVER_NAME: resvName, RM_TP: roomType} | orderBy:sorter ">
                        <td>
                            <button class="btn btn-default btn-xs" ng-click="check(reserve)">入住</button>
                        </td>
                        <td >
                            {{reserve.RESVER_NAME}}
                        </td>
                        <td >
                            {{reserve.RESVER_PHONE}}
                        </td>
                        <td >
                            {{reserve.RESV_WAY}}
                        </td>
                        <td >
                            {{reserve.RESV_TMESTMP}}
                        </td>
                        <td >
                            {{reserve.CHECK_IN_DT}}
                        </td>
                        <td >
                            {{reserve.CHECK_OT_DT}}
                        </td>
                        <td >
                            {{reserve.RM_TP}}
                        </td>
                        <td >
                            {{reserve.RM_QUAN}}
                        </td>
                        <td >
                            {{reserve.TREATY_ID}}
                        </td>
                        <td >
                            {{reserve.MEMBER_ID}}
                        </td>
                        <td >
                            {{reserve.RESV_DAY_PAY}}元
                        </td>
                        <td >
                            {{reserve.RMRK}}
                        </td>
                    </tr>
        	</table>
        </div>
    </div>
</div>

