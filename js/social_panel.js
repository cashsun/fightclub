$(document).ready(function(){
    $('#social_tabs').tabs();
    $("#friends_radios").buttonset();
    $('#input_friend').tipsy({fallback:'ENTER to search',gravity:'s',fade:false,offset:0});
    $('#friends_button').toggle(function(){
    $('#panel_social').animate({right: 0},300,function(){
        //ftype=0,friends only ||ftype=1, search
        makeSocialAjaxCall('get','view/friends.php',{ftype:0},function(resp){
            $('#friends_wrapper').html(resp);
        },function(){});
    });
    },function(){
        var width = $('#panel_social').width();
        $('#panel_social').animate({right: -width-4},300);
    });
});

function makeSocialAjaxCall(type,url,param,successCallback,callback){
    social_loading.show(0);
    $.ajax({
        url:url,
        timeout:6000,
        type:type,
        data:param,
        success:function(response){
            if(response==-1){
                location.reload();
            }else if(successCallback!=null){
                successCallback(response);
            }
        },
        error:function(){
        },
        complete:function(){
            if(callback != null){callback();}
            else{
                location.reload();
            }
            social_loading.hide(0);
        }
    });
}
