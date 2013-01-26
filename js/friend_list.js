$(document).ready(function(){
    $('.f_task_text').click(function(){
        alert($(this).text());
    });

    $('.fighto').click(function(){
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
    $('.f_group').tipsy({gravity:'s',fade:false,offset:0});
 
});


