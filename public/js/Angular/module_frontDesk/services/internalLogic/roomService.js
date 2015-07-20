/**
 * Created by charlie on 7/8/15.
 */

app.factory('roomFactory',function($http,paymentFactory){
    return{
        createNewRoom : function(payMethodStr) {
            var newRoom =  {RM_TP:"", RM_ID:"",finalPrice:"",SUGG_PRICE:"",discount:"",deposit:"",MasterRoom:'fasle',
                GuestsInfo:[this.createNewGuest()],payment:paymentFactory.createNewPayment(payMethodStr), check:true};
            return newRoom;
        },
        createNewGuest : function(){
            var newGuest =  {Name:"",MemberId:"",Phone:"",SSN:"",SSNType:"二代身份证",DOB:"",Address:"",MEM_TP:"",Points:"",RemarkInput:"",TIMES:""};
            return newGuest;
        },
        createBookRoom : function(BookRoom,len,payMethodStr){
            for (var i=0; i<len; i++){
                BookRoom.push(this.createNewRoom(payMethodStr));
            }
        },
        createBookRoomByTP : function(singleRoom,BookRoomByTP){
            if (singleRoom.RM_TP in BookRoomByTP){
                BookRoomByTP[singleRoom.RM_TP].rooms.push(singleRoom);
                BookRoomByTP[singleRoom.RM_TP].roomAmount++;
            }else{
                BookRoomByTP[singleRoom.RM_TP] = {SUGG_PRICE:singleRoom.SUGG_PRICE,discount:singleRoom.discount,resvPrice:singleRoom.resvPrice
                    ,finalPrice:singleRoom.finalPrice,rooms:[singleRoom],roomAmount:1};
                if (singleRoom.AVAIL_QUAN!=null){
                    BookRoomByTP[singleRoom.RM_TP]['AVAIL_QUAN'] = singleRoom.AVAIL_QUAN;
                }
            }
        }
    }
});


//$scope.syncSingleRoomTP = function(TP,singleTP){
//    for(var i =0; i< singleTP.rooms.length; i++){
//        var room = singleTP.rooms[i];
//        room.RM_TP = TP;
//        room.RM_ID = "";
//        room.discount = singleTP.discount;
//        room.SUGG_PRICE = singleTP.SUGG_PRICE;
//        room.finalPrice = singleTP.finalPrice;
//    }
//}
