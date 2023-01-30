<html lang="jp">
<head>
<?php $this->load->view('layout/import');?>
<title>BPA | オーダー管理</title>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/order/open_waiting.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/order/order_list.js"></script>
</head>
<body class="cloak">
    <!-- header -->
    <?php $this->load->view('layout/header');?>
    
    <div class="container">
        <ul class="breadcrumb bg-white mb-0 pt-0 pb-0 pl-0">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item active">オーダー管理 / API開通待ちSIM一覧</li>
        </ul>
        
        <div class="ui divider"></div>
 
        <?php 
            $cardkeijo_list = array('' => '全ての形状') + get_select_property('cardkeijo');
            $sim_status_list = array('' => '全ての状態') + get_select_property('sim_status');
        ?>
        
        <!-- 検索エリア -->
        <form class="mb-0 row fsize-12 search-container" action="<?php echo site_url('order/open_waiting/do_search')?>" method="post">
            <div class="form-group col-4 col-lg-4 ">
                <label class="mb-0">オーダー日付</label>
                <div class="form-row">
                    <div class="col-5">
                        <input type="text" data-toggle="datetimepicker" name="time_from" value="<?php echo $time_from;?>" class="form-control form-control-sm" placeholder="検索日付(始)" maxlength="10">
                    </div>
                    <div class="col-2 text-center col-form-label">〜</div>
                    <div class="col-5">
                        <input type="text" data-toggle="datetimepicker" name="time_to" value="<?php echo $time_to;?>" class="form-control form-control-sm" placeholder="検索日付(終)" maxlength="10">
                    </div>
                </div>
            </div>

<!--             <div class="col-lg-2 d-none d-lg-inline-flex"></div> -->
                <div class="form-group col-4 col-lg-2">
                    <label class="mb-0">SIMタイプ</label>
                        <?php 
