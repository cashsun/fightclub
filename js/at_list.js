$(document).ready(function(){
    $(".at_friend_box").click(function(){
        var username = $(this).children().find(".f_username").html();
        var src = commentInput.val();
        var list = src.split(" ");
        var str = list[list.length-1];
        var afterstr = "";
        for(var i=0;i<list.length-1;i++){
            afterstr+=list[i]+" ";
        }
        afterstr += str.substring(0, atindex)+"@"+username+" ";
        commentInput.focus().val(afterstr);
    });
});


