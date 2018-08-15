$(function () {
    // 新增费用类型切换显示
    $(".weui-check1").click(function () {
        $('.check-input1').show();
        $('.check-input2').hide();
    });
    $(".weui-check2").click(function () {
        $('.check-input2').show();
        $('.check-input1').hide();
    });
    // 表格提交
    $('#add-submit').click(function () {
        $("#add_recond").submit();
    });
    $("#costType").change(function () {
        if ($(this).val() == 1) {
            $('.c_percent').show().addClass('c_active');
            $('.c_fact').hide().removeClass('c_active');
        } else if ($(this).val() == 2) {
            $('.c_percent').hide().removeClass('c_active');
            $('.c_fact').show().addClass('c_active');
        }
    });
    // 新增时间timer-picker
    $("#add_page_time").click(function () {
        $("#add_page_time").datetimePicker();
    });
  
})