/**
 * Created by charlie on 7/6/15.
 */

app.factory('paymentFactory',function($http){
    return{
        createNewPayByMethod : function(){
            var payByMethod =  {payAmount:"",payMethod:"现金",rmIdClass:null,RM_TRAN_ID:null,TKN_RM_TRAN_ID:null,roomId:null,roomNumError:0};
            return payByMethod;
        },
        createNewPayment : function(paymentType){
            var Payment =  {paymentRequest:"", paymentType:paymentType, payInDue:"",payByMethods:[this.createNewPayByMethod()]};
            return Payment;
        },
        checkInPayMethodOptions : function(){
            return ["现金","银行卡","信用卡"];
        },
        dumbPurchasePayMethodOptions : function(){
            return ['现金','银行卡', '信用卡', '房间挂账'];

        },
        roomPurchasePayMethodOptions : function(){
            return ['房间挂账'];
        }
    }
})
