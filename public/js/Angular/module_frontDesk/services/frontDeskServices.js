/*
Database version


app.factory('resrvFactory',function($http){
    return{
        resvShow: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showReservation'
            })
        }
    }
});

app.factory('roomStatusFactory',function($http){
    return{
        roomShow: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showRoomStatus'
            })
        }
    }
});

*/

/* static enabled version */
app.factory('resrvFactory',function($http){
    return{
        resvShow: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showReservation'
            });
        },

        resvShowStatic: function(){
            return  [{"GUEST_NM":"Baoer","RESVER_CARDNM":"40002032123214","RESVER_PHONE":215480000,"RESVER_NAME":"Charlie Xu","RESV_WAY":"Credit","RESV_TMESTMP":"2014-06-01 21:41:24","CHECK_IN_DT":"2014-06-03","CHECK_OT_DT":"2014-06-07","RM_TP":"Double","RM_QUAN":1,"TREATY_ID":"1111","MEMBER_ID":"9388201","RMRK":"super hot"},{"GUEST_NM":null,"RESVER_CARDNM":"31483125134130","RESVER_PHONE":222432424,"RESVER_NAME":"Ben Shi","RESV_WAY":"Debit","RESV_TMESTMP":"2014-06-04 21:43:55","CHECK_IN_DT":"2014-06-10","CHECK_OT_DT":"2014-06-16","RM_TP":"Single","RM_QUAN":2,"TREATY_ID":"2222","MEMBER_ID":null,"RMRK":"good student"},{"GUEST_NM":"Bill Gates","RESVER_CARDNM":null,"RESVER_PHONE":231434253,"RESVER_NAME":"Bing Li","RESV_WAY":"Cash","RESV_TMESTMP":"2014-06-04 21:45:37","CHECK_IN_DT":"2014-06-10","CHECK_OT_DT":"2014-06-13","RM_TP":"Kingbed","RM_QUAN":1,"TREATY_ID":"0000","MEMBER_ID":"8888888","RMRK":"boxer "}];
        }
    }
});

