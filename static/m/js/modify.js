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
    // 类型选择
    $("#costType").change(function () {
        if ($(this).val() == 0) {
            $('.c_percent').show().addClass('c_active');
            $('.c_fact').hide().removeClass('c_active');
        } else if ($(this).val() == 1) {
            $('.c_percent').hide().removeClass('c_active');
            $('.c_fact').show().addClass('c_active');
        }
    });

    // 新增时间timer-picker
    $("#modify_page_time").click(function () {
        $("#modify_page_time").datetimePicker();
    });
    // 申请时间timer-picker
    $("#applyTime").click(function () {
        $('#applyTime').datetimePicker();
    });
    // 提交资料时间timer-picker
    $("#fileTime").click(function () {
        $("#fileTime").datetimePicker();
    });
    // 审核通过时间timer-picker
    $("#checkingTime").click(function () {
        $('#checkingTime').datetimePicker();
    });
    // 放款时间timer-picker
    $("#loanedTime").click(function () {
        $("#loanedTime").datetimePicker();
    });
    // 缴费时间timer-picker
    $("#paiedTime").click(function () {
        $("#paiedTime").datetimePicker();
    });

   


})