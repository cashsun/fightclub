$(document).ready(function(){
    checkIfEmpty();
    $('.f_task_text').click(function(){
        alert($(this).text());
    });
    $('.f_toggle').toggleSwitch({
			highlight: false,
			width: 25
    });
    $('#f_opt .ui-toggle-switch').find('label').eq(0).click(function(){
        $('#f_group_wrapper').hide(0,function(){
            socialLoading.show(0,function(){
                makeAjaxCall('get',{
                fuid:function(){return $('#f_opt').parent().attr('fuid');},
                webaction:14},function(){socialLoading.hide(0,function(){
                    checkIfEmpty();
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
        });
    });
    $('#f_opt .ui-toggle-switch').find('label').eq(1).click(function(){
        $('#f_group_wrapper').hide(0,function(){
            makeSocialAjaxCall('get','view/f_friends.php',{
                fuid:function(){return $('#f_opt').parent().attr('fuid');},
                ftype:0},
            function(resp){
                $('#f_group_wrapper').html(resp).fadeIn(200);
                },function(){showSocial = true});
        });
            
    });
    $('.fighto').click(function(){
        var tid = $(this).attr('tid');
        if(!$(this).hasClass('liked')){
            var oriexp = $(this).prev().prev().html();
            $(this).prev().prev().html(parseInt(oriexp)+1);
            makeAjaxCall('post',{
            uid:function(){return $('#uid').html();},
            tid:tid,
            webaction:12},function(){},function(r){if(r==-1){alert('you have already FIGHTOed this task.')} });
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
    initBtns();
});
function checkIfEmpty(){
    if($('#f_group_wrapper').html()==''){
        $('#f_group_wrapper').html('No task shared by this user.');
        return true;
    }
    return false;
}


