function getFightoList(e,t,n){fightolist.slideUp(200,function(){t.after(fightolist);loading_image.show(0,function(){makeSocialAjaxCall("get","view/fighto_list.php",{tid:e},function(e){fightolist.html(e)},function(){showSocial=true;fightolist.slideDown(200,function(){loading_image.hide(0,function(){$(".friend_image_ss",fightolist).tipsy({fade:false,gravity:"s"});if(n!=null){n()}})})})})})}function initTasks(){$(".t_content_text","#tasks_sortable").unbind("click").click(function(){$("#tid","#t_dialog").html($(this).parent().attr("tid"));$("#update_task").val($(this).text());var e=$(this).parent().attr("privacy");$("#deadline_date").val($(this).attr("dead_date"));$("#deadline_time").val($(this).attr("dead_time"));$("#t_dialog").dialog("open");$("#privacy"+e).next().click()}).mouseover(function(){$(this).css("color","white")}).mouseout(function(){$(this).css("color","#8d8f90")});$("#tasks_sortable").sortable({update:function(){updateTorder()},handle:".handle"});$(".texp").unbind("click").click(function(){var e=$(this).attr("mytid");var t=$(this).parent();getFightoList(e,t)});$(".comment").unbind("click").click(function(){var e=$(this);commentMain.slideUp(200,function(){var t=e.parent().attr("tid");if(t!=commentMain.attr("tid")){commentMain.attr("tid",t);e.parent().after(commentMain);loading_image.show(0,function(){getComments(t,0,commentMain.find("#comment_dialog"),function(){loading_image.hide(0,function(){commentMain.slideDown(200)})})})}else{commentMain.attr("tid",-1)}})});activeDeletes();initIsDone();if(!$("#u_g_dialog").dialog("isOpen")){$("#input_task").val("").focus()}}function updateTorder(){var e=$("#tgid").html();var t="";var n=",";visibleTasks=$("#tasks_sortable li");var r;for(var i=0;i<visibleTasks.length;i++){if(i==visibleTasks.length-1){n=""}r=visibleTasks.eq(i);t+=r.attr("tid")+n}$("#"+e,"#cache").html(visibleTasks.clone(true,true));makeAjaxCall("post",{tgid:e,t_order:t,webaction:7},function(){})}function initIsDone(){$('input[type="checkbox"]',"#tasks_sortable").unbind("change").change(function(){var e=$(this);checkIfDoneSingle(e);positionIsDone(e);var t=e.parent().parent().attr("tid");var n=0;if(e.is(":checked")){n=1}makeAjaxCall("post",{tid:t,isdone:n,webaction:8},function(){},function(e){if(e==-1){alert("you cannot undone public task!");location.reload()}})})}function initTaskGroups(e){$(".tg_title_text").unbind("click").click(function(){$("#cache").prepend(commentMain);var t=$(".tg_title_text").index(this);$("#tg_selector").css("top",t*51+"px");$("#tgid").html($(this).parent().attr("id"));var n="#"+$(this).parent().attr("id");$(".tg_title").removeClass("selected");$(".tg_title_text").removeClass("tg_text_selected");$(this).addClass("tg_text_selected").parent().addClass("selected");$("#tasks_sortable").fadeOut(200,function(){var t=$(n,"#cache");var r=$("li",t).clone(true).show(0);$(this).html(r);checkIfDoneMulti();$(this).fadeIn(200,function(){visibleTasks=$("#tasks_sortable li");initTasks()});resizeTaskPanel(e)})}).dblclick(function(e){e.preventDefault();$("#u_group").click()})}function checkIfDoneSingle(e){var t=1;var n="none";if($(e).is(":checked")){t=.4;n="line-through";if($(e).parent().hasClass("isDoneNonIE8")){$(e).parent().addClass("checked")}}else{$(e).parent().removeClass("checked")}$(e).parent().parent().css("opacity",t);$(e).parent().siblings(".t_content_text").css("text-decoration",n)}function checkIfDoneMulti(){var e=$('input[type="checkbox"]',"#tasks_sortable");var t=null;for(var n=0;n<e.length;n++){t=e.eq(n);checkIfDoneSingle(t)}}function positionIsDone(e){var t=$(e).parent().parent();if($(e).is(":checked")){t.slideUp(300,function(){$("#tasks_sortable").append(t);checkIfDoneMulti();t.children().find("input").attr("checked","checked");t.slideDown(300,function(){updateTorder()})})}else{t.slideUp(300,function(){$("#tasks_sortable").prepend(t);t.children().find("input").removeAttr("checked");checkIfDoneMulti();t.slideDown(300,function(){updateTorder()})})}}function activeDeletes(){$(".delete_task").unbind("click").click(function(){if(confirm("Delete this task?")){var e=$(this).parent().attr("tid");postDeleteTask(e)}})}function postCreateTaskGroup(){$(".dialog").dialog("close");var e=$.trim($("#input_group").val());var t=$("#g_priority > span").slider("value");var n=$("#g_type").val();makeAjaxCall("post",{uid:myuid,title:e,priority:t,type:n,webaction:1},function(){positionGroup(true,tgidnew,e,t,n);checkIfGroupExists()},function(e){tgidnew=e})}function postCreateTask(e){makeAjaxCall("post",{uid:myuid,tgid:function(){return $("#tgid").html()},content:e,webaction:0},function(){var t="";if($.browser.msie&&parseInt($.browser.version,10)!=8||!$.browser.msie){t="isDoneNonIE8"}$("#tasks_sortable").prepend('<li privacy="0" tid="'+tidnew+'"class="t_content hoverable roundcorner hidden"><div class="handle"></div><div title="❤" class="texp">0</div><div title="comment" class="comment"></div><div class="isDone '+t+'"><input title="complete" class="isdone_checkbox" type="checkbox"/></div><div dead_date="0000-00-00" dead_time="00:00:00" class="t_content_text">'+e+'</div><div class="delete_task"></div></li>');initTasks();resizeTaskPanel();$('[tid="'+tidnew+'"]',"#tasks_sortable").slideDown(300,function(){updateTorder()})},function(e){tidnew=e})}function postDeleteTaskGroup(e){$(".dialog").dialog("close");$("#"+e).slideUp(200,function(){$(this).remove();$("#"+e,"#cache").remove();checkIfGroupExists()});makeAjaxCall("post",{tgid:e,webaction:3},function(){checkIfGroupExists()})}function postDeleteTask(e){$('[tid="'+e+'"]',"#tasks_sortable").slideUp(200,function(){$(this).remove();$('li[tid="'+e+'"]',"#cache").remove();$("#input_task").focus()});makeAjaxCall("post",{tid:e,webaction:2},function(){updateTorder()})}function postUpdateTask(){$(".dialog").dialog("close");var e=$("#tid","#t_dialog").html();var t=$("#update_task").val();var n=$("#u_t_privacy :radio:checked").val();var r=$("#deadline_date").val()+" "+$("#deadline_time").val();var i=$('[tid="'+e+'"]',"#tasks_sortable");var s=i.children().eq(4);i.attr("privacy",n);s.attr("dead_date",$("#deadline_date").val()).attr("dead_time",$("#deadline_time").val()).html(t);switch(parseInt(n)){case 0:i.removeClass("shared_f shared_g");break;case 1:i.removeClass("shared_g").addClass("shared_f");break;case 2:i.removeClass("shared_f").addClass("shared_g");break}makeAjaxCall("post",{tid:function(){return $("#tid","#t_dialog").html()},content:function(){return $("#update_task").val()},privacy:n,deadline:function(){return r},webaction:4},function(){})}function postUpdateTaskGroup(){$(".dialog").dialog("close");var e=$("#tgid").html();var t=$.trim($("#update_group").val());var n=$("#u_g_priority > span").slider("value");var r=$("#u_g_type").val();positionGroup(false,e,t,n,r);makeAjaxCall("post",{tgid:e,title:t,priority:n,type:r,webaction:5},function(){})}function checkIfGroupExists(){if($(".tg_title").length==0){$("#tg_selector,#task_wrapper").hide(0)}else{$("#tg_selector,#task_wrapper").fadeIn(200);$(".tg_title_text").first().click()}}function positionGroup(e,t,n,r,i){$("#task_wrapper").show();var s=null;var o=null;var u=null;var a=null;var f=null;if(e){s='<div gtype="'+i+'" priority="'+r+'" id="'+t+'"></div>';$("#cache").prepend(s);o='<div gtype="'+i+'" priority="'+r+'" id="'+t+'" class="tg_title"><div class="delete_group"></div><div class="tg_title_text"><span>'+n+"</span></div></div>";u=$(".tg_title","#group_wrapper");if(u.length==0){$("#group_wrapper").append(o)}else{a=u.first();for(var l=0;l<u.length;l++){f=a.attr("priority");if(r>f){a.before(o);break}else if(r==f){id_temp=a.attr("id");if(id_temp<t){a.before(o)}else{a.after(o)}break}else if(l==u.length-1){a.after(o);break}else{a=a.next()}}}}else{if(originalPriority==r){$(".tg_title_text","#"+t).children().text(n);$("#"+t,"#group_wrapper").attr("gtype",i);$("#"+t,"#cache").attr("gtype",i)}else{$("#"+t,"#group_wrapper").remove();o='<div gtype="'+i+'" priority="'+r+'" id="'+t+'" class="tg_title"><div class="delete_group"></div><div class="tg_title_text"><span>'+n+"</span></div></div>";u=$(".tg_title","#group_wrapper");if(u.length==0){$("#group_wrapper").append(o)}else{a=u.first();for(var l=0;l<u.length;l++){f=a.attr("priority");if(r>f){a.before(o);break}else if(r==f){id_temp=a.attr("id");if(id_temp<t){a.before(o)}else{a.after(o)}break}else if(l==u.length-1){a.after(o);break}else{a=a.next()}}}}}initTaskGroups(false);$("#"+t,"#group_wrapper").children().eq(1).click();$("#"+t,"#group_wrapper").children().first().click(function(){if(confirm("Delete this group?")){var e=$(this).parent().attr("id");postDeleteTaskGroup(e)}})}function makeAjaxCall(e,t,n,r){loading_image.show(0);$.ajax({url:"service/web/webactions.php",timeout:6e3,type:e,data:t,success:function(e){if(r!=null){r(e)}else if(e==-1){location.reload()}},error:function(){},complete:function(){if(n!=null){n()}else{location.reload()}loading_image.hide(0)}})}function resizeTaskPanel(e){var t=windowDiv.width();if(t<650){showGroup=false;hideGroupPanel(e)}else{showGroup=true;showGroupPanel(e)}}function showGroupPanel(e){var t=e==true?200:0;var n=windowDiv.width();showGroup=true;$(".dialog").dialog("option","width",500);$("#panel_group").removeClass("hidden");$("#task_wrapper").animate({width:n-310},t);$(".t_content_text").animate({width:n-552},t);$(".t_content").animate({width:n-320},t);$(".input_task").animate({width:n-320},t);$("#panel_task").animate({marginLeft:270,width:n-270},t);$("#tg_selector").show(0);if(!e){$("#logout").show(0);$("#group_button .ui-toggle-switch").find("label").eq(0).click()}}function hideGroupPanel(e){var t=e==true?200:0;var n=windowDiv.width();showGroup=false;$(".dialog").dialog("option","width",n-25);$("#panel_task").animate({marginLeft:0,width:n},t,function(){$("#panel_group").addClass("hidden")});$("#task_wrapper").animate({width:n-40},t);$(".t_content_text").animate({width:n-282},t);$(".t_content").animate({width:n-50},t);$(".input_task").animate({width:n-50},t);$("#tg_selector").hide();if(!e){$("#logout").hide(0);$("#group_button .ui-toggle-switch").find("label").eq(1).click()}}function toggleGroupPanel(){if(showGroup){hideGroupPanel(true)}else{showGroupPanel(true)}}function postCreateComment(e,t,n){makeAjaxCall("post",{tid:e,content:$.trim(t),webaction:15},function(){},n)}function postDeleteComment(e,t){makeAjaxCall("post",{commentid:e,webaction:16},function(){},t)}function getComments(e,t,n,r){makeSocialAjaxCall("get","view/comments.php",{tid:e,lastcid:t},function(e){n.html(e);$(".comment_delete").unbind("click").click(function(){var e=$(this).attr("cid");if(confirm("Delete this comment?")){postDeleteComment(e,function(){var e=commentMain.attr("tid");getComments(e,0,commentDialog,function(){commentMain.find("textarea").val("")})})}})},r,true)}function initCommentBox(){commentMain=$("#comment_main");commentDialog=commentMain.find("#comment_dialog");commentMain.find("button").button().unbind("click").click(function(){var e=commentMain.find("textarea").val();if($.trim(e)!=""){commentMain.find("textarea").val("");var t=commentMain.attr("tid");postCreateComment(t,e,function(e){getComments(t,0,commentDialog,function(){})})}})}var commentMain;var loading_image;var priorityMap;var commentDialog;var searchText;var visibleTasks;var myuid;var fightolist;$(document).ready(function(){showGroup=true;$("#panel_task").click(function(){if(showSocial){$("#club_button").click()}});$("button").button();$("input.datepicker").datepicker();tidnew=tgidnew=-1;windowDiv=$(window);fightolist=$("#fightolist");loading_image=$("#loadingImage");initCommentBox();originalPriority=0;myuid=$("#uid").html();$("#tg_selector").click(function(){$("#panel_group").hide()});$("#logout").click(function(){location.replace("test/logout.php")});priorityMap=new Array("casual","very low","low","minor","medium","important","major","urgent","urgent+","immediate");$("#g_priority > span,#u_g_priority > span").each(function(){$(this).slider({range:"min",animate:false,min:0,max:9,step:1,orientation:"horizontal",slide:function(){var e=$(this).slider("value");$("#g_phint,#u_g_phint").html(priorityMap[e])},stop:function(){var e=$(this).slider("value");$("#g_phint,#u_g_phint").html(priorityMap[e])}})});$("#input_task").tipsy({fallback:"press ENTER to create new task",gravity:"n",fade:false});$("#input_task").keyup(function(){searchText=$.trim($(this).val().toLowerCase());visibleTasks=$("#tasks_sortable li").removeClass("highlight");if(searchText!=""){for(var e=0;e<visibleTasks.length;e++){if(visibleTasks.eq(e).find(".t_content_text").html().toLowerCase().indexOf(searchText)>=0){visibleTasks.eq(e).addClass("highlight")}}}});$(".dialog").dialog({autoOpen:false,height:500,width:500,modal:true,resizable:false,closeOnEscape:true});$("#u_t_privacy").buttonset();$("#show_group").toggleSwitch({highlight:true,width:25,callback:function(e){if(e==0){showGroupPanel(true)}else{hideGroupPanel(true)}}});$("#group_button").tipsy({fallback:"Group panel",gravity:"n",fade:false,offset:0});$("#deadline_date").datepicker({dateFormat:"yy-mm-dd"});$("#deadline_time").timepicker({timeFormat:"H:i:s"});initTaskGroups(false);checkIfGroupExists();if($.browser.msie&&parseInt($.browser.version,10)!=8||!$.browser.msie){$(".isDone").addClass("isDoneNonIE8")}$("#t_dialog").dialog("option","buttons",[{text:"OK",click:function(){if($("#update_task").val()!=""){postUpdateTask()}}}]);$("#g_dialog").dialog("option","buttons",[{text:"OK",click:function(){if($("#input_group").val()!=""){postCreateTaskGroup()}}}]);$("#u_g_dialog").dialog("option","buttons",[{text:"OK",click:function(){if($.trim($("#update_group").val())!=""){postUpdateTaskGroup()}}}]);$("#u_group").click(function(){var e="#"+$("#tgid").html();originalPriority=$(e).attr("priority");$("#update_group").val($(".tg_title_text",e).text());$("#u_g_priority > span").slider("value",originalPriority);$("#u_g_type").val($(e).attr("gtype"));$("#u_g_dialog").dialog("open")}).tipsy({fallback:"edit group content",gravity:"s"});$("#user_tabs").tabs();$("#profile_image").click(function(){$("#avatar_dialog").dialog("open")}).tipsy({fallback:"change Avatar",gravity:"n"});$(".avatar").click(function(){var e=$(this).attr("pid");$("#profile_image").attr("src","image/"+e+".png");$("#avatar_dialog").dialog("close");makeAjaxCall("post",{uid:myuid,avatar:e,webaction:11},function(){})});$("#create_group").click(function(){$("#g_dialog").dialog("open")}).tipsy({fallback:"create new group",gravity:"nw"});$("#option").click(function(){$("#user_dialog").dialog("open")}).tipsy({fallback:"user options",gravity:"s"});$(".delete_group").click(function(){if(confirm("Delete this group?")){var e=$(this).parent().attr("id");postDeleteTaskGroup(e)}});$(window).resize(function(){resizeTaskPanel(false)});$(document).keypress(function(e){if(e.which==13){e.preventDefault();if($("#g_dialog").dialog("isOpen")){if($.trim($("#input_group").val())!=""){postCreateTaskGroup()}}else if($("#t_dialog").dialog("isOpen")){if($.trim($("#update_task").val())!=""){postUpdateTask()}}else if($("#u_g_dialog").dialog("isOpen")){if($.trim($("#update_group").val())!=""){postUpdateTaskGroup()}}else if($("#input_task").is(":focus")){if($.trim($("#input_task").val())!=""){postCreateTask($.trim($("#input_task").val()));$("#input_task").val("")}}else if($("#input_friend").is(":focus")){if($.trim($("#input_friend").val())!=""){$("#friends_wrapper").hide(0,function(){makeSocialAjaxCall("get","view/friends.php",{ftype:3,key:function(){return $.trim($("#input_friend").val())}},function(e){$("#friends_wrapper").html(e).fadeIn(200)},function(){})})}}else if($("#comment_input").is(":focus")){var t=commentMain.find("textarea").val();if($.trim(t)!=""){commentMain.find("textarea").val("");var n=commentMain.attr("tid");postCreateComment(n,t,function(e){getComments(n,0,commentDialog,function(){})})}}}});$.imgpreload(["image/checkbox.png","image/checkbox_checked.png","image/button_close.png","theme/images/modalClose.png","image/delfriend.png","image/addfriend.png","image/ribbon_f.png","image/ribbon_g.png"])})