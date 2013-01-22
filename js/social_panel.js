$(document).ready(function(){
    $('#social_tabs').tabs();
    $('#friends_button').toggle(function(){
    $('#panel_social').animate({right: 0},300,function(){
        //ftype=0,friends only ||ftype=1, people I follow
        makeSocialAjaxCall('get','view/friends.php',{ftype:0},function(resp){
            $('#tabs-1').html(resp);
        },function(){});
    });
    },function(){
        var width = $('#panel_social').width();
        $('#panel_social').animate({right: -width-4},300);
    });
});

function makeSocialAjaxCall(type,url,param,successCallback,callback){
    loading_image.show(0);
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
            alert(3);
        },
        complete:function(){
            if(callback != null){callback();}
            else{
                alert(2)
//                location.reload();
            }
            loading_image.hide(0);
        }
    });
}
