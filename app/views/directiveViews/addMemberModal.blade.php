<div id="wholeModal">
    <div class="card-actions">
        <h4>
            <span class="glyphicon glyphicon-send"></span>
            <label>添加新会员</label>
            <span class="pull-right close" ng-click="cancel()">&#x2715</span>
        </h4>
    </div>
    <div class="card-body">
        <div class="padded-form" ng-show ="viewClick=='Info'">
            <div class="form-group">
                <div class="col-sm-4 ">
                    <label>卡类型</label>
                    <select class="form-control input-lg" ng-model="BookCommonInfo.MEM_TP" ng-options="MemTP.MEM_TP as MemTP.MEM_TP for MemTP in MemberTPs"
                        ng-disabled="initialstring!='levelAdjustment' && initialstring!='addMember'" />
                </div>
                <div class="col-sm-4" hidden>
                    <label>会员卡号</label>
                    <input class="form-control input-lg" ng-model="BookCommonInfo.MEM_ID" disabled/>
                </div>
                <div class="col-sm-4 ">
                    <label xlabel ng-transclude checker="isNotEmpty|isNumber|isLargerEqualThan0" checkee="BookCommonInfo.POINTS" btn-pass="infoError">
                        <spam ng-if="initialstring=='addMember'">初始积分</spam>
                        <spam ng-if="initialstring!='addMember'">现有积分</spam>
                    </label>
                    <input class="form-control input-lg" ng-model="BookCommonInfo.POINTS"
                           ng-disabled="initialstring!='pointsAjustment' && initialstring!='addMember'"/>
                </div>
            </div>
            <div class="form-group" >
                <div class="col-sm-2 ">
                    <label xlabel ng-transclude checker="isNotEmpty|isChineseOrEnglishOrSpace" checkee="BookCommonInfo.MEM_NM" btn-pass="infoError">姓名</label>
                    <input class="form-control input-lg" ng-model="BookCommonInfo.MEM_NM"
                           ng-disabled=" initialstring!='editProfile' &&  initialstring!='addMember'" />
                </div>
                <div class="col-sm-2 ">
                    <label>证件类型</label>
                    <select class="form-control input-lg" ng-model="BookCommonInfo.SSN_TP" ng-init="BookCommonInfo.SSN_TP='二代身份证'"
                            ng-disabled=" initialstring!='editProfile' &&  initialstring!='addMember'">
                        <option>二代身份证</option>
                        <option>护照</option>
                    </select>
                </div>
                <div class="col-sm-5 ">
                    <label xlabel ng-transclude checker="isNotEmpty|isSSN" checkee="BookCommonInfo.SSN" btn-pass="infoError" >证件号码</label>
                    <div class="input-group" >
                        <input class="form-control input-lg" ng-model="BookCommonInfo.SSN"
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
                    <label xlabel ng-transclude checker="isPhoneNum" checkee="BookCommonInfo.PHONE" btn-pass="infoError">联系电话</label>
                    <input class="form-control input-lg" ng-model="BookCommonInfo.PHONE"
                           ng-disabled=" initialstring!='editProfile' &&  initialstring!='addMember'"/>
                </div>
            </div>
            <div class="form-group">
                <label>会员备注</label>
                <textarea class="form-control" rows="3" cols="60" ng-model="BookCommonInfo.RMRK"/>
            </div>
            <div class="form-group" >
                <button class="pull-right btn btn-primary btn-lg"
                        ng-click="confirm('Pay')"
                        ng-if="infoError == '0' || infoError == null ">
                    确认办理</button>
                <button class="pull-right btn btn-alert btn-lg" ng-if="infoError != '0' && infoError != null ">
                    信息未改</button>
            </div>
        </div>
        <div class="padded-form" ng-show ="viewClick=='Pay'" >
            <div class="form-group">
                <div class="col-sm-6">
                    <label xlabel ng-transclude checker="isNotEmpty|isNumber|isLargerEqualThan0" checkee="memPay.payment.paymentRequest" btn-pass="payError">应收数目</label>
                    <input class="form-control input-lg" ng-model="memPay.payment.paymentRequest" />
                </div>
                <div class="col-sm-6">
                    <label>账目类型</label>
                    <select class="form-control input-lg"  ng-model="memPay.payment.paymentType">
                        <option value="入会">会员费</option>
                    </select>
                </div>
                <!--                <div class="col-sm-4 ">-->
                <!--                    <label>账单号</label>-->
                <!--                    <input class="form-control"ng-model="b" />-->
                <!--                </div>-->
            </div>
            <div class="form-group" ng-repeat="singlePay in memPay.payment.payByMethods" ng-controller="memSinglePayCtrl">
                <div class="col-sm-6">
                    <label xlabel ng-transclude checker="isNotEmpty|isNumber|isLargerEqualThan0" checkee="singlePay.payAmount" btn-pass="payError">实收数目</label>
                    <input class="form-control input-lg" ng-model="singlePay.payAmount" />
                </div>
                <div class="col-sm-6">
                    <label>支付方式</label>
                    <select class="form-control input-lg"  ng-model="singlePay.payMethod" >
                        <option value="现金">现金</option>
                        <option value="银行卡">银行卡</option>
                        <option value="信用卡">信用卡</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <a class="pull-right btn btn-link btn-lg" ng-click="addNewPayByMethod(memPay)">添加支付方式</a>
            </div>
            <div class="form-group">
                <label>未收数目</label>
                <label class="text-lg text-danger">{{memPay.payment.payInDue}}元</label>
            </div>
            <div class="form-group">
                <button class="pull-right btn btn-primary btn-lg" ng-if="initialstring == 'addMember'  && (payError == '0' || payError == null ) "
                        ng-click="submit()"
                        btn-loading="submitLoading"
                        loading-text="处理中请您稍候..."
                        loading-gif= 'assets/dummy/buttonProcessing.gif'
                        >确认会员办理</button>
                <button class="pull-right btn btn-primary btn-lg" ng-if="initialstring != 'addMember'  && (payError == '0' || payError == null )"
                        ng-click="editSubmit('true')"
                        btn-loading="submitLoading"
                        loading-text="处理中请您稍候..."
                        loading-gif= 'assets/dummy/buttonProcessing.gif'
                        >确认会员修改</button>
                <button class="pull-right btn btn-alert btn-lg" ng-if=" payError != '0' && payError != null ">请更正错误信息</button>
                <button class="pull-right btn btn-primary btn-lg"
                        ng-click="backward('Info')">返回修改</button>
            </div>
        </div>
    </div>
</div>
