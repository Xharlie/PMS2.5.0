
<div class="pop pop-group"  id="largeMenu">
    <!--  在这个div container 内可随意修改,div 外不可加内容包括comment,会被认为是dom sub root,
    另外 id largeMenu不可删去，用作destroy identifier-->
    <div class="pop-menu">
        <ul>
            <li ng-repeat="action in iconNAction">
                <span>
                    <a ng-click="excAction(action.action)" class="btn">
                        <span ng-class="action.icon"></span>
                        {{ action.action }}
                    </a>
                </span>
                    <!-- icon class 在 public/js/Angular/module_frontDesk/pan_lib/util.js中 变量infoIconAction里 -->
            </li>
        </ul>
    </div>
    <div class="pop-panel">
        <div class="infoAction">
            <table>
                    <tr>
                        <td>客人</td>
                        <td><label>{{nametogether}}</label></td>
                    </tr>
                    <tr>
                        <td>入住时间</td>
                        <td><label>{{owner.CHECK_IN_DT}}</label></td>
                    </tr>
                    <tr>
                        <td>预离时间</td>
                        <td>
                            <label>{{owner.CHECK_OT_DT}}</label>
                            <label>{{owner.LEAVE_TM}}</label>
                            <a class="pull-right" style="cursor: pointer;" ng-click="excAction('信息修改')">续住</a>
                        </td>
                    </tr>
<!--                   <tr>
                        <td>预离时间</td>
                        <td>
                            <label>{{owner.LEAVE_TM}}</label>
                            <a class="pull-right " style="cursor: pointer; ">更改</a>
                        </td>
                    </tr> -->
                    <tr>
                        <td>早叫</td>
                        <td><label>{{owner.WKP}}未设置</label></td>
                    </tr>
                    <tr>
                        <td>在借物品</td>
                        <td><label>{{owner.BRRW}}暂无</label></td>
                    </tr>
                    <tr>
                        <td>日均价</td>
                        <td><label>{{owner.RM_AVE_PRCE}}元</label></td>
                    </tr>
                    <tr>
                        <td>押金余额</td>
                        <td><label>{{owner.CONN_DPST_RMN}}元</label></td>
                    </tr>
            </table>
        </div>



<!--        <div ng-switch-when ="connectCheckOut">-->
<!--            <h4>点击选则联房中进行退房的房间</h4>-->
<!--            <lable >主房:{{ConnRooms[0]["RM_ID"]}}</lable>-->
<!--            <div ui-sortable ng-model="ConnRooms" class="MasterRoom">-->
<!--                <div class="room room-full"-->
<!--                     ng-init ="room.checkRoom = {}"-->
<!--                     ng-style="room.checkRoom"-->
<!--                     ng-click="checkItOut(room)"-->
<!--                     ng-repeat = "room in ConnRooms | orderBy: room.RM_ID " >-->
<!--                    <table>-->
<!--                        <li>-->
<!--                            <span>{{room.RM_ID}}</span>-->
<!--                        </li>-->
<!--                        <li>-->
<!--                            <span>房单号: {{room.RM_TRAN_ID}}</span>-->
<!--                        </li>-->
<!--                    </table>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div>-->
<!--                <label>选中号房间: {{roomNumString}}</label>-->
<!--                <button class="btn btn-primary" ng-click = "confirmConnCheckOut()">办理退房</button>-->
<!--                <button class="btn btn-warning" ng-click = "cancelConnCheckOut()">取消</button>-->
<!--            </div>-->
<!--        </div>-->

    </div>
</div>