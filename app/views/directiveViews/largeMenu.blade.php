
<div class="pop pop-group"  id="largeMenu">
    <div class="pop-menu">
        <ul>
            <li ng-repeat="action in iconNAction">
                <span>
                    <a ng-click="excAction(action.action)" class="btn">
                        <span ng-class="action.icon"></span>
                        {{ action.action }}
                    </a>
                </span>
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
                    <td>
                        <label>{{owner.CHECK_IN_DT}}</label>
                        <label>{{owner.IN_TM}}</label>
                    </td>
                </tr>
                <tr>
                    <td>预离时间</td>
                    <td>
                        <label>{{owner.CHECK_OT_DT}}</label>
                        <label>{{owner.LEAVE_TM}}</label>
                        <a class="pull-right" style="cursor: pointer;" ng-click="excAction('信息修改')">续住</a>
                    </td>
                </tr>
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
    </div>
</div>