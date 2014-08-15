<link rel="stylesheet" type="text/css" href="css/newOut.css" xmlns="http://www.w3.org/1999/html">
<form xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" >
    <div class="wholeInfoBlock" ng-repeat="singleRoom in BookRoom">
        <div class="headDiv">
            <h1 class="PerRoom">{{singleRoom.RM_ID}}号房</h1>
        </div>
        <div class="ChooseRoom">
            <div class="CrossTab">
                <h4>押金</h4>
                <table>
                    <tr>
                        <th>时间</th>
                        <th>房单号</th>
                        <th>存入方式</th>
                        <th>存入金额</th>
                    </tr>
                    <tr  ng-repeat = "depo in singleRoom.AcctDepo | orderBy: DEPO_TSTAMP  ">
                        <td>{{depo.DEPO_TSTMP}}</td>
                        <td>{{depo.RM_TRAN_ID}}</td>
                        <td>{{depo.PAY_METHOD}}</td>
                        <td>{{depo.DEPO_AMNT}}元</td>
                    </tr>
                </table>
                <label class="floatRightLabel" style="color: #5bc0de">押金合计+{{singleRoom.DEPO_SUM}}元</label>
            </div>
            <div class="CrossTab">
                <h4>房费</h4>
                <table>
                    <tr>
                        <th>时间</th>
                        <th>房单号</th>
                        <th>付费方式</th>
                        <th>付费金额</th>
                    </tr>
                    <tr  ng-repeat = "bill in singleRoom.AcctPay | orderBy: BILL_TSTMP  ">
                        <td>{{bill.BILL_TSTMP}}</td>
                        <td>{{bill.RM_TRAN_ID}}</td>
                        <td>{{bill.RM_PAY_METHOD}}</td>
                        <td>{{bill.RM_PAY_AMNT}}元</td>
                    </tr>
                </table>
                <label class="floatRightLabel" style="color: lightcoral">房费合计-{{singleRoom.Acct_SUM}}元</label>
            </div>
            <div class="CrossTab">
                <h4>商品帐</h4>
                <table>
                    <tr>
                        <th>取消</th>
                        <th>时间</th>
                        <th>房单号</th>
                        <th>商品名称</th>
                        <th>商品数量</th>
                        <th>付费方式</th>
                        <th>应付金额</th>
                    </tr>
                    <tr  ng-repeat = "store in singleRoom.AcctStore | orderBy: STR_TRAN_TSTAMP  ">
                        <td></td>
                        <td>{{store.STR_TRAN_TSTAMP}}</td>
                        <td>{{store.RM_TRAN_ID}}</td>
                        <td>{{store.PROD_NM}}</td>
                        <td>{{store.PROD_QUAN}}</td>
                        <td>{{store.STR_PAY_METHOD}}</td>
                        <td>{{store.PROD_PRICE*store.PROD_QUAN}}元</td>
                    </tr>
                    <tr  ng-repeat = "Rstore in singleRoom.RoomConsume">
                        <td><button ng-click="deleteNewRMProduct(singleRoom,Rstore)">-</button></td>
                        <td>{{Rstore.STR_TRAN_TSTAMP}}</td>
                        <td>{{Rstore.RM_TRAN_ID}}</td>
                        <td>{{Rstore.PROD_NM}}</td>
                        <td>{{Rstore.PROD_QUAN}}</td>
                        <td>{{Rstore.STR_PAY_METHOD}}</td>
                        <td>{{Rstore.STR_PAY_AMNT}}</td>
                    </tr>
                </table>
            </div>
            <div class="CrossTab">
                <div >
                    <div style=" display: inline-block;  width:140px; border: 1px solid goldenrod; margin-left:15px;">
                        <label style="background-color: gainsboro; display: block">房内消费品: </label>
                        <label>{{singleRoom.RM_PROD_RMRK}}</label>
                    </div>
                    <div style="float:right; display: inline-block; width:480px; margin-right:5px;">
                        <table>
                            <tr>
                                <th>增加</th>
                                <th>商品名称</th>
                                <th>数量</th>
                                <th>付款方式</th>
                                <th>应付金额</th>
                            </tr>
                            <tr>
                                <td><button ng-click="addNewRMProduct(singleRoom)">+</button></td>
                                <td><input type="text" ng-change="getPrice(singleRoom)"
                                        ng-model="singleRoom.newRMProduct.newRProductNM"
                                        style="width: 100px"/></td>
                                <td><input type="text" ng-change="getPrice(singleRoom)"
                                        ng-model="singleRoom.newRMProduct.newRProductQUAN"
                                        style="width: 100px"/></td>
                                <td><input type="text" ng-model="singleRoom.newRMProduct.newRProductPAYmethod"
                                           style="width: 100px"/></td>
                                <td>{{singleRoom.newRMProduct.newRProductPAY}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <label  class="floatRightLabel" style="color: lightcoral">
                    商品消费合计-{{singleRoom.Store_SUM+singleRoom.newConsumeSum}}元
                </label>

            </div>
            <div class="CrossTab">
                <h4>屋内损坏赔偿</h4>
                <div >
                    <table>
                        <tr>
                            <th></th>
                            <th>房单号</th>
                            <th>损坏说明</th>
                            <th>付费方式</th>
                            <th>应付金额</th>
                        </tr>
                        <tr  ng-repeat = "fee in singleRoom.penalty">
                            <td><button ng-click="deleteFee(singleRoom,fee)">-</button></td>
                            <td>{{fee.RM_TRAN_ID}}</td>
                            <td>{{fee.RMRK}}</td>
                            <td>{{fee.PAY_METHOD}}</td>
                            <td>{{fee.PAY_AMNT}}元</td>
                        </tr>
                        <tr>
                            <td><button ng-click="addNewFee(singleRoom)">+</button></td>
                            <td>{{singleRoom.RM_TRAN_ID}}</td>
                            <td><textarea class="" ng-model="singleRoom.newFee.RMRK"
                                          rows="1" cols="40" ></textarea></td>
                            <td><input type="text" ng-model="singleRoom.newFee.PAY_METHOD" style="width: 80px"/></td>
                            <td><input type="text" ng-model="singleRoom.newFee.PAY_AMNT" style="width: 80px"/></td>
                        </tr>
                    </table>
                </div>
                <label class="floatRightLabel" style="color: lightcoral; ">赔偿款-{{singleRoom.newFeeSum}}元</label>
            </div>
            <label style=" margin-left: 516px; font-size: medium; color:darkorange;">总计{{singleRoom.Sumation}}元</label>
        </div>
        <div class="checkInCustomer">
             <div>
                 <h4>房间情况</h4>

                     <label class="RoomleftCaption" style="margin-left: 30px">房间号: </label>
                     <label class="RoomleftContent" style="color: lightcoral">{{singleRoom.RM_ID}}</label>

                     <label class="RoomleftCaption">住单号: </label>
                     <label class="RoomleftContent" style="color: lightcoral">{{singleRoom.RM_TRAN_ID}}</label>

                     <label class="RoomleftCaption">房型: </label>
                     <label class="RoomleftContent" style="color: lightcoral">{{singleRoom.RM_TP}}</label>

                     <label class="RoomleftCaption">预付天数: </label>
                     <label class="RoomleftContent" style="color: lightcoral">{{(singleRoom.RSRV_PAID_DYS != undefine) ? singleRoom.RSRV_PAID_DYS +"天" :"0天" }}</label>

                    </br>

                     <label class="RoomleftCaption" style="margin-left: 30px">入住日期: </label>
                     <label class="RoomleftContent" style="color: lightcoral">{{singleRoom.CHECK_IN_DT}}</label>

                     <label class="RoomleftCaption">退房日期: </label>
                     <label class="RoomleftContent" style="color: lightcoral">{{singleRoom.CHECK_OT_DT}}</label>

                     <label class="RoomleftCaption">实际在住天数: </label>
                     <label class="RoomleftContent" style="color: lightcoral">{{singleRoom.DAYS_STAY}}天</label>

             </div>
            </br    >
             <div>
                  <h4>客人信息</h4>
                 <div class="guest" ng-repeat=" singleGuest in singleRoom.Customers">
                     <span class="GuestNum">{{$index+1}}</span>
                     </br>

                     <label class="gustCaption">客人姓名:</label>
                     <label class="gustContent">{{singleGuest.CUS_NAME}}</label>

                     <label class="gustCaption" >证件号码:</label>
                     <label class="gustContent">{{singleGuest.SSN}}</label>

                     <label  class="gustCaption">出生日期:</label>
                     <label class="gustContent">
                         {{singleGuest.SSN.substr(6, 4)+"/"+singleGuest.SSN.substr(10, 2)+"/"+singleGuest.SSN.substr(12, 2)}}
                     </label>

                     <label class="gustCaption">性别:</label>
                     <label class="gustContent">{{(singleGuest.SSN.substr(16, 1)%2 == 1)? "男" : "女" }}</label>

                     <div style="display: inline-block" ng-style="(singleRoom.MEM_ID != undefine)? {}:{'display':'none'}">
                         <label class="gustCaption">会员卡号:</label>
                         <label class="gustContent">{{singleGuest.MEM_ID}}</label>
                     </div>

                     <div style="display: inline-block" ng-style="(singleRoom.TREATY_ID != undefine)? {}:{'display':'none'}">
                         <label class="gustCaption"  >合作协议:</label>
                         <label class="gustContent">{{singleGuest.TREATY_ID}}</label>
                     </div>

                     <div style="display: inline-block" ng-style="(singleRoom.PROVNCE != undefine)? {}:{'display':'none'}">
                         <label class="gustCaption">省份:</label>
                         <label class="gustContent">{{singleGuest.PROVNCE}}</label>
                     </div>

                     <div style="display: inline-block" ng-style="(singleRoom.PHONE != undefine)? {}:{'display':'none'}">
                         <label class="gustCaption">电话:</label>
                         <label class="gustContent">{{singleGuest.PHONE}}</label>
                     </div>

                     <div style="display: inline-block" ng-style="(singleRoom.POINTS != undefine)? {}:{'display':'none'}">
                         <label class="gustCaption">会员积分:</label>
                         <label class="gustContent">{{singleGuest.POINTS}}</label>
                     </div>

                      </br>

                     <div style="display: inline-block" ng-style="(singleRoom.RMRK != undefine)? {}:{'display':'none'}">
                         <label class="gustCaption">备注:</label>
                             <textarea class="RemarkInput" ng-model="singleGuest.RMRK" rows="3" cols="90">
                         </textarea>
                     </div>
                 </div>
            </div>
    </div>
</form>