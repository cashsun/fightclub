function activeTitles(){
        $('.t_content').click(function(){
//        alert($(this).attr('tid'));
    });
}
$('.tg_title').click(function(){
        $('#tgid').html($(this).attr('id'));
        var tgid = '#'+$(this).attr('id');
        $('#panel_task').fadeOut(500, function(){
            $(this).html('<input id="input_task" type="text" maxlength="140"/>');
             $(this).append($(tgid,'#cache').html()).fadeIn(500);
             activeTitles();
        });
});
$('.delete_task').click(function(){
        $(this).parent();
});
$(document).ready(function(){
    activeTitles();
    $(document).keypress(function(e) {
        if(e.which == 13) {
            if($('#input_task').val()!=''){
                    $.post(
                    "service/createOriTask.php",
                    {uid:function(){return $('#uid').html()},
                        tgid:function(){return $('#tgid').html()},
                        content:function(){return $('#input_task').val()}},
                    function(response){
                            alert(response);
                            var content = $('#input_task').val();
                            var tgid = $('#tgid').html();
                            var taskStr = '<div tid="'+tgid+'"class="t_content">'+content+'<div class="delete_task">x</div></div>';
                            $('#'+tgid,'#cache').append(taskStr);
                            $('#'+tgid,'#panel_group').click();
                    });
            }
        }
    });
});

