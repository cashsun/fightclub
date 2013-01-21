$(document).ready(function(){
    $('#friends_button').toggle(function(){
    $('#panel_social').animate({right: 0},400,function(){
        $.ajax({
            url:'view/friends.php',
            timeout:6000,
            success:function(response){
                if(response==-1){
                    location.reload();
                }
                $('#tabs-1').html(response);
            },
            error:function(){
                
            }
        });
    });
    },function(){
        var width = $('#panel_social').width();
        $('#panel_social').animate({right: -width-4},400);
    });
});