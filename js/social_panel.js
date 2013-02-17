var socialLoading;
$(document).ready(function(){
    socialLoading = $('#social_loading');
    showSocial = false;
    clubBtnAlrm=$('#club_button').children().find(".club_alarm");
    friendTabAlrm=$("li","#social_tabs").eq(0).children().find('.club_alarm');
    newsTabAlrm = $("li","#social_tabs").eq(1).children().find('.club_alarm');
    getAlarms();
    $('#social_tabs').tabs();
    $("#friends_radios").buttonset();
    $('#input_friend').tipsy({fallback:'ENTER to search',gravity:'s',fade:false,offset:0});
    $('li[aria-controls=tabs-1]').click(function(){
        $('#radio0').click();
    });
    $('li[aria-controls=tabs-2]').click(function(){
        getNews();
    });
    $('#club_button').click(function(){  
        if(!showSocial){
            $('#panel_social').animate({right: 0},300,function(){
                if($('#friends_wrapper').html()==''){
                    $('li[aria-controls=tabs-1]').click();
                }
                showSocial = true;
            });
        }else{
                var width = $('#panel_social').width();
                $('#panel_social').animate({right: -width-4},300);
                showSocial = false; 
        }
    });
    $('#radio0').click(function(){
        getMyFollows();
    });
    $('#radio1').click(function(){
        getMyFriends();
    });
    $('#radio2').click(function(){
        getMyFans();
    });
});
function getMyFollows(){
        $('#friends_wrapper').hide(0,function(){
            makeSocialAjaxCall('get','view/friends.php',{ftype:0},function(resp){
                $('#friends_wrapper').html(resp).fadeIn(200);
                },function(){showSocial = true});
        });
}

function getMyFriends(){
        $('#friends_wrapper').hide(0,function(){
            makeSocialAjaxCall('get','view/friends.php',{ftype:1},function(resp){
                getAlarmOnce();
                $('#friends_wrapper').html(resp).fadeIn(200);
                },function(){showSocial = true});
        });
}
function getMyFans(){
        $('#friends_wrapper').hide(0,function(){
            makeSocialAjaxCall('get','view/friends.php',{ftype:2},function(resp){
                getAlarmOnce();
                $('#friends_wrapper').html(resp).fadeIn(200);
                },function(){showSocial = true});
        });
}
function getNews(){
        $('#news_wrapper').hide(0,function(){
            makeSocialAjaxCall('get','view/news.php',null,function(resp){
                getAlarmOnce();
                $('#news_wrapper').html(resp).fadeIn(200);
                },function(){showSocial = true});
        });
}
function getUserLists(fuid,callback){
    $('#friends_wrapper').hide(0,function(){
        makeSocialAjaxCall('get','view/friend_list.php',{fuid:fuid},function(resp){
            $('#friends_wrapper').html(resp).show('slide',{direction:'right'},200);
            },function(){showSocial = true});
    });
    if(callback!=null){callback();}
}
function makeSocialAjaxCall(type,url,param,successCallback,callback,isForComments){
    socialLoading.fadeIn(0,function(){
        if(!isForComments){
            $('#cache').prepend(commentMain);
        }
        $.ajax({
            url:url,
            timeout:6000,
            type:type,
            data:param,
            success:function(response){
                if(response==-2){
                    location.reload();
                }else if(successCallback!=null){
                    successCallback(response);
                }
            },
            error:function(){
                $('#friends_wrapper').html('<div style="font-size:0.8em;text-align:center;margin-top:50px">server error. Try again later.</div>').fadeIn(200);
            },
            complete:function(){
                if(callback != null){callback();}
                else{
                    location.reload();
                }
                socialLoading.fadeOut(0);
            }
        });
    });
}
function updateTaskAlarm(tid){
    var tasks = $('li[tid="'+tid+'"]');
    for(var i=0;i<tasks.length;i++){
        tasks.eq(i).children().eq(2).addClass('comment_new').html('*');
    }
}
function updateTgAlarm(tgid,counter){
    var targetTg = $("#"+tgid,"#group_wrapper");
    var targetAlarm = $(".tg_alarm",targetTg);
    targetAlarm.addClass("tg_alarm_new");
    targetAlarm.html(counter);
}
function updateClubAlarm(flcounter,newsCounter){
    clubBtnAlrm.html(flcounter+newsCounter).addClass("club_alarm_new");
    updateNewsAlarm(newsCounter);
    updateFriendAlarm(flcounter);
}
function updateFriendAlarm(flcounter){
    if(flcounter>0){friendTabAlrm.html(flcounter).addClass("club_alarm_new");}
}
function updateNewsAlarm(newsCounter){
    if(newsCounter>0){newsTabAlrm.html(newsCounter).addClass("club_alarm_new");}
}
function getAlarms(){
    getAlarmOnce();
    setTimeout(function(){
        getAlarms();
    },10000);
}
var clubBtnAlrm;
var friendTabAlrm;
var newsTabAlrm;
function getAlarmOnce(){
    makeAjaxCall('post',{webaction:19},function(){},function(r){
        clubBtnAlrm.removeClass("club_alarm_new");
        friendTabAlrm.removeClass("club_alarm_new");
        newsTabAlrm.removeClass("club_alarm_new");
        $('.tg_alarm').removeClass("tg_alarm_new"); 
        var jsonArray = $.parseJSON(r);
        var tempTgid=-1;
        var tempTid = -1;
        var counter = 0;
        var flcounter = 0;
        var update = false;
        for(var i=0;i<jsonArray.length;i++){
            switch(parseInt(jsonArray[i].alarmtype)){
                case 0:console.log("new follow!");
                    flcounter++;
                    break;
                case 1:console.log("new comment!");
                    var tgid = jsonArray[i].tgid;
                    updateTaskAlarm(jsonArray[i].tid);
                    if(tempTgid == -1)
                      tempTgid = tgid;
                    counter++;
                    if(i==jsonArray.length-1 || jsonArray[i+1].alarmtype == 2 || jsonArray[i+1].tgid!=tgid)
                      update = true;
                    if(update)
                    {
                      updateTgAlarm(tgid, counter);
                      update = false;
                      counter = 0;
                    }
                    break;
                case 2:console.log("new fighto!");break;
                case 3:console.log("news!");
                    updateClubAlarm(flcounter,jsonArray[i].tid);
                    break;
            }
        }
    });
}