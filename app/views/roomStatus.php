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
                         not-show ="connectFlag" pop-menu  menu-type="roomST.menuType"
                         owner="roomST" icon-n-action="roomST.menuIconAction" ng-transclude>
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
            </div>
        </div>
    </div>
</div>
<div style="margin-top: 20%; margin-left: 50%" ng-hide="ready">
    <img src="assets/dummy/pageloading.gif" />
</div>