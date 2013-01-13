$(document).ready(function(){
    $('#friends_button').toggle(function(){
    $('#panel_social').animate({right: 0},400,function(){
        //load friends
    });
    },function(){
        var width = $('#panel_social').width();
        $('#panel_social').animate({right: -width},400);
    });
});