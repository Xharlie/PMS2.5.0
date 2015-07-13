<div class="row alert alert-info">
    <div class="form-group col-sm-5 ">
        <label>查询:{{caption.searchCaption}}</label>
        <div class="input-group">
            <input class="form-control input-lg" ng-model="check.checkInput" ng-disabled="disable.searchDisable"/>
            <span class="input-group-addon btn " ng-click="checkSource(BookCommonInfo.roomSource,check.checkInput)">查询</span>
        </div>
    </div>
    <div class="col-sm-7">
        <table  class="pull-right" ng-show="BookCommonInfo.roomSource =='会员' " class="ng-hide">
            <tr>
                <th></th>
                <th>会员号&nbsp;&nbsp;</th>
                <th>会员姓名</th>
            </tr>
            <tr  ng-repeat = "memberOption in BookCommonInfo.Members |  orderBy:MEM_ID"
                 tooltip-html-unsafe="{{memberOption.summary}}"
                 tooltip-trigger="mouseenter"
                 tooltip-append-to-body="true">
                <td><input type="radio" name="memberchoose" ng-model="BookCommonInfo.Member" ng-value="memberOption"
                           ng-show="Members.length>1" class="ng-hide input-lg">&nbsp;</td>
                <td>{{memberOption.MEM_ID}}&nbsp;</td>
                <td>{{memberOption.MEM_NM}}</td>
            </tr>
        </table>
        <table  class="pull-right" ng-show="BookCommonInfo.roomSource =='协议' " class="ng-hide">
            <tr>
                <th>&nbsp;</th>
                <th>协议号&nbsp;&nbsp;</th>
                <th>单位名称</th>
            </tr>
            <tr  ng-repeat = "treatyOption in BookCommonInfo.Treaties |  orderBy:TREATY_ID "
                 tooltip-html-unsafe="{{treatyOption.summary}}"
                 tooltip-trigger="mouseenter"
                 tooltip-append-to-body="true">
                <td><input  type="radio" name="treatychoose" ng-model="BookCommonInfo.Treaty" ng-value="treatyOption"
                            ng-show="Treaties.length>1" class="ng-hide input-lg">&nbsp;</td>
                <td>{{treatyOption.TREATY_ID}}&nbsp;</td>
                <td>{{treatyOption.CORP_NM}}</td>
            </tr>
        </table>
    </div>
</div>