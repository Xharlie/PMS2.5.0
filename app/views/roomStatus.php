<!doctype html >
<div class="hotelStatus">
	当前入住 10/70 	今日欲达 5
</div>
<div ng-switch on="connectClick" >
    <div ng-switch-when ="toStart">
        <button ng-click="connectStart()">办理连房入住</button>
    </div>
    <div ng-switch-when ="toEnd">
        <button ng-click="connectEnd('confirm')">确认选择</button>
        <button ng-click="connectEnd('cancel')">取消选择</button>
    </div>
<div id="roomStatusFrame" class="roomStatus">
    <div class="goRight">
        <input class="intellSearchBox" type="text"  ng-model = "overall" placeholder="智能算法搜索">
        <!-- old search by categories
        <input type="text"  ng-model = "roomNM" placeholder="房间号码">
        <select ng-model="roomType">
            <option value="">所有房型</option>>
            <option value="Single">单人床房</option>
            <option value="Double">双人床房</option>
            <option value="Kingbed">大床房</option>
        </select>
        <select ng-model="roomFloor" >
            <option value="">所有楼层</option>
            <option value="10">一层</option>
            <option value="11">二层</option>
            <option value="12">三层</option>
            <option value="13">四层</option>
        </select>
        <select ng-model="roomStatus" >
            <option value="">所有状态</option>>
            <option value="Empty">空房</option>
            <option value="Occupied">有人</option>
            <option value="Preparing">脏房</option>
            <option value="Mending">维修房</option>
        </select>
        -->
    </div>
    <div class="roomStatusFull">
        <!-- define shape, just for fun    <div class="roomBox" ng-class ="shape(roomST.RM_TP)" ng-repeat = "roomST in roomStatusInfo | filter: ngFloorFilter  | filter: {RM_TP: roomType ,RM_ID: roomNM, RM_CONDITION: roomStatus} | orderBy:roomST.RM_ID " ng-style="ngSetRoomBoxColor(roomST)">  -->
        <!-- old search by categories
        <div ng-click="open(roomST)" class="roomBox" ng-repeat = "roomST in roomStatusInfo | filter: ngFloorFilter  | filter: {RM_TP: roomType ,RM_ID: roomNM, RM_CONDITION: roomStatus} | orderBy: roomST.RM_ID " ng-style="ngSetRoomBoxColor(roomST)">
        -->
        <div ng-click="open(roomST)" class="roomBox" ng-init="ngSetRoomBoxColor(roomST)" ng-repeat = "roomST in roomStatusInfo | filter: customerizeFilter | orderBy: roomST.RM_ID " ng-style="roomST.boxStyle">
        <table>
                <tr>
                    <td>{{roomST.RM_ID}}</td>
                </tr>
                <tr>
                    <td>{{roomST.RM_CONDITION}}</td>
                </tr>
                <tr>
                    <td>{{roomST.RM_TP}}</td>
                </tr>
            </table>
         </div>
    </div>

        <script type="text/ng-template" id="roomModalContent">
            <div class="modal-header">
                <h3 class="modal-title">客房{{roomST.RM_ID}}  {{roomST.RM_CONDITION}}</h3>
            </div>
            <div class="modal-body">
                <div class="roomAction">
                    <table>
                        <tr ng-repeat="action in roomAction" class="actionRepeater">
                            <td><a ng-click="excAction(action[1])">{{action[0]}} </a></td>
                        </tr>
                    </table>
                </div>
                <div ng-switch on="RMviewClick" class="infoPanel">
                    <div class="infoAction" ng-switch-when ="infoAction" style="border: 1px solid black">
                        <table class="roomInfo">
                            <tr ng-repeat="info in roomInfo">
                                <td><a ng-click="">{{info[0]}} : {{info[1]}}</a></td>
                            </tr>
                        </table>
                    </div>
                    <div ng-switch-when ="connectCheckOut" >
                        <h4>点击选则联房中进行退房的房间</h4>
                        <lable >主房:{{ConnRooms[0]["RM_ID"]}}</lable>
                        <div ui-sortable ng-model="ConnRooms" class="MasterRoom">
                            <div class="roomSmallBox "
                                 ng-init ="room.checkRoom = {}"
                                 ng-style="room.checkRoom"
                                 ng-click="checkItOut(room)"
                                 ng-repeat = "room in ConnRooms | orderBy: room.RM_ID " >
                                <table>
                                    <tr>
                                        <td>{{room.RM_ID}}</td>
                                    </tr>
                                    <tr>
                                        <td>房单号: {{room.RM_TRAN_ID}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div ng-switch-when ="infoAccounting" style="border: 1px solid black">
                        <div>
                            <h4>住房帐</h4>
                            <div class="CrossTab">
                                <label>押金</label>
                                <table>
                                    <tr>
                                        <th>时间</th>
                                        <th>房单号</th>
                                        <th>存入金额</th>
                                        <th>存入方式</th>
                                    </tr>
                                    <tr  ng-repeat = "depo in AcctDepo | orderBy: DEPO_TSTAMP  ">
                                        <td>{{depo.DEPO_TSTMP}}</td>
                                        <td>{{depo.RM_TRAN_ID}}</td>
                                        <td>{{depo.DEPO_AMNT}}</td>
                                        <td>{{PayMethod(depo.PAY_METHOD)}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="CrossTab">
                                <label>房费</label>
                                <table>
                                    <tr>
                                        <th>时间</th>
                                        <th>房单号</th>
                                        <th>付费金额</th>
                                        <th>付费方式</th>
                                    </tr>
                                    <tr  ng-repeat = "bill in AcctPay | orderBy: BILL_TSTMP  ">
                                        <td>{{bill.BILL_TSTMP}}</td>
                                        <td>{{bill.RM_TRAN_ID}}</td>
                                        <td>{{bill.RM_PAY_AMNT}}</td>
                                        <td>{{PayMethod(bill.RM_PAY_METHOD)}}</td>
                                    </tr>
                                </table>
                             </div>
                        </div>
                        <div>
                            <div class="CrossTab">
                                <h4>商品帐</h4>
                                <table>
                                    <tr>
                                        <th>时间</th>
                                        <th>房单号</th>
                                        <th>付费金额</th>
                                        <th>付费方式</th>
                                    </tr>
                                    <tr  ng-repeat = "store in AcctStore | orderBy: STR_TRAN_TSTAMP  ">
                                        <td>{{store.STR_TRAN_TSTAMP}}</td>
                                        <td>{{store.RM_TRAN_ID}}</td>
                                        <td>{{store.STR_PAY_AMNT}}</td>
                                        <td>{{PayMethod(store.STR_PAY_METHOD)}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div ng-switch on="RMviewClick" class="infoPanel">
                    <div ng-switch-when ="connectCheckOut" >

                        <label>选中号房间: {{roomNumString}}</label>
                        <button class="btn btn-primary" ng-click = "confirmConnCheckOut()">办理退房</button>
                        <button class="btn btn-warning" ng-click = "cancelConnCheckOut()">取消</button>
                    </div>
                </div>
            </div>
        </script>



        <script type="text/ng-template" id="connectRMModalContent">
            <div class="modal-header">
                <h3 class="modal-title">以下将成为连房</h3>
            </div>
            <div class="modal-body">
                <div class="roomSmallBox"  ng-repeat = "room in rooms | orderBy: room.RM_TP " >
                    <table>
                        <tr>
                            <td>{{room.RM_ID}}</td>
                        </tr>
                        <tr>
                            <td>{{room.RM_TP}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" ng-click = "confirmConn()">确认</button>
                <button class="btn btn-warning" ng-click = "cancelConn()">取消</button>
            </div>
        </script>



</div>
<script src="js/Angular/module_frontDesk/module.js"></script>