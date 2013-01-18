function initTasks(){
    $('#tasks_sortable').sortable({
        update:function(){
            sync();
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
    $('.ui-slider-handle').tipsy({fallback:'Done?',gravity:'n',fade:true});
    activeDeletes();
    $('#input_task').val('').focus();
    $('#tg_selector').show();
}
function sync(){
    var current_tgid = $('#tgid').html();
    var tasks = $('li','#tasks_sortable');
    var task_order = '';
    var cacheContent = '';
    var comma = ',';
    var task = tasks.first();
    for(var i=0;i<tasks.length;i++){
        if(i==tasks.length-1){
            comma = '';
        }
        task_order +=task.attr('tid')+comma;
        var selected = '<option value="0" selected="selected"></option><option value="1"></option>';
        if($('select',task).val()==1){
            selected = '<option value="0"></option><option value="1" selected="true"></option>';
        }
        cacheContent += '<li privacy="'+task.attr('privacy')+'" tid="'+task.attr('tid')+'"class="t_content hoverable roundcorner"><div class="isDone"><select class="done" data-highlight="true">'+selected+'</select></div><div class="t_content_text">'+
            task.children().eq(1).text()+'</div><div class="delete_task"></div></li>';
        task = task.next();
    }
    $('#'+current_tgid,'#cache').html(cacheContent);
    makeAjaxCall('post',{
        tgid:current_tgid,
        t_order:task_order,webaction:7},function(){});
}
function initTaskGroups(isFromClick){
    $('.tg_title_text').click(function(){
        var index = $('.tg_title_text').index(this);
        $('#tg_selector').css('top', index*51+'px');
        $('#tgid').html($(this).parent().attr('id'));
        var tgid = '#'+$(this).parent().attr('id');
        $('.tg_title').removeClass('selected');
        $('.tg_title_text').removeClass('tg_text_selected');
        $(this).addClass('tg_text_selected').parent().addClass('selected');
        $('#tasks_sortable').fadeOut(200, function(){
            var task_content = $(tgid,'#cache').html();
             $(this).html(task_content);
             $('.isDone select','#tasks_sortable').each(function(i,item) {
                $(item).toggleSwitch({
                        highlight: $(item).data("highlight"),
                        width: 25,
                        change:function(){
                        checkIfDone(item);
                        sync();}
                });
             });
            $(this).fadeIn(200, function(){
                 initTasks();
                 sync();
            });
            resizeTaskPanel(isFromClick);
        });
    });
}
function checkIfDone(item){
    var opac = 1;
    var isDone = $(item).val();
    if(isDone==1){
        opac = 0.2;
    }
    $(item).parent().siblings('.t_content_text').css('opacity',opac); 
    
}
function activeDeletes(){
    $('.delete_task').click(function(){
        if(confirm ("Delete this task?")){
            var tid = $(this).parent().attr('tid');
            postDeleteTask(tid);
        }
    });
}
function postCreateTaskGroup(){
    $('.dialog').dialog('close');
    isNewGroup=true;
    makeAjaxCall('post',
            {uid:function(){return $('#uid').html()},
            title:function(){return $.trim($('#input_group').val())},
            priority: function(){return $('#g_priority').val()},
            type: function(){return 0},
            //todo
            webaction:1},function(){
                positionGroup();
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
            $(tgid,'#cache').prepend('<li privacy="0" tid="'+tidnew+'"class="t_content hoverable roundcorner"><div class="isDone"><select class="done" data-highlight="true"><option value="0"></option><option value="1"></option></select></div><div class="t_content_text">'
+$.trim($('#input_task').val())+'</div><div class="delete_task"></div></li>');
            $('.tg_title_text',tgid).click();
        });
}
function postDeleteTaskGroup(tgid){
        $('.dialog').dialog('close');
        $('#'+tgid).slideUp(200,function(){
            $(this).remove();
            $('#'+tgid,'#cache').remove();
            checkIfGroupExists();
        });
        makeAjaxCall('post',{tgid:tgid,webaction:3},function(){});
}
function postDeleteTask(tid){
        $('[tid="'+tid+'"]','#tasks_sortable').slideUp(200, function(){
            $(this).remove();
            $('li[tid="'+tid+'"]','#cache').remove();
            $('#input_task').focus();
        })
        makeAjaxCall('post',
            {tid:tid,webaction:2},function(){
                sync();
            })
}
function postUpdateTask(){
    $('.dialog').dialog('close');
    var tid = $('#tid','#t_dialog').html();
    var content = $('#update_task').val();
    var privacy = $('#u_t_privacy').val();
    $('[tid="'+tid+'"]','#tasks_sortable').attr('privacy', privacy).children().eq(1).html(content);
    makeAjaxCall('post',{
        tid:function(){return $('#tid','#t_dialog').html()},
        content:function(){return $('#update_task').val()},
        privacy:function(){return $('#u_t_privacy').val()},
        webaction:4
        },function(){
            sync();
        }
        );
}
function postUpdateTaskGroup(){
    $('.dialog').dialog('close');
    var tgid = $('#tgid').html();
    var title = $.trim($('#update_group').val());
    var priority = $('#u_g_priority').val();
    isNewGroup = false;
    positionGroup();
    makeAjaxCall('post',{
        tgid:tgid,
        title:title,
        priority:priority,
        type:function(){return 0},
        webaction:5},function(){});
}
function checkIfGroupExists(){
    if($('.tg_title').length==0){
        $('#tg_selector,#task_wrapper').hide(0);
    }else{
        $('#tg_selector,#task_wrapper').fadeIn(200);
        $('.tg_title_text').first().click(); 
    }
}
function positionGroup(){
    $('#task_wrapper').show();     
    var title = $.trim($('#update_group').val());
    var priority = $('#u_g_priority').val();
    var tgid = $('#tgid').html();
    var cachecontent = null;
    var tgcontent = null;
    var groups = null;
    var group = null;
    var p_temp = null
    if(isNewGroup || originalPriority != priority){
        if(isNewGroup){
            tgid=tgidnew;
            title = $.trim($('#input_group').val());
            priority = $('#g_priority').val();
            cachecontent = '<div priority="'+priority+'" id="'+tgid+'"></div>';
            $('#cache').prepend(cachecontent);
        }else{
            $('#'+tgid,'#group_wrapper').remove();
        }
        tgcontent = '<div priority="'+priority+'" id="'+tgid+'" class="tg_title"><div class="delete_group"></div><div class="tg_title_text"><span>'+title+'</span></div></div>';
        groups = $('.tg_title','#group_wrapper');
        if(groups.length == 0){
            $('#group_wrapper').prepend(tgcontent);
        }else{
            group = groups.first();
            for(var i=0;i<groups.length;i++){
                p_temp = group.attr('priority');
                if(priority>p_temp){
                    group.before(tgcontent);
                    break;
                }else if(priority==p_temp){
                    var id_temp = group.attr('id');
                    if(id_temp>tgid){
                        group.after(tgcontent);
                    }else{
                        group.before(tgcontent);
                    }
                    break;
                }else if(i==groups.length-1){
                    group.after(tgcontent);
                    break;
                }else{
                    group = group.next()
                }
            }
        }
    }else{
        $('.tg_title_text','#'+tgid).children().text(title);
    }
    
    initTaskGroups(false);
    $('#'+tgid,'#group_wrapper').children().eq(1).click();
    $('#'+tgid,'#group_wrapper').children().first().click(function(){
        if(confirm ("Delete this group?")){
            var tgid = $(this).parent().attr('id');
            postDeleteTaskGroup(tgid);
        }
    });
}
function makeAjaxCall(type,param,callback){
    loading_image.show(0);
    $.ajax({
        url:'service/web/webactions.php',
        timeout:6000,
        type:type,
        data:param,
        success:function(response){
            if(response==-1&&response==-2){
                alert('Operation failed!');
                location.reload();
            }
            tidnew =tgidnew= response;
        },
        error:function(){
            alert('Operation failed!');
        },
        complete:function(){
            if(callback != null){callback();}
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

function resizeTaskPanel(isFromClick){
    var width = windowDiv.width();
    if(width<550){
        hideGroupPanel(isFromClick)
    }else{
        showGroupPanel(isFromClick);
    }
}
function showGroupPanel(isFromClick){
        var animaTime = (isFromClick==true?300:0);
        var width = windowDiv.width();
        showGroup = true;
        if(!isFromClick){
            $('#logout').show(0);
        }
        $('#g_dialog,#t_dialog,#u_g_dialog').dialog('option','width',550);
        $('#panel_group').removeClass('hidden');
        $('#task_wrapper').animate({width:width-310},animaTime);
        $('.t_content_text').animate({width:width-420},animaTime);
        $('.t_content').animate({width:width-320},animaTime);
        $('.input_task').animate({width:width-320},animaTime);
        $('#panel_task').animate({marginLeft:270,width:width-270},animaTime);
        $('#pane_social').css({'right':-404,'width':400});
        $('#tg_selector').show(0);
}
function hideGroupPanel(isFromClick){
        var animaTime = (isFromClick==true?300:0)
        var width = windowDiv.width();
        showGroup = false;
        if(!isFromClick){
            $('#logout').hide(0);
        }
        $('#g_dialog,#t_dialog,#u_g_dialog').dialog('option','width',width-10);
        $('#panel_task').animate({marginLeft:0,width:width},animaTime,function(){
            $('#panel_group').addClass('hidden');
        });
        $('#task_wrapper').animate({width:width-40},animaTime);
        $('.t_content_text').animate({width:width-150},animaTime);
        $('.t_content').animate({width:width-50},animaTime);
        $('.input_task').animate({width:width-50},animaTime);
        $('#pane_social').css({'right':-width+50,'width':width-50});
        $('#tg_selector').hide();    
}
function toggleGroupPanel(){
    if(showGroup){
        hideGroupPanel(true);
    }else{
        showGroupPanel(true);
    }
}
$(document).ready(function(){
    showGroup = true;
    $('button').button();
    $('input.datepicker').datepicker();
    tidnew = tgidnew= -1;
    windowDiv = $(window);
    loading_image = $('#loadingImage');
    isNewGroup = true;
    originalPriority = 0;
    $('#tg_selector').click(function(){
        $('#panel_group').hide();
    });
    $('#logout').click(function(){
        location.replace("test/logout.php");
    });
    $('#group_button').click(function(){
        toggleGroupPanel();
    })
    $('#input_task').tipsy({fallback:'press ENTER to create new task',gravity:'n',fade:true});
    $('#g_dialog,#t_dialog,#u_g_dialog').dialog({autoOpen: false,height:400,width:500,modal:true,resizable:false,closeOnEscape: true});

    $('#panel_task').removeClass('hidden');
    initTaskGroups(false);
    checkIfGroupExists();
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

    $('#u_group').click(function(){
        var tgid = '#'+$('#tgid').html();
        originalPriority = $(tgid).attr('priority');
        $('#update_group').val($('.tg_title_text',tgid).text());
        $('#u_g_priority').val(originalPriority);
        $('#u_g_dialog').dialog('open');
    }).tipsy({fallback:'edit group content',gravity:'s'});
    
    $('#create_group').click(function(){
        $('#g_dialog').dialog('open');
    }).tipsy({fallback:'create new group',gravity:'sw'});
    
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
            e.preventDefault();
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
    $.imgpreload(['image/button_close.png','theme/images/modalClose.png']);
});