//                         $input_status_list = array('' => '全ての状態') + get_select_property('cardkeijo');
                        // select list
                        echo form_dropdown('cardkeijo', $cardkeijo_list, $cardkeijo, array('class' => 'form-control form-control-sm'));
                    ?>
               </div>
                <div class="form-group col-4 col-lg-2">
                <label class="mb-0">SIM状態</label>
                        <?php 
                        $sim_status_list = array('' => '全ての状態') + get_select_property('sim_status');
                        // select list
                        echo form_dropdown('sim_status', $sim_status_list, $sim_status, 
                            array('class' => 'form-control form-control-sm'));
                    ?>
            </div>
                
            <div class="form-group col-8 col-lg-6">
                <label class="mb-0">検索項目(スペースで、AND条件)</label>
                <input type="text" name="search_target" value="<?php echo $search_target;?>" class="form-control form-control-sm" placeholder="ID/テナント名/代表番号/電話番号/製造暗号" maxlength="40" />
            </div>
            <div class="form-group col-4 col-lg-2 align-self-end">
                <button class="btn btn-success btn-block pull-right btn-sm" type="submit">
                    <i class="fas fa-search"></i>&nbsp;検索する
                </button>
            </div>
        </form><!-- 検索エリア end -->
        
        <div class="card mb-0 pb-3 w-100">
            <!-- データがない場合 -->
            <?php if ($total_rows == 0) :?>
            <div class="card-body text-center d-flex align-items-center justify-content-center" style="height: 240px;">
                <h5 class="text-secondary">データが見つかりません。</h5>
            </div>
            <?php else :?>
            <!-- 入力リストテーブル -->
            <div class="card-body pb-0">
                <div class="row">
                    <form action="<?php echo site_url('order/open_waiting/set_page_size'); ?>" method="post" class="col-4 col-md-2 mb-0">
                        <div class="form-group">
                            <?php echo form_dropdown('per_page', get_select_property('page_size'), $per_page, 
                                array('class' => 'form-control form-control-sm submit')); ?>
                        </div>
                    </form>
                    <div class="offset-lg-6 col-2 col-lg-2 p-0">
                    </div>

                    <div class="col-2 col-lg-2" style="display: block;" id="btn_open_hankuro_sim">
                    		<button type="button" class="btn btn-info btn-block">開通オーダー発行</button>
                		</div>
                    
                </div><!-- row end -->
                
                <div class="table-responsive content-fadeIn">
                    <table class="table table-condensed table-striped middle table-hover mb-0 sortable">
                        <colgroup>
                            <col width="4%" />
                            <col width="6%" />
                            <col width="*" />
                            <col width="14%" />
                            <col width="14%" />
                            <col width="14%" />
                            <col width="10%" />
                            <col width="10%" />
                            <col width="10%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th>
                                    <label class="checkbox mb-0">
                                        <input type="checkbox" class="master check-lg" />
                                    </label>
                                </th>
                                <th class="sorting" data-toggle="tooltip" title="ユニークID">ID</th>
                                <th class="sorting">テナント名</th>
                                <th class="sorting">代表番号</th>
                                <th class="sorting">個別番号(電話番号)</th>
                                <th class="sorting">SIMタイプ</th>
                                <th class="sorting">製造番号(15桁)</th>
                                <th class="sorting">SIM状態</th>
                                <th class="sorting">オーダー日付</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($input_data)) :?>
                            <?php foreach ($input_data as $data) :?>
                            <tr>
                                <td class="text-center">
                                    <label class="checkbox mb-0">
                                        <input type="checkbox" class="child check-lg" value="<?php echo $data->UID;?>" />
                                    </label>
                                </td>
                                <td><?php echo $data->UID;?></td>
                                <td>
                                    <?php 
                                          echo $data->TENANT_NAME;
                                    ?>
                                </td>
                                <!--  <td><?php //echo $transactionType_list[$data->transactionTYPE]; ?></td> -->
                                <td><?php echo empty($data->denwabango) ? '-' : $data->denwabango;?></td>
                                <td>
                                        <?php 
                                        echo empty($data->denwabango) ? '-' : $data->denwabango;
                                    ?>
                                </td>
                                <td><?php echo $cardkeijo_list[$data->cardkeijo];?></td>
                                <td class="fsize-12">
                                        <?php 
                                        echo $data->SEIZOBANGO;
                                    ?>
                                        <?php //echo "半黒処理";
                                        $transactionType_list = get_select_property('transaction_type');
                                        if($transactionType_list == "M02-1" or $transactionType_list == "M02-2" or $transactionType_list == "02-2")
                                        {
//                                     echo $transactionType_list[$data->transactionTYPE] . "半黒";
                                        }
                                    ?>
                                </td>
                                <td>
                                        <?php
                                          if($data->SIM_STATUS == 0)
                                          {
                                              echo "未作成";
                                          }else
                                          {
                                              echo "作成済";
                                          }
                                    ?>
                                </td>
                                <td><?php echo date('Y/m/d h:i:s', strtotime($data->UPDATE_DATETIME));?></td>
                                <td class="text-center"></td>
                            </tr>
                            <?php endforeach;?>
                            <?php endif;?>
                        </tbody>
                    </table><!-- table end -->
                </div><!-- table-responsive end -->
                <div class="ui divider d-sm-done mt-0"></div>
                <div class="row mt-3 mt-sm-0">
                    <?php if (isset($total_rows) and $total_rows > 0) : ?>
                    <div class="col-sm-4 d-none d-sm-block mt-2"><?php echo $total_rows;?>件中
                    <?php echo ($cur_page - 1) * $per_page + 1; ?>から
                    <?php echo $cur_page * $per_page > $total_rows ? $total_rows : $cur_page * $per_page;?>まで表示</div>
                    <?php endif;?>
                    <div class="col-sm-8">
                        <?php if(isset($page_links)) echo $page_links;?>
                    </div>
                </div><!-- row end -->
            </div><!-- card-body end -->
            <?php endif;?>
        </div><!-- card end -->
    </div><!-- container end -->
    
    <!-- footer -->
    <?php $this->load->view('layout/footer'); ?>
</body>
</html>