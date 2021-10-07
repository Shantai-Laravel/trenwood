$().ready(function(e){
    $el = $('.valid').children('b');
    for (var i = 0; i < $el.length; i++) {
        $el = $('.valid').children('b').eq(i).text();
        $res = $el.replace(".", "-");
        $('#'+$res).parent().addClass('has-error');
    }

    setTimeout(function() { $('.faded').fadeOut() }, 5000);
    $('.faded').on('click', function(){
        $(this).fadeOut();
    });
});
