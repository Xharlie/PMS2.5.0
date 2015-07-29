<div id="wholeModal">
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="icon-users-outline"></span>
            <label>多人入住</label>
            <span class="pull-right close" ng-click="cancel()">&#x2715</span>
        </h4>
    </div>
    <div class="panel-body">
        <div ng-show ="viewClick=='Info'"  >
            <div class="row">
                <div class="form-group col-sm-4">
                    <label xlabel ng-transclude checker="isDate" checkee="BookCommonInfo.CHECK_OT_DT" btn-pass="infoError" >离店日期</label>
                    <div class="input-group datePick" ng-controller="Datepicker" >
                        <input type="text" class="form-control input-lg" show-weeks="false" datepicker-popup="yyyy-MM-dd"
                               ng-model="BookCommonInfo.CHECK_OT_DT" is-open="opened2" min-date="minDate" max-date="'2020-06-22'"
                               datepicker-options="dateOptions" date-disabled="disabled(date, mode)"
                               ng-required="true" close-text="Close"
                               ng-style="BookCommonInfo.CheckOTStyle"
                               datepicker-append-to-body="true" />
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default btn-lg" ng-click="open2($event)"><i class="icon-calendar-outline"></i></button>
                        </span>
                    </div>
                </div>
                <div class="form-group col-sm-4">
                    <label xlabel ng-transclude checker="isDate" checkee="BookCommonInfo.leaveTime" btn-pass="infoError" >离店时间</label>
                    <div ng-controller="TimePickerDemoCtrl" class="removeArrow">
                        <timepicker ng-model="BookCommonInfo.leaveTime" show-meridian="true"
                                    meridians="chineseM" mousewheel="false"></timepicker>
                    </div>
                </div>
                <div class="form-group col-sm-4">
                    <label>出租类型</label>
                    <select class="form-control input-lg" ng-model="BookCommonInfo.rentType">
                        <option value="全日租">全日租</option>
                        <option ng-repeat=" plan in plans | filter:{RM_TP: BookRoom[0].RM_TP}:true  | orderBy: ['PLAN_COV_MIN','PLAN_COV_PRCE'] "
                                value="{{plan.PLAN_ID}}" >
                            {{(plan.RM_TP+': '+plan.PLAN_COV_MIN+'分钟 '+plan.PLAN_COV_PRCE+'元')}}
                        </option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6">
                    <label>客人来源</label>
                    <select class="form-control input-lg" name="sourceSelection" ng-model="BookCommonInfo.roomSource"
                            ng-change="sourceChange()">
                        <option value="普通散客">普通散客</option>
                        <option value="会员">会员</option>
                        <option value="协议">协议</option>
                        <option value="活动码">活动码</option>
                        <option value="预订">预订</option>
                        <option value="免费房">免费房</option>
                    </select>
                </div>
                <div class="form-group col-sm-6">
                    <label class="padded-label">连房入住
                        <input type="checkbox" ng-click="toggleMaster()" ng-checked="Connected"/>
                    </label>
                </div>
            </div>
            <!--------------------------------------   roomSource part   ------------------------------------------------------->
            <div room-source  disable="disable" book-common-info="BookCommonInfo" check="check" caption="caption"></div>
            <div class="splitter"></div>
            <div class="row" ng-repeat="(TP,singleTP) in BookRoomByTP " ng-controller="multiSingleTPCtrl">
                <div class="form-group col-sm-4">
                    <label>房型</label>
                    <label class="form-control input-lg" >{{TP}}</label>
                </div>
                <div class="form-group col-sm-4" >
                    <label xlabel checker="isNotEmpty|isNumber|isInt|isLargerEqualThan0" checkee="singleTP.roomAmount" ng-transclude btn-pass="infoError">数量</label>
                    <input class="form-control input-lg" ng-model="singleTP.roomAmount" ng-disabled="initialString=='multiWalkIn'">
                </div>
                <div class="form-group col-sm-4">
                    <label xlabel checker="isNotEmpty|isNumber|isLargerEqualThan0" checkee="singleTP.finalPrice" ng-transclude btn-pass="infoError" >每晚房价</label>
                    <input class="form-control input-lg" ng-model="singleTP.finalPrice" />
                </div>
                <div class="form-group col-sm-3 hidden">
                    <label xlabel checker="isNumber|isLargerEqualThan0|isLessEqualThan100" checkee="singleTP.discount" ng-transclude btn-pass="infoError">折扣(%)</label>
                    <input class="form-control input-lg" ng-model="singleTP.discount" ng-change="discountChange4TP(singleTP)"/>
                </div>
            </div>
            <div class="row modal-control">
                <button class="pull-right btn btn-primary btn-lg"
                        ng-click="editRoomAndGuest()" ng-if="infoError == '0' || infoError == null ">
                    下一步: 添加客人</button>
                <button class="pull-right btn btn-disabled btn-lg" ng-if="infoError != '0' && infoError != null ">
                    请更正错误信息</button>
            </div>
        </div>
        <div ng-if ="viewClick=='roomInfo'">
            <form>
                <div ng-repeat="singleRoom in BookRoom | filter: {check:'true'}" ng-controller="multiSingleRoomCtrl">
                     <div class="row">
                        <div class="form-group col-sm-4">
                            <label xlabel ng-transclude checker="isNotEmpty" checkee="singleRoom.RM_ID" btn-pass="roomError">房间号</label>
                            <select class="form-control input-lg" ng-model="singleRoom.RM_ID" ng-disabled="initialString=='multiWalkIn'">
                                <option ng-repeat="room in roomsAndRoomTypes[singleRoom.RM_TP]" ng-selected="{{room.RM_ID == singleRoom.RM_ID}}"
                                        ng-disabled= "roomsDisableList[room.RM_ID]" value="{{room.RM_ID}}">
                                    {{room.RM_ID}}
                                    {{(roomsDisableList[room.RM_ID])&&(singleRoom.RM_ID != room.RM_ID)?'('+singleRoom.RM_TP+' 已选)':'('+singleRoom.RM_TP+')'}}
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-sm-4">
                            <label>房型</label>
                            <select class="form-control input-lg" ng-model="singleRoom.RM_TP" ng-change="roomTypeChange(singleRoom)"
                                    ng-options="rmTp as rmTp for (rmTp,rmList) in roomsAndRoomTypes " disabled/>
                        </div>
                        <div class="form-group col-sm-4" ng-show="Connected">
                            <label class="padded-label">选为主房
                                <input type="radio" name="MasterRoom" ng-model="BookCommonInfo.Master.CONN_RM_ID" ng-value="singleRoom.RM_ID" />
                            </label>
                        </div>
                    </div>
                    <div class="row" ng-repeat=" singleGuest in singleRoom.GuestsInfo">
                        <div class="form-group col-sm-2">
                            <label xlabel ng-transclude checker="isChineseOrEnglishOrSpace" checkee="singleGuest.Name" btn-pass="roomError">姓名</label>
                            <input class="form-control input-lg" ng-model="singleGuest.Name" />
                        </div>
                        <div class="form-group col-sm-2">
                            <label>证件类型</label>
                            <select class="form-control input-lg"  ng-model="singleGuest.SSNType"
                                    ng-init="singleGuest.SSNType='二代身份证'">
                                <option value="二代身份证">二代身份证</option>
                                <option value="护照">护照</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-5 ">
                            <label xlabel ng-transclude checker="isSSN" checkee="singleGuest.SSN" btn-pass="roomError" >证件号码</label>  <!-- delete isNotEmpty -->
                            <div class="input-group" >
                                <input class="form-control input-lg" ng-model="singleGuest.SSN"
                                       id="guest{{$index}}SSN"
                                       popover="{{singleGuest.notFindWarning}}"
                                       popover-trigger="openEvent"
                                       popover-append-to-body="true"/>
                                        <span class="input-group-addon btn" ng-click="readFromIDCard(singleGuest)"
                                              ng-mouseleave="closePopover('guest'+$index+'SSN')" >识别</span>
                            </div>
                        </div>
                        <div class="form-group col-sm-3 ">
                            <label xlabel ng-transclude checker="isPhoneNum" checkee="singleGuest.Phone" btn-pass="roomError" >联系电话</label>
                        <!--                        <button class="form-group col-sm-6 btn-xs btn-danger ng-hide" ng-click="deleteCustomer(singleRoom.GuestsInfo,$index)"-->
                        <!--                                ng-show="singleRoom.GuestsInfo.length < -1">删除客人</button>-->
                            <input class="form-control input-lg" ng-model="singleGuest.Phone" />
                        </div>
                    </div>
                    <div class="row">
                        <a class="pull-right btn btn-lg btn-link" ng-click="addCustomer(singleRoom.GuestsInfo)">添加更多客人</a>
                    </div>
                    <div class="splitter"></div>
                </div>
                <div class="row modal-control">
                    <button class="pull-right btn btn-primary btn-lg"
                            ng-click="confirm()" ng-if="roomError == '0' || roomError == null ">
                        确认办理</button>
                    <button class="pull-right btn btn-disabled btn-lg" ng-if="roomError != '0' && roomError != null ">
                        请修改错误信息</button>
                    <button class="pull-right btn btn-primary btn-lg"
                            ng-click="backward('Info')">
                        上一步: 选择房型</button>
                </div>
            </form>
        </div>
        <div ng-if ="viewClick=='Pay'">
            <div ng-if ="(!Connected)" payment  book-room="BookRoom" pay-method-options="payMethodOptions" pay-error="payError"></div>
            <div ng-if ="(Connected)" payment  book-room="BookRoomMaster" pay-method-options="payMethodOptions" pay-error="payError"></div>

            <div class="row modal-control">
                <button class="pull-right btn btn-primary btn-lg"
                        ng-click="submit()"
                        btn-loading="submitLoading"
                        loading-text = '处理中请您稍候...'
                        loading-gif= 'assets/dummy/buttonProcessing.gif'
                        ng-if=" payError == '0' || payError == null "
                        >确认入住并打印押金单</button>
                <button class="pull-right btn btn-disabled btn-lg" ng-if=" payError != '0' && payError != null ">请更正错误信息</button>
                <button class="pull-right btn btn-primary btn-lg"
                        ng-click="backward('roomInfo')">返回修改</button>
            </div>
        </div>
    </div>
</div>