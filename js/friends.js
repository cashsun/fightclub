$(document).ready(function(){
    $('.add_friend').button().click(function(){
        var uid = $('#uid').html();
        var fuid = $(this).attr('uid');
        makeAjaxCall('post',{uid:uid,fuid:fuid,webaction:9},function(){},function(r){
            if(r==-1){
                alert('You are already friends.')
            }
            $('#radio0').click();
        });
    });
    $('.unfollow_friend').button().click(function(){
        var uid = $('#uid').html();
        var fuid = $(this).attr('uid');
        if(confirm ("Unfollow this user?")){
            makeAjaxCall('post',{uid:uid,fuid:fuid,webaction:10},function(){},function(r){
                if(r==-1||r==0){
                    alert('You are not following this user.')
                }
                $('#radio0').click();
            });
        }
    });
    $('.friend_image').click(function(){
        var fuid = $(this).attr('uid');
        getUserLists(fuid);
    });
});
