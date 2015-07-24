<!doctype html >
<div class="hotelStatus" >
    <div class="col-sm-3">
        <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title"><span class="glyphicon glyphicon-home"></span> 房型余量</h4>
                </div>
                <table class="table">
                    <tr ng-repeat="(rmTp,status) in BookCommonInfo.roomSummary" ng-click="rmTpToggle(rmTp)" style="cursor: pointer;">
                        <td>
                            <div>
                                <div>
                                    <div class="pull-left">{{rmTp}}</div>
                                    <div class="pull-right">
                                        <span style="font-size:18px;">{{status['空房']}}</span>
                                        <span  style="font-size:12px;vertical-align:text-bottom;"> 间</span>
                                    </div>
                                </div>
                                <div class="progress col-md-12">
                                    <div class="progress-bar progress-bar-striped active" role="progressbar"
                                         aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                                         style="width: {{status['空房']*100/status.total}}%;">
                                        <span class="sr-only">60% Complete</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title"><span class="glyphicon glyphicon-time"></span> 近期预达</h4>
                </div>
                <table class="table">
                    <tr ng-repeat="resv in resvComInfo | orderBy:'RESV_LATEST_TIME':false">
                        <td>
                            <div class="pull-left" ng-bind="resv.RESVER_NAME"></div>
                            <div class="pull-right" >今天 {{resv.RESV_LATEST_TIME}}</div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading"><h4 class="panel-title"><span class="glyphicon glyphicon-star"></span> 店长通知</h4></div>
                <div class="panel-body">
                    <p class="padded-block">今晨接到消息，本周公安将对石家庄市酒店进行抽检。请各位同事务必注意，确保入住客人一人一证。</p>
                </div>
            </div>  
        </div>
</div>
<div class="col-sm-9" ng-show="ready">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-control" ng-switch on="connectClick" >
                <div class="pull-left">
                    <div ng-switch-when ="toStart">
                        <button ng-click="connectStart()" class="btn btn-default btn-lg">多间入住</button>
                    </div>
                    <div ng-switch-when ="toEnd">
                        <button ng-click="connectEnd('cancel')" class="btn btn-lg btn-default">取消入住</button>
                        <button ng-click="connectEnd('confirm')" class="btn btn-lg btn-primary">选房完成</button>
                    </div>
                </div>
                <div class="pull-right">
                    <input class="searchBox intellSearchBox input-lg" type="text"  ng-model = "overall" placeholder="关键字搜索">
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
        <div class="panel-body padded-block">
            <div id="roomStatusFrame" class="roomStatus" ng-repeat="(FLOOR_ID,roomsOnFloor) in BookCommonInfo.roomFloor | orderObjectByNum:'FLOOR_ID' " style="margin-bottom: 10px;">
                <label style="font-weight: normal">
                    <span hidden>{{roomsOnFloor.FLOOR}}</span>
                </label>
                <div class="roomStatusFull">
                    <div ng-dblclick="fastAction(roomST)"
                         class="room" ng-class="roomST.blockClass"
                         onclick="event.preventDefault();"
                         ng-repeat = "roomST in roomsOnFloor.rooms | filter: customerizeFilter | filter: {RM_TP: RM_TPfilter} | orderBy:'RM_ID' "
                         ng-mouseenter="connLightUp(roomST)"
                         ng-mouseleave = 'connLightback(roomST)'
                         sglclick="open(roomST)" block-class="blockClass"
                         not-show ="connectFlag" pop-menu  menu-type="roomST.menuType"
                         owner="roomST" icon-n-action="roomST.menuIconAction"
                         update-all-room="updateInfo.updateAllRoom" ng-transclude>
                            <div >
                                <span ng-repeat="alert in roomST.alertInfo" ng-class="alert.iconClass">
                                </span>
                            </div>
                            <ul>
                                <li>
                                    <div>{{roomST.RM_ID}}</div>
                                </li>
                                <li>
                                    <div>
                                        <span>{{roomST.customers[0].CUS_NAME}}</span><br>
                                        <span>{{roomST.RM_TP}}</span>
                                    </div>
                                </li>
                            </ul>
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="loader loader-main" ng-hide="ready">
    <div class="loader-inner ball-scale-multiple">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>