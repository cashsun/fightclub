function activeTasks(){
        $('.t_content').click(function(){
//        alert($(this).attr('tid'));
    });
}
function activeDeletes(){
    $('.delete_task').click(function(){
        var tid = $(this).parent().attr('tid');
        $.post(
            "service/web/deleteTask.php",
            {tid:tid},
            function(response){
            if(response==1){
                alert('success');
            }else{
                alert(response);
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
    $('#g_dialog').dialog({autoOpen: false,height:400,width:700,modal:true,resizable:false,closeOnEscape: true});
    $('#g_dialog').dialog("option", "buttons", [ 
        {text:"OK",click:function(){
            if($('#input_group').val()!=''){
                $.post(
                    "service/web/createTaskGroup.php",
                    {uid:function(){return $('#uid').html()},
                    title:function(){return $('#input_group').val()}},
                    function(response){
                        if(response==1){
                            alert('success!');
                            location.reload();
                        }else{
                            alert(response);
                        }
                    });
            }
        }}]);
    $('#create_group').click(function(){
        $('#g_dialog').dialog('open');
    });
    
    $('.delete_group').click(function(){
        var tgid = $(this).parent().attr('id');
        $.post(
            "service/web/deleteTaskGroup.php",
            {tgid:tgid},
            function(response){
            if(response==1){
                alert('success');
            }else{
                alert(response);
            }
            location.reload();
        });
    });
    $(document).keypress(function(e) {
        if(e.which == 13) {
            if($( "#g_dialog" ).dialog( "option", "modal" )){
                if($('#input_group').val()!=''){
                $.post(
                    "service/web/createTaskGroup.php",
                    {uid:function(){return $('#uid').html()},
                    title:function(){return $('#input_group').val()}},
                    function(response){
                        if(response==1){
                            alert('success!');
                            location.reload();
                        }else{
                            alert(response);
                        }
                    });
                }
            }
            if($('#input_task').val()!=''){
                    $.post(
                    "service/web/createTask.php",
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

