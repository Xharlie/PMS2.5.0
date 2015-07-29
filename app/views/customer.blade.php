<!doctype html>
<div class="col-sm-12" ng-show="ready">
    <div class="panel panel-default">
        <div class="animate-switch fixedheight">
            <div class="panel-heading">
                <div class="panel-control">
                    <div class="pull-right">
                        <input class="input-lg searchBox" type="text"  ng-change = "clearMEMIDfilter()" ng-model = "memberID" placeholder="会员编号">
                        <input class="input-lg searchBox" type="text" ng-model = "CustomerNM" placeholder="客人姓名">
                        <input class="input-lg searchBox" type="text" ng-model = "RoomID" placeholder="房间号">
                        <!--  sorter  -->
                        <div class="btn-group" dropdown is-open="sorter.isopen"
                             ng-init="selectTo('','排序',sorter)" dropdown-append-to-body>
                            <button type="button" class="btn btn-drop dropdown-toggle" dropdown-toggle>
                                {{sorter.caption}} <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href ng-click="selectTo('','排序',sorter)">排序</a></li>
                                <li><a href ng-click="selectTo('GUEST_NM','姓名',sorter)">姓名</a></li>
                                <li><a href ng-click="selectTo('RM_ID','房号',sorter)">房号</a></li>
                                <li><a href ng-click="selectTo('SSN','证件号',sorter)">证件号</a></li>
                                <li><a href ng-click="selectTo('CHECK_TP','类型',sorter)">类型</a></li>
                                <li><a href ng-click="selectTo('MEM_ID','会员卡号',sorter)">会员卡号</a></li>
                                <li><a href ng-click="selectTo('MEM_TP','会员级别',sorter)">会员级别</a></li>
                                <li><a href ng-click="selectTo('PHONE','手机号码',sorter)">手机号码</a></li>
                                <li><a href ng-click="selectTo('CHECK_IN_DT','入住日期',sorter)">入住日期</a></li>
                                <li><a href ng-click="selectTo('CHECK_OT_DT','离店日期',sorter)">离店日期</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-acct">
                <tr>
                    <th>姓名</th>
                    <th>房号</th>
                    <th>证件号</th>
                    <th>类型</th>
                    <th>会员卡号</th>
                    <th>会员级别</th>
                    <!--<th>省份</th>-->
                    <th>手机号码</th>
                    <th>入住日期</th>
                    <th>离店日期</th>
                    <th>备注</th>
                </tr>
                <tr ng-repeat = "customer in customerInfo | filter : {MEM_ID: memberID, CUS_NM: CustomerNM, RM_ID: RoomID} | orderBy:sorter.value ">
                    <td>{{customer.CUS_NM}}</td>
                    <td>{{customer.RM_ID}}</td>
                    <td>{{customer.SSN}}</td>
                    <td>{{customer.CHECK_TP}}</td>
                    <td>{{customer.MEM_ID}}</td>
                    <td>{{customer.MEM_TP}}</td>
                    <!--<td>{{customer.PROVNCE}}</td>-->
                    <td>{{customer.PHONE}}</td>
                    <td>{{customer.CHECK_IN_DT}}</td>
                    <td>{{customer.CHECK_OT_DT}}</td>
                    <td>{{customer.RMRK}}</td>
                </tr>
            </table>
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
