function getFightoList(tid,target,callback){
    fightolist.slideUp(200,function(){
        target.after(fightolist);
        loading_image.show(0,function(){
        makeSocialAjaxCall('get','view/fighto_list.php',{tid:tid},function(resp){
        fightolist.html(resp);
        },function(){showSocial = true;
            fightolist.slideDown(200,function(){
                loading_image.hide(0,function(){
                    $('.friend_image_ss',fightolist).tipsy({fade:false,gravity:"s"});
                    if(callback!=null){callback();}
                })
            })});
        });
    });
}
function initTasks(){
    $('.t_content_text','#tasks_sortable').unbind('click').click(function(){
        $('#tid','#t_dialog').html($(this).parent().attr('tid'));
        $('#update_task').val(($(this).text()));
        var p = $(this).parent().attr('privacy');
        $('#deadline_date').val($(this).attr('dead_date'));
        $('#deadline_time').val($(this).attr('dead_time'));
        $('#t_dialog').dialog('open');
        $('#privacy'+p).next().click();
    }).mouseover(function(){$(this).css('color','white');
        }).mouseout(function(){$(this).css('color', '#8d8f90');
    });
    $('#tasks_sortable').sortable({
                update:function(){
                    updateTorder();
                },
                handle:'.handle'
    });
    $('.texp').unbind('click').click(function(){
        var tid = $(this).attr("mytid");
        var target = $(this).parent();
        getFightoList(tid,target);
    });
    $('.comment').unbind('click').click(function(){
        var target = $(this);
        autofill.hide();
        commentInput.val("");
        commentMain.slideUp(200,function(){
            var ctid = target.parent().attr('tid');
            if(ctid!=commentMain.attr('tid')){
                commentMain.attr('tid',ctid);
                target.parent().after(commentMain);
                loading_image.show(0,function(){
                    getComments(ctid,0,commentMain.find('#comment_dialog'),
                    function(){
                        loading_image.hide(0,function(){
                            commentMain.slideDown(200);
                        });
                    });
                }) 
            }else{
                commentMain.attr('tid',-1);
            }
        });
    });
    activeDeletes();
    initIsDone();
    if(!$("#u_g_dialog").dialog( "isOpen" )){$('#input_task').val('').focus();}
}
var autofill;
var commentMain;
//update t_order and cache
function updateTorder(){
    var current_tgid = $('#tgid').html();
    var task_order = '';
    var comma = ',';
    visibleTasks = $('#tasks_sortable li');
    autofill = $("#autofill");
    var task;
    for(var i=0;i<visibleTasks.length;i++){
        if(i==visibleTasks.length-1){
            comma = '';
        }
        task = visibleTasks.eq(i);
        task_order +=task.attr('tid')+comma;
    }
    $('#'+current_tgid,'#cache').html(visibleTasks.clone(true,true));
    makeAjaxCall('post',{
        tgid:current_tgid,
        t_order:task_order,webaction:7},function(){});
}
function initIsDone(){
    $('input[type="checkbox"]','#tasks_sortable').unbind('change').change(function(){       
        var tcheckbox = $(this);
        checkIfDoneSingle(tcheckbox);
        positionIsDone(tcheckbox);
        var tid = tcheckbox.parent().parent().attr('tid');
        var isdone = 0;
        if(tcheckbox.is(':checked')){isdone = 1};
            makeAjaxCall('post',
            {tid:tid,
                isdone:isdone,
                webaction:8},function(){},function(r){
                    if(r==-1){
                        alert('Server error.');
                        location.reload();
                    }
                        var expNew = tcheckbox.parent().siblings('.texp').html();
                        updateExp(expNew,r);
                });
    });
}
function initTaskGroups(isFromClick){
    $('.tg_title_text').unbind('click').click(function(){
        $('#cache').prepend(commentMain);
        
        var index = $('.tg_title_text').index(this);
        $('#tg_selector').css('top', index*51+'px');
        $('#tgid').html($(this).parent().attr('id'));
        var tgid = '#'+$(this).parent().attr('id');
        $('.tg_title').removeClass('selected');
        $('.tg_title_text').removeClass('tg_text_selected');
        $(this).addClass('tg_text_selected').parent().addClass('selected');
        $('#tasks_sortable').fadeOut(200, function(){
            var targetCacheGroup = $(tgid,'#cache');
            var task_content = $('li',targetCacheGroup).clone(true).show(0);
             $(this).html(task_content);
             checkIfDoneMulti();
            $(this).fadeIn(200, function(){
                visibleTasks = $('#tasks_sortable li');
                initTasks();
            });
            resizeTaskPanel(isFromClick);
        });
    }).dblclick(function(e){
        e.preventDefault();
        $('#u_group').click();
    });
}
function checkIfDoneSingle(item){
    var opac = 1;
    var text_dec = 'none';
    if($(item).is(':checked')){
        opac = 0.4;
        text_dec = 'line-through';
        if($(item).parent().hasClass('isDoneNonIE8')){
            $(item).parent().addClass('checked');
        }
    }else{
        $(item).parent().removeClass('checked'); 
    }
    $(item).parent().parent().css('opacity',opac);
    $(item).parent().siblings('.t_content_text').css('text-decoration',text_dec); 
}
function checkIfDoneMulti(){
    var tcheckboxes = $('input[type="checkbox"]','#tasks_sortable');
    var tcb = null;
    for(var i=0;i<tcheckboxes.length;i++){
        tcb = tcheckboxes.eq(i);
        checkIfDoneSingle(tcb);
    }
}
function positionIsDone(item){
    var target = $(item).parent().parent();
    if($(item).is(':checked')){
        target.slideUp(300,function(){
            $('#tasks_sortable').append(target);
            checkIfDoneMulti();
            target.children().find('input').attr('checked','checked');
            target.slideDown(300,function(){
                updateTorder();
            });
        });
    }else{
        target.slideUp(300,function(){
            $('#tasks_sortable').prepend(target);
            target.children().find('input').removeAttr('checked');
            checkIfDoneMulti();
            target.slideDown(300,function(){
                updateTorder();
            })
        });
    }
    
}
function activeDeletes(){
    $('.delete_task').unbind('click').click(function(){
        if(confirm ("Delete this task?")){
            var tid = $(this).parent().attr('tid');
            postDeleteTask(tid);
        }
    });
}
function postCreateTaskGroup(){
    $('.dialog').dialog('close');
    var title = $.trim($('#input_group').val());
    var priority = $('#g_priority > span').slider('value');
    var gtype = $('#g_type').val();
    makeAjaxCall('post',
            {uid:myuid,
            title:title,
            priority: priority,
            type: gtype,
            webaction:1},function(){
                positionGroup(true,tgidnew,title,priority,gtype);        
                checkIfGroupExists();
            },function(response){tgidnew = response})
}
function postCreateTask(content){
    makeAjaxCall('post',
        {uid:myuid,
            tgid:function(){return $('#tgid').html()},
            content:content,
            webaction:0},
        function(){
            var isNonIE8 = '';
            if(($.browser.msie && parseInt($.browser.version, 10) != 8)||!$.browser.msie){
                isNonIE8 = 'isDoneNonIE8';
             }
            $('#tasks_sortable').prepend('<li privacy="0" tid="'+tidnew+'"class="t_content hoverable roundcorner hidden"><div class="handle"></div><div mytid="'+tidnew+'" title="❤" class="texp">0</div><div title="comment" class="comment">0</div><div class="isDone '+isNonIE8+'"><input class="isdone_checkbox" title="complete" type="checkbox"/></div><div dead_date="0000-00-00" dead_time="00:00:00" class="t_content_text">'
+content+'</div><div class="delete_task"></div><div class="tshare"><div></li>');
            initTasks();
            resizeTaskPanel();
            $('[tid="'+tidnew+'"]','#tasks_sortable').slideDown(300,function(){updateTorder()});
            
        },function(response){tidnew = response;});
}
function postDeleteTaskGroup(tgid){
        $('.dialog').dialog('close');
        $('#'+tgid).slideUp(200,function(){
            $(this).remove();
            $('#'+tgid,'#cache').remove();
            checkIfGroupExists();
        });
        makeAjaxCall('post',{tgid:tgid,webaction:3},function(){checkIfGroupExists()});
}
function postDeleteTask(tid){
        var expNew = $('[tid="'+tid+'"]','#tasks_sortable').children().eq(1).html();
        $('[tid="'+tid+'"]','#tasks_sortable').slideUp(200, function(){
            
            $(this).remove();
            $('[tid="'+tid+'"]','#cache').remove();
            $('#input_task').focus();
        })
        makeAjaxCall('post',
            {tid:tid,webaction:2},function(){
                updateTorder();
            },function(r){
                updateExp(expNew,r);
            })
}
function updateExp(expNew,actionCode){
    var expOld = $('.my_exp').html().split(" ")[1];
    if(actionCode==1){
        $('.my_exp').html("Exp "+(parseInt(expOld)+parseInt(expNew)));
    }else if(actionCode==2){
        $('.my_exp').html("Exp "+(parseInt(expOld)-parseInt(expNew)));
    }
}
function postUpdateTask(){
    $('.dialog').dialog('close');
    var tid = $('#tid','#t_dialog').html();
    var content = $('#update_task').val();
    var privacy = $('#u_t_privacy :radio:checked').val();
    var deadline = $('#deadline_date').val()+' '+$('#deadline_time').val();
    var task = $('[tid="'+tid+'"]','#tasks_sortable');
    var t_text = task.children().eq(4);
    task.attr('privacy', privacy);
    t_text.attr('dead_date', $('#deadline_date').val()).attr('dead_time', $('#deadline_time').val()).html(content);
    switch(parseInt(privacy)){case 0:task.children().eq(6).removeClass('shared_f shared_g');break;case 1:task.children().eq(6).removeClass('shared_g').addClass('shared_f');break;case 2:task.children().eq(6).removeClass('shared_f').addClass('shared_g');break;}
    makeAjaxCall('post',{
        tid:function(){return $('#tid','#t_dialog').html()},
        content:function(){return $('#update_task').val()},
        privacy:privacy,
        deadline:function(){return deadline},
        webaction:4
        },function(){},function(r){
            var expNew = task.children().eq(1).html();
            updateExp(expNew,r);
        }
        );
}
function postUpdateTaskGroup(){
    $('.dialog').dialog('close');
    var tgid = $('#tgid').html();
    var title = $.trim($('#update_group').val());
    var priority = $('#u_g_priority > span').slider('value');
    var gtype = $('#u_g_type').val();
    positionGroup(false,tgid,title,priority,gtype);
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
function positionGroup(isNewGroup,tgid,title,priority,gtype){
    $('#task_wrapper').show();     
    var cachecontent = null;
    var tgcontent = null;
    var groups = null;
    var group = null;
    var p_temp = null
    if(isNewGroup){
            cachecontent = '<div gtype="'+gtype+'" priority="'+priority+'" id="'+tgid+'"></div>';
            $('#cache').prepend(cachecontent);
        
        tgcontent = '<div gtype="'+gtype+'" priority="'+priority+'" id="'+tgid+'" class="tg_title"><div class="delete_group"></div><div class="tg_title_text"><span>'+title+'</span></div><div class="roundcorner tg_alarm"></div></div>';
        groups = $('.tg_title','#group_wrapper');
        if(groups.length == 0){
            $('#group_wrapper').append(tgcontent);
        }else{
            group = groups.first();
            for(var i=0;i<groups.length;i++){
                p_temp = group.attr('priority');
                if(priority>p_temp){
                    group.before(tgcontent);
                    break;
                }else if(priority==p_temp){
                    id_temp = group.attr('id');
                    if(id_temp<tgid){
                        group.before(tgcontent);
                    }else{
                        group.after(tgcontent);
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
        if(originalPriority==priority){
            $('.tg_title_text','#'+tgid).children().text(title);
            $('#'+tgid,'#group_wrapper').attr('gtype',gtype);
            $('#'+tgid,'#cache').attr('gtype',gtype);
        }else{
            $('#'+tgid,'#group_wrapper').remove();
            tgcontent = '<div gtype="'+gtype+'" priority="'+priority+'" id="'+tgid+'" class="tg_title"><div class="delete_group"></div><div class="tg_title_text"><span>'+title+'</span></div></div>';
            groups = $('.tg_title','#group_wrapper');
            if(groups.length == 0){
            $('#group_wrapper').append(tgcontent);
            }else{
                group = groups.first();
                for(var i=0;i<groups.length;i++){
                    p_temp = group.attr('priority');
                    if(priority>p_temp){
                        group.before(tgcontent);
                        break;
                    }else if(priority==p_temp){
                        id_temp = group.attr('id');
                        if(id_temp<tgid){
                            group.before(tgcontent);
                        }else{
                            group.after(tgcontent);
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
        }
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
function makeAjaxCall(type,param,callback,successCallback,url){
    loading_image.show(0);
    var realurl = url;
    if(realurl == null){
        realurl = 'service/web/webactions.php';
    }
    $.ajax({
        url:realurl,
        timeout:6000,
        type:type,
        data:param,
        success:function(response){
            if(response == -2){
                location.reload();
            }else if(successCallback!=null){
                successCallback(response);
            }
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
function resizeTaskPanel(isFromClick){
    var width = windowDiv.width();
    if(width<650){
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
        $('.dialog').dialog('option','width',500);
        $('#panel_group').removeClass('hidden');
        $('#task_wrapper').animate({width:width-310},animaTime);
        $('.t_content_text').animate({width:width-552},animaTime);
        $('.t_content').animate({width:width-320},animaTime);
        $('.input_task').animate({width:width-320},animaTime);
        $('#panel_task').animate({marginLeft:270,width:width-270},animaTime);
        $('#tg_selector').show(0);
        if(!isFromClick){
            $('#logout').show(0);
            $('#group_button .ui-toggle-switch').find('label').eq(0).click();
        }
}
function hideGroupPanel(isFromClick){
        var animaTime = (isFromClick==true?200:0)
        var width = windowDiv.width();
        showGroup = false;
        $('.dialog').dialog('option','width',width-25);
        $('#panel_task').animate({marginLeft:0,width:width},animaTime,function(){
            $('#panel_group').addClass('hidden');
        });
        $('#task_wrapper').animate({width:width-40},animaTime);
        $('.t_content_text').animate({width:width-282},animaTime);
        $('.t_content').animate({width:width-50},animaTime);
        $('.input_task').animate({width:width-50},animaTime);
        $('#tg_selector').hide();
        if(!isFromClick){
            $('#logout').hide(0);
            $('#group_button .ui-toggle-switch').find('label').eq(1).click();
        }
}
function toggleGroupPanel(){
    if(showGroup){
        hideGroupPanel(true);
    }else{
        showGroupPanel(true);
    }
}
function postCreateComment(tid,content,callback){
    makeAjaxCall('post',{
            tid:tid,
            content:$.trim(content),
            webaction:15},function(){},callback);
}
function postDeleteComment(cid,callback){
    makeAjaxCall('post',{
            commentid:cid,
            webaction:16},function(){},callback);
}
function getComments(tid,lastcid,target,callback){
    makeSocialAjaxCall('get','view/comments.php',{tid:tid,lastcid:lastcid},function(resp){
        if(resp==-2){
            location.reload();
        }else if(resp==-1){
            target.html("oops,please try again later.");
        }else{
            getAlarmOnce();
            target.html(resp);
            var tasks = $('li[tid="'+tid+'"]');
            for(var i=0;i<tasks.length;i++){
                tasks.eq(i).children().eq(2).removeClass('comment_new').html($(".ccount").html());
            }
            $('.comment_delete').unbind('click').click(function(){
                var cid = $(this).attr('cid');
                if(confirm('Delete this comment?')){   
                    postDeleteComment(cid,function(){
                        var tid = commentMain.attr('tid');
                        getComments(tid,0,commentDialog,function(){
                            commentMain.find('textarea').val('');
                        });
                    });
                }
            });
        }
    },callback,true);
}
function initCommentBox(){
    commentMain =$('#comment_main');
    commentInput = commentMain.find('textarea');
    commentDialog=commentMain.find('#comment_dialog');
    commentMain.find('button').button().unbind('click').click(function(){
        var content = commentMain.find('textarea').val();
        if($.trim(content)!=''){
            commentMain.find('textarea').val('');
            var tid = commentMain.attr('tid');
             postCreateComment(tid,content,function(r){
                 getComments(tid,0,commentDialog,function(){});
             });
        }
    });
}
var loading_image;
var priorityMap;
var commentDialog;
var searchText;
var visibleTasks;
var myuid;
var fightolist;
var commentInput;
$(document).ready(function(){
    showGroup = true;
    $('#panel_task').click(function(){
        if(showSocial){
            $('#club_button').click();
        }
    });
    $('button').button();
    $('input.datepicker').datepicker();
    tidnew = tgidnew= -1;
    windowDiv = $(window);
    fightolist = $('#fightolist');
    loading_image = $('#loadingImage');
    initCommentBox();
    originalPriority = 0; 
    autofill = $('#autofill');
    myuid = $('#uid').html();
    $('#tg_selector').click(function(){
        $('#panel_group').hide();
    });
    $('#logout').click(function(){
        location.replace("service/logout.php");
    });
    priorityMap = new Array("casual","very low","low","minor","medium","important","major","urgent","urgent+","immediate");
    $('#g_priority > span,#u_g_priority > span').each(function(){
        $(this).slider({
            range:"min",animate:false,min:0,max:9,step:1,orientation:'horizontal',
            slide:function(){
                var index=$(this).slider('value');
                $('#g_phint,#u_g_phint').html(priorityMap[index]);
            },stop:function(){
                var index=$(this).slider('value');
                $('#g_phint,#u_g_phint').html(priorityMap[index]);
            }
        });
    });
    $('#input_task').tipsy({fallback:'press ENTER to create new task',gravity:'n',fade:false});
    $('#input_task').keyup(function() {
            searchText = $.trim($(this).val().toLowerCase());
            visibleTasks = $('#tasks_sortable li').removeClass('highlight');
            if(searchText!=''){
                for(var i=0;i<visibleTasks.length;i++){
                    if(visibleTasks.eq(i).find('.t_content_text').html().toLowerCase().indexOf(searchText)>=0){
                        visibleTasks.eq(i).addClass('highlight');
                    }
                }
            }						
    });
    $('.dialog').dialog({autoOpen: false,height:500,width:500,modal:true,resizable:false,closeOnEscape: true});
    $('#u_t_privacy').buttonset();
    $('#show_group').toggleSwitch({
            highlight: true,
            width: 25,
            callback:function(i){
                if(i==0){showGroupPanel(true)}else{hideGroupPanel(true)}
            }
    });
    $('#group_button').tipsy({fallback:'Group panel',gravity:'n',fade:false,offset:0});
    $('#deadline_date').datepicker({dateFormat: 'yy-mm-dd'});
    $('#deadline_time').timepicker({'timeFormat': 'H:i:s'});
    initTaskGroups(false);
    checkIfGroupExists();
    if(($.browser.msie && parseInt($.browser.version, 10) != 8)||!$.browser.msie){
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
        $('#u_g_priority > span').slider('value',originalPriority);
        $('#u_g_type').val($(tgid).attr('gtype'));
        $('#u_g_dialog').dialog('open');
    }).tipsy({fallback:'edit group content',gravity:'s'});
    
    $('#user_tabs').tabs();
    $('#profile_image').click(function(){
        $('#avatar_dialog').dialog('open');
    }).tipsy({fallback:'change Avatar',gravity:'n'});
    $('.avatar').click(function(){
        var avatar = $(this).attr('pid');
        $('#profile_image').attr('src','image/'+avatar+'.png');
        $('#avatar_dialog').dialog('close');
        makeAjaxCall('post',{
        uid:myuid,
        avatar:avatar,
        webaction:11},function(){});
    });
    
    $('#create_group').click(function(){
        $('#g_dialog').dialog('open');
    }).tipsy({fallback:'create new group',gravity:'nw'});
    
    $('#option').click(function(){
        $('#user_dialog').dialog('open');
    }).tipsy({fallback:'user options',gravity:'s'});
    
    $('.delete_group').click(function(){
        if(confirm ("Delete this group?")){
            var tgid = $(this).parent().attr('id');
            postDeleteTaskGroup(tgid);
        }
    });
    
    $(window).resize(function() {
        resizeTaskPanel(false);
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
            }else if($('#input_task').is(':focus')){
                if($.trim($('#input_task').val())!=''){
                    postCreateTask($.trim($('#input_task').val()));
                    $('#input_task').val('');
                }
            }else if($('#input_friend').is(':focus')){
                if($.trim($('#input_friend').val())!=''){
                    $('#friends_wrapper').hide(0,function(){
                        makeSocialAjaxCall('get','view/friends.php',{
                        ftype:3,
                        key:function(){return $.trim($('#input_friend').val())}
                    },function(resp){
                        $('#friends_wrapper').html(resp).fadeIn(200);
                    },function(){});
                    })
                }
            }else if($('#comment_input').is(':focus')){
                var content = commentMain.find('textarea').val();
                if($.trim(content)!=''){
                    commentMain.find('textarea').val('');
                    var tid = commentMain.attr('tid');
                     postCreateComment(tid,content,function(r){
                         getComments(tid,0,commentDialog,function(){});
                     });
                }   
            }
        }
    });
    $.imgpreload(['image/checkbox.png','image/checkbox_checked.png','image/button_close.png','theme/images/modalClose.png','image/delfriend.png','image/addfriend.png','image/ribbon_f.png','image/ribbon_g.png']);
});