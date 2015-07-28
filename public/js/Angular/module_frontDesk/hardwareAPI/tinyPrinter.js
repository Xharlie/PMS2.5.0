/**
 * Created by charlie on 7/5/15.
 */

/************** ********************************** printer  ********************************** *************/

var printer = {
    toPrintable: function(variable){
        return (variable == null)?'':variable.toString();
    },
    tranPush2printer: function(key,ac,printerRCtransactions){
        switch(key){
            case 'AcctDepo':
                printerRCtransactions.push(
                    {detailUnit:ac.PAY_METHOD+ac.SUB_CAT+'押金',detailCost:'0.0',detailPay:util.Limit(ac.DEPO_AMNT),detailDate:ac.DEPO_TSTMP}
                );
                break;
            case 'AcctPay':
                printerRCtransactions.push(
                    {detailUnit:ac.SUB_CAT+'房费',detailCost:util.Limit(ac.RM_PAY_AMNT),detailPay:'0.0',detailDate:ac.BILL_TSTMP}
                );
                break;
            case 'AcctPenalty':
                printerRCtransactions.push(
                    {detailUnit:'赔偿费',detailCost:util.Limit(ac.PNLTY_PAY_AMNT),detailPay:'0.0',detailDate:ac.BILL_TSTMP}
                );
                break;
            case 'AcctStore':
                printerRCtransactions.push(
                    {detailUnit:ac.PROD_NM+'X'+ac.PROD_QUAN,detailCost:util.Limit(ac.STR_PAY_AMNT),detailPay:'0.0',detailDate:ac.STR_TRAN_TSTMP}
                );
                break;
            case 'merchant':
                printerRCtransactions.push(
                    {detailUnit:ac.showUp,detailCost:util.Limit(ac.PAY_AMNT),detailPay:'0.0',detailDate:ac.TSTMP}
                );
                break;
            case 'penalty':
                printerRCtransactions.push(
                    {detailUnit:ac.showUp,detailCost:util.Limit(ac.PAY_AMNT),detailPay:'0.0',detailDate:ac.TSTMP}
                );
                break;
            case 'newAcct':
                printerRCtransactions.push(
                    {detailUnit:ac.showUp,detailCost:util.Limit(ac.PAY_AMNT),detailPay:'0.0',detailDate:ac.TSTMP}
                );
                break;
        }
    },
    checkIn: function (pms, room, guest) {
        try {
            plugin().RoomReceiptNumber = this.toPrintable(room.RM_TRAN_ID); // – 房单号
            plugin().HotelName = pms.HTL_NM; // -- 酒店名字
            plugin().GuestName = guest.Name; // – 客人姓名
            plugin().PartnerCompany = ''; // – 协议单位
            plugin().GuestDOB = guest.DOB; // – 客人出生日期
            plugin().GuestIDType = '身份证'; // – 客人证件类型
            plugin().GuestIDCode = this.toPrintable(guest.SSN); // – 客人ID卡号信息
            plugin().GuestAddress = guest.Address; // – 客人地址
            plugin().InDate = room.CHECK_IN_DT; // – 到店日期
            plugin().EstimateOutDate = room.CHECK_OT_DT; // – 预离日期
            plugin().MemberNumber = this.toPrintable(guest.MemberId); // – 会员卡号
            plugin().RoomType = room.RM_TP; // – 房型
            plugin().RoomPrice = this.toPrintable(room.finalPrice); // – 房价
            plugin().RoomNumber = this.toPrintable(room.RM_ID); // – 房号
            plugin().SecurityDeposit = ((room["payment"]["paymentRequest"] == "" || room["payment"]["paymentRequest"] == null) ? '0' : room["payment"]["paymentRequest"].toString()); // – 预收押金
            plugin().FoodTicket = ''; // – 餐卷
            plugin().Misc = ''; // – 备注
            plugin().Operator = pms.EMP_NM; // – 操作员
            plugin().updateInfo();
            plugin().printMoveInCheck();
        } catch (err) {
            console.log(err.message);
        }
    },
    deposit: function (pms, room, guest) {
        try {
            var deposit = 0;
            for (var i = 0; i < room.payment.payByMethods.length; i++) {
                if (room.payment.payByMethods[i].payMethod == "现金") deposit = deposit + parseFloat(room.payment.payByMethods[i].payAmount);
            }
            plugin().Deposit = this.toPrintable(deposit); // – 押金
            plugin().HotelName = pms.HTL_NM; // – 酒店名字
            plugin().Operator = pms.EMP_NM; // – 操作员
            plugin().RoomReceiptNumber = this.toPrintable(room.RM_TRAN_ID); // – 房单号
            plugin().GuestName = guest.Name; // – 客人姓名
            plugin().RoomNumber = this.toPrintable(room.RM_ID); // – 房单号

            plugin().updateInfo();
            plugin().printDepositCheck();
        } catch (err) {
            console.log(err.message);
        }
    },
    receipt: function (pms, room, guest,printerRCtransactions) {
        try {
            plugin().HotelName = pms.HTL_NM; // – 酒店名字
            plugin().HotelPhone = '' // – 酒店电话
            plugin().RoomReceiptNumber = this.toPrintable(room.RM_TRAN_ID); // – 房单号
            plugin().GuestName = guest.Name; // – 客人姓名
            plugin().RoomType = room.RM_TP; // – 房型
            plugin().RoomNumber = this.toPrintable(room.RM_ID); // – 房号
            plugin().RoomPrice = this.toPrintable(room.RM_AVE_PRCE); // – 房价
            plugin().MemberNumber = this.toPrintable(guest.MEM_ID); // – 会员卡号
            plugin().PartnerCompany = ''; // – 协议单位
            plugin().InDate = room.CHECK_IN_DT; // – 到店日期
            plugin().OutDate = room.CHECK_OT_DT; // – 离店日期
            plugin().Operator = pms.EMP_NM; // – 操作员
            var detailUnit ='';
            var detailCost ='';
            var detailPay ='';
            var detailDate ='';
            structure.sortByProperties(printerRCtransactions,'detailDate');
            for(var i =0 ;i < printerRCtransactions.length; i++){
                detailUnit = detailUnit + this.toPrintable(printerRCtransactions[i].detailUnit) + ',';
                detailCost = detailCost + this.toPrintable(printerRCtransactions[i].detailCost) + ',';
                detailPay = detailPay + this.toPrintable(printerRCtransactions[i].detailPay) + ',';
                detailDate = detailDate + this.toPrintable(printerRCtransactions[i].detailDate).substring(0,10)+ ',';
            }
            plugin().detailUnit = detailUnit;
            plugin().detailCost = detailCost;
            plugin().detailPay = detailPay;
            plugin().detailDate = detailDate;
            plugin().updateInfo();
            plugin().printDetailCheck();
        } catch (err) {
            console.log(err.message);
        }
    },
    IDcardreader: function () {
        try {
            plugin().readIDCard();
            var IDcardInfo = {
                CUS_NAME: plugin().GuestName,    //客人姓名
                CUS_GNDR: plugin().GuestGender,  //客人性别
                CUS_ETHNC: plugin().GuestRace,    //客人民族
                DOB: plugin().GuestDOB, //客人出生日期
                ADDRSS: plugin().GuestAddress, //客人地址
                CUS_ATRT: plugin().GuestIDAuthority, //客人发证机关
                CUS_ID_ST_DT: plugin().GuestIDStartDate, //客人ID有效开始日期
                CUS_ID_ED_DT: plugin().GuestIDExpireDate,    //客人ID失效日期
                SSN: plugin().GuestIDCode   //客人ID卡号信息
            }
            return IDcardInfo;
        } catch (err) {
            console.log(err.message);
        }
    }
};

