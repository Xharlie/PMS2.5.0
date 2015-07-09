 <div class="" ng-if="newItem.itemCategory == 'newAcct'">
        <div class="col-sm-6 form-group">
            <label>金额</label>
            <input class=" form-control input-lg" ng-model="newItem.paymentRequest" />
        </div>
        <div class="col-sm-6 form-group">
            <label>备注</label>
            <input class=" form-control input-lg" ng-model="newItem.RMRK" />
        </div>
    </div>
    <div class="" ng-if="newItem.itemCategory == 'merchant'">
        <div class="col-sm-4 form-group">
            <label>商品名</label>
            <input type="text"  class="form-control input-lg"
                   typeahead-append-to-body="true" typeahead-editable="true" typeahead-on-select="loadSelectedProd($item)"
                   typeahead="prod.PROD_NM as prod.PROD_NM for prod in prodInfo | filter:{PROD_NM:$viewValue} " ng-model="newItem.prodInfo.PROD_NM" />
            <!-- ng-change="cleanSelectedProd()" -->
        </div>
        <div class="col-sm-4 form-group">
            <label>数目</label>
            <input class=" form-control input-lg" ng-model="newItem.prodInfo.PROD_QUAN" />
        </div>
        <div class="col-sm-4 form-group">
            <label>金额</label>
            <input class=" form-control input-lg" ng-model="newItem.prodInfo.PROD_PAY" />
        </div>
    </div>
    <div class="" ng-if="newItem.itemCategory == 'penalty'">
        <div class="col-sm-3 form-group">
            <label>赔偿项目</label>
            <input class="form-control input-lg" ng-model="newItem.penalty.PENALTY_ITEM" />
        </div>
        <div class="col-sm-3 form-group">
            <label>赔偿金额</label>
            <input class="form-control input-lg" ng-model="newItem.penalty.PAY_AMNT" />
        </div>
        <div class="col-sm-3 form-group">
            <label>赔款人姓名</label>
            <input class="form-control input-lg" ng-model="newItem.penalty.PAYER" />
        </div>
        <div class="col-sm-3 form-group">
            <label>赔款人电话</label>
            <input class="form-control input-lg" ng-model="newItem.penalty.PAYER_PHONE" />
        </div>
        <div class="col-sm-12 form-group">
            <label>备注</label>
            <input class="form-control input-lg" ng-model="newItem.RMRK" />
        </div>
    </div>
    <!-- <div class="col-sm-5 ">
        <label>查询:{{caption.searchCaption}}</label>
        <div class="input-group">
            <input class="form-control input-lg" ng-model="check.checkInput" ng-disabled="disable.searchDisable"/>
            <span class="input-group-addon btn " ng-click="checkSource(BookCommonInfo.roomSource,check.checkInput)">查询</span>
        </div>
    </div>
    <div class="col-sm-7">
        <table  class="pull-right" ng-show="BookCommonInfo.roomSource =='会员'" class="ng-hide">
            <tr>
                <th></th>
                <th>会员号&nbsp;&nbsp;</th>
                <th>会员姓名</th>
            </tr>
            <tr  ng-repeat = "memberOption in Members | orderBy:MEM_ID"
                 tooltip-html-unsafe="{{memberOption.summary}}"
                 tooltip-trigger="mouseenter"
                 tooltip-append-to-body="true">
                <td><input type="radio" name="memberchoose" ng-model="BookCommonInfo.Member" ng-value="memberOption"
                           ng-show="Members.length>1" class="ng-hide input-lg">&nbsp;</td>
                <td>{{memberOption.MEM_ID}}&nbsp;</td>
                <td>{{memberOption.MEM_NM}}</td>
            </tr>
        </table>
        <table  class="pull-right" ng-show="BookCommonInfo.roomSource =='协议'" class="ng-hide">
            <tr>
                <th>&nbsp;</th>
                <th>协议号&nbsp;&nbsp;</th>
                <th>单位名称</th>
            </tr>
            <tr  ng-repeat = "treatyOption in Treaties |  orderBy:TREATY_ID "
                 tooltip-html-unsafe="{{treatyOption.summary}}"
                 tooltip-trigger="mouseenter"
                 tooltip-append-to-body="true">
                <td><input  type="radio" name="treatychoose" ng-model="BookCommonInfo.Treaty" ng-value="treatyOption"
                            ng-show="Treaties.length>1" class="ng-hide input-lg">&nbsp;</td>
                <td>{{treatyOption.TREATY_ID}}&nbsp;</td>
                <td>{{treatyOption.CORP_NM}}</td>
            </tr>
        </table>
    </div> -->

    <!--
     <div class="col-sm-2 ">
        <label>入账金额</label>
        <input class="form-control input-lg" ng-model="newItem.paymentRequest" />
     </div>
    <div class="col-sm-4 ">
        <label>备注</label>
        <input class="form-control input-lg" ng-model="newItem.RMRK" />
    </div>

                        -->