app.factory('roomStatusFactory',function($http){
    return{
        roomShow: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showRoomStatus'
            })
        },
        roomShowStatic: function(){
            return  [{"RM_ID":1001,"RM_TRAN_ID":1,"RM_CONDITION":"Occupied","RM_TP":"Single","CHECK_IN_DT":"2014-06-13","CHECK_OT_DT":"2014-06-14","RM_AVE_PRCE":128,"DPST_RMN":201,"RSRV_PAID_DYS":2},{"RM_ID":1002,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Double","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1003,"RM_TRAN_ID":9,"RM_CONDITION":"Occupied","RM_TP":"Kingbed","CHECK_IN_DT":"2014-06-12","CHECK_OT_DT":"2014-06-14","RM_AVE_PRCE":177,"DPST_RMN":332,"RSRV_PAID_DYS":0},{"RM_ID":1003,"RM_TRAN_ID":9,"RM_CONDITION":"Occupied","RM_TP":"Kingbed","CHECK_IN_DT":"2014-06-14","CHECK_OT_DT":"2014-06-17","RM_AVE_PRCE":175,"DPST_RMN":310,"RSRV_PAID_DYS":1},{"RM_ID":1004,"RM_TRAN_ID":3,"RM_CONDITION":"Occupied","RM_TP":"Single","CHECK_IN_DT":"2014-06-13","CHECK_OT_DT":null,"RM_AVE_PRCE":112,"DPST_RMN":302,"RSRV_PAID_DYS":null},{"RM_ID":1005,"RM_TRAN_ID":null,"RM_CONDITION":"Preparing","RM_TP":"Kingbed","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1006,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Single Supreme","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1007,"RM_TRAN_ID":null,"RM_CONDITION":"Preparing","RM_TP":"Single","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1008,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Double","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1009,"RM_TRAN_ID":4,"RM_CONDITION":"Occupied","RM_TP":"Double","CHECK_IN_DT":"2014-06-14","CHECK_OT_DT":"2014-06-15","RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":1},{"RM_ID":1101,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Double","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1102,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Double","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1103,"RM_TRAN_ID":5,"RM_CONDITION":"Occupied","RM_TP":"Double","CHECK_IN_DT":"2014-06-12","CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":0},{"RM_ID":1104,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Double","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1105,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Kingbed","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1106,"RM_TRAN_ID":null,"RM_CONDITION":"Preparing","RM_TP":"Kingbed Supreme","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1107,"RM_TRAN_ID":10,"RM_CONDITION":"Occupied","RM_TP":"Double Supreme","CHECK_IN_DT":"2014-06-14","CHECK_OT_DT":"2014-06-17","RM_AVE_PRCE":236,"DPST_RMN":356,"RSRV_PAID_DYS":null},{"RM_ID":1107,"RM_TRAN_ID":10,"RM_CONDITION":"Occupied","RM_TP":"Double Supreme","CHECK_IN_DT":"2014-06-17","CHECK_OT_DT":null,"RM_AVE_PRCE":245,"DPST_RMN":102,"RSRV_PAID_DYS":3},{"RM_ID":1108,"RM_TRAN_ID":null,"RM_CONDITION":"Mending","RM_TP":"Single","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1201,"RM_TRAN_ID":null,"RM_CONDITION":"Preparing","RM_TP":"Single","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1202,"RM_TRAN_ID":7,"RM_CONDITION":"Occupied","RM_TP":"Kingbed Supreme","CHECK_IN_DT":"2014-06-15","CHECK_OT_DT":null,"RM_AVE_PRCE":186,"DPST_RMN":220,"RSRV_PAID_DYS":2},{"RM_ID":1203,"RM_TRAN_ID":null,"RM_CONDITION":"Preparing","RM_TP":"Double","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1204,"RM_TRAN_ID":null,"RM_CONDITION":"Preparing","RM_TP":"Single","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1205,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Kingbed","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1206,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Kingbed Supreme","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1207,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Single Supreme","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1301,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Kingbed","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1302,"RM_TRAN_ID":8,"RM_CONDITION":"Occupied","RM_TP":"Double","CHECK_IN_DT":"2014-06-12","CHECK_OT_DT":"2014-06-15","RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null}]
        }
    };
});

