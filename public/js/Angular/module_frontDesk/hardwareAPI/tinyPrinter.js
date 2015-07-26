/**
 * Created by charlie on 7/5/15.
 */

/************** ********************************** printer  ********************************** *************/

var printer = {
    checkIn: function(pms,room,guest){
        return;
        //var checkInStr = ( "hotelname#" +pms.HTL_NM+ "&" // to be implemented
        //+ "guestname#" + guest.Name + "&"
        //+ "company#" + "" + "&"  // to be implemented
        //+ "DOB#" + guest.DOB + "&"
        //+ "IDNumber#" + guest.SSN.toString() + "&"
        //+ "Address#" + guest.Address + "&"
        //+ "indate#" + room.CHECK_IN_DT + "&"
        //+ "intime#" + room.inTime + "&"
        //+ "estimateoutdate#" + room.CHECK_OT_DT + "&"
        //+ "estimateouttime#" + room.leaveTime + "&"
        //+ "membernumber#" + guest.MemberId.toString() + "&"
        //+ "roomstyle#" + room.RM_TP + "&"
        //+ "roomprice#" + room.finalPrice.toString() + "&"
        //+ "roomnumber#" + room.RM_ID.toString() + "&"
        //+ "securitydeposit#" + ((room["payment"]["paymentRequest"] == "" || room["payment"]["paymentRequest"] == null) ? '0' : room["payment"]["paymentRequest"].toString()) + "&"
        //+ "securitycredit#" + "&"  // to be implemented
        //+ "foodticket#"  + "&"  // to be implemented
        //+ "misc#" + "&"    // to be implemented
        //+ "controller#" + pms.EMP_NM + "&"  // to be implemented
        //+ "number#" + room.CONN_RM_TRAN_ID+ "&"
        //+ "transactionnumber#" + room.RM_TRAN_ID + "&");
        plugin().RoomReceiptNumber = room.RM_TRAN_ID.toString(); // – 房单号
        plugin().HotelName = pms.HTL_NM; // -- 酒店名字
        plugin().GuestName =  guest.Name; // – 客人姓名
        plugin().PartnerCompany = ''; // – 协议单位
        plugin().GuestDOB = guest.DOB; // – 客人出生日期
        plugin().GuestIDType = '身份证'; // – 客人证件类型
        plugin().GuestIDCode = guest.SSN.toString(); // – 客人ID卡号信息
        plugin().GuestAddress = guest.Address; // – 客人地址
        plugin().InDate = room.CHECK_IN_DT; // – 到店日期
        plugin().EstimateOutDate = room.CHECK_OT_DT; // – 预离日期
        plugin().MemberNumber = guest.MemberId.toString(); // – 会员卡号
        plugin().RoomType = room.RM_TP; // – 房型
        plugin().RoomPrice = room.finalPrice.toString(); // – 房价
        plugin().RoomNumber = room.RM_ID.toString(); // – 房号
        plugin().SecurityDeposit = ((room["payment"]["paymentRequest"] == "" || room["payment"]["paymentRequest"] == null) ? '0' : room["payment"]["paymentRequest"].toString()); // – 预收押金
        plugin().FoodTicket = ''; // – 餐卷
        plugin().Misc = ''; // – 备注
        plugin().Operator = pms.EMP_NM; // – 操作员
    },
    deposit: function(pms,room,guest){
        return;
        var deposit=0;
        for(var i = 0; i< room.payment.payByMethods.length; i++){
            if(room.payment.payByMethods[i].payMethod == "现金") deposit = deposit + parseFloat(room.payment.payByMethods[i].payAmount);
        }
        plugin().Deposit = deposit.toString(); // – 押金
        plugin().HotelName = pms.HTL_NM; // – 酒店名字
        plugin().Operator = pms.EMP_NM; // – 操作员
        plugin().RoomReceiptNumber = room.RM_TRAN_ID.toString(); // – 房单号
        plugin().GuestName = guest.Name; // – 客人姓名
        plugin().RoomNumber = room.RM_ID.toString(); // – 房单号
    },
    receipt: function(pms,room,guest){
        plugin().HotelName = pms.HTL_NM; // – 酒店名字
        plugin().HotelPhone = '' // – 酒店电话
        plugin().RoomReceiptNumber = room.RM_TRAN_ID.toString(); // – 房单号
        plugin().GuestName = guest.Name; // – 客人姓名
        plugin().RoomType = room.RM_TP; // – 房型
        plugin().RoomNumber = room.RM_ID.toString(); // – 房号
        plugin().RoomPrice = room.finalPrice.toString(); // – 房价
        plugin().MemberNumber = guest.MemberId.toString(); // – 会员卡号
        plugin().PartnerCompany = ''; // – 协议单位
        plugin().InDate = room.CHECK_IN_DT; // – 到店日期
        plugin().OutDate = room.CHECK_IN_DT // – 离店日期
        plugin().Operator = pms.EMP_NM; // – 操作员
    },
    IDcardreader: function(pms,room,guest){
        return;
        var IDcardInfo = {
            CUS_NAME :plugin().GuestName,    //客人姓名
            CUS_GNDR :plugin().GuestGender,  //客人性别
            CUS_ETHNC :plugin().GuestRace,    //客人民族
            DOB :plugin().GuestDOB, //客人出生日期
            ADDRSS :plugin().GuestAddress, //客人地址
            CUS_ATRT :plugin().GuestIDAuthority, //客人发证机关
            CUS_ID_ST_DT :plugin().GuestIDStartDate, //客人ID有效开始日期
            CUS_ID_ED_DT :plugin().GuestIDExpireDate,    //客人ID失效日期
            SSN :plugin().GuestIDCode   //客人ID卡号信息
        }
    }
}

