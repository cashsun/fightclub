function initTasks(){
    $('.t_content_text').click(function(){
        $('#tid','#t_dialog').html($(this).parent().attr('tid'));
        $('#update_task').val(($(this).text()));
        $('#u_t_privacy').val($(this).parent().attr('privacy'));
        $('#t_dialog').dialog('open');
    }).mouseover(function(){$(this).css('color', 'white');
        }).mouseout(function(){$(this).css('color', '#8d8f90');
    });
    activeDeletes();
    $('#input_task').focus();
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
            content:function(){return $.trim($('#input_task').val())}},
        function(){
            var tgid = '#'+$('#tgid').html();
            $(tgid,'#cache').prepend('<div privacy="0" tid="'+tidnew+'"class="t_content hoverable roundcorner"><div class="t_content_text">'+$.trim($('#input_task').val())+'</div><div class="delete_task">x</div></div>');
            $('.tg_title_text',tgid).click();
        });
}
function postDeleteTaskGroup(tgid){
            makeAjaxCall('post',"service/web/deleteTaskGroup.php",{tgid:tgid});
}
function postDeleteTask(tid){
        makeAjaxCall('post',
            "service/web/deleteTask.php",
            {tid:tid},function(){
                var tgid = '#'+$('#tgid').html();
                $('[tid="'+tid+'"]','#cache').remove();
                $('.tg_title_text',tgid).click();
            })
}
function postUpdateTask(){
    makeAjaxCall('post',"service/web/updateTask.php",{
        tid:function(){return $('#tid','#t_dialog').html()},
        content:function(){return $('#update_task').val()},
        privacy:function(){return $('#u_t_privacy').val()}
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
function makeAjaxCall(type, url, param,callback){
    loading_image.show(0);
    $.ajax({
        url:url,
        type:type,
        data:param,
        success:function(response){
            if(response==-1){
                alert('Operation failed!');
            }
            
            tidnew = response;
        },
        error:function(){
            alert('Operation failed!');
        },
        complete:function(){
            if(callback != null){callback()}
            else{
                location.reload();
            }
            loading_image.hide(0);
        }
    });
}
function resizeTaskPanel(){
    var width = windowDiv.width();
    if(width<550){
        $('#logout').hide(0);
        $('#g_dialog,#t_dialog,#u_g_dialog').dialog('option','width',width-10);
        $('#panel_group').addClass('hidden');
        $('#panel_task').css({'margin-left':0,'width':width});
        $('#pane_social').css({'right':-width+50,'width':width-50});
        $('#task_wrapper').css('width', width-40);
        $('.t_content_text').css('width', width-100);
        $('.input_task').css('width', width-50);
        $('#tg_selector').hide();
    }else{
        $('#logout').show(0);
        $('#g_dialog,#t_dialog,#u_g_dialog').dialog('option','width',550);
        $('#panel_group').removeClass('hidden');
        $('#panel_task').css({'margin-left':270,'width':width-270});
        $('#pane_social').css({'right':-400,'width':400});
        $('#task_wrapper').css('width', width-310);
        $('.t_content_text').css('width', width-370);
        $('.input_task').css('width', width-320);
        $('#tg_selector').show(0);
    }
    $('#panel_task').removeClass('hidden');
}
function activeDeletes(){
    $('.delete_task').click(function(){
        if(confirm ("Delete this task?")){
            var tid = $(this).parent().attr('tid');
            postDeleteTask(tid);
        }
    });
}
$(document).ready(function(){
    tidnew = -1;
    windowDiv = $(window);
    loading_image = $('#loadingImage');
    loading_image.hide();
    $('#g_dialog,#t_dialog,#u_g_dialog').dialog({autoOpen: false,height:400,width:500,modal:true,resizable:false,closeOnEscape: true});
    resizeTaskPanel();
    $('#panel_group').css('background-image', 'url(image/panel_g_shadow.png)');
    initTasks();
    $('.tg_title_text').click(function(){
        var index = $('.tg_title_text').index(this);
        if(index!=0){
            $('.tg_title').first().css('background-image', 'url(image/tg_shadow.png)');
        }
        $('#tg_selector').css('top', index*51+'px');
        $('#tgid').html($(this).parent().attr('id'));
        var tgid = '#'+$(this).parent().attr('id');
        $('.tg_title').removeClass('selected');;
        $('.tg_title_text').removeClass('tg_text_selected');
        $(this).addClass('tg_text_selected').parent().addClass('selected');
        $('#task_wrapper').fadeOut(200, function(){
            var task_content = '<input id="input_task" class= "input_task roundcorner" type="text" maxlength="140"/>'+$(tgid,'#cache').html();
             $(this).html(task_content).fadeIn(200, function(){
                 initTasks();
             });
             resizeTaskPanel();
        });
    });
    $('#u_group').click(function(){
        var tgid = '#'+$('#tgid').html();
        $('#update_group').val($('.tg_title_text',tgid).text());
        $('#u_g_priority').val($(tgid).attr('priority'));
        $('#u_g_dialog').dialog('open');
    });
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
        $('#g_dialog').dialog('open');
    });
    
    $('.delete_group').click(function(){
        if(confirm ("Delete this group?")){
            var tgid = $(this).parent().attr('id');
            postDeleteTaskGroup(tgid);
        }
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

