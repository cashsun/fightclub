function getFriendsAjaxCall(){
    jsonAjaxRequest('post',
            "service/web/webactions.php",
            {uid:function(){return $('#uid').html()},
            webaction: 6},
            function(r){
              var friend_place_holder = $('#tabs-1','#social_tabs');
              var htmlstr = "";
              if(r.length == 0){
                
                htmlstr += '<div class="message">You do not have any friend now.</div>';
              }
              for(var i = 0; i < r.length; i++)
              {
                htmlstr +=  '<img src="image/profile.png"/></td>'+r[i].username+r[i].firstname+' '+r[i].lastname;
              }
              friend_place_holder.html(htmlstr);
            });
}

$(document).ready(function(){
    $('#friends_button').toggle(function(){
    $('#panel_social').animate({right: 0},400,function(){
        getFriendsAjaxCall();
        //load friends
    });
    },function(){
        var width = $('#panel_social').width();
        $('#panel_social').animate({right: -width-4},400);
    });
});