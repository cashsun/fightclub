function getFriendsAjaxCall(){
    makeAjaxCall('get',
            "service/web/getFriends.php",
            {uid:function(){return $('#uid').html()}},
            function(r){
              $friend_place_holder = $('#panel_social');
              //for(var i = 0; i < r.length; i++)
              alert(r);
            });
}

$(document).ready(function(){
    $('#friends_button').toggle(function(){
    $('#panel_social').animate({right: 0},400,function(){
        //getFriendsAjaxCall();
        //load friends
    });
    },function(){
        var width = $('#panel_social').width();
        $('#panel_social').animate({right: -width},400);
    });
});