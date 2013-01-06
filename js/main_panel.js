$(document).ready(function(){
    $('.t_content').click(function(){
        alert($(this).attr('tid'));
    });
    
    $('.tg_title').click(function(){
        var tgid = '#'+$(this).attr('id');
        $(tgid,'#cache')
    });
});

