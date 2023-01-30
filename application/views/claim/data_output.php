<html lang="jp">
    <head>
        <?php $this->load->view('layout/import'); ?>
        <title>BPA | 請求管理</title>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/order/order_list.js"></script>
    </head>
    <body class="cloak">
        <!-- header -->
        <?php $this->load->view('layout/header'); ?>


        <div class="container pb-2">
            <ul class="breadcrumb bg-white pt-0 pb-0 mb-0 pl-1">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active">請求管理 - 顧客別請求データ出力</li>
            </ul>

            <div class="ui divider"></div>

            <div class="card w-100">
                <div class="card-body">
                    <div class="error-area"></div>
                    <form action="" class="mainForm" method="post" accept-charset="utf-8">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <label class="col-2 col-form-label">mvno</label>
                                    <div class="col-9">
                                        <select name="MVNO" class="form-control form-control-sm">
                                            <?php foreach ($Mvno_list as $key => $item): ?>
                                                <?php echo '<option value="' . $item['MVNO_ID'] . '">' . $item['MVNO_NAME'] . '(' . $item['ADDITIONAL_BUSINESS_CODE'] . ')</option>'; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div><!-- col-lg-6 end -->
                            <div class="col-lg-6">
                                <div class="form-group row">
                                    <label class="col-2 col-form-label">顧客</label>
                                    <div class="col-9">
                                        <select name="SIM_TYPE" class="form-control form-control-sm">
                                            <?php foreach ($Name_list as $key => $item): ?>
                                                <?php echo '<option value="' . $item['HEAD_LINE_GROUP_ID'] . '">' . $item['HEAD_LINE_GROUP_NAME'] . '</option>'; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div><!-- col-lg-6 end -->

                        </div><!-- row end -->
                        <div class="row">                    
                            <div class="col-lg-6">
                                <div class="form-group row mb-lg-0">

                                    <label class="col-2 col-form-label">年-月</label>
                                    <div class="col-9">
                                        <select name="SIM_NAME" class="form-control form-control-sm">
                                            <?php foreach ($Ym_list as $item): ?>
                                                <?php echo '<option value="' . $item . '">' . $item . '</option>'; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div><!-- col-lg-6 end -->
                        </div>
                        <div class="ui divider mt-4 mb-4"></div>

                        <div class="row justify-content-center">
                            <div class="col-4 col-lg-2">
                                <button type="submit" class="btn btn-primary btn-sm btn-block">csv出力</button>
                            </div>
                        </div>
                    </form>            
                </div><!-- card-body end -->
            </div><!-- card end -->
        </div>

        <!-- footer -->
        <?php $this->load->view('layout/footer'); ?>
    </body>
</html>