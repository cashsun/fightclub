$(document).ready(function(){
    $('.f_task_text').click(function(){
        alert($(this).text());
    });
    $('.fighto').click(function(){
        var tid = $(this).attr('tid');
        makeAjaxCall('post',{
        uid:function(){return $('#uid').html();},
        tid:tid,
        webaction:12},function(){});
        if ($.browser.webkit) {
            $(this).addClass('liked').animate({zoom:'150%',top:'-5px',right:'-5px'},0,function(){
                $(this).animate({zoom:'100%',top:'0px',right:'0px'},400);
            });
        }else{
            $(this).addClass('liked').animate({top:'-4px',right:'4px'},0,function(){
                $(this).animate({top:'0px',right:'0px'},400);
            });
        }
        
    });
    if($('#f_group_wrapper').html()==''){
        $('#f_group_wrapper').html('No task shared by this user.');
    }
    $('.f_group .f_task_text').tipsy({gravity:'s',fade:false,offset:0});
    
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
 
});


