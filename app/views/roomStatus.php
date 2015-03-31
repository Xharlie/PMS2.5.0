<!doctype html >
<div class="hotelStatus">
       <div class="col-sm-3">
            <div class="card card-default">
                <div class="card-actions"><h4><div class="title-decor title-decor-md"></div>店长通知</h4></div>
                <div class="card-body">
                    <p>今晨接到消息，本周公安将对石家庄市酒店进行抽检。请各位同事务必注意，确保入住客人一人一证。</p>
                </div>
            </div>  
            <div class="card card-default">
                <div class="card-actions">
                    <h4><div class="title-decor title-decor-md"></div>今日预达</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-condensed">
                        <tr>
                            <td>16:00</td>
                            <td>李兵</td>
                            <td>13334554321</td>
                            <td><button class="btn btn-primary btn-xs">入住</button></td>
                        </tr>
                        <tr>
                            <td>18:30</td>
                            <td>徐乾庚</td>
                            <td>13123123121</td>
                            <td><button class="btn btn-primary btn-xs">入住</button></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card card-default">
                <div class="card-actions">
                    <h4><div class="title-decor title-decor-md"></div>房型余量</h4>
                </div>
                <div class="card-body">
                    <table>
                        <tr style="height: 24px;">
                            <td class="col-sm-3">大床房</td>
                            <td class="col-sm-9">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                                        <span class="sr-only">60% Complete</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-sm-3">标准间</td>
                            <td class="col-sm-9">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%;">
                                        <span class="sr-only">60% Complete</span>
                                    </div>
                                </div></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
</div>

