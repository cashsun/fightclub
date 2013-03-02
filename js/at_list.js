$(document).ready(function(){
    $(".at_friend_box").click(function(){
        var username = $(this).children().find(".f_username").html();
        var str = commentInput.val();
        var afterstr = str.substring(0, atindex)+"@"+username+" ";
        commentInput.focus().val(afterstr);
    });
});


