<div>
    <div class="modal-header">
        <span class="glyphicon glyphicon-send"></span>
        <label style="font-size: 15px;">添加新会员</label>{{}}
        <span class="pull-right btn" ng-click="cancel()">&#x2715</span>
    </div>
    <div class="col-sm-12" style="padding: 30px 60px 45px 60px;">
        <div class="col-sm-12" ng-show ="viewClick=='Info'"  >
            <div class="col-sm-12 form-group" style="padding-bottom:20px;border-bottom:1px solid #D3D6DE;">
                <div class="col-sm-4 ">
                    <label>卡类型</label>
                    <select class="form-control" ng-model="BookCommonInfo.MEM_TP" ng-options="MemTP.MEM_TP as MemTP.MEM_TP for MemTP in MemberTPs"
                        ng-disabled="initialstring!='levelAdjustment' && initialstring!='addMember'" />
                </div>
                <div class="col-sm-4">
                    <label>会员卡号</label>
                    <input class="form-control" ng-model="BookCommonInfo.MEM_ID" disabled/>
                </div>
                <div class="col-sm-4 ">
                    <label ng-if="initialstring=='addMember'">初始积分</label>
                    <label ng-if="initialstring!='addMember'">现有积分</label>
                    <input class="form-control" ng-model="BookCommonInfo.POINTS"
                           ng-disabled="initialstring!='pointsAjustment' && initialstring!='addMember'"/>
                </div>
            </div>
            <div class="col-sm-12 " >
                <div class="col-sm-2 ">
                    <label>会员姓名</label>
                    <input class="form-control" ng-model="BookCommonInfo.MEM_NM"
                           ng-disabled=" initialstring!='editProfile' &&  initialstring!='addMember'" />
                </div>
                <div class="col-sm-2 ">
                    <label>证件类型</label>
                    <select class="form-control" ng-model="BookCommonInfo.SSN_TP" ng-init="BookCommonInfo.SSN_TP='二代身份证'"
                            ng-disabled=" initialstring!='editProfile' &&  initialstring!='addMember'">
                        <option>二代身份证</option>
                        <option>护照</option>
                    </select>
                </div>
                <div class="col-sm-5 ">
                    <label>证件号码</label>
                    <div class="input-group" >
                        <input class="form-control" ng-model="BookCommonInfo.SSN"
                               id="guest{{$index}}SSN"
                               popover="{{singleGuest.notFindWarning}}"
                               popover-trigger="openEvent"
                               popover-append-to-body="true"
                               ng-disabled=" initialstring!='editProfile' &&  initialstring!='addMember'"/>
                        <span class="input-group-addon btn" ng-click="showIdentity(singleGuest,$index)"
                                          ng-mouseleave="closePopover('guest'+$index+'SSN')" >识别</span>
                    </div>
                </div>
                <div class="col-sm-3 ">
                    <label>联系电话</label>
                    <input class="form-control" ng-model="BookCommonInfo.PHONE"
                           ng-disabled=" initialstring!='editProfile' &&  initialstring!='addMember'"/>
                </div>
            </div>
            <div class="col-sm-12 " style="margin-top: 20px;">
                <label>会员备注</label>
                <textarea class="form-control" rows="3" cols="60" ng-model="BookCommonInfo.RMRK"/>
            </div>
            <div class="col-sm-12 " >
                <button class="pull-right" style="margin-top:30px;padding: 10px 30px 10px 30px; background-color:#69B4F5; color: #ffffff"
                        ng-click="confirm('Pay')">
                    确认办理</button>
            </div>
        </div>
        <div  class="col-sm-12"  ng-show ="viewClick=='Pay'" >
            <div class="col-sm-12">
                <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px; padding-bottom:20px; border-bottom: 1px solid #D3D6DE">
                    <div class="col-sm-4 ">
                        <label>应收数目</label>
                        <input class="form-control"ng-model="memPay.payment.paymentRequest" />
                    </div>
                    <div class="col-sm-4 ">
                        <label>账目类型</label>
                        <select class="form-control"  ng-model="memPay.payment.paymentType">
                            <option value="入会">入会</option>
                        </select>
                    </div>
                    <!--                <div class="col-sm-4 ">-->
                    <!--                    <label>账单号</label>-->
                    <!--                    <input class="form-control"ng-model="b" />-->
                    <!--                </div>-->
                </div>
                <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px;"
                     ng-repeat="singlePay in memPay.payment.payByMethods" ng-controller="memSinglePayCtrl">
                    <div class="col-sm-4 ">
                        <label>实收数目</label>
                        <input class="form-control" ng-model="singlePay.payAmount" />
                    </div>
                    <div class="col-sm-4 ">
                        <label>支付方式</label>
                        <select class="form-control"  ng-model="singlePay.payMethod" >
                            <option value="现金">现金</option>
                            <option value="银行卡">银行卡</option>
                            <option value="信用卡">信用卡</option>
                        </select>
                    </div>
                </div>
                <a class="pull-right btn" style="margin-top: 5px; margin-right:33%" ng-click="addNewPayByMethod(memPay)">添加支付方式</a>
                <div class="col-sm-12 form-group" style="padding-right:25px; padding-left: 25px;padding-top:20px;  border-top: 1px solid #D3D6DE">
                    <label>未收数目</label>
                    <label style="display:block;color: red; font-size: 25px;">{{memPay.payment.payInDue}}元</label>
                </div>
            </div>
            <button class="pull-right" ng-if="initialstring == 'addMember'"
                    style="margin-top:25px;padding: 10px 30px 10px 30px; background-color:grey; color: #ffffff"
                    ng-click="submit()">确认入住并打印押金单</button>
            <button class="pull-right" ng-if="initialstring != 'addMember'"
                    style="margin-top:25px;padding: 10px 30px 10px 30px; background-color:grey; color: #ffffff"
                    ng-click="editSubmit('true')">确认修改并打印押金单</button>
            <button class="pull-right"
                    style="margin-top:25px;padding: 10px 30px 10px 30px; background-color:#69B4F5; color: #ffffff"
                    ng-click="backward('Info')">返回修改</button>
        </div>
    </div>
</div>
