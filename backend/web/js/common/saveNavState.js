$(function () {
    var $body = $('body');
    $body.on('click','a.sidebar-toggle',function () {
        var bool = $body.hasClass('sidebar-collapse');
        var type = bool ? 0 : 1 ;
        if(bool){
            $body.removeClass('sidebar-collapse')
        }else{
            $body.addClass('sidebar-collapse')
        }
        $.ajax({
            url:'/user/get-side-bar?type=' + type,
            type:'GET',
            dataType:'json'
        }).done(function (res) {
            console.log(res.success);
        }).fail(function (error) {
            console.log(error);
        })
    })
})