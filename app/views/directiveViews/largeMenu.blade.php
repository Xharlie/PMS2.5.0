
<div class="menu-large"  id="largeMenu">
    <!--  在这个div container 内可随意修改,div 外不可加内容包括comment,会被认为是dom sub root,
    另外 id largeMenu不可删去，用作destroy identifier-->
    <div class="roomAction col-sm-4">
        <table>
            <tr ng-repeat="action in iconNAction">
                <td>
                    <span ng-class="action.icon"></span>
                    <a ng-click="excAction(action.action)">{{ action.action }}</a>
                </td>
                    <!-- icon class 在 public/js/Angular/module_frontDesk/pan_lib/util.js中 变量infoIconAction里 -->
            </tr>
        </table>
    </div>
    <div ng-switch on="RMviewClick" class="infoPanel col-sm-7">
        <div class="infoAction" ng-switch-when ="infoAction">
            <table class="roomInfo">
                    <tr>
                        <td>客人</td>
                        <td><label>{{owner.nametogether}}</label></td>
                    </tr>
                    <tr>
                        <td>入住时间</td>
                        <td><label>{{owner.CHECK_IN_DT}}</label></td>
                    </tr>
                    <tr>
                        <td>预离时间</td>
                        <td>
                            <label>{{owner.CHECK_OT_DT}}</label>
                            <a>续住</a>
                        </td>
                    </tr>
                    <tr>
                        <td>早叫</td>
                        <td><label>{{owner.WKP}}</label></td>
                    </tr>
                    <tr>
                        <td>在接物品</td>
                        <td><label>{{owner.BRRW}}</label></td>
                    </tr>
                    <tr>
                        <td>日均价</td>
                        <td><label>{{owner.RM_AVE_PRCE}}</label></td>
                    </tr>
                    <tr>
                        <td>押金余额</td>
                        <td><label>{{owner.DPST_RMN}}</label></td>
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