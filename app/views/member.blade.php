<!doctype html>
<div class="loader loader-main" ng-hide="ready">
    <div class="loader-inner ball-scale-multiple">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>
<div class="col-sm-12" ng-show="ready">
    <div class="panel panel-default">

        <div class="panel-heading">
            <div class="panel-control fixedheight">
                <div class="pull-left">
                    <button class="btn btn-primary btn-lg" ng-click="addNewCustomer()">添加会员</button>
                </div>
                <div class="pull-right">
                    <input class="input-lg searchBox" type="text"  ng-model = "memberID" placeholder="会员编号">
                    <input class="input-lg searchBox" type="text" ng-model = "memberNM" placeholder="会员姓名">
                    <input class="input-lg searchBox" type="text" ng-model = "memProvince" placeholder="省份">
                    <input class="input-lg searchBox" type="text" ng-change = "clearMEMphonefilter()" ng-model = "memPhone" placeholder="手机号">
                    <div class="btn-group" dropdown is-open="memSorter.isopen"
                         ng-init="selectTo('','排序',memSorter)" dropdown-append-to-body>
                        <button type="button" class="btn btn-primary dropdown-toggle" dropdown-toggle>
                            {{memSorter.caption}} <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href ng-click="selectTo('','排序',memSorter)">排序</a></li>
                            <li><a href ng-click="selectTo('GUEST_NM','姓名',memSorter)">姓名</a></li>
                            <li><a href ng-click="selectTo('RM_ID','会员卡号',memSorter)">会员卡号</a></li>
                            <li><a href ng-click="selectTo('SSN','会员级别',memSorter)">会员级别</a></li>
                            <li><a href ng-click="selectTo('CHECK_TP','姓名',memSorter)">姓名</a></li>
                            <li><a href ng-click="selectTo('MEM_ID','生日',memSorter)">生日</a></li>
                            <li><a href ng-click="selectTo('MEM_TP','入会时间',memSorter)">入会时间</a></li>
                            <li><a href ng-click="selectTo('PHONE','累计住店次数',memSorter)">累计住店次数</a></li>
                            <li><a href ng-click="selectTo('CHECK_IN_DT','积分',memSorter)">积分</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-striped table-acct">
            <tr>
                <th>会员卡号</th>
                <th>会员级别</th>
                <th>姓名</th>
                <th>证件号</th>
                <th>性别</th>
                <th>生日</th>
                <th>省份</th>
                <th>城市</th>
                <th>手机号码</th>
                <th>电子邮件</th>
                <th>入会时间</th>
                <th>累计住店次数</th>
                <th>积分</th>
            </tr>
            <tr ng-repeat = "member in memberInfo | filter : {MEM_ID: memberID, MEM_NM: memberNM,PROV: memProvince ,PHONE: memPhone} | orderBy:memSorter.value "
                ng-mouseenter="LightUp(member)"
                ng-mouseleave = 'LightBack(member)'
                ng-dblclick="fastAction(member)"
                sglclick="open(member)" onclick="event.preventDefault();" ng-class="member.blockClass" block-class="blockClass"
                not-show ="menuNoshow" pop-menu  menu-type="menuType" owner="member" icon-n-action="iconAndAction.memberIconAction" ng-transclude>
                <td>{{member.MEM_ID}}</td>
                <td>{{member.MEM_TP}}</td>
                <td>{{member.MEM_NM}}</td>
                <td>{{member.SSN}}</td>
                <td>{{member.MEM_GEN}}</td>
                <td>{{member.MEM_DOB}}</td>
                <td>{{member.PROV}}</td>
                <td>{{member.CITY}}</td>
                <td>{{member.PHONE}}</td>
                <td>{{member.EMAIL}}</td>
                <td>{{member.IN_DT}}</td>
                <td>{{member.TIMES}}</td>
                <td>{{member.POINTS}}</td>
            </tr>
        </table>

    </div>
</div>