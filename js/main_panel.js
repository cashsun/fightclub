function activeTasks(){
        $('.t_content').click(function(){
//        alert($(this).attr('tid'));
    });
}
function activeDeletes(){
    $('.delete_task').click(function(){
        var tid = $(this).parent().attr('tid');
        $.post(
            "service/deleteTask.php",
            {tid:tid},
            function(response){
            if(response==1){
                alert('success');
            }
            location.reload();
        });
    });
}
$(document).ready(function(){
    activeTasks();
    activeDeletes();
    $('.tg_title').click(function(){
        $('#tgid').html($(this).attr('id'));
        var tgid = '#'+$(this).attr('id');
        $('#panel_task').fadeOut(500, function(){
            $(this).html('<input id="input_task" type="text" maxlength="140"/>');
             $(this).append($(tgid,'#cache').html()).fadeIn(500);
             activeTasks();
             activeDeletes();
        });
    });
    $('#g_dialog').dialog({ autoOpen: false });
    $('#create_group').click(function(){
        $('#g_dialog').dialog('open');
    });
    $(document).keypress(function(e) {
        if(e.which == 13) {
            if($('#input_task').val()!=''){
                    $.post(
                    "service/createTask.php",
                    {uid:function(){return $('#uid').html()},
                        tgid:function(){return $('#tgid').html()},
                        content:function(){return $('#input_task').val()}},
                    function(response){
                            alert(response);
                            if(response==0){
                                
                            }
                            location.reload();
                    });
            }
        }
    });
});

