<!doctype html>
<!-- <div class="mode col-sm-12" xmlns="http://www.w3.org/1999/html">
    <button class="buttonUnClicked tab tab-active" ng-class ="buttonClicked">购买记录</button>
</div>  -->

<div ng-show="ready" class="col-sm-12">
    <div class="card card-default">
        <div class="card-body">
            <div class="histoPurchase CrossTab tableArea">
                <table class="table table-striped table-acct">
                    <tr>
                        <th>账单号</th>
                        <th>时间</th>
                        <th>房间号</th>
                        <th>付款方式</th>
                        <th>付款总额</th>
                        <th>房单号</th>
                    </tr>
                        <tr ng-repeat = "info in histoInfo | orderBy:'STR_TRAN_TSTMP':true"
                            ng-mouseenter="lightUp(info)"  ng-mouseleave = 'lightBack(info)'
                            sglclick="open(info)" onclick="event.preventDefault();" ng-class="info.blockClass" block-class="blockClass"
                            not-show ="menuNoshow" pop-menu  menu-type="'content-menu'" owner="{'owner':info,'format':contentFormat}"
                            ng-transclude>
                            <td>{{info.STR_TRAN_ID}}</td>
                            <td>{{info.STR_TRAN_TSTMP}}</td>
                            <td>{{(info.RM_ID == null)?"N/A":info.RM_ID}}</td>
                            <td>{{info.STR_PAY_METHOD}}</td>
                            <td>{{twoDigit(info.STR_PAY_AMNT)}}</td>
                            <td>{{(info.RM_TRAN_ID == null)?"哑房帐":info.RM_TRAN_ID}}</td>
                        </tr>
                </table>
<!--               <div collapse="info.histoInfoCollapsed" class="collpaseTB" >-->
<!--                    <table>-->
<!--                        <tr>-->
<!--                            <th>类别</th>-->
<!--                            <th>商品名称</th>-->
<!--                            <th>编号</th>-->
<!--                            <th>零售价</th>-->
<!--                            <th>售出数量</th>-->
<!--                        </tr>-->
<!--                        <tr  ng-repeat = "prod in info.histoProduct | orderBy:PROD_TP ">-->
<!--                            <td>{{prod.PROD_TP}}</td>-->
<!--                            <td>{{prod.PROD_NM}}</td>-->
<!--                            <td>{{prod.PROD_ID}}</td>-->
<!--                            <td>{{prod.PROD_PRICE}}</td>-->
<!--                            <td>{{prod.PROD_QUAN}}</td>-->
<!--                        </tr>-->
<!--                    </table>-->
<!--                </div>-->
            </div>
        </div>
    </div>
</div>
<div style="margin-top: 20%; margin-left: 50%" ng-hide="ready">
    <img src="assets/dummy/pageloading.gif" />
</div>


