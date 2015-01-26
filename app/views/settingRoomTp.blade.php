<!doctype html >
<div class=" card card-default" ng-show="ready">
    <div class="card-body">
        <div class="merchandiseList tableArea CrossTab">
            <table class="table table-striped table-bordered">
                <tr>
                    <th>操作</th>
                    <th>房型名称</th>
                    <th>门市价</th>
                    <th>标准住客数</th>
                    <th>房间数量</th>
                    <th>商品备注</th>
                </tr>
                <tr ng-repeat = "rmTp in rmTps"
                    ng-class="rmTp.focusedCss">
                    <td><button class="btn btn-default btn-xs" ng-click="deleteEdit(rmTp,$index)">删除</button>
                        <button class="btn btn-default btn-xs" ng-click="confirmEdit(rmTp)">确认修改</button>
                    </td>
                    <td><input ng-model="rmTp.RM_TP" ng-focus="focus(rmTp)"/ ></td>
                    <td><input ng-model="rmTp.SUGG_PRICE" ng-focus="focus(rmTp)"/ ></td>
                    <td><input ng-model="rmTp.CUS_QUAN" ng-focus="focus(rmTp)"/></td>
                    <td>{{rmTp.RM_QUAN}}</td>
                    <td><textarea ng-model="rmTp.RM_PROD_RMRK" ng-focus="focus(rmTp)"/></td>
                </tr>
                <tr class="addOnRow">
                    <td>新添房型:<button class="btn btn-default btn-xs" ng-click="addNewTp()">添加确认</button></td>
                    <td><input ng-model="newTp.RM_TP"/></td>
                    <td><input ng-model="newTp.SUGG_PRICE"/></td>
                    <td><input ng-model="newTp.CUS_QUAN"/></td>
                    <td>N/A</td>
                    <td><textarea ng-model="newTp.RM_PROD_RMRK"/></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div style="margin-top: 20%; margin-left: 50%" ng-hide="ready">
    <img src="assets/dummy/pageloading.gif" />
</div>
