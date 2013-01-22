function initTasks(){
    $('.t_content_text').click(function(){
        $('#tid','#t_dialog').html($(this).parent().attr('tid'));
        $('#update_task').val(($(this).text()));
        $('#u_t_privacy').val($(this).parent().attr('privacy'));
        $('#t_dialog').dialog('open');
    }).mouseover(function(){$(this).css('color','white');
        }).mouseout(function(){$(this).css('color', '#8d8f90');
    });
    activeDeletes();
    initIsDone();
    if(!$("#u_g_dialog").dialog( "isOpen" )){$('#input_task').val('').focus();}
    $('#tg_selector').show();
}

//update t_order and cache
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
        var checked = '';
        var isNonIE8 = '';
        var tcheckbox = task.children().eq(0).find('input');
        if(tcheckbox.is(':checked')){
            checked='checked'
        }
        if(($.browser.msie  && parseInt($.browser.version, 10) != 8)||!$.browser.msie){
            isNonIE8 = 'isDoneNonIE8';
        }
        cacheContent += '<li privacy="'+task.attr('privacy')+'" tid="'+task.attr('tid')+'"class="t_content hoverable roundcorner"><div class="isDone '+isNonIE8+'"><input class="isdone_checkbox" type="checkbox" '+checked+'/></div><div class="t_content_text">'+
            task.children().eq(1).text()+'</div><div class="delete_task"></div></li>';
        task = task.next();
    }
    $('#'+current_tgid,'#cache').html(cacheContent);
    makeAjaxCall('post',{
        tgid:current_tgid,
        t_order:task_order,webaction:7},function(){});
}
function initIsDone(){
    $('input[type="checkbox"]','.isDone').change(function(){       
        var tcheckbox = $(this);
        checkIfDoneSingle(tcheckbox);
        var tid = tcheckbox.parent().parent().attr('tid');
        var isdone = 0;
        if(tcheckbox.is(':checked')){isdone = 1};
        makeAjaxCall('post',
        {tid:tid,
            isdone:isdone,
            webaction:8},function(){
                sync();
            });
    });
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
             sync();
             checkIfDoneMulti();
              $('#tasks_sortable').sortable({
                    update:function(){
                        sync();
                    }
                });
            $(this).fadeIn(200, function(){
                initTasks();
            });
            resizeTaskPanel(isFromClick);
        });
    }).dblclick(function(){
        $('#u_group').click();
    });
}
function checkIfDoneSingle(item){
    var opac = 1;
    if($(item).is(':checked')){
        opac = 0.4;
        if(($.browser.msie  && parseInt($.browser.version, 10) != 8)||!$.browser.msie){
            $(item).parent().addClass('checked');
        }
        
    }else{
        $(item).parent().removeClass('checked'); 
    }
    $(item).parent().siblings('.t_content_text').css('opacity',opac); 
}
function checkIfDoneMulti(){
    var tcheckboxes = $('input[type="checkbox"]','#tasks_sortable');
    var tcb = null;
    for(var i=0;i<tcheckboxes.length;i++){
        tcb = tcheckboxes.eq(i);
        checkIfDoneSingle(tcb);
    }
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
            type: function(){return $('#g_type').val()},
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
            var isNonIE8 = '';
            if(($.browser.msie  && parseInt($.browser.version, 10) != 8)||!$.browser.msie){
                isNonIE8 = 'isDoneNonIE8';
             }
            $(tgid,'#cache').prepend('<li privacy="0" tid="'+tidnew+'"class="t_content hoverable roundcorner"><div class="isDone '+isNonIE8+'"><input class="isdone_checkbox" type="checkbox"/></div><div class="t_content_text">'
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
    var gtype = $('#u_g_type').val();
    isNewGroup = false;
    positionGroup();
    makeAjaxCall('post',{
        tgid:tgid,
        title:title,
        priority:priority,
        type:gtype,
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
    var type = $('#u_g_type').val();
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
            type = $('#g_type').val();
            cachecontent = '<div gtype="'+type+'" priority="'+priority+'" id="'+tgid+'"></div>';
            $('#cache').prepend(cachecontent);
        }else{
            $('#'+tgid,'#group_wrapper').remove();
        }
        tgcontent = '<div gtype="'+type+'" priority="'+priority+'" id="'+tgid+'" class="tg_title"><div class="delete_group"></div><div class="tg_title_text"><span>'+title+'</span></div></div>';
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
                    if(id_temp<tgid){
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
        $('#'+tgid,'#group_wrapper').attr('gtype',type);
        $('#'+tgid,'#cache').attr('gtype',type);
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
function makeAjaxCall(type,param,callback,successCallback){
    loading_image.show(0);
    $.ajax({
        url:'service/web/webactions.php',
        timeout:6000,
        type:type,
        data:param,
        success:function(response){
            if(response==-1){
                location.reload();
            }else if(successCallback!=null){
                successCallback(response);
            }
            tidnew =tgidnew= response;
        },
        error:function(){
            
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
        showGroup = false;
        hideGroupPanel(isFromClick)
    }else{
        showGroup = true;
        showGroupPanel(isFromClick);
    }
}
function showGroupPanel(isFromClick){
        var animaTime = (isFromClick==true?200:0);
        var width = windowDiv.width();
        showGroup = true;
        $('#g_dialog,#t_dialog,#u_g_dialog').dialog('option','width',550);
        $('#panel_group').removeClass('hidden');
        $('#task_wrapper').animate({width:width-310},animaTime);
        $('.t_content_text').animate({width:width-420},animaTime);
        $('.t_content').animate({width:width-320},animaTime);
        $('.input_task').animate({width:width-320},animaTime);
        $('#panel_task').animate({marginLeft:270,width:width-270},animaTime);
        $('#panel_social').css({'right':-404,'width':400});
        $('#tg_selector').show(0);
        if(!isFromClick){
            $('#logout').show(0);
            $('.ui-toggle-switch').find('label').eq(0).click();
        }
}
function hideGroupPanel(isFromClick){
        var animaTime = (isFromClick==true?200:0)
        var width = windowDiv.width();
        showGroup = false;
        $('#g_dialog,#t_dialog,#u_g_dialog').dialog('option','width',width-10);
        $('#panel_task').animate({marginLeft:0,width:width},animaTime,function(){
            $('#panel_group').addClass('hidden');
        });
        $('#task_wrapper').animate({width:width-40},animaTime);
        $('.t_content_text').animate({width:width-150},animaTime);
        $('.t_content').animate({width:width-50},animaTime);
        $('.input_task').animate({width:width-50},animaTime);
        $('#panel_social').css({'right':-width+50,'width':width-50});
        $('#tg_selector').hide();
        if(!isFromClick){
            $('#logout').hide(0);
            $('.ui-toggle-switch').find('label').eq(1).click();
        }
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
    $('#show_group').each(function(i,item) {
                $(item).toggleSwitch({
                        highlight: $(item).data("highlight"),
                        width: 25
                });
    });
    $('#group_button').tipsy({fallback:'Group panel',gravity:'n',fade:true,offset:0});
    $('.ui-toggle-switch').find('label').eq(0).click(function(){showGroupPanel(true)});
    $('.ui-toggle-switch').find('label').eq(1).click(function(){hideGroupPanel(true)});
    
    $('#input_task').tipsy({fallback:'press ENTER to create new task',gravity:'n',fade:true});
    $('#g_dialog,#t_dialog,#u_g_dialog').dialog({autoOpen: false,height:400,width:500,modal:true,resizable:false,closeOnEscape: true});
    initTaskGroups(false);
    checkIfGroupExists();
    if(($.browser.msie  && parseInt($.browser.version, 10) != 8)||!$.browser.msie){
        $('.isDone').addClass('isDoneNonIE8');
    }
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
        $('#u_g_type').val($(tgid).attr('gtype'));
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
    $.imgpreload(['image/checkbox.png','image/checkbox_checked.png','image/button_close.png','theme/images/modalClose.png']);
});

