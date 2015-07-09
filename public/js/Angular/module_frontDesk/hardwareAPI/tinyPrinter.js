/**
 * Created by charlie on 7/5/15.
 */

/************** ********************************** printer  ********************************** *************/

var printer = {
    checkIn: function(pms,room,guest){
        var checkInStr = ( "hotelname#" +pms.HTL_NM+ "&" // to be implemented
        + "guestname#" + guest.Name + "&"
        + "company#" + "" + "&"  // to be implemented
        + "DOB#" + guest.DOB + "&"
        + "IDNumber#" + guest.SSN.toString() + "&"
        + "Address#" + guest.Address + "&"
        + "indate#" + room.CHECK_IN_DT + "&"
        + "intime#" + room.inTime + "&"
        + "estimateoutdate#" + room.CHECK_OT_DT + "&"
        + "estimateouttime#" + room.leaveTime + "&"
        + "membernumber#" + guest.MemberId.toString() + "&"
        + "roomstyle#" + room.RM_TP + "&"
        + "roomprice#" + room.finalPrice.toString() + "&"
        + "roomnumber#" + room.RM_ID.toString() + "&"
        + "securitydeposit#" + ((room["payment"]["paymentRequest"] == "" || room["payment"]["paymentRequest"] == null) ? '0' : room["payment"]["paymentRequest"].toString()) + "&"
        + "securitycredit#" + "&"  // to be implemented
        + "foodticket#"  + "&"  // to be implemented
        + "misc#" + "&"    // to be implemented
        + "controller#" + pms.EMP_NM + "&"  // to be implemented
        + "number#" + room.CONN_RM_TRAN_ID+ "&"
        + "transactionnumber#" + room.RM_TRAN_ID + "&");
        show(checkInStr);
    },
    deposit: function(pms,room,guest){
        var deposit=0;
        for(var i = 0; i< room.payment.payByMethods.length; i++){
            if(room.payment.payByMethods[i].payMethod == "现金") deposit = deposit + parseFloat(room.payment.payByMethods[i].payAmount);
        }
        var depositStr = ("hotelname#" + pms.HTL_NM + "&" // to be implemented
        + "controller#" + pms.EMP_NM + "&"
        + "number#" + room.CONN_RM_TRAN_ID + "&"
        + "transactionnumber#" + room.RM_TRAN_ID + "&"
        + "guestname#" + guest.Name + "&"
        + "roomnumber#" + room.RM_ID + "&"
        + "deposit#" + util.Limit(deposit).toString()+ "&"
        );
        show(depositStr);
    }
}