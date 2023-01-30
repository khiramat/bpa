<html lang="jp">
<head>
<?php $this->load->view('layout/import');?>
<title>BPA | オーダー管理</title>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/order/order_list.js"></script>
</head>
<body class="cloak">
    <!-- header -->
    <?php $this->load->view('layout/header');?>
    
    <div class="container">
        <ul class="breadcrumb bg-white mb-0 pt-0 pb-0 pl-1">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item active">オーダー管理 / API新規オーダー一覧</li>
        </ul>
        
        <div class="ui divider"></div>
        
        <!-- 検索エリア -->
        <form class="mb-0 row fsize-12 search-container" action="<?php echo site_url('order/order_list/do_search')?>" method="post">
            <div class="form-group col-7 col-lg-4 pr-0 pr-lg-3">
                <label class="mb-0">更新日付</label>
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
            <div class="form-group col-5 col-lg-3">
                <label class="mb-0">トランザクションType</label>
                <?php 
                    $transactionType_list = array('' => '全てのタイプ') + get_select_property('transaction_type');
                    // select list
                    echo form_dropdown('transactionTYPE', $transactionType_list, $transactionTYPE, 
                        array('class' => 'form-control form-control-sm'));
                ?>
            </div>
            <div class="form-group col-4 col-lg-3">
                <label class="mb-0">カード形状</label>
                <?php 
                $cardkeijo_list = array('' => '全ての形状') + get_select_property('cardkeijo');
                // select list
                echo form_dropdown('cardkeijo', $cardkeijo_list, $cardkeijo,
                            array('class' => 'form-control form-control-sm'));
                ?>
            </div>
            <div class="col-lg-2 d-none d-lg-inline-flex"></div>
            <div class="form-group col-4 col-lg-2">
                <label class="mb-0">登録状態</label>
                <?php 
                    $input_status_list = array('' => '全ての状態') + get_select_property('input_status');
                    // select list
                    echo form_dropdown('input_status', $input_status_list, $input_status, 
                        array('class' => 'form-control form-control-sm'));
                ?>
            </div>
            <div class="form-group col-4 col-lg-2">
                <label class="mb-0">実行状態</label>
                <?php 
                    $call_status_list = array('' => '全ての状態') + get_select_property('call_status');
                    // select list
                    echo form_dropdown('call_status', $call_status_list, $call_status, 
                        array('class' => 'form-control form-control-sm'));
                ?>
            </div>
            <div class="form-group col-8 col-lg-6">
                <label class="mb-0">検索項目(スペースで、AND条件)</label>
                <input type="text" name="search_target" value="<?php echo $search_target;?>" class="form-control form-control-sm" placeholder="ID/テナント名/電話番号/枚数" maxlength="40" />
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
                <h5 class="text-secondary">データが見つかりませんでした。</h5>
            </div>
            <?php else :?>
            <!-- 入力リストテーブル -->
            <div class="card-body pb-0">
                <div class="row">
                    <form action="<?php echo site_url('order/order_list/set_page_size'); ?>" method="post" class="col-4 col-md-2 mb-0">
                        <div class="form-group">
                            <?php echo form_dropdown('per_page', get_select_property('page_size'), $per_page, 
                                array('class' => 'form-control form-control-sm submit')); ?>
                        </div>
                    </form>
    
                    
                    <div class="offset-lg-2 col-2 col-lg-2 pr-1 pl-1 pl-lg-3 pr-lg-3">
                    		<button type="button" class="btn btn-info btn-sm btn-block pl-0 pr-0 text-center" id="btn_reserve">実行予約</button>
                		</div>
                    
                    <div class="col-2 col-lg-2 pr-1 pl-1 pl-lg-3 pr-lg-3">
                    		<button type="button" class="btn btn-warning btn-sm btn-block" id="btn_stop">中断</button>
                		</div>
                    
                    <div class="col-2 col-lg-2 pr-1 pl-1 pl-lg-3 pr-lg-3">
                    		<button type="button" class="btn btn-primary btn-sm btn-block" id="btn_restart">再開</button>
                		</div>
                    
                    <div class="col-2 col-lg-2 pr-1 pl-1 pl-lg-3 pr-lg-3">
                    		<button type="button" class="btn btn-danger btn-sm btn-block" id="btn_remove">削除</button>
                		</div>
                    
                </div><!-- row end -->
                
                <div class="table-responsive content-fadeIn">
                    <table class="table table-condensed table-striped middle table-hover mb-0 sortable">
                        <colgroup>
                            <col width="4%" />
                            <col width="6%" />
                            <col width="12%" />
                            <col width="*" />
                            <col width="10%" />
                            <col width="12%" />
                            <col width="8%" />
                            <col width="14%" />
                            <col width="10%" />
                            <col width="7%" />
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
                                <th class="sorting" data-toggle="tooltip" title="トランザクションType">Tx Type</th>
                                <th class="sorting">電話番号</th>
                                <th class="sorting">カード形状</th>
                                <th class="sorting">枚数</th>
                                <th class="sorting" data-toggle="tooltip" title="登録と実行の状態">登録 / 実行</th>
                                <th class="sorting">更新日</th>
<!--                                 <th class="sorting" data-toggle="tooltip" title="特定実行予約日時">予約日時</th> -->
                                <th class="text-center">件別管理</th>
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
                                <td><?php echo $transactionType_list[$data->transactionTYPE]; ?></td>
                                <td><?php echo empty($data->denwabango) ? '-' : $data->denwabango;?></td>
                                <td>
                                <?php 
                                    if ($data->cardkeijo === NULL or $data->cardkeijo === '')
                                    {
                                        echo '-';
                                    }
                                    else 
                                    {
                                        echo $cardkeijo_list[$data->cardkeijo];
                                    }
                                ?>
                                </td>
                                <td><?php echo number_format($data->LINE_CNT);?></td>
                                <td class="fsize-12">
                                <?php 
                                // 入力完了の場合
                                if ($data->INPUT_STATUS == INPUT_STATUS_DONE) 
                                {
                                    echo $input_status_list[$data->INPUT_STATUS] . ' / ';
                                    switch ($data->CALL_STATUS)
                                    {
                                        case CALL_STATUS_STOP:
                                            echo '<span class="btn-warning">' .$call_status_list[$data->CALL_STATUS]. '</span>';
                                            break;
                                        case CALL_STATUS_RESERVE:
                                            echo '<span class="btn-info">' .$call_status_list[$data->CALL_STATUS]. '</span>';
                                            break;
                                        default:
                                            echo $call_status_list[$data->CALL_STATUS];
                                            break;
                                    }
                                }
                                else 
                                {
                                    echo $input_status_list[$data->INPUT_STATUS];
                                }
                                ?>
                                </td>
                                <td><?php echo date('Y/m/d', strtotime($data->UPDATE_DATETIME));?></td>
                                <td class="text-center">
<!--                                         <button type="button" class="btn btn-primary btn-xs">表示</button> -->
                                    <?php if ($data->INPUT_STATUS == INPUT_STATUS_DONE) : ?>
                                    <a class="btn btn-success1 btn-xs" href="<?php echo site_url('order/order_edit/' . $data->UID);?>"><i class="fas fa-list" style="font-size:16px;color:red"></i></a>
<!--                                         <button type="button" class="btn btn-success btn-xs"><i class="fa fa-search"></i> -->
                                    <?php endif;?>
<!--                                     <button type="button" class="btn btn-danger btn-xs">削除</button> -->
                                        
                                </td>
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