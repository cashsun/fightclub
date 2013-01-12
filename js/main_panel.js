function activeTasks(){
    $('.t_content_text').click(function(){
        $('#tid','#t_dialog').html($(this).parent().attr('tid'));
        $('#update_task').val(($(this).text()));
        $('#t_dialog').removeClass('hiddable').dialog('open');
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
    makeAjaxCall('post',"service/web/updateTask.php",{
        tid:function(){return $('#tid','#t_dialog').html()},
        content:function(){return $('#update_task').val()}
        }
        );
}
function postUpdateTaskGroup(){
    makeAjaxCall('post',"service/web/updateTaskGroup.php",{
        tgid:function(){return $('#tgid').html()},
        title:function(){return $.trim($('#update_group').val())},
        priority:function(){return $('#u_g_priority').val()}
        }
        );
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
function resizeTaskPanel(){
    var width = $(window).width();
    $('#panel_task').css('width', width-270+'px');
    $('#task_wrapper').css('width', width-300+'px');
    $('.t_content_text').css('width', width-360+'px');
    
    $('#input_task').css('width', width-310+'px');
    
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
    resizeTaskPanel();
    activeTasks();
    activeDeletes();
    $('#input_task').focus();
    $('.tg_title_text').click(function(){
        $('#tgid').html($(this).parent().attr('id'));
        var tgid = '#'+$(this).parent().attr('id');
         $('.tg_title').removeClass('selected');
        $(this).parent().addClass('selected');
        $('#panel_task').fadeOut(500, function(){
            $(this).html('<div id="task_wrapper" style="margin-left: auto;margin-right: auto"><input id="input_task" class= "input" type="text" maxlength="140"/>');
             $(this).append($(tgid,'#cache').html()+'</div>').fadeIn(500);
             activeTasks();
             activeDeletes();
        });
    });
    $('#u_group').click(function(){
        var tgid = '#'+$('#tgid').html();
        $('#update_group').val($('.tg_title_text',tgid).text());
        $('#u_g_dialog').removeClass('hiddable').dialog('open');
    });
    $('#g_dialog,#t_dialog,#u_g_dialog').dialog({autoOpen: false,height:400,width:700,modal:true,resizable:false,closeOnEscape: true});
    $('#t_dialog').dialog("option", "buttons", [ 
        {text:"OK",click:function(){
            if($('#update_task').val()!=''){
                postUpdateTask();
            }
    }}]);
    $('#g_dialog').dialog("option", "buttons", [ 
        {text:"OK",click:function(){
            if($('#input_group').val()!=''){
                postCreateGroup();
            }
    }}]);

    $('#u_g_dialog').dialog("option", "buttons", [ 
        {text:"OK",click:function(){
            if($.trim($('#update_group').val())!=''){
                postUpdateTaskGroup();
            }
    }}]);
    $('#create_group').click(function(){
        $('#g_dialog').removeClass('hiddable').dialog('open');
    });
    
    $('.delete_group').click(function(){
        var tgid = $(this).parent().attr('id');
        postDeleteTaskGroup(tgid);
    });
    $(window).resize(function() {
        resizeTaskPanel();
    });
    $(document).keypress(function(e) {
        if(e.which == 13) {
            if($( "#g_dialog" ).dialog( "isOpen" )){
                if($.trim($('#input_group').val())!=''){
                    postCreateGroup();
                }
            }else if($("#t_dialog").dialog( "isOpen" )){
                if($.trim($('#update_task').val())!=''){
                    postUpdateTask();
                }
            }else if($("#u_g_dialog").dialog( "isOpen" )){
                if($.trim($('#update_group').val())!=''){
                    postUpdateTaskGroup();
                }
            }else{
                if($.trim($('#input_task').val())!=''){
                    postCreateTask();
                }
            }
        }
    });
});

