$(function(){
    index.init();
});

var index = {
    init : function()
    {
        var inputBox = $('.front-login .input-box');
        
        //attach relevant listeners
        index.attachListeners(inputBox);
    },
    attachListeners : function(inputBox)
    {
      inputBox.focus(
        function(){
          if(($(this).attr('name')=='cm-name' && $(this).val()=='username or email') || ($(this).attr('name')=='cm-pass' && $(this).val()=='password') )
            $(this).val('');
        }
      );
      inputBox.blur(
        function(){
          var box = $(this);
          if(box.val() == '')
          {
            if(box.attr('name')=='cm-name')
              box.val('username or email');
          }
        }
      );
    }
}