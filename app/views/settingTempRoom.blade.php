<div class=" card card-default">
    <div class="card-body">
        <div class="merchandiseList tableArea CrossTab">
            <table class="table table-striped table-bordered">
                <tr>
                    <th>操作</th>
                    <th>房型名称</th>
                    <th>套餐时长(分钟)</th>
                    <th>价格</th>
                    <th>每分钟超时收费</th>
                </tr>
                <tr ng-repeat = "plan in plans"
                    ng-class="plan.focusedCss">
                    <td><button class="btn btn-default btn-xs" ng-click="deleteEdit(plan,$index)">删除</button>
                        <button class="btn btn-default btn-xs" ng-click="confirmEdit(plan)">确认修改</button>
                    </td>
                    <td>
                        <select class="form-control RoomTypeSelection"
                                ng-model="plan.RM_TP"
                                ng-options="value.RM_TP as value.RM_TP for value in rmTps"
                                ng-focus="focus(plan)">
                        </select>
                    </td>
                    <td><input ng-model="plan.PLAN_COV_MIN" ng-focus="focus(plan)"/ ></td>
                    <td><input ng-model="plan.PLAN_COV_PRCE" ng-focus="focus(plan)"/></td>
                    <td><input ng-model="plan.PNLTY_PR_MIN" ng-focus="focus(plan)"/></td>
                </tr>
                <tr class="addOnRow">
                    <td>新添房型:<button class="btn btn-default btn-xs" ng-click="addNewPlan()">添加确认</button></td>
                    <td>
                        <select class="form-control"
                                ng-model="newPlan.RM_TP"
                                ng-options="value.RM_TP as value.RM_TP for value in rmTps">
                        </select>
                    </td>
                    <td><input ng-model="newPlan.PLAN_COV_MIN"/></td>
                    <td><input ng-model="newPlan.PLAN_COV_PRCE"/></td>
                    <td><input ng-model="newPlan.PNLTY_PR_MIN"/></td>
                </tr>
            </table>
        </div>
    </div>
</div>

