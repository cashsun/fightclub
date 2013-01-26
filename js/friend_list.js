$(document).ready(function(){
    $('.f_task_text').click(function(){
        alert($(this).text());
    });

    $('.fighto').click(function(){
        $(this).addClass('liked').css({zoom:'150%',top:'-5px',right:'-5px'});
        $(this).animate({zoom:'100%',top:'0px',right:'0px'},400);
    });

    if($('#f_group_wrapper').html()==''){
        $('#f_group_wrapper').html('No task shared by this user.');
    }
    $('.f_group').tipsy({gravity:'s',fade:false,offset:0});
 
});


