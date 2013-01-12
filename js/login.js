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
          if(($(this).attr('name')=='cm-name' && $(this).val()=='username') || ($(this).attr('name')=='cm-pass' && $(this).val()=='password') )
            $(this).val('');
          var sib = $(this).siblings('.input-box');
          if(sib.val() == '')
          {
            if(sib.attr('name')=='cm-name')
              sib.val('username');
            else
              sib.val('password');
          }
        }
      );
      
    }
}