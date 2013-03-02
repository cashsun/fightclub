var atindex
$(document).ready(function(){
    commentInput.keyup(function(){
        var offset = commentInput.offset();
        var height = commentInput.height();
        autofill.offset({left:offset.left,top:offset.top+height});
        var src = commentInput.val();
        var list = src.split(" ");
        var str = list[list.length-1];
        atindex = str.indexOf("@");
        if(atindex>=0){
            autofill.fadeIn();
            var key = str.substring(atindex).replace("@","");
            if(key.indexOf(" ")<0&&key!=""){
                setTimeout(function(){
                    if(src == commentInput.val()){
                        getAutofill(key,autofill);
                    }
                },700);
            }
        }else{
            autofill.hide();
        }
    });
});
function getAutofill(key,autofill){
        makeAjaxCall('get',{key:key},function(){},function(resp){
            autofill.html(resp);
        },'view/at_list.php');
}
