function initTasks(){
    $('#tasks_sortable').sortable({
        update:function(){
            var tasks = $('li',this);
            var t_order = '';
            var comma = ',';
            var task = tasks.first();
            for(var i=0;i<tasks.length;i++){
                if(i==tasks.length-1){
                    comma = '';
                }
                t_order +=task.attr('tid')+comma;
                task = task.next();
            }
            alert(t_order);
        }
    });
    $('.t_content_text').click(function(){
        $('#tid','#t_dialog').html($(this).parent().attr('tid'));
        $('#update_task').val(($(this).text()));
        $('#u_t_privacy').val($(this).parent().attr('privacy'));
        $('#t_dialog').dialog('open');
    }).mouseover(function(){$(this).css('color', 'white');
        }).mouseout(function(){$(this).css('color', '#8d8f90');
    });
    activeTaskDeletes();
    $('#input_task').val('').focus();
}
function activeTaskDeletes(){
    $('.delete_task').click(function(){
        if(confirm ("Delete this task?")){
            var tid = $(this).parent().attr('tid');
            postDeleteTask(tid);
        }
    });
}
function activeGroupDeletes(){
    $('.delete_group').click(function(){
            if(confirm ("Delete this group?")){
                var tgid = $(this).parent().attr('id');
                postDeleteTaskGroup(tgid);
            }
    });
}
function initGroups(){
        $('.tg_title_text').click(function(){
            var index = $('.tg_title_text').index(this);
            $('#tg_selector').css('top', index*51);
            $('#tgid').html($(this).parent().attr('id'));
            var tgid = '#'+$(this).parent().attr('id');
            $('.tg_title').removeClass('selected');
            $('.tg_title_text').removeClass('tg_text_selected');
            $(this).addClass('tg_text_selected').parent().addClass('selected');
            $('#tasks_sortable').fadeOut(200, function(){
                var task_content = $(tgid,'#cache').html();
                $(this).html(task_content).fadeIn(200, function(){
                    initTasks();
                });
            });
        });
        activeGroupDeletes();
}
function postCreateTaskGroup(){
    $('.dialog').dialog('close');
    makeAjaxCall('post',
            {uid:function(){return $('#uid').html()},
            title:function(){return $.trim($('#input_group').val())},
            priority: function(){return $('#g_priority').val()},
            type: function(){return 0},
            //todo
            webaction:1},function(){
                var priority = $('#g_priority').val();
                var title = $.trim($('#input_group').val());
                var groups = $('.tg_title','#panel_group');
                var newcontent = '<div priority="'+priority+'" id="'+tgidnew+'" class="tg_title"><div class="delete_group"></div><div class="tg_title_text"><span>'+title+'</span></div></div>';
                var cachecontent =  '<div priority="'+priority+'" id="'+tgidnew+'"></div>';
                var group = groups.first();
                for(var i=0;i<groups.length;i++){
                    var p_temp = group.attr('priority');
                    if(priority>=p_temp){
                        group.before(newcontent);
                        initGroups();
                        $('#cache').append(cachecontent);
                        $('#'+tgidnew).children().eq(1).click();
                        return;
                    }else if(i==groups.length-1){
                        group.after(newcontent);
                        $('#cache').append(cachecontent);
                        $('#'+tgidnew).children().eq(1).click();
                        return;
                    }else{
                        group = group.next();
                    }
                }
            })
}
function postCreateTask(){
    makeAjaxCall('post',
        {uid:function(){return $('#uid').html()},
            tgid:function(){return $('#tgid').html()},
            content:function(){return $.trim($('#input_task').val())},
            webaction:0},
        function(){
            var tgid = '#'+$('#tgid').html();
            $(tgid,'#cache').prepend('<li privacy="0" tid="'+tidnew+'"class="t_content hoverable roundcorner"><div class="t_content_text">'+$.trim($('#input_task').val())+'</div><div class="delete_task"></div></li>');
            $('.tg_title_text',tgid).click();
        });
}
function postDeleteTaskGroup(tgid){
        $('.dialog').dialog('close');
        $('#'+tgid).slideUp(200,function(){
            $(this).remove();
            if($('.tg_title').length==0){
                $('#tg_selector,#task_wrapper').hide();
            }else{
               $('.tg_title_text').first().click(); 
            }
        });
        makeAjaxCall('post',{tgid:tgid,webaction:3},function(){});
}
function postDeleteTask(tid){
        var tgid = '#'+$('#tgid').html();
        $('[tid="'+tid+'"]','#cache').remove();
        $('.tg_title_text',tgid).click();
        makeAjaxCall('post',
            {tid:tid,webaction:2},function(){})
}
function postUpdateTask(){
    $('.dialog').dialog('close');
    var tid = $('#tid','#t_dialog').html();
    var content = $('#update_task').val();
    var privacy = $('#u_t_privacy').val();
    $('[tid="'+tid+'"]','#tasks_sortable').attr('privacy', privacy).children().first().html(content);
    makeAjaxCall('post',{
        tid:function(){return $('#tid','#t_dialog').html()},
        content:function(){return $('#update_task').val()},
        privacy:function(){return $('#u_t_privacy').val()},
        webaction:4
        },function(){}
        );
}
function postUpdateTaskGroup(){
    $('.dialog').dialog('close');
    makeAjaxCall('post',{
        tgid:function(){return $('#tgid').html()},
        title:function(){return $.trim($('#update_group').val())},
        priority:function(){return $('#u_g_priority').val()},
        type:function(){return 0},
        webaction:5}
        );
}
function makeAjaxCall(type,param,callback){
    loading_image.show(0);
    $.ajax({
        url:'service/web/webactions.php',
        type:type,
        data:param,
        success:function(response){
            if(response==-1&&response==-2){
                alert('Operation failed!');
                location.reload();
            }
            tidnew = tgidnew = response;
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

function jsonAjaxRequest(type, url, param,callback){
    $.ajax({
        url:url,
        type:type,
        datatype: 'json',
        data:param,
        success:function(data){
          json = jQuery.parseJSON(data);
          callback(json);
        },
        error:function(){
            alert('Operation failed!');
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
        $('.t_content').css('width', width-50);
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
        $('.t_content').css('width', width-320);
        $('.input_task').css('width', width-320);
        $('#tg_selector').show(0);
    }
}
$(document).ready(function(){
    $('#panel_main').hide();
    tidnew = -1;
    tgidnew = -1;
    windowDiv = $(window);
    loading_image = $('#loadingImage');
    $('#g_dialog,#t_dialog,#u_g_dialog').dialog({autoOpen: false,height:400,width:500,modal:true,resizable:false,closeOnEscape: true});
    resizeTaskPanel();
    $('#panel_task').removeClass('hidden');
    initTasks();
    initGroups();
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
                postCreateTaskGroup();
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
    
    $(window).resize(function() {
        resizeTaskPanel();
    });
    $(document).keypress(function(e) {
        if(e.which == 13) {
            if($( "#g_dialog" ).dialog( "isOpen" )){
                if($.trim($('#input_group').val())!=''){
                    postCreateTaskGroup();
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

