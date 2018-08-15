$(function(){
    $('.close').click(function(){
        $("#suction").hide();
    });
    $('#mobile').on('focus', function() {
        $('.registMiddle').css('border-color', 'rgb(15, 136, 235)')
    });
    $('#mobile').on('blur', function() {
        $('.registMiddle').css('border-color', '#b3bcc5')
    });
    // 返回顶部
    $('#sucSumbit').on('click', function() {
        $('body,html').animate({
            scrollTop: 0
        }, 500);
        $('#mobile').focus();
    })
    // 
    $('.loan_way').on('click', function() {
        $('html,body').animate({
            scrollTop: '0px'
        }, 800, function() {
            $('#mobile').focus()
        })
    });
    // 轮播
    var slideImages=$('.slider_images').children()
    var slideTips=$('.slider_tips').children()
    var zIndex=1
    slideImages.eq(0).addClass('active')
    slideTips.eq(0).addClass('active')
    $('.J_slide li').eq(0).addClass('active')
    $('.J_slide li').hover(function(){
        var index=$(this).index()
        $('.J_slide li').removeClass('active')
        $(this).addClass('active')
        var img=slideImages.eq(index)
        img.css({'zIndex':++zIndex}).addClass('active')
        slideImages.not(img).removeClass('active')
        slideTips.removeClass('active')
        slideTips.eq(index).addClass('active')
    },function(){})   
    // 利息计算
    var r = 0,r2=0, s = '', t = '';
    function b(e, o) {
        var t = 1 + 0.0085 * 12
          , i = (Number(e) * t) / Number(o);
        return i.toFixed(2)
    }
    function a(e, o) {
        var t = 1 + 0.0033 * 12
          , i = (Number(e) * t) / Number(o);
        return i.toFixed(2)
    };$('.price').on('click', function() {
        $('.price').removeAttr('id', 'curror');
        $(this).attr('id', 'curror');
        $('#calcul').css('background-color', '#62a1f8');
        if ($(this).attr('data-price') > 0) {
            $('#loanAmount').val($(this).attr('data-price'))
        } else {
            $('#loanAmount').val('')
        }
    });
    $('.time').on('click', function() {
        $('.time').removeAttr('id', 'time-curror');
        $(this).attr('id', 'time-curror');
        $('#calcul').css('background-color', '#62a1f8');
        t = $(this).attr('data-time')
    });
    
    $('#calcul').on('click', function() {
        if (!$('#loanAmount').val()) {
            alert('借款金额为空,请重新输入');
            return !1
        } else if ($('#loanAmount').val() > 500000) {
            alert('借款金额最高上限为50万,请重新输入');
            return !1
        } else {
            s = $('#loanAmount').val()
        }
        ;if (!t) {
            alert('请选择借款期限');
            return !1
        }
        ;if (s && t) {
            r = a(s, t);
            r2=b(s,t);
            $('.monthSupply').show();
            $('#money').text(r);
            $('#money2').text(r2);
        }
    }); 
    // 点击微信领取旋转
    $('.weixin').click(function(){
        $('.flipper').addClass('w_active');
    });
    $('.back_btn').click(function(){
        $('.flipper').removeClass('w_active');
    })
})