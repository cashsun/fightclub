$(document).ready(function(){
    $('.f_task').click(function(){
        alert($(this).text());
    });
    if($('#f_group_wrapper').html()==''){
        $('#f_group_wrapper').html('No task shared by this user.');
    }
    $('.f_group').tipsy({gravity:'s',fade:false,offset:0});
 
});


