<!doctype html>

<!--<div class="mode col-sm-12 hidden" xmlns="http://www.w3.org/1999/html">
    <button class="buttonUnClicked tab tab-active" ng-class ="buttonClicked" >购买商品</button>
</div>  -->
<div ng-show="ready">
    <div class="col-md-8">
        <div class="productBoard panel panel-default">
            <div class="panel-heading">
                <div class="panel-control">
                    <div class="prodSearch pull-right">
                        <div class="btn-group" dropdown is-open="prodTypeFilter.isopen" on-toggle="nullify(prodTypeFilter.value);"
                             ng-init="selectTo('','全部商品类别',prodTypeFilter)" dropdown-append-to-body>
                            <button type="button" class="btn btn-drop dropdown-toggle" dropdown-toggle >
                                {{prodTypeFilter.caption}} <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href ng-click="selectTo('','全部商品类别',prodTypeFilter)">全部商品类别</a></li>
                                <li ng-repeat="(PROD_TP,value) in prodType">
                                    <a href ng-click="selectTo(value,value,prodTypeFilter)">{{value}}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="btn-group" dropdown is-open="prodSorter.isopen" dropdown-append-to-body ng-init="selectTo('','排序',prodSorter);">
                            <button type="button" class="btn btn-drop dropdown-toggle" dropdown-toggle >
                                {{prodSorter.caption}} <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href ng-click="selectTo('','排序',prodSorter)">排序</a></li>
                                <li><a href ng-click="selectTo('PROD_TP','类别',prodSorter)">类别</a></li>
                                <li><a href ng-click="selectTo('PROD_NM','商品名称',prodSorter)">商品名称</a></li>
                                <li><a href ng-click="selectTo('PROD_ID','编号',prodSorter)">编号</a></li>
                                <li><a href ng-click="selectTo('PROD_PRICE','零售价',prodSorter)">零售价</a></li>
                                <li><a href ng-click="selectTo('PROD_AVA_QUAN','零售价',prodSorter)">零售价</a></li>
                            </ul>
                        </div>


                        <input class="searchBox input-lg" type="text"  ng-change = "nullify(prodNameFilter);"
                               ng-model = "prodNameFilter" placeholder="按产品名搜索">
                    </div>
                </div>
            </div>

            <table class="table table-striped table-acct">
                <tr popover="aa" popover-trigger="click" >
                    <th>商品号</th>
                    <th>类别</th>
                    <th>商品名称</th>
                    <th>零售价</th>
                    <th>库存</th>
                </tr>
                <tr  ng-repeat = "prod in prodInfo | filter : {PROD_TP: prodTypeFilter.value, PROD_NM: prodNameFilter.value} | orderBy:prodSorter.value:false"
                     ng-mouseenter="lightUp(prod)"  ng-mouseleave = 'lightBack(prod)'
                     ng-dblclick="addBuy(prod)"
                     sglclick="open(prod)" onclick="event.preventDefault();" ng-class="prod.blockClass" block-class="blockClass"
                     not-show ="menuNoshow" pop-menu  menu-type="'small-menu'" owner="prod"
                     icon-n-action="iconAndAction.merchIconAction" ng-transclude>
                    <td>{{prod.PROD_ID}}</td>
                    <td>{{prod.PROD_TP}}</td>
                    <td>{{prod.PROD_NM}}</td>
                    <td>{{twoDigit(prod.PROD_PRICE)}}</td>
                    <td>{{prod.PROD_AVA_QUAN}}</td>
<!--                                    <td>-->
<!--                                        <button class="btn btn-default btn-xs" ng-click="addProd(prod)" ng-style="prod.buyArrow" ng-init="prod.CorC='选择商品'">{{prod.CorC}}</button>-->
<!--                                    </td>-->
                </tr>
            </table>

        </div>
    </div>
    <div class="col-md-4">
        <div class="purchaseBoard panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><span class="icon-basket"></span>
                    购物篮
                </h4>
            </div>
            <table class="table table-striped table-acct">
                <tr  ng-repeat = "buy in onCounter | orderBy: PROD_NM" ng-controller="eachBuyCtrl">
                    <td>{{buy.PROD_NM}}</td>
                    <td style="text-align: right">{{twoDigit(buy.PROD_PRICE)}} * </td>
                    <td>
                        <input type ="text" ng-class="buy.amountClass " class="input-sm"
                               ng-model="buy.AMOUNT"
                               ng-init="buy.AMOUNT=1;">
                    </td>
                    <td><a ng-click="removeBuy($index);"><span class="icon-cancel gly-spin text-info"></span></a></td>
                </tr>
            </table>
            <div class="panel-body">
                <div class="form-group" style="text-align: center" ng-if="showNotice">
                    <label>
                        哎呀,购物篮是空的，</br>
                        双击添加商品至购物篮。
                    </label>
                </div>
                <div class="from-group">
                    <button
                           class="buyButton btn  btn-block btn-lg"
                           ng-class="buyButtonClass"
                           ng-click="buySubmit()" ng-disabled="showNotice">
                        购买
                    </button>
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

