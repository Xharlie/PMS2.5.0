<!doctype html>
<nav class="navbar navbar-default navbar-fixed-top">
  <!--  <div class="container"> -->
    	<div class="navbar-header">
            <a class="navbar-brand" href="#">
                <img src="./assets/dummy/logo.png" style="margin-left:10px;">
            </a>
        </div>
        <div class="navbar-right">
            <p class="navbar-text" style="margin-right:20px;">
                你好，<b>京华酒店！</b>
            </p>
        </div>
        <!-- add inline style!!!!!!! -->
        <div class="navbar-right">
        	<div class="collapse navbar-collapse" ng-controller="sideBarController" style="overflow: visible;" >
            <!-- add inline style!!!!!!! -->
                <ul class="nav navbar-nav" style="overflow: visible; margin-right:200px;">
                <!-- add inline style!!!!!!! -->
                    <li ng-class="tabClassObj.room"><a href="#/roomStatus"  ng-click="emphasize('room')">房态信息</a></li>
        			<li ng-class="tabClassObj.rese"><a href="#/reservation" ng-click="emphasize('rese')">预订信息</a></li>
                    
                    <li class="btn-group"  dropdown is-open="status.merc.isopen"
                        ng-mouseover="status.merc.isopen = true" ng-mouseleave="status.merc.isopen = false"
                        >
                        <a href ng-class="tabClassObj.merc" class="dropdown-toggle" >
                            商品管理<span class="caret" ></span>
                        </a>
                        <ul class="dropdown-menu" role="menu"
                            ng-mouseover="status.merc.isopen = true" ng-mouseleave="status.merc.isopen = false">
                            <li><a href="#/merchandise/:" ng-click="emphasize('merc')">购买商品</a></li>
                            <li><a href="#/merchandiseHisto/:" ng-click="emphasize('merc')">购买记录</a></li>
                        </ul>
                    </li>
                <!--    <li class="btn-group">
                      <a  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">商品管理
                          <span class="caret"></span>
                      </a>
                      <ul class="dropdown-menu">
                        <li><a href="#/merchandise/:">购买商品</a></li>
                        <li><a href="#/merchandiseHisto/:">销售记录</a></li>
                      </ul>
                    </li>   -->
        		    <li ng-class="tabClassObj.cust"><a href="#/customer" ng-click="emphasize('cust')">客户管理</a></li>
        		    <li ng-class="tabClassObj.acco"><a href="#/accounting" ng-click="emphasize('acco')">账目管理</a></li>
        		    <li class="hidden" ng-class="tabClassObj.prob"><a ng-click="emphasize('prob')">问题汇报</a></li>
        		    <li ng-class="tabClassObj.oneK" class="hidden"><a href="#/oneKeyShift" ng-click="emphasize('oneK')">一键交班</a></li>
            <!--        <div class="btn-group" dropdown is-open="status.sett.isopen" ng-mouseover="status.sett.isopen = true" ng-mouseleave="status.sett.isopen = false">
                        <li ng-class="tabClassObj.sett" class=" dropdown-toggle" >
                            客房设置<span class="caret" ></span>
                        </li>
                        <ul class="dropdown-menu" role="menu"  ng-mouseover="status.sett.isopen = true" ng-mouseleave="status.sett.isopen = false">
                            <li><a href="#/settingRoomTp" ng-click="emphasize('sett')">房型设置</a></li>
                            <li><a href="#/settingRooms" ng-click="emphasize('sett')">房间设置</a></li>
                            <li><a href="#/settingTempRoom" ng-click="emphasize('sett')">钟点房设置</a></li>
                        </ul>
                    </div> -->
                    <li class="dropdown hidden">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">客房设置<span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a href="#/settingRoomTp">房型设置</a></li>
                        <li><a href="#/settingRooms">房间设置</a></li>
                        <li><a href="#/settingTempRoom:">钟点房设置</a></li>
                      </ul>
                    </li>
                </ul>
        	</div>
        </div>
 <!--   </div> -->
</nav>
