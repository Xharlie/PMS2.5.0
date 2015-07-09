/**
 * Created by charlie on 7/8/15.
 */

app.factory('roomFactory',function($http){

})



var createNewRoom = function(){
    var newRoom =  {RM_TP:"", RM_ID:"",finalPrice:"",SUGG_PRICE:"",discount:"",deposit:"",MasterRoom:'fasle',
        GuestsInfo:[createNewGuest()],payment:paymentFactory.createNewPayment('住房押金'), check:true};
    return newRoom;
}

var createNewGuest = function(){
    var newGuest =  {Name:"",MemberId:"",Phone:"",SSN:"",SSNType:"二代身份证",DOB:"",Address:"",MEM_TP:"",Points:"",RemarkInput:"",TIMES:""};
    return newGuest;
}



var createBookRoomByTP = function(singleRoom){
    if (singleRoom.RM_TP in $scope.BookRoomByTP){
        $scope.BookRoomByTP[singleRoom.RM_TP].rooms.push(singleRoom);
        $scope.BookRoomByTP[singleRoom.RM_TP].roomAmount++;
    }else{
        $scope.BookRoomByTP[singleRoom.RM_TP] = {SUGG_PRICE:singleRoom.SUGG_PRICE,discount:singleRoom.discount,finalPrice:singleRoom.finalPrice,rooms:[singleRoom],roomAmount:1};
    }
}

function createNewRoom(){
    var newRoom =  {RM_TP:"", RM_ID:"",finalPrice:"",SUGG_PRICE:"",discount:"",deposit:"",MasterRoom:'fasle',
        GuestsInfo:[createNewGuest()],payment:paymentFactory.createNewPayment('住房押金'), check:true};
    return newRoom;
}

var createNewGuest = function(){
    var newGuest =  {Name:"",MemberId:"",Phone:"",SSN:"",SSNType:"二代身份证",DOB:"",Address:"",MEM_TP:"",Points:"",RemarkInput:"",TIMES:""};
    return newGuest;
}
