<!doctype html>



<nav class ="mode col-sm-12">
    <ul class="nav nav-pills">
        <li class="buttonUnClicked" ng-class ="buttonClicked" ng-click = "viewClickCustomer()"><a>在住客人</a></li>
        <li class="buttonUnClicked" ng-class ="buttonClicked" ng-click = "viewClickMember()"><a>会员管理</a></li>
    </ul>
</nav>

<div class="col-sm-12" ng-show="ready">
    <div class="panel panel-default">
        <div ng-switch on="viewClick">
            <div class="animate-switch fixedheight" ng-switch-when ="recentCustomer">
                <div class="panel-heading">
                    <div class="panel-control">
                        <div class="pull-right">
                            <input class="input-lg searchBox" type="text"  ng-change = "clearMEMIDfilter()" ng-model = "memberID" placeholder="会员编号">
                            <input class="input-lg searchBox" type="text" ng-model = "CustomerNM" placeholder="客人姓名">
                            <input class="input-lg searchBox" type="text" ng-model = "RoomID" placeholder="房间号">
                            <select class="btn btn-default btn-lg" ng-model="sorter" >
                                <option value="">排序</option>
                                <option value="GUEST_NM">姓名</option>
                                <option value="RM_ID">房号</option>
                                <option value="SSN">证件号</option>
                                <option value="CHECK_TP">类型</option>
                                <option value="MEM_ID">会员卡号</option>
                                <option value="MEM_TP">会员级别</option>
                                <option value="PRVNCE">省份</option>
                                <option value="PHONE">手机号码</option>
                                <option value="CHECK_IN_DT">入住日期</option>
                                <option value="CHECK_OT_DT">离店日期</option>
                            </select>
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
                        <th>省份</th>
                        <th>手机号码</th>
                        <th>入住日期</th>
                        <th>离店日期</th>
                        <th>备注</th>
                    </tr>
                    <tr ng-repeat = "customer in customerInfo | filter : {MEM_ID: memberID, CUS_NM: CustomerNM, RM_ID: RoomID} | orderBy:sorter ">
                        <td>{{customer.CUS_NM}}</td>
                        <td>{{customer.RM_ID}}</td>
                        <td>{{customer.SSN}}</td>
                        <td>{{customer.CHECK_TP}}</td>
                        <td>{{customer.MEM_ID}}</td>
                        <td>{{customer.MEM_TP}}</td>
                        <td>{{customer.PROVNCE}}</td>
                        <td>{{customer.PHONE}}</td>
                        <td>{{customer.CHECK_IN_DT}}</td>
                        <td>{{customer.CHECK_OT_DT}}</td>
                        <td>{{customer.RMRK}}</td>
                    </tr>
                </table>
            </div>

            <div class="animate-switch"  ng-switch-when ="membership">
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
                            <select class="btn btn-default btn-lg" ng-model="memSorter" >
                                <option value="">排序</option>
                                <option value="MEM_ID">会员卡号</option>
                                <option value="MEM_TP">会员级别</option>
                                <option value="MEM_NM">姓名</option>
                                <option value="MEM_DOB">生日</option>
                                <option value="IN_DT">入会时间</option>
                                <option value="TIMES">累计住店次数</option>
                                <option value="POINTS">积分</option>
                            </select>
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
                    <tr ng-repeat = "member in memberInfo | filter : {MEM_ID: memberID, MEM_NM: memberNM,PROV: memProvince ,PHONE: memPhone} | orderBy:memSorter "
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
    </div>
</div>
<div class="loader loader-main" ng-hide="ready">
    <div class="loader-inner ball-scale-multiple">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>