app.factory('customerFactory',function($http){
    return{
        customerShow: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showCustomer'
            });
        },

        customerShowStatic: function(){
            [{"RM_ID":1003,"SSN":"110101199301011765","CUS_NM":"\u5173\u7fbd","CHECK_TP":"\u666e\u901a","MEM_ID":3839900,"MEM_TP":"\u91d1\u5361","PROVNCE":"\u5c71\u4e1c","PHONE":"13651090367","RMRK":"\u9700\u8981\u5b89\u9759","CHECK_IN_DT":"2014-06-12","CHECK_OT_DT":"2014-06-14"},{"RM_ID":1107,"SSN":"110101199301013568","CUS_NM":"\u6cf0\u68ee","CHECK_TP":"\u6563\u5ba2","MEM_ID":null,"MEM_TP":null,"PROVNCE":null,"PHONE":null,"RMRK":null,"CHECK_IN_DT":"2014-06-17","CHECK_OT_DT":null},{"RM_ID":1202,"SSN":"110101199301014560","CUS_NM":"\u8d75\u672c\u5c71","CHECK_TP":"\u94f6\u5361","MEM_ID":2121212,"MEM_TP":"\u94f6\u5361","PROVNCE":"\u65b0\u7586","PHONE":null,"RMRK":null,"CHECK_IN_DT":"2014-06-15","CHECK_OT_DT":null},{"RM_ID":1302,"SSN":"110101199301019281","CUS_NM":"\u8d75\u4e91","CHECK_TP":"\u6563\u5ba2","MEM_ID":null,"MEM_TP":null,"PROVNCE":null,"PHONE":"11231233333","RMRK":null,"CHECK_IN_DT":"2014-06-12","CHECK_OT_DT":"2014-06-15"},{"RM_ID":1001,"SSN":"110101201401012415","CUS_NM":"\u845b\u4f18","CHECK_TP":"\u6563\u5ba2","MEM_ID":null,"MEM_TP":null,"PROVNCE":null,"PHONE":null,"RMRK":null,"CHECK_IN_DT":"2014-06-13","CHECK_OT_DT":"2014-06-14"},{"RM_ID":1202,"SSN":"110101201401013637","CUS_NM":"\u5c0f\u5929","CHECK_TP":"\u94f6\u5361","MEM_ID":1212121,"MEM_TP":"\u666e\u901a","PROVNCE":"\u5e7f\u4e1c","PHONE":null,"RMRK":null,"CHECK_IN_DT":"2014-06-15","CHECK_OT_DT":null},{"RM_ID":1103,"SSN":"110101201401015472","CUS_NM":"\u9a6c\u4e91","CHECK_TP":"\u91d1\u5361","MEM_ID":null,"MEM_TP":null,"PROVNCE":null,"PHONE":null,"RMRK":null,"CHECK_IN_DT":"2014-06-12","CHECK_OT_DT":null},{"RM_ID":1009,"SSN":"110101201401016192","CUS_NM":"\u9f50\u8fbe\u5185","CHECK_TP":"\u7f51\u8ba2","MEM_ID":null,"MEM_TP":null,"PROVNCE":null,"PHONE":null,"RMRK":null,"CHECK_IN_DT":"2014-06-14","CHECK_OT_DT":"2014-06-15"},{"RM_ID":1103,"SSN":"110101201401016993","CUS_NM":"\u5218\u5f3a\u4e1c","CHECK_TP":"\u91d1\u5361","MEM_ID":8888888,"MEM_TP":"\u91d1\u5361","PROVNCE":"\u5317\u4eac","PHONE":"13489271992","RMRK":"\u5e97\u957f\u670b\u53cb","CHECK_IN_DT":"2014-06-12","CHECK_OT_DT":null},{"RM_ID":1004,"SSN":"110101201401017275","CUS_NM":"\u5b89\u5409\u4e3d\u5a1c\u6731\u8389","CHECK_TP":"\u534f\u8bae","MEM_ID":null,"MEM_TP":null,"PROVNCE":null,"PHONE":null,"RMRK":null,"CHECK_IN_DT":"2014-06-13","CHECK_OT_DT":null},{"RM_ID":1009,"SSN":"110101201401017670","CUS_NM":"\u5185\u9a6c\u5c14","CHECK_TP":"\u7f51\u8ba2","MEM_ID":9103324,"MEM_TP":"\u666e\u901a","PROVNCE":"\u5df4\u897f","PHONE":null,"RMRK":null,"CHECK_IN_DT":"2014-06-14","CHECK_OT_DT":"2014-06-15"}];        },

        memberShow: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showMember'
            });
        },
        memberShowStatic: function(){
            return [{"MEM_ID":1212121,"SSN":"110101201401013637","MEM_NM":"\u5c0f\u5929","MEM_GEN":"1212121","MEM_DOB":"2014-01-01","PROV":"\u5e7f\u4e1c","CITY":"\u4e1c\u839e","ADDRS":"\u8427\u5c71\u533a\u540e\u5ead\u82b13\u697c1\u53f7","PHONE":"","IN_DT":"2014-06-10","EMAIL":null,"TIMES":1,"POINTS":81,"MEM_TP":"\u666e\u901a"},{"MEM_ID":1239901,"SSN":"110101201401017232","MEM_NM":"\u5468\u6770\u4f26","MEM_GEN":"male","MEM_DOB":"2014-01-01","PROV":"\u6e56\u5317","CITY":"\u6b66\u6c49","ADDRS":"\u6c49\u53e3\u533a\u6f02\u79fb\u5730\u76d8\u513f2\u53f7","PHONE":"","IN_DT":"2014-01-01","EMAIL":"dierpiao@gmail.com","TIMES":3,"POINTS":125,"MEM_TP":"\u666e\u901a"},{"MEM_ID":2121212,"SSN":"110101199301014560","MEM_NM":"\u8d75\u672c\u5c71","MEM_GEN":"female","MEM_DOB":"1993-01-01","PROV":"\u65b0\u7586","CITY":"\u5580\u4ec0","ADDRS":"\u963f\u5c14\u5df4\u4ee5\u4e61\uff0c\u697c\u540c\u6d77\u5b50 1233","PHONE":"13789022121","IN_DT":"2014-05-05","EMAIL":"zhaobenshan@gmail.com","TIMES":34,"POINTS":9222,"MEM_TP":"\u94f6\u5361"},{"MEM_ID":3124753,"SSN":"110108199006136017","MEM_NM":"\u5f90\u4e7e\u5e9a","MEM_GEN":"male","MEM_DOB":"1990-06-13","PROV":"\u5317\u4eac","CITY":"\u5317\u4eac","ADDRS":"\u671d\u9633\u533a\u5916\u4f20\u5927\u53a6","PHONE":"651090367","IN_DT":"2004-12-06","EMAIL":"qiangeng@126.com","TIMES":33,"POINTS":9010,"MEM_TP":"\u91d1\u5361"},{"MEM_ID":3839900,"SSN":"110101199301011765","MEM_NM":"\u5173\u7fbd","MEM_GEN":"female","MEM_DOB":"1993-01-01","PROV":"\u5c71\u4e1c","CITY":"\u70df\u53f0","ADDRS":"\u83b1\u5dde\u5e02\uff0c\u5f90\u5218\u6751\uff0c204","PHONE":"13651090367","IN_DT":"2014-06-01","EMAIL":"guanyu@gmail.com","TIMES":2,"POINTS":54,"MEM_TP":"\u91d1\u5361"},{"MEM_ID":8888888,"SSN":"110101201401016993","MEM_NM":"\u5218\u5f3a\u4e1c","MEM_GEN":"male","MEM_DOB":"2014-01-01","PROV":"\u5317\u4eac","CITY":"\u5317\u4eac","ADDRS":"\u540e\u6d77\u5927\u524d\u95e8\u8857\u89d2\u8001\u5218\u5bb6","PHONE":"13489271992","IN_DT":"2014-04-23","EMAIL":"","TIMES":9,"POINTS":833,"MEM_TP":"\u91d1\u5361"},{"MEM_ID":9103324,"SSN":"BZ832045324","MEM_NM":"\u5185\u9a6c\u5c14","MEM_GEN":"male","MEM_DOB":null,"PROV":"\u5df4\u897f","CITY":"\u91cc\u7ea6","ADDRS":"\u63a5\u5934\u5404\u5904","PHONE":"+743389623423","IN_DT":"2001-06-25","EMAIL":null,"TIMES":5,"POINTS":287,"MEM_TP":"\u666e\u901a"},{"MEM_ID":9291213,"SSN":"110101201401014859","MEM_NM":"\u5b8b\u5e86\u9f84","MEM_GEN":"male","MEM_DOB":"2014-01-01","PROV":"\u6c5f\u82cf","CITY":"\u65e0\u9521","ADDRS":"\u65e0\u6b32\u533a\u7ea2\u697c23\u53f7","PHONE":"18670002399","IN_DT":"2014-06-09","EMAIL":"qingling@gmail.com","TIMES":5,"POINTS":433,"MEM_TP":"\u666e\u901a"}];
        }
    }
 });

