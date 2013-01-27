$(document).ready(function(){
    initBtns();
    $('.friend_image').click(function(){
        var fuid = $(this).attr('uid');
        getUserLists(fuid);
    });
});
