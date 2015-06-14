<!doctype html >
<div class="hotelStatus">
       <div class="col-sm-3">
            <div class="card card-default">
                <div class="card-actions">
                    <h4><div class="title-decor title-decor-md"></div>房型余量</h4>
                </div>
                <div class="card-body">
                    <ul>
                        <li>
                            <div class="padded-row">
                                <div class="col-md-12">
                                    <div class="pull-left">大床房</div>
                                    <div class="pull-right">6间</div>
                                </div>
                                <div class="progress col-md-12">
                                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                                        <span class="sr-only">60% Complete</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="padded-row">
                                <div class="col-md-12">
                                    <div class="pull-left">标准房</div>
                                    <div class="pull-right">7间</div>
                                </div>
                                <div class="progress col-md-12">
                                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                                        <span class="sr-only">60% Complete</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="padded-row">
                                <div class="col-md-12">
                                    <div class="pull-left">三人间</div>
                                    <div class="pull-right">16间</div>
                                </div>
                                <div class="progress col-md-12">
                                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                                        <span class="sr-only">60% Complete</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="padded-row">
                                <div class="col-md-12">
                                    <div class="pull-left">团购标准间</div>
                                    <div class="pull-right">32间</div>
                                </div>
                                <div class="progress col-md-12">
                                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                                        <span class="sr-only">60% Complete</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card card-default">
                <div class="card-actions">
                    <h4><div class="title-decor title-decor-md"></div>近期预达</h4>
                </div>
                <div class="card-body">
                    <ul>
                        <li class="padded-row">
                            <span class="pull-left">李兵</span>
                            <span class="pull-right">今天 13:20</span>
                        </li>
                        <li class="padded-row">
                            <span class="pull-left">徐乾庚</span>
                            <span class="pull-right">今天 16:30</span>
                        </li>
                        <li class="padded-row">
                            <span class="pull-left">吴为龙</span>
                            <span class="pull-right">4月18日 8:00</span>
                        </li>
                        <li class="padded-row">
                            <span class="pull-left">石奔</span>
                            <span class="pull-right">4月19日 8:45</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card card-default">
                <div class="card-actions"><h4><div class="title-decor title-decor-md"></div>店长通知</h4></div>
                <div class="card-body">
                    <p class="padded-block">今晨接到消息，本周公安将对石家庄市酒店进行抽检。请各位同事务必注意，确保入住客人一人一证。</p>
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
                        <button ng-click="connectStart()" class="btn btn-default btn-lg">多间入住</button>
                    </div>
                    <div ng-switch-when ="toEnd">
                        <button ng-click="connectEnd('cancel')" class="btn btn-lg btn-default">取消入住</button>
                        <button ng-click="connectEnd('confirm')" class="btn btn-lg btn-primary">选房完成</button>
                    </div>
                </div>
                <div class="ctrlRight">
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

        <div class="card-body padded-block">
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