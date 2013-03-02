var commentInput;
var atindex
$(document).ready(function(){
    commentInput = commentMain.find('textarea');
    commentInput.keyup(function(){
        var str = commentInput.val().toLowerCase();
        atindex = str.indexOf("@");
        if(atindex>=0){
            var offset = commentInput.offset();
//            autofill.offset({left:offset.left,top:offset.top+53}).show();
autofill.show();
            var key = str.substring(atindex).replace("@","");
            if(key.indexOf(" ")<0&&key!=""){
                setTimeout(function(){
                    if(str == commentInput.val().toLowerCase()){
                        getAutofill(key,autofill);
                    }
                },700);
            }
        }
    });
});
function getAutofill(key,autofill){
        makeAjaxCall('get',{key:key},function(){},function(resp){
            autofill.html(resp);
        },'view/at_list.php');
}
