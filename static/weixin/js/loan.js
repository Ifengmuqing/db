$(function(){
    $(".tabs .tab").click(function(){
        // alert($(this).index());
        var index=$(this).index();
        $(this).addClass("active").siblings().removeClass("active");
        console.log(index);
        $(".content .content_tab").eq(index).show().siblings().hide();
    });
})