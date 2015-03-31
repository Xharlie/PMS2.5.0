<!doctype html >
<div ng-show="ready">
    <div class=" card card-default col-sm-3">
        <div class="card-body">
            <table>
                <tr>
                    <td></td>
                    <td>
                        <canvas id="canvas" width="150" height="60"/>
                    </td>
                </tr>
                <tr  ng-repeat = "floor in floors | orderBy:'FLOOR_ID':true" ng-class="floor.focusedCss">
                    <td>
                        <button class="btn btn-default btn-xs" ng-click="deleteEditFloor(floor)">删除</button>
                        <button class="btn btn-default btn-xs" ng-click="ConfirmEditFloor(floor)">修改</button>
                    </td>
                    <td>
                        <div class="floor" ng-click="select(floor)" ng-style="floor.selectedStyle">
                            <input  ng-focus="focusFloor(floor)" ng-model="floor.FLOOR" />
                        </div>
                    </td>
                </tr >
                <tr>
                    <td>
                        <button class="btn btn-default" ng-click="addNewFl()">添加楼层:</button>
                    </td>
                    <td>
                        <div class="floorAdding">
                            <input  ng-model="newFloor.FLOOR"/>
                        </div>
                    </td>
                </tr>
            </table>
            {{newFloor.FLOOR_ID}}
        </div>
    </div>
    <div class=" card card-default col-sm-9">
        <div class="card-body">
            <div class="merchandiseList tableArea CrossTab">
                <table class="table table-bordered table-room">
                    <tr>
                        <th>操作</th>
                        <th>房号</th>
                        <th>楼层</th>
                        <th>房型</th>
                        <th>电话</th>
                        <th>备注</th>
                    </tr>
                    <tr ng-repeat = "room in rooms[selectedFloor.FLOOR_ID] | paginate: (currentPage-1)*VarItemPerPage:VarItemPerPage" ng-class="room.focusedCss">
                        <td>
                            <button class="btn btn-default btn-xs" ng-click="deleteEditRoom(room,$index)">删除</button>
                            <button class="btn btn-default btn-xs" ng-click="confirmEditRoom(room,$index)">确认修改</button>
                        </td>
                        <td>
                            <input  ng-model="room.RM_ID" ng-focus="focusRoom(room)" ng-focus="focused()"  />
                        </td>
                        <td>
                            <select class="form-control"
                                    ng-model="room.FLOOR"
                                    ng-options="floor.FLOOR as floor.FLOOR for floor in floors"
                                    ng-focus="focusRoom(room)">
                            </select>
                        </td>
                        <td>
                            <select class="form-control"
                                    ng-model="room.RM_TP"
                                    ng-options="value.RM_TP as value.RM_TP for value in rmTps"
                                    ng-focus="focusRoom(room)">
                            </select>
                        </td>
                        <td><input ng-model="room.PHONE" ng-focus="focusRoom(room)"/ ></td>
                        <td><textarea ng-model="room.RMRK" ng-focus="focusRoom(room)"/></td>
                    </tr>
                    <tr class="addOnRow">
                        <td>新添房间:<button class="btn btn-default btn-xs" ng-click="addNewRm()">添加</button></td>
                        <td><input ng-model="newRoom.RM_ID"/></td>
                        <td>
                            <select class="form-control" class="RoomTypeSelection"
                                    ng-model="selectedFloor.FLOOR"
                                    ng-options="floor.FLOOR as floor.FLOOR for floor in floors" disabled>
                            </select>
                        </td>
                        <td>
                            <select class="form-control" class="RoomTypeSelection"
                                    ng-model="newRoom.RM_TP"
                                    ng-options="value.RM_TP as value.RM_TP for value in rmTps">
                            </select>
                        </td>
                        <td><input ng-model="newRoom.PHONE"/></td>
                        <td><textarea ng-model="newRoom.RMRK"/></td>
                    </tr>
                </table>
            </div>
            <pagination total-items="totalItems" ng-model="currentPage" max-size="VarMaxSize" items-per-page="VarItemPerPage"
                        previous-text="上一页" next-text="下页" first-text="首页" last-text="尾页"
                        boundary-links="true" rotate="false" num-pages="numPages"></pagination>
        </div>
    </div>
</div>
<div style="margin-top: 20%; margin-left: 50%" ng-hide="ready">
    <img src="assets/dummy/pageloading.gif" />
 </div>