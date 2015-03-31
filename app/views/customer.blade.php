<!doctype html>



<div class ="mode col-sm-12">
    <button class="buttonUnClicked tab tab-active" ng-class ="buttonClicked" ng-click = "viewClickCustomer()">在住客人</button>
    <button class="buttonUnClicked tab tab-inactive" ng-class ="buttonClicked" ng-click = "viewClickMember()">会员管理</button>
</div>

<div class="col-sm-12" ng-show="ready">
    <div class="card card-default">
        <div ng-switch on="viewClick" class="animate-switch-container">
            <div class="animate-switch fixedheight" ng-switch-when ="recentCustomer">
                <div class="card-actions">
                    <div class="ctrlArea">
                        <div class="ctrlLeft">
                            <button class="btn btn-primary">添加会员</button>
                        </div>
                        <div class="ctrlRight">
                            <input class="input-sm searchBox" type="text"  ng-change = "clearMEMIDfilter()" ng-model = "memberID" placeholder="会员编号">
                            <input class="input-sm searchBox" type="text" ng-model = "CustomerNM" placeholder="客人姓名">
                            <input class="input-sm searchBox" type="text" ng-model = "RoomID" placeholder="房间号">
                            <select class="btn btn-default" ng-model="sorter" >
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

                <div class="card-body">
                    <div class="customerList tableArea CrossTab">
                        <table class="table table-striped table-bordered">
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
                </div>
            </div>

            <div class="animate-switch"  ng-switch-when ="membership">
                <div class="card-actions">
                    <div class="ctrlArea fixedheight">
                        <div class="ctrlLeft">
                            <button class="btn btn-primary">添加会员</button>
                        </div>
                        <div class="ctrlRight">
                            <input class="input-sm searchBox" type="text"  ng-model = "memberID" placeholder="会员编号">
                            <input class="input-sm searchBox" type="text" ng-model = "memberNM" placeholder="会员姓名">
                            <input class="input-sm searchBox" type="text" ng-model = "memProvince" placeholder="省份">
                            <input class="input-sm searchBox" type="text" ng-change = "clearMEMphonefilter()" ng-model = "memPhone" placeholder="手机号">
                            <select class="btn btn-default" ng-model="memSorter" >
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

                <div class="card-body">
                    <div class="memberList CrossTab tableArea">
                        <table class="table table-striped table-bordered">
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
                            <tr ng-repeat = "member in memberInfo | filter : {MEM_ID: memberID, MEM_NM: memberNM,PROV: memProvince ,PHONE: memPhone} | orderBy:memSorter ">
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
                                <td>{{customer.POINTS}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="margin-top: 20%; margin-left: 50%" ng-hide="ready">
    <img src="assets/dummy/pageloading.gif" />
</div>