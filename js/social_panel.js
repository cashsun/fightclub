$(document).ready(function(){
    showSocial = false;
    $('#social_tabs').tabs();
    $("#friends_radios").buttonset().change(function(){alert($('label[aria-pressed=true]',this).attr('for'))});
    $('#input_friend').tipsy({fallback:'ENTER to search',gravity:'s',fade:false,offset:0});
    $('#friends_button').click(function(){  
    if(!showSocial){
        $('#panel_social').animate({right: 0},300,function(){
            getFriends();
        });
    }else{
            var width = $('#panel_social').width();
            $('#panel_social').animate({right: -width-4},300);
            showSocial = false; 
    }
});
});

function getFriends(){
        $('#friends_wrapper').hide(0,function(){
            makeSocialAjaxCall('get','view/friends.php',{ftype:0},function(resp){
                $('#friends_wrapper').html(resp).fadeIn(200);
                },function(){showSocial = true});
        });
}
function makeSocialAjaxCall(type,url,param,successCallback,callback){
    $('#social_loading').show(0);
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
            $('#friends_wrapper').html('server error. Try again later.')
        },
        complete:function(){
            if(callback != null){callback();}
            else{
                location.reload();
            }
            $('#social_loading').hide(0);
        }
    });
}
