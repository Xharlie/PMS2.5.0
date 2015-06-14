<!doctype html>
<div class="sideNav" style="overflow: visible;">
	<div class="pull-left"><img src="./assets/dummy/logo.png"></div>
    <!-- add inline style!!!!!!! -->
	<div class="sideNav-menu"   ng-controller="sideBarController" style="overflow: visible;" >
    <!-- add inline style!!!!!!! -->
        <ul style="overflow: visible;">
        <!-- add inline style!!!!!!! -->
            <li ng-class="tabClassObj.room"><a href="#/roomStatus"  ng-click="emphasize('room')"></span>房态信息</a></li>
			<li ng-class="tabClassObj.rese"><a href="#/reservation" ng-click="emphasize('rese')"></span>预订信息</a></li>
            <div class="btn-group"  dropdown is-open="status.merc.isopen" ng-mouseover="status.merc.isopen = true" ng-mouseleave="status.merc.isopen = false">
                <li ng-class="tabClassObj.merc" class=" dropdown-toggle" >
                    </span>商品管理<span class="caret" ></span>
                </li>
                <ul class="dropdown-menu" role="menu"
                    ng-mouseover="status.merc.isopen = true" ng-mouseleave="status.merc.isopen = false">
                    <li><a href="#/merchandise/:" ng-click="emphasize('merc')">购买商品</a></li>
                    <li><a href="#/merchandiseHisto/:" ng-click="emphasize('merc')">购买记录</a></li>
                </ul>
            </div>
		    <li ng-class="tabClassObj.cust"><a href="#/customer" ng-click="emphasize('cust')">客户管理</a></li>
		    <li ng-class="tabClassObj.acco"><a href="#/accounting" ng-click="emphasize('acco')">账目管理</a></li>
		    <li ng-class="tabClassObj.prob"><a ng-click="emphasize('prob')">问题汇报</a></li>
		    <li ng-class="tabClassObj.oneK"><a href="#/oneKeyShift" ng-click="emphasize('oneK')">一键交班</a></li>
            <div class="btn-group" dropdown is-open="status.sett.isopen" ng-mouseover="status.sett.isopen = true" ng-mouseleave="status.sett.isopen = false">
                <li ng-class="tabClassObj.sett" class=" dropdown-toggle" >
                    客房设置<span class="caret" ></span>
                </li>
                <ul class="dropdown-menu" role="menu"  ng-mouseover="status.sett.isopen = true" ng-mouseleave="status.sett.isopen = false">
                    <li><a href="#/settingRoomTp" ng-click="emphasize('sett')">房型设置</a></li>
                    <li><a href="#/settingRooms" ng-click="emphasize('sett')">房间设置</a></li>
                    <li><a href="#/settingTempRoom" ng-click="emphasize('sett')">钟点房设置</a></li>
                </ul>
            </div>
        </ul>
	</div>
    <div class="pull-right"><div class="sideNav-profile"></div>早上好！</div>
</div>
