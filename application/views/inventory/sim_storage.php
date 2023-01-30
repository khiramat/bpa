<html lang="jp">
<head>
<?php $this->load->view('layout/import');?>
<title>BPA | インベントリ管理</title>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/inventory/sim_storage.js"></script>
</head>
<body class="cloak">
    <!-- header -->
    <?php $this->load->view('layout/header');?>
    
    <div class="container pb-2">
        <ul class="breadcrumb bg-white pt-0 pb-0 mb-0 pl-1">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item active">インベントリ管理 - SIM入庫処理</li>
        </ul>
        <div class="ui divider"></div>
        
        <div class="card w-100">
            <div class="card-body">
            <div class="error-area"></div>
            <?php echo form_open('inventory/sim_storage/save', array('class'=>'mainForm'));?>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group row">
                            <label class="col-3 col-form-label">仕入先</label>
                            <div class="col-9">
                                <div class="basic-input">docomo</div>
                            </div>
                        </div>
                        <div class="form-group row mb-lg-0">
                            <label class="col-3 col-form-label">Type</label>
                            <div class="col-9">
                                <?php echo form_dropdown('SIM_TYPE', get_select_property('sim_type'), 
                                            '', array('class' => 'form-control form-control-sm')); ?>
                            </div>
                        </div>
                    </div><!-- col-lg-4 end -->
                    <div class="col-lg-8 d-flex">
                        <div class="form-group row mb-0 flex-auto">
                            <label class="col-3 col-lg-2 col-form-label text-left text-lg-right">読取結果</label>
                            <div class="col-9 col-lg-10 d-flex">
                                <input type="hidden" name="SEIZOBANGO_STR" />
                                <div class="row border rounded flex-auto ml-0 mr-0 p-2" style="min-height: 60px;" id="load_result_area">
<!--                                     <div class="col-lg-6"> -->
<!--                                         <div class="input-group"> -->
<!--                                             <input type="text" class="form-control"> -->
<!--                                             <div class="input-group-append"> -->
<!--                                                 <button class="input-group-text btn btn-danger">&times;</button> -->
<!--                                             </div> -->
<!--                                         </div> -->
<!--                                     </div> -->
                                </div>
                            </div><!-- col-9 end -->
                        </div><!-- form-group end -->
                    </div><!-- col-lg-8 end -->
                </div><!-- row end -->
                
                <div class="ui divider mt-4 mb-4"></div>
                
                <div class="row justify-content-center">
                    <div class="col-4 col-lg-2">
                        <button type="button" class="btn btn-primary btn-sm btn-block" id="btn_start_scan">スキャン開始</button>
                    </div>
                    <div class="col-4 col-lg-2" style="display: none;">
                        <button type="submit" class="btn btn-info btn-sm btn-block" id="btn_save">登録する</button>
                    </div>
                </div>
            <?php echo form_close();?>
            </div><!-- card-body end -->
        </div><!-- card end -->
        
    </div><!-- container end -->
    <!-- footer -->
    <?php $this->load->view('layout/footer'); ?>
    
    <!-- scanning model -->
    <div class="scanning-modal">
        <div class="scanning"></div>
        <div class="scanning-content">
            <i class="fas fa-spinner mb-2"></i>
            <p>スキャン中...<span class="count"></span></p>
            <button class="btn btn-danger btn-sm mt-4" id="btn_end_scan">スキャン終了</button>
        </div>
    </div>
    
</body>
</html>