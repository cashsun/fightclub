$(document).ready(function(){
    initNewsList();
    $('.friend_image_s,.friend_image_ss').click(function(){
        var fuid = $(this).attr('uid');
        getUserLists(fuid,function(){
            $('#social_tabs').tabs('option','active',0);
        });
    });
});
function initNewsList(){
    comtBtns = $('.comment_btn','#news_wrapper');
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
                                    getUserLists(fuid,function(){
                                        $('#social_tabs').tabs('option','active',0);
                                    });
                                });
                            });
                        });
                    });
                })
                
            }else{
                commentMain.attr('tid',-1);
            }
        });
    });
    $('.f_task_texp').click(function(){
        var tid = $(this).attr("tid");
        var target = $(this).parent();
        getFightoList(tid,target,function(){
            $('.friend_image_ss',fightolist).click(function(){
                var fuid = $(this).attr("uid");
                getUserLists(fuid,function(){
                    $('#social_tabs').tabs('option','active',0);
                });
            });
        })
    });
    
    $('.f_task_text').click(function(){});
    $('.fighto').click(function(){
        var tid = $(this).attr('tid');
        var fightoBtn = $(this);
        if(!$(this).hasClass('liked')){
            var oriexp = $(this).siblings('.f_task_texp').html();
            $(this).siblings('.f_task_texp').html(parseInt(oriexp)+1);
            makeAjaxCall('post',{
            uid:function(){return $('#uid').html();},
            tid:tid,
            webaction:12},function(){},function(r){if(r==-1){alert('you have already FIGHTOed this task.')}});
        }
        fightoBtn.siblings(".f_task_texp").click();
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
}

