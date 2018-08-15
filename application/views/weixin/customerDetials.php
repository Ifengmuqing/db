<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telphone=no, email=no"/>
    <link rel="stylesheet" href="https://cdn.bootcss.com/weui/1.1.2/style/weui.min.css">
    <link rel="stylesheet" href="https://cdn.bootcss.com/jquery-weui/1.2.0/css/jquery-weui.min.css">
    <link rel="stylesheet" href="<?php echo base_url('static/m/css/customerDetials.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('static/fonts/iconfont.css'); ?>">
    <title><?php echo $userinfo['name'] ?></title>
</head>

<body>
    <div class="weui-tab">
        <div class="weui-tab__bd">
            <div id="tab4" class="weui-tab__bd-item weui-tab__bd-item--active">
                <div class="add_page customer_page">
                    <div class="show_page" style="">
                        <div class="weui-flex add_page_title">
                            <div class="weui-flex__item">
                                <i class="iconfont icon-geren"></i>客户详情
                            </div>
                        </div>
                    </div>
                    <div class="page_customer">
                        <div class="costomer_item">
                            <div class="item_content">
                                <div class="item_content_label">
                                    日期：
                                </div>
                                <div class="item_content_text">
                                    <?php echo $userinfo['date']; ?>
                                </div>
                            </div>
                            <div class="item_content">
                                <div class="item_content_label">
                                    姓名：
                                </div>
                                <div class="item_content_text">
                                <?php echo $userinfo['name']; ?>
                                </div>
                            </div>
                            <div class="item_content">
                                <div class="item_content_label">手机：</div>
                                <div class="item_content_text">
                                    <?php echo $userinfo['phone']; ?>
                                </div>
                            </div>
                        </div>
                        <div class="costomer_item identity_item">
                            <div class="item_content">
                                <div class="item_content_label">
                                    身份证号：
                                </div>
                                <div class="item_content_text">
                                    44122319930425291X
                                </div>
                            </div>
                            <div class="item_content">
                                <div class="item_content_label">
                                    性别：
                                </div>
                                <div class="item_content_text">
                                    男
                                </div>
                            </div>
                        </div>
                        <div class="costomer_item">
                            <div class="item_content">
                                <div class="item_content_label">
                                    客户来源：
                                </div>
                                <div class="item_content_text">
                                <?php echo $userinfo['source']; ?>
                                </div>
                            </div>
                            <div class="item_content">
                                <div class="item_content_label">
                                    职业：
                                </div>
                                <div class="item_content_text">

                                    <?php echo $userinfo['profession']; ?>
                                </div>
                            </div>
                            <div class="item_content">
                                <div class="item_content_label">
                                    工资：
                                </div>
                                <div class="item_content_text">
                                <?php echo $userinfo['salary']; ?>
                                </div>
                            </div>
                        </div>
                        <div class="costomer_item">
                            <div class="item_content">
                                <div class="item_content_label">
                                    需求(万)：
                                </div>
                                <div class="item_content_text">
                                <?php echo $userinfo['requirement']; ?>
                                </div>
                            </div>
                            <?php 
                            if($userinfo['costType']==0){
                                print"<div class=\"item_content\">
                                   <div class=\"item_content_label\">
                                       费用类型：
                                        </div>
                                        <div class=\"item_content_text\">
                                            百分比
                                        </div>
                                    </div>
                                    <div class=\"item_content\">
                                        <div class=\"item_content_label\">
                                            费率：
                                        </div>
                                        <div class=\"item_content_text\">
                                                {$userinfo['percent']}%
                                        </div>
                                    </div>";
                                }else{
                                   print"<div class=\"item_content\">
                                   <div class=\"item_content_label\">
                                       费用类型：
                                        </div>
                                        <div class=\"item_content_text\">
                                            固定额
                                        </div>
                                    </div>
                                    <div class=\"item_content\">
                                        <div class=\"item_content_label\">
                                            费率：
                                        </div>
                                        <div class=\"item_content_text\">
                                                {$userinfo['cost']}元
                                        </div>
                                    </div>";
                                }?>
                            <!-- <div class="item_content">
                                <div class="item_content_label">
                                    费用类型：
                                </div>
                                <div class="item_content_text">
                               
                                </div>
                            </div>
                            <div class="item_content">
                                <div class="item_content_label">
                                    费率：
                                </div>
                                <div class="item_content_text">
                                    <?php echo $userinfo['percent']; ?>
                                </div>
                            </div> -->
                        </div>
                        <div class="costomer_item">
                            <div class="item_content">
                                <div class="item_content_label">
                                    关注微信：
                                </div>
                                <div class="item_content_text">
                                    <?php
                            if ($userinfo['ifweixin'] == 1) {
                                echo "是";
                            } else {
                                echo "否";
                            }?>
                                </div>
                            </div>
                            <div class="item_content">
                                <div class="item_content_label">
                                    收款日：
                                </div>
                                <div class="item_content_text">
                                <?php echo $userinfo['incomeDate']; ?>
                                </div>
                            </div>
                            <div class="item_content">
                                <div class="item_content_label">
                                    金额：
                                </div>
                                <div class="item_content_text">
                                <?php echo $userinfo['income']; ?>
                                </div>
                            </div>
                        </div>

                        <div class="costomer_item remark_item">
                            <div class="item_content">
                                <div class="item_content_label">
                                    <span>
                                        备注：
                                    </span>

                                </div>
                                <div class="item_content_text" style="border:1px solid #e2dfdf;border-radius:4px;padding:2px 0">
                                <?php echo $userinfo['remark'];?>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="btn_item">
                            <span>保存</span>
                            <span>修改</span> -->
                    </div>
                </div>
                <div class="page_progress">
                    <div class="weui-flex page_progress_title">
                        <div class="weui-flex__item">
                            <i class="iconfont icon-jindu"></i>进度信息
                        </div>
                        <a href="<?php echo site_url('admin/addLoan') . '?uid=' . $userinfo['uid'] . '&name=' . $userinfo['name']; ?>" class="progress_link">+</a>
                    </div>
                    <div class="progress">
                        <div class="progress_order">2.</div>
                        <div class="progress_content">
                            <div class="progress_item">华润银行抵押贷</div>
                            <div class="progress_item">50
                                <em>万</em>
                            </div>
                            <div class="progress_item">申请成功</div>
                        </div>
                    </div>
                    <?php $index = 1;?>
                    <?php foreach ($process as $key): ?>
                    <a href="<?php echo site_url('admin/updateLoan') . '?id=' . $key['id'] . '&name=' . $userinfo['name'];"" ?>">


                        <div class="progress">
                            <div class="progress_order"><?php echo $index?>.</div>
                            <?php $index+=1;?>
                            <div class="progress_content">
                                <div class="progress_item"><?php echo $key['bankProduct'];?></div>
                                <div class="progress_item"><?php echo $key['quota'];?>
                                </div>
                                <div class="progress_item">
                                 <?php
                                    switch ($key['process']) {
                                        case "0":
                                            echo '申请已受理';
                                            break;
                                        case "1":
                                            echo '完成进件';
                                            break;
                                        case "2":
                                            echo '系统审核中';
                                            break;
                                        case "3":
                                            echo '已放款';
                                            break;
                                        case "4":
                                            echo '终审被拒';
                                            break;
                                        default:
                                            break;
                                    }
                                    ?>
                                </div>
                            </div>
                    </div>
                  </a>
                    <?php endforeach;?>
                </div>
                <div class="loaned_manager">
                    <div class="weui-flex loaned_manger_title">
                        <div class="weui-flex__item">
                            <i class="iconfont icon-jilu"></i>贷后管理
                        </div>
                        <a href="<?php echo site_url('admin/loanedRecond') . '?uid=' . $userinfo['uid'] . '&name=' . $userinfo['name']; ?>" class="loaned_link">+</>
                    </div>
                    <div class="loaned">
                        <div class="loaned_content">
                            <div class="loaned_item">
                                <span></span>
                            </div>
                            <div class="loaned_item">
                                <span></span>
                            </div>
                            <div class="loaned_item">
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="del" style="padding:10px 15px;margin:30px auto">
                    <a href="javascript:;" class="weui-btn weui-btn_warn">删除此客户</a>
                </div>
            </div>
        </div>
    </div>
    <div class="weui-tabbar">
        <a href="<?php echo base_url('admin#tab1'); ?>" class="weui-tabbar__item " id="get_apply_list">
            <div class="weui-tabbar__icon">
                <i class="iconfont icon-moshengren"></i>
            </div>
            <p class="weui-tabbar__label">陌生客户</p>
        </a>
        <a href="<?php echo base_url('admin#tab2'); ?>" class="weui-tabbar__item" id="get_follow">
            <div class="weui-tabbar__icon">
                <i class="iconfont icon-hezuo"></i>
            </div>
            <p class="weui-tabbar__label">新增客户</p>
        </a>
        <a href="<?php echo base_url('admin#tab4'); ?>" class="weui-tabbar__item  weui-bar__item--on" id="get_list">
            <div class="weui-tabbar__icon">
                <i class="iconfont icon-xinzeng"></i>
            </div>
            <p class="weui-tabbar__label">客户管理</p>
        </a>
    </div>
    </div>

</body>
<!-- body 最后 -->
<script src="https://cdn.bootcss.com/jquery/1.11.0/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/jquery-weui/1.2.0/js/jquery-weui.min.js"></script>
<!-- 如果使用了某些拓展插件还需要额外的JS -->
<script src="https://cdn.bootcss.com/jquery-weui/1.2.0/js/swiper.min.js"></script>
<script src="https://cdn.bootcss.com/jquery-weui/1.2.0/js/city-picker.min.js"></script>
<script src="https://cdn.bootcss.com/fastclick/1.0.6/fastclick.js"></script>
<script src="https://cdn.bootcss.com/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<!-- <script src="https://cdn.bootcss.com/jquery-validate/1.17.0/localization/messages_zh.min.js"></script> -->
<script>
    $(function () {
        FastClick.attach(document.body);
    });
</script>

</html>