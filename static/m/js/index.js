$(function(){
    $('.age-list').on('tap','li',function(){
         $(this).siblings().removeClass("active");
         $(this).addClass("active");

    });
    $(".btn-radio").tap(function(){
        $this=$(this);
        if($this.hasClass('active')){
            $this.removeClass('active');
        }else{
            $this.addClass('active');
        }
    });

    // 城市选择
    var mobileSelect1 = new MobileSelect({
        trigger: '#city', 
        title: '选择城市',  
        wheels: [
                    {data:['广州','深圳','佛山','珠海']}
                ],
        position:[0], //Initialize positioning
        callback:function(indexArr, data){
            var sendcity=document.getElementById('sendcity');
            sendcity.setAttribute('value',indexArr);
        } 
    });
})