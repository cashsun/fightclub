function activeTitles(){
        $('.t_content').click(function(){
//        alert($(this).attr('tid'));
    });
}
$(document).ready(function(){
    activeTitles();
    $('.tg_title').click(function(){
        var tgid = '#'+$(this).attr('id');
        $('#panel_task').fadeOut(500, function(){
             $(this).html($(tgid,'#cache').html()).fadeIn(500);
             activeTitles();
        });
    });
    
    $(document).keypress(function(e) {
        if(e.which == 13) {
            if($('#input_task').val()!=''){
                
            }
        }
    });
});

