<html lang="jp">
<head>
<?php $this->load->view('layout/import');?>
<title>BPA | オーダー管理</title>
<?php if ($active_tab == '1') :?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/order/new_order/open.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/order/cascading_select_tag.js"></script>
<?php elseif ($active_tab == '2') :?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/order/new_order/modify.js"></script>
<?php else :?>
<link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/dropzone/dropzone.css" />
<script type="text/javascript" src="<?php echo base_url();?>assets/plugins/dropzone/dropzone.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/order/new_order/upload.js"></script>

<?php endif;?>
<script type="text/javascript">
<?php if ($active_tab == '1') : ?>
    var acs_open_property = <?php echo json_encode(get_acs_property('open'));?>;
<?php elseif ($active_tab == '2') :?>
    var acs_modify_property = <?php echo json_encode(get_acs_property('modify'));?>;
<?php endif;?>
</script>



</head>
<body class="cloak">
    <!-- header -->
    <?php $this->load->view('layout/header');?>
    
    <div class="container">
        <ul class="breadcrumb bg-white mb-2 pt-0 pb-0 pl-1">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item active">オーダー管理 / API新規オーダー</li>
        </ul>
        
        <div class="ui divider"></div>
        
        <div class="row">
            <!-- acs menu -->
            <div class="col-lg-3 acs_menu mb-3">
                <div class="row stretched">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-none d-lg-inline-block pt-2 pb-2">項目選択</div>
                            <div class="card-body p-0 p-lg-3">
                                <div class="tab-menu row m-0">
                                    <a class="col-4 col-lg-12 mb-0 mb-lg-1<?php if ($active_tab == 1) echo ' active';?>" href="<?php echo site_url('order/new_order');?>">SIM開通</a>
                                    <a class="col-4 col-lg-12 mb-0 mb-lg-1<?php if ($active_tab == 2) echo ' active';?>" href="<?php echo site_url('order/new_order/2');?>">SIM変更</a>
                                    <a class="col-4 col-lg-12<?php if ($active_tab == 3) echo ' active';?>" href="<?php echo site_url('order/new_order/3');?>">アップロード</a>
                                </div>
                            </div>
                        </div><!-- card end -->
                    </div><!-- col end -->
                </div><!-- row end -->
            </div><!-- acs menu end -->
            
            <!-- tab content -->
            <div class="col-lg-9 col-12 content-fadeIn">
                <?php 
                // SIM開通
                if ($active_tab == '1')
                {
                    $this->load->view('order/new_order/open');
                }
                // SIM変更
                elseif ($active_tab == '2')
                {
                    $this->load->view('order/new_order/modify');
                }
                // アップロード
                else
                {
                    $this->load->view('order/new_order/upload');
                }
                ?>
            </div><!-- col end -->
        </div><!-- row end -->
    </div><!-- container end -->
    <!-- footer -->
    <?php $this->load->view('layout/footer'); ?>
</body>
</html>