function getFriendsAjaxCall(){
    jsonAjaxRequest('post',
            "service/web/webactions.php",
            {uid:function(){return $('#uid').html()},
            webaction: 6},
            function(r){
              var friend_place_holder = $('#panel_social');
              var htmlstr = "<ul>";
              if(r.length == 0){
                
                htmlstr += '<div class="message">You do not have any friend now.</div>';
              }
              for(var i = 0; i < r.length; i++)
              {
                htmlstr += '<li><table><tr>'+'<td class="social-list-avatar" cellpadding="0" border="0" rowspan="2">'+
                              '<img src="image/profile.png"/></td>'+
                              '<td class="social-list-username">'+r[i].username+
                              '</td></tr><tr><td class="social-list-fullname">'+r[i].firstname+' '+r[i].lastname+'</td></tr></table></li>';
              }
              htmlstr += "</ul>";
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