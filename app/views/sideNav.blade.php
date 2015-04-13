<!doctype html>
<div class="sideNav shadow-z-1">
	<div class="sideNav-title">
		<div class="ctrlArea">
			<span class="ctrlLeft"><img src="assets/dummy/logo.png" style="height:38px;"/></span>
			<span class="ctrlRight"><div class="sideNav-profile"></div>早上好！</span>
		</div>
	</div>
	<div class="sideNav-menu"   ng-controller="sideBarController" >
		<ul>
			<li ng-class="tabClassObj.room"><a href="#/roomStatus"  ng-click="emphasize('room')"><span class="glyphicon glyphicon-home" ></span>房态信息</a></li>
			<li ng-class="tabClassObj.rese"><a href="#/reservation" ng-click="emphasize('rese')"><span class="glyphicon glyphicon-time"></span>预订信息</a></li>
            <div class="btn-group" dropdown is-open="status.merc.isopen" ng-mouseover="status.merc.isopen = true" ng-mouseleave="status.merc.isopen = false">
                <li ng-class="tabClassObj.merc" class=" dropdown-toggle" >
                    <span class="glyphicon glyphicon-tag"></span>商品管理<span class="caret" ></span>
                </li>
                <ul class="dropdown-menu" role="menu"  ng-mouseover="status.merc.isopen = true" ng-mouseleave="status.merc.isopen = false">
                    <li><a href="#/merchandise/:" ng-click="emphasize('merc')">购买商品</a></li>
                    <li><a href="#/merchandiseHisto/:" ng-click="emphasize('merc')">购买记录</a></li>
                </ul>
            </div>
		    <li ng-class="tabClassObj.cust"><a href="#/customer" ng-click="emphasize('cust')"><span class="glyphicon glyphicon-user"></span>客户管理</a></li>
		    <li ng-class="tabClassObj.acco"><a href="#/accounting" ng-click="emphasize('acco')"><span class="glyphicon glyphicon-usd"></span>账目管理</a></li>
		    <li ng-class="tabClassObj.prob"><a ng-click="emphasize('prob')"><span class="glyphicon glyphicon-warning-sign"></span>问题汇报</a></li>
		    <li ng-class="tabClassObj.oneK"><a href="#/oneKeyShift" ng-click="emphasize('oneK')"><span class="glyphicon glyphicon-transfer"></span>一键交班</a></li>
            <div class="btn-group" dropdown is-open="status.sett.isopen" ng-mouseover="status.sett.isopen = true" ng-mouseleave="status.sett.isopen = false">
                <li ng-class="tabClassObj.sett" class=" dropdown-toggle" >
                    <span class="glyphicon glyphicon-tower"></span> 客房设置<span class="caret" ></span>
                </li>
                <ul class="dropdown-menu" role="menu"  ng-mouseover="status.sett.isopen = true" ng-mouseleave="status.sett.isopen = false">
                    <li><a href="#/settingRoomTp" ng-click="emphasize('sett')">房型设置</a></li>
                    <li><a href="#/settingRooms" ng-click="emphasize('sett')">房间设置</a></li>
                    <li><a href="#/settingTempRoom" ng-click="emphasize('sett')">钟点房设置</a></li>
                </ul>
            </div>
        </ul>
	</div>
</div>
