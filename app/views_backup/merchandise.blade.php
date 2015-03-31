<!doctype html>
<div class = "mode ctrlLeft" >
    <button class="buttonUnClicked tab tab-active" ng-class ="buttonClicked" ng-click = "viewClickBuy()">购买商品</button>
    <button class="buttonUnClicked tab tab-inactive" ng-class ="buttonClicked" ng-click = "viewClickManage()">购买记录</button>
</div>
<div ng-switch on="viewClick" ng-init=" viewClick = 'Buy'" class="animate-switch-container">
    <div class="animate-switch fixedheight container-fluid" ng-switch-when ="Buy">
        <div class="col-md-8">
            <div class="productBoard card-group">
                <div class="card-header">
                    <div class="ctrlArea">
                        <div class="prodSearch ctrlRight">
                            <select ng-model="prodTypeFilter" ng-change="nullify(prodTypeFilter);" class="btn btn-default">
                                <option value="">全部商品类别</option>
                                <option value="烟">烟类</option>
                                <option value="酒">酒类</option>
                                <option value="零食">零食类</option>
                                <option value="用具">用具类</option>
                            </select>
                            <select ng-model="prodSorter" class="btn btn-default">
                                <option value="">排序</option>
                                <option value="PROD_TP">类别</option>
                                <option value="PROD_NM">商品名称</option>
                                <option value="PROD_ID">编号</option>
                                <option value="PROD_PRICE">零售价</option>
                                <option value="PROD_AVA_QUAN">库存</option>
                            </select>
                            <input class="searchBox input-sm" type="text"  ng-change = "nullify(prodNameFilter);" ng-model = "prodNameFilter" placeholder="按产品名搜索">
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="merchandiseList tableArea CrossTab">
                        <table class="table table-striped table-bordered">
                            <tr>
                                <th>类别</th>
                                <th>商品名称</th>
                                <th>编号</th>
                                <th>零售价</th>
                                <th>库存</th>
                                <th>选择</th>
                            </tr>
                            <tr  ng-repeat = "prod in prodInfo | filter : {PROD_TP: prodTypeFilter, PROD_NM: prodNameFilter} | orderBy:prodSorter:false  ">
                                <td>{{prod.PROD_TP}}</td>
                                <td>{{prod.PROD_NM}}</td>
                                <td>{{prod.PROD_ID}}</td>
                                <td>{{prod.PROD_PRICE}}</td>
                                <td>{{prod.PROD_AVA_QUAN}}</td>
                                <td>
                                    <button class="btn btn-default btn-xs" ng-click="addProd(prod)" ng-style="prod.buyArrow" ng-init="prod.arrow='>>'">{{prod.arrow}}</button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
                <div class="purchaseBoard card">
               <!-- <label>{{room}}</label> -->
                    <div class="purchaseItem tableArea CrossTab">
                        <table class="table table-striped table-bordered">
                            <tr>
                   <!--             <th>取消</th> -->
                   <!--             <th>编号</th> -->
                                <th>商品名称</th>
                                <th>零售价</th>
                   <!--             <th>库存</th> -->
                                <th>数量</th>
                   <!--             <th>总计</th> -->
                            </tr>
                            <tr  ng-repeat = "buy in onCounter | orderBy: PROD_NM  ">
                    <!--            <td>
                                    <button ng-click="removeBuy($index);"><<</button>
                                </td> -->
                    <!--            <td>{{buy.PROD_ID}}</td> -->
                                <td>{{buy.PROD_NM}}</td>
                                <td>{{buy.PROD_PRICE}}</td>
                    <!--            <td>{{buy.PROD_AVA_QUAN}}</td> -->
                                <td>
                                    <input type ="text" ng-style="buy.amountInput " class="prodInput inputBox" ng-change="calculateMoney(buy)" ng-model="buy.AMOUNT"
                                           ng-init="buy.AMOUNT=1;buy.MONEY=buy.PROD_PRICE">
                                </td>
                    <!--            <td>{{buy.MONEY}}</td> -->
                            </tr>
                        </table>
                    </div>
                    <div class="ctrlArea">
                        <label class="sumLabel ctrlRight">共计:{{prodSumPrice}}元</label>
                        <input class="input-sm searchBox ctrlLeft" type="text"  ng-init="initRoomsInfo(prodRoom);" ng-model="prodRoom"  ng-change = "checkRoom(prodRoom)" placeholder="房间号，默认为哑房帐" />
                        <input type="submit"
                               class="buyButton btn btn-primary"
                               ng-mouseenter="purchaseCheck()"
                               popover-placement="bottom"
                               popover-animation="true"
                               popover="{{err}}"
                               popover-trigger="mouseenter"
                               value = "购买"
                               ng-click="buySubmit()" />
                    </div>
                </div>
        </div>
    </div>

    <div class="animate-switch"  ng-switch-when ="Manage">
        <div class="card">
            <div class="ctrlArea"></div>
            <div class="histoPurchase CrossTab tableArea">
                    <table class="table table-striped table-bordered">
                        <tr>
    <!--                        <th>细节</th> -->
                            <th>账单号</th>
                            <th>时间</th>
                            <th>房间号</th>
                            <th>付款方式</th>
                            <th>付款总额</th>
                            <th>房单号</th>
                        </tr>
                            <tr ng-repeat = "info in histoInfo | orderBy:'STR_TRAN_TSTAMP':true">
     <!--                           <td>
                                    <button class="btn btn-default btn-xs" ng-click="expandHisto(info)" ng-style="info.expandSign" ng-init="info.expand='+';info.histoInfoCollapsed = true;">{{info.expand}}</button>
                                </td> -->
                                <td>{{info.STR_TRAN_ID}}</td>
                                <td>{{info.STR_TRAN_TSTAMP}}</td>
                                <td>{{(info.RM_ID == 0)?"N/A":info.RM_ID}}</td>
                                <td>{{info.STR_PAY_METHOD}}</td>
                                <td>{{info.STR_PAY_AMNT}}</td>
                                <td>{{(info.RM_TRAN_ID == 0)?"哑房帐":info.RM_TRAN_ID}}</td>
                            </tr>
                        </table>
          <!--              <div collapse="info.histoInfoCollapsed" class="collpaseTB" >
                            <table>
                                <tr>
                                    <th>类别</th>
                                    <th>商品名称</th>
                                    <th>编号</th>
                                    <th>零售价</th>
                                    <th>售出数量</th>
                                </tr>
                                <tr  ng-repeat = "prod in info.histoProduct | orderBy:PROD_TP ">
                                    <td>{{prod.PROD_TP}}</td>
                                    <td>{{prod.PROD_NM}}</td>
                                    <td>{{prod.PROD_ID}}</td>
                                    <td>{{prod.PROD_PRICE}}</td>
                                    <td>{{prod.PROD_QUAN}}</td>
                                </tr>
                            </table>
                        </div> -->
            </div>
        </div>
    </div>
</div>
