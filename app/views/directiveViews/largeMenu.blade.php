
<div class="menu-large container-fluid"  id="largeMenu">
    <!--  在这个div container 内可随意修改,div 外不可加内容包括comment,会被认为是dom sub root,
    另外 id largeMenu不可删去，用作destroy identifier-->
    <div class="pull-left">
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
    <div ng-switch on="RMviewClick" class="pull-left padded-block">
        <div class="infoAction" ng-switch-when ="infoAction">
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
                        <td><label>{{owner.DPST_RMN}}元</label></td>
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


        <div ng-switch-when ="infoAccounting">

            <div class="roomAccounting">
                <!--                            <h4>住房帐</h4> -->
                <div class="CrossTab">
                    <!--                                <label>押金</label> -->
                    <ul class="table table-striped table-bordered">
                        <li>
                            <th>时间</th>
                            <th>房单号</th>
                            <th>金额</th>
                            <th>类型</th>
                        </li>
                        <li  ng-repeat = "depo in AcctDepo | orderBy: DEPO_TSTAMP  ">
                            <span>{{depo.DEPO_TSTMP}}</span>
                            <span>{{depo.RM_TRAN_ID}}</span>
                            <span>{{depo.DEPO_AMNT}}</span>
                            <!--                                        <span>{{PayMethod(depo.PAY_METHOD)}}</span> -->
                            <span>押金</span>
                        </li>
                    </ul>
                </div>
                <div class="CrossTab">
                    <!--                                <label>房费</label> -->
                    <ul class="table table-striped table-bordered">
                        <!--                                     <li>
                                                                <th>时间</th>
                                                                <th>房单号</th>
                                                                <th>金额</th>
                                                                <th>类型</th>
                                                            </li> -->
                        <li  ng-repeat = "bill in AcctPay | orderBy: BILL_TSTMP  ">
                            <span>{{bill.BILL_TSTMP}}</span>
                            <span>{{bill.RM_TRAN_ID}}</span>
                            <span>{{bill.RM_PAY_AMNT}}</span>
                            <!--                                         <span>{{PayMethod(bill.RM_PAY_METHOD)}}</span> -->
                            <span>房费</span>
                        </li>
                    </ul>
                </div>
                <div class="CrossTab">
                    <!--                                 <h4>商品帐</h4> -->
                    <ul class="table table-striped table-bordered">
                        <!--                                  <li>
                                                           <th>时间</th>
                                                                <th>房单号</th>
                                                                <th>金额</th>
                                                                <th>类型</th>
                                                            </li> -->
                        <li  ng-repeat = "store in AcctStore | orderBy: STR_TRAN_TSTAMP  ">
                            <span>{{store.STR_TRAN_TSTAMP}}</span>
                            <span>{{store.RM_TRAN_ID}}</span>
                            <span>{{store.STR_PAY_AMNT}}</span>
                            <!--                                         <span>{{PayMethod(store.STR_PAY_METHOD)}}</span> -->
                            <span>商品</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>