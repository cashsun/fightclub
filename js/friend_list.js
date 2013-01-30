$(document).ready(function(){
    initList();
    initBtns();
    $('.f_toggle').toggleSwitch({
			highlight: false,
			width: 25,
                        callback:function(i){
                            switch(i){
                                case 0:$('#f_group_wrapper').hide(0,function(){
                                            socialLoading.show(0,function(){
                                                makeAjaxCall('get',{
                                                fuid:function(){return $('#f_opt').parent().attr('fuid');},
                                                webaction:14},function(){socialLoading.hide(0,function(){
                                                    checkIfEmpty();
                                                    initList();    
                                                });
                                                },function(r){
                                                            if(r==-1){
                                                                $('#f_group_wrapper').html('oops, try again later.')}
                                                            else{
                                                                $('#f_group_wrapper').html(r).fadeIn(200);
                                                            }
                                                            showSocial = true;
                                                });
                                            }); 
                                        });break;
                                case 1:var fuid = $('#f_opt').parent().attr('fuid');
                                        $('#f_group_wrapper').hide(0,function(){
                                            makeSocialAjaxCall('get','view/f_friends.php',{
                                                fuid:fuid,
                                                ftype:0},
                                            function(resp){
                                                $('#f_group_wrapper').html(resp).fadeIn(200);
                                                },function(){});
                                        });
                                    break;
                            }
                        }
    });
});
function checkIfEmpty(){
    if($('#f_group_wrapper').html()==''){
        $('#f_group_wrapper').html('No task shared by this user.');
        return true;
    }
    return false;
}
var comtBtns;
function initList(){
    comtBtns = $('.comment_btn');
    comtBtns.click(function(){
        comtBtns.removeClass('comment_btn_c')
        var target = $(this);
        commentMain.slideUp(200,function(){
            var ctid = target.attr('tid');
            if(ctid!=commentMain.attr('tid')){
                target.addClass('comment_btn_c');
                commentMain.attr('tid',ctid);
                target.parent().after(commentMain);
                loading_image.show(0,function(){
                    getComments(ctid,0,commentMain.find('#comment_dialog'),
                    function(){
                        loading_image.hide(0,function(){
                            commentMain.slideDown(200,function(){
                                $('.friend_image_s').click(function(){
                                    var fuid = $(this).attr('uid');
                                    getUserLists(fuid);
                                });
                                var myuid = $('#uid','#cache').html();
                                $('.friend_image_s[uid='+myuid+']').unbind('click');
                            });
                        });
                    });
                })
                
            }else{
                commentMain.attr('tid',-1);
            }
        });
    });
    $('.f_task_text').click(function(){});
    $('.fighto').click(function(){
        var tid = $(this).attr('tid');
        if(!$(this).hasClass('liked')){
            var oriexp = $(this).siblings('.f_task_texp').html();
            $(this).siblings('.f_task_texp').html(parseInt(oriexp)+1);
            makeAjaxCall('post',{
            uid:function(){return $('#uid').html();},
            tid:tid,
            webaction:12},function(){},function(r){if(r==-1){alert('you have already FIGHTOed this task.')}});
        }
        if ($.browser.webkit) {
            $(this).addClass('liked').animate({zoom:'150%',top:'-5px',right:'-5px'},0,function(){
                $(this).animate({zoom:'100%',top:'0px',right:'0px'},400);
            });
        }else{
            $(this).addClass('liked').animate({top:'-4px',right:'4px'},0,function(){
                $(this).animate({top:'0px',right:'0px'},400);
            });
        }
    }).tipsy({fallback:'FIGHTO!',gravity:'s',fade:false,offset:0});

    $('.f_group').tipsy({gravity:'s',fade:false,offset:0});
}