app.factory('accountingFactory',function($http){
    return{
        accountingGetAll: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'accountingGetAll'
            })
        },
        summerize:function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'summerize'
            })
        }

    };
});







app.factory('merchandiseFactory',function($http){
    return{
        productShow: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showProduct'
            })
        },
        merchanRoomShow: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showMerchanRoom'
            })
        },
        buySubmit: function(buyInfo){
            return $http({
                method: 'POST',
                heasders: {'content-Type':'application/json'},
                url: 'buySubmit',
                data: buyInfo
            });
        },
        histoPurchaseShow: function(){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showHistoPurchase'
            })
        },
        histoProductShow: function(STR_TRAN_ID){
            return $http({
                method: 'GET',
                heasders: {'content-Type':'application/json'},
                url: 'showHistoProduct/'+STR_TRAN_ID
            })
        },

        roomShowStatic: function(){
            return  [{"RM_ID":1001,"RM_TRAN_ID":1,"RM_CONDITION":"Occupied","RM_TP":"Single","CHECK_IN_DT":"2014-06-13","CHECK_OT_DT":"2014-06-14","RM_AVE_PRCE":128,"DPST_RMN":201,"RSRV_PAID_DYS":2},{"RM_ID":1002,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Double","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1003,"RM_TRAN_ID":9,"RM_CONDITION":"Occupied","RM_TP":"Kingbed","CHECK_IN_DT":"2014-06-12","CHECK_OT_DT":"2014-06-14","RM_AVE_PRCE":177,"DPST_RMN":332,"RSRV_PAID_DYS":0},{"RM_ID":1003,"RM_TRAN_ID":9,"RM_CONDITION":"Occupied","RM_TP":"Kingbed","CHECK_IN_DT":"2014-06-14","CHECK_OT_DT":"2014-06-17","RM_AVE_PRCE":175,"DPST_RMN":310,"RSRV_PAID_DYS":1},{"RM_ID":1004,"RM_TRAN_ID":3,"RM_CONDITION":"Occupied","RM_TP":"Single","CHECK_IN_DT":"2014-06-13","CHECK_OT_DT":null,"RM_AVE_PRCE":112,"DPST_RMN":302,"RSRV_PAID_DYS":null},{"RM_ID":1005,"RM_TRAN_ID":null,"RM_CONDITION":"Preparing","RM_TP":"Kingbed","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1006,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Single Supreme","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1007,"RM_TRAN_ID":null,"RM_CONDITION":"Preparing","RM_TP":"Single","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1008,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Double","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1009,"RM_TRAN_ID":4,"RM_CONDITION":"Occupied","RM_TP":"Double","CHECK_IN_DT":"2014-06-14","CHECK_OT_DT":"2014-06-15","RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":1},{"RM_ID":1101,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Double","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1102,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Double","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1103,"RM_TRAN_ID":5,"RM_CONDITION":"Occupied","RM_TP":"Double","CHECK_IN_DT":"2014-06-12","CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":0},{"RM_ID":1104,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Double","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1105,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Kingbed","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1106,"RM_TRAN_ID":null,"RM_CONDITION":"Preparing","RM_TP":"Kingbed Supreme","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1107,"RM_TRAN_ID":10,"RM_CONDITION":"Occupied","RM_TP":"Double Supreme","CHECK_IN_DT":"2014-06-14","CHECK_OT_DT":"2014-06-17","RM_AVE_PRCE":236,"DPST_RMN":356,"RSRV_PAID_DYS":null},{"RM_ID":1107,"RM_TRAN_ID":10,"RM_CONDITION":"Occupied","RM_TP":"Double Supreme","CHECK_IN_DT":"2014-06-17","CHECK_OT_DT":null,"RM_AVE_PRCE":245,"DPST_RMN":102,"RSRV_PAID_DYS":3},{"RM_ID":1108,"RM_TRAN_ID":null,"RM_CONDITION":"Mending","RM_TP":"Single","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1201,"RM_TRAN_ID":null,"RM_CONDITION":"Preparing","RM_TP":"Single","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1202,"RM_TRAN_ID":7,"RM_CONDITION":"Occupied","RM_TP":"Kingbed Supreme","CHECK_IN_DT":"2014-06-15","CHECK_OT_DT":null,"RM_AVE_PRCE":186,"DPST_RMN":220,"RSRV_PAID_DYS":2},{"RM_ID":1203,"RM_TRAN_ID":null,"RM_CONDITION":"Preparing","RM_TP":"Double","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1204,"RM_TRAN_ID":null,"RM_CONDITION":"Preparing","RM_TP":"Single","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1205,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Kingbed","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1206,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Kingbed Supreme","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1207,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Single Supreme","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1301,"RM_TRAN_ID":null,"RM_CONDITION":"Empty","RM_TP":"Kingbed","CHECK_IN_DT":null,"CHECK_OT_DT":null,"RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null},{"RM_ID":1302,"RM_TRAN_ID":8,"RM_CONDITION":"Occupied","RM_TP":"Double","CHECK_IN_DT":"2014-06-12","CHECK_OT_DT":"2014-06-15","RM_AVE_PRCE":null,"DPST_RMN":null,"RSRV_PAID_DYS":null}]
        }


    };
});
