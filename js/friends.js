var btnImgHtml = '<img src="image/delfriend.png" alt=""/>';
var addBtns;
var delBtns;
$(document).ready(function(){
    initBtns();
    $('.friend_image').click(function(){
        var fuid = $(this).attr('uid');
        getUserLists(fuid);
    });
});
function initBtns(){
    addBtns = $('.add_friend');
    delBtns = $('.unfollow_friend');
    addBtns.unbind('click');
    delBtns.unbind('click');
    addBtns.button().click(function(){
        btnImgHtml = '<img src="image/delfriend.png" alt=""/>';
        $(this).removeClass('add_friend').addClass('unfollow_friend').children().html(btnImgHtml);
        var fuid = $(this).attr('uid');
        addFriend(fuid);
    });
    delBtns.button().click(function(){
        if(confirm ("Unfollow this user?")){
        btnImgHtml = '<img src="image/addfriend.png" alt=""/>';
        $(this).removeClass('unfollow_friend').addClass('add_friend').children().html(btnImgHtml);
        var fuid = $(this).attr('uid');
        unfollowFriend(fuid);
        }
    });
    showSocial = true;
}
function addFriend(fuid){
    makeAjaxCall('post',{fuid:fuid,webaction:9},function(){initBtns()},function(r){
            if(r==-1){
                alert('You are already friends.');
            }
    });
}
function unfollowFriend(fuid){ 
            makeAjaxCall('post',{fuid:fuid,webaction:10},function(){initBtns()},function(r){
                if(r==-1||r==0){
                    alert('You are not following this user.');
                }
            });
}