<div class="col-sm-9" ng-show="ready">
    <div class="card card-default">
        <div class="card-actions">
            <div class="ctrlArea" ng-switch on="connectClick" >
                <div class="ctrlLeft">
                    <div ng-switch-when ="toStart">
                        <button ng-click="connectStart()" class="btn btn-default">多间入住</button>
                    </div>
                    <div ng-switch-when ="toEnd">
                        <button ng-click="connectEnd('cancel')" class="btn btn-default">取消入住</button>
                        <button ng-click="connectEnd('confirm')" class="btn btn-info">选房完成</button>
                    </div>
                </div>
                <div class="ctrlRight">
                    <input class="searchBox intellSearchBox input-sm" type="text"  ng-model = "overall" placeholder="关键字搜索">
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
                    </select>                                           sglclick="open(roomST)"
                    -->
                </div>
            </div>
        </div>

        <div class="card-body">
            <div id="roomStatusFrame" class="roomStatus">
                <div class="roomStatusFull">
                    <div ng-dblclick="fastAction(roomST)"
                         class="room" ng-class="roomST.blockClass"
                         onclick="event.preventDefault();"
                         ng-repeat = "roomST in roomStatusInfo | filter: customerizeFilter | orderBy: roomST.RM_ID "
                         ng-mouseenter="connLightUp(roomST)"
                         ng-mouseleave = 'connLightback(roomST)'
                         sglclick="open(roomST)" block-class="blockClass"
                         not-show ="connectFlag" pop-menu  menu-type="roomST.menuType" owner="roomST" icon-n-action="roomST.menuIconAction" ng-transclude>
                            <table>
                                <tr>
                                    <td><span class="roomBadge">{{roomST.RM_ID}}</span></td>
                                </tr>
                                <tr>
                                    <td>{{roomST.RM_TP}}</td>
                                </tr>
                                <tr>
                                    <td>{{roomST.RM_CONDITION}}</td>
                                </tr>
                            </table>
                     </div>
                </div>

                    <script type="text/ng-template" id="roomModalContent">
                        <div class="modal-header">
                            <h4>客房{{roomST.RM_ID}} <div class="room-modal-status" ng-class="ngSetRoomClass(roomST)">{{roomST.RM_CONDITION}}</div></h4>
                        </div>
                        <div class="modal-body container-fluid">
                            <div class="roomAction col-sm-2">
                                <table>
                                    <tr ng-repeat="action in roomAction" class="actionRepeater">
                                        <td><a ng-click="excAction(action[1])">{{action[0]}} </a></td>
                                    </tr>
                                </table>
                            </div>
                            <div ng-switch on="RMviewClick" class="infoPanel col-sm-10">
                                <div class="infoAction" ng-switch-when ="infoAction">
                                    <table class="roomInfo">
                                        <tr ng-repeat="info in roomInfo">
                                            <td><a ng-click="">{{info[0]}} : {{info[1]}}</a></td>
                                        </tr>
                                    </table>

                                </div>
                                <div ng-switch-when ="connectCheckOut">
                                    <h4>点击选则联房中进行退房的房间</h4>
                                    <lable >主房:{{ConnRooms[0]["RM_ID"]}}</lable>
                                    <div ui-sortable ng-model="ConnRooms" class="MasterRoom">
                                        <div class="room room-full"
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
                                    <div>
                                        <label>选中号房间: {{roomNumString}}</label>
                                        <button class="btn btn-primary" ng-click = "confirmConnCheckOut()">办理退房</button>
                                        <button class="btn btn-warning" ng-click = "cancelConnCheckOut()">取消</button>
                                    </div>
                                </div>
                                <div ng-switch-when ="infoAccounting">
                                
                                    <div class="roomAccounting">
            <!--                            <h4>住房帐</h4> -->
                                        <div class="CrossTab">
            <!--                                <label>押金</label> -->
                                            <table class="table table-striped table-bordered">
                                               <tr>
                                                    <th>时间</th>
                                                    <th>房单号</th>
                                                    <th>金额</th>
                                                    <th>类型</th>
                                                </tr> 
                                                <tr  ng-repeat = "depo in AcctDepo | orderBy: DEPO_TSTAMP  ">
                                                    <td>{{depo.DEPO_TSTMP}}</td>
                                                    <td>{{depo.RM_TRAN_ID}}</td>
                                                    <td>{{depo.DEPO_AMNT}}</td>
            <!--                                        <td>{{PayMethod(depo.PAY_METHOD)}}</td> -->
                                                    <td>押金</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="CrossTab">
            <!--                                <label>房费</label> -->
                                            <table class="table table-striped table-bordered">
            <!--                                     <tr>
                                                    <th>时间</th>
                                                    <th>房单号</th>
                                                    <th>金额</th>
                                                    <th>类型</th>
                                                </tr> -->
                                                <tr  ng-repeat = "bill in AcctPay | orderBy: BILL_TSTMP  ">
                                                    <td>{{bill.BILL_TSTMP}}</td>
                                                    <td>{{bill.RM_TRAN_ID}}</td>
                                                    <td>{{bill.RM_PAY_AMNT}}</td>
            <!--                                         <td>{{PayMethod(bill.RM_PAY_METHOD)}}</td> -->
                                                    <td>房费</td>
                                                </tr>
                                            </table>
                                         </div>
                                        <div class="CrossTab">
            <!--                                 <h4>商品帐</h4> -->
                                            <table class="table table-striped table-bordered">
            <!--                                  <tr>
                                               <th>时间</th>
                                                    <th>房单号</th>
                                                    <th>金额</th>
                                                    <th>类型</th>
                                                </tr> -->
                                                <tr  ng-repeat = "store in AcctStore | orderBy: STR_TRAN_TSTAMP  ">
                                                    <td>{{store.STR_TRAN_TSTAMP}}</td>
                                                    <td>{{store.RM_TRAN_ID}}</td>
                                                    <td>{{store.STR_PAY_AMNT}}</td>
            <!--                                         <td>{{PayMethod(store.STR_PAY_METHOD)}}</td> -->
                                                    <td>商品</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                        </div>
                    </script>



                    <script type="text/ng-template" id="connectRMModalContent">
                        <div class="modal-header">
                            <h3 class="modal-title">以下将成为连房</h3>
                        </div>
                        <div class="modal-body">
                            <div class="col-sm-12">
                                <div class="room-empty room" ng-repeat = "room in rooms | orderBy: room.RM_TP " >
                                    <table>
                                        <tr>
                                            <td>{{room.RM_ID}}</td>
                                        </tr>
                                        <tr>
                                            <td>{{room.RM_TP}}</td>
                                        </tr>
                                        <tr>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button class="btn btn-warning pull-right" ng-click = "cancelConn()">取消</button>
                                <button class="btn btn-primary pull-right" ng-click = "confirmConn()">确认</button>
                            </div>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </script>

            </div>


        </div>
    </div>
</div>
<div style="margin-top: 20%; margin-left: 50%" ng-hide="ready">
    <img src="assets/dummy/pageloading.gif" />
</div>