function activeTasks(){
    $('.t_content').click(function(){
        $('#tid','#t_dialog').html($(this).attr('tid'));
        $('#update_task').val(($('.tt_content',this).text()));
        $('#t_dialog').dialog('open');
    });
}
function postCreateGroup(){
    makeAjaxCall('post',
            "service/web/createTaskGroup.php",
            {uid:function(){return $('#uid').html()},
            title:function(){return $.trim($('#input_group').val())},
            priority: function(){return $('#g_priority').val()}})
}
function postCreateTask(){
    makeAjaxCall('post', "service/web/createTask.php",
        {uid:function(){return $('#uid').html()},
            tgid:function(){return $('#tgid').html()},
            content:function(){return $.trim($('#input_task').val())}});
}
function postDeleteTaskGroup(tgid){
        makeAjaxCall('post',"service/web/deleteTaskGroup.php",{tgid:tgid}
        );
}
function postUpdateTask(){
    
}
function makeAjaxCall(type, url, param){
    $.ajax({
        url:url,
        type:type,
        data:param,
        success:function(response){
            if(response==-1){
                alert('Operation failed!');
            }else{
                alert('Success!');
            }
        },
        error:function(){
            alert('Operation failed!');
        },
        complete:function(){
            location.reload();
        }
    });
}
function activeDeletes(){
    $('.delete_task').click(function(){
        var tid = $(this).parent().attr('tid');
        makeAjaxCall('post',
            "service/web/deleteTask.php",
            {tid:tid})
    });
}
$(document).ready(function(){
    activeTasks();
    activeDeletes();
    $('#input_task').focus();
    $('.tg_title').click(function(){
        $('#tgid').html($(this).attr('id'));
        var tgid = '#'+$(this).attr('id');
         $('.tg_title').removeClass('selected');
        $(this).addClass('selected');
        $('#panel_task').fadeOut(500, function(){
            $(this).html('<input id="input_task" class= "input" type="text" maxlength="140"/>');
             $(this).append($(tgid,'#cache').html()).fadeIn(500);
             activeTasks();
             activeDeletes();
        });
    });
    $('#g_dialog,#t_dialog').dialog({autoOpen: false,height:400,width:700,modal:true,resizable:false,closeOnEscape: true});
    $('#t_dialog').dialog("option", "buttons", [ 
        {text:"OK",click:function(){
            if($('#update_task').val()!=''){
                alert('update task');
            }
    }}]);
    $('#g_dialog').dialog("option", "buttons", [ 
        {text:"OK",click:function(){
            if($('#input_group').val()!=''){
                postCreateGroup();
            }
    }}]);
    $('#create_group').click(function(){
        $('#g_dialog').dialog('open');
    });
    
    $('.delete_group').click(function(){
        var tgid = $(this).parent().attr('id');
        postDeleteTaskGroup(tgid);
    });
    $(document).keypress(function(e) {
        if(e.which == 13) {
            if($( "#g_dialog" ).dialog( "isOpen" )){
                if($.trim($('#input_group').val())!=''){
                    postCreateGroup();
                }
            }else if($( "#t_dialog" ).dialog( "isOpen" )){
                if($.trim($('#input_task').val())!=''){
                    postUpdateTask();
                }
            }else{
                if($.trim($('#input_task').val())!=''){
                    postCreateTask();
                }
            }
        }
    });
});

