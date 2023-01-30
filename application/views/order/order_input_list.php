<html lang="jp">
<head>
<?php $this->load->view('layout/import');?>
<title>BPA | オーダー管理</title>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/order/order_input_list.js"></script>
</head>
<body class="cloak">
    <!-- header -->
    <?php $this->load->view('layout/header');?>
    
    <div class="container">
        <ul class="breadcrumb bg-white mb-0 pt-0 pb-0 pl-1">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="<?php echo site_url('order/order_list');?>">API新規オーダー一覧</a></li>
            <li class="breadcrumb-item active">入力データ一覧</li>
        </ul>
        
        <?php if (!isset($input_data_info)) : ?>
        <div class="card">
            <div class="card-body text-center d-flex align-items-center justify-content-center" style="height: 240px;">
                <div>
                    <h5 class="text-secondary">入力データが見つかりませんでした。</h5><br/>
                    <a href="<?php echo site_url('order/order_list');?>" class="btn btn-success">&emsp;新規オーダー一覧へ&emsp;</a>
                </div>
            </div>
        </div>
        <?php else :?>
        <div class="ui divider"></div>
        
        <h5 class="mt-2 mb-2">入力データ一覧　
            <?php if (isset($total_rows) and $total_rows > 0) : ?>
            <small class="fsize-12 text-secondary d-none d-md-inline-block">該当件数 <?php echo number_format($total_rows);?>件(
            <?php echo ($cur_page - 1) * $per_page + 1; ?>&nbsp;-&nbsp;
            <?php echo $cur_page * $per_page > $total_rows ? $total_rows : $cur_page * $per_page;?>件)
            </small>
            <?php endif;?>
            <small class="float-right fsize-12 mt-2 text-secondary">
                <a class="text-secondary" style="display: none;" href="javascript:void(0)" id="show_basic_info">
                    <i class="fas fa-plus-square"></i>&nbsp;基本情報・検索条件を表示
                </a>
                <a class="text-secondary" href="javascript:void(0)" id="hide_basic_info">
                    <i class="fas fa-minus-square"></i>&nbsp;基本情報・検索条件を隠す
                </a>&nbsp;|&nbsp;
                <a class="text-secondary" href="<?php echo site_url('order/order_list');?>">新規オーダー一覧へ戻る</a>
            </small>
        </h5>
        
        <div class="row stretched" id="basic_info">
            <!-- 基本情報 -->
            <div class="col-lg-5 mb-3">
                <div class="card border-info">
<!--                 fas fa-info-circle -->
                    <div class="card-header text-white bg-info p-2"><i class="far fa-check-circle"></i>&nbsp;
                        <span>基本情報　</span>
                        <small>( 入力データID&nbsp;:&nbsp;<span class="fsize-14"><?php echo $input_data_info->UID;?></span> )</small>
                    </div>
                    <div class="card-body p-2">
                        <div class="row fsize-12 mb-0">
                            <div class="form-group col-6">
                                <label class="mb-0">テナント名</label>
                                <div class="basic-input bg-light"><?php echo $input_data_info->TENANT_NAME;?></div>
                            </div>
                            <div class="form-group col-6">
                                <label class="mb-0">総枚数</label>
                                <div class="basic-input bg-light"><?php echo $input_data_info->LINE_CNT;?></div>
                            </div>
                            <div class="form-group col-6">
                                <label class="mb-0">状態</label>
                                <div class="basic-input bg-light">
                                <?php
                                // 未入力/入力中の場合
                                if ($input_data_info->INPUT_STATUS != INPUT_STATUS_DONE)
                                {
                                    echo get_select_property('input_status')[$input_data_info->INPUT_STATUS];
                                }
                                else 
                                {
                                    echo get_select_property('input_status')[$input_data_info->INPUT_STATUS] . ' / '
                                            . get_select_property('call_status')[$input_data_info->CALL_STATUS];
                                }
                                ?>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label class="mb-0">更新日時</label>
                                <div class="basic-input bg-light"><?php echo $input_data_info->UPDATE_DATETIME;?></div>
                            </div>
                        </div>
                    </div><!-- card-body end -->
                </div><!-- card end -->
            </div><!-- col end -->
            
            <!-- 検索情報 -->
            <div class="col-lg-7 mb-3">
                <div class="card border-info">
                    <div class="card-header text-white bg-info p-2"><i class="fas fa-search"></i>&nbsp;検索</div>
                    <form class="card-body p-2 mb-0 search-container" action="<?php echo site_url('order/order_input_list/do_search')?>" method="post">
                        <div class="row fsize-12">
                            <input type="hidden" name="INPUT_DATA_UID" value="<?php echo $INPUT_DATA_UID;?>" />
                            <div class="form-group col-4">
                                <label class="mb-0">トランザクションType</label>
                                <?php 
                                    $transactionType_list = array('' => '全てのタイプ') + get_select_property('transaction_type');
                                    // select list
                                    echo form_dropdown('transactionTYPE', $transactionType_list, $transactionTYPE, 
                                        array('class' => 'form-control form-control-sm'));
                                ?>
                            </div>
                            <div class="form-group col-4">
                                <label class="mb-0">カード形状</label>
                                <?php 
                                    $cardkeijo_list = array('' => '全て') + get_select_property('cardkeijo');
                                    // select list
                                    echo form_dropdown('cardkeijo', $cardkeijo_list, $cardkeijo, 
                                        array('class' => 'form-control form-control-sm'));
                                ?>
                            </div>
                            <div class="form-group col-4">
                                <label class="mb-0">実行状態</label>
                                <?php 
                                    $call_status_list = array('' => '全ての状態') + get_select_property('call_status');
                                    // select list
                                    echo form_dropdown('call_status', $call_status_list, $call_status, 
                                        array('class' => 'form-control form-control-sm'));
                                ?>
                            </div>
                            <div class="form-group col-8">
                                <label class="mb-0">検索項目</label>
                                <input type="text" name="search_target" value="<?php echo $search_target;?>" class="form-control form-control-sm" placeholder="ID/電話番号/MNP予約等" maxlength="40" />
                            </div>
                            <div class="form-group col-4 align-self-end">
                                <button class="btn btn-success btn-block btn-sm" type="submit">
                                    <i class="fas fa-search"></i>&nbsp;検索する
                                </button>
                            </div>
                        </div><!-- row end -->
                    </form>
                </div><!-- card end -->
            </div><!-- col end -->
        </div><!-- row end -->

        <div class="card mb-0 pb-3 border-info w-100">
            <!-- データがない場合 -->
            <?php if ($total_rows == 0) :?>
            <div class="card-body text-center d-flex align-items-center justify-content-center" style="height: 240px;">
                <h5 class="text-secondary">データが見つかりませんでした。</h5>
            </div>
            <?php else :?>
            <!-- 入力リストテーブル -->
            <div class="card-body pb-0 pl-3 pr-3 pt-3">
                <div class="row">
                    <form action="<?php echo site_url('order/order_input_list/set_page_size'); ?>" method="post" class="col-4 col-lg-2 mb-0">
                        <input type="hidden" name="INPUT_DATA_UID" value="<?php echo $INPUT_DATA_UID;?>" />
                        <div class="form-group">
                            <?php echo form_dropdown('per_page', get_select_property('page_size'), $per_page, 
                                array('class' => 'form-control form-control-sm submit')); ?>
                        </div>
                    </form>
                    <div class="col-3 col-lg-1 offset-5 offset-lg-9">
                        <button type="button" class="btn btn-danger btn-sm btn-block" id="removeList" data-url="<?php echo site_url('order/order_input_list/remove');?>">削除</button>
                    </div>
                </div><!-- row end -->
                
                <div class="table-responsive content-fadeIn" id="dataList">
                    <table class="table table-condensed table-striped middle table-hover mb-0 sortable">
                        <colgroup>
                            <col width="4%" />
                            <col width="6%" />
                            <col width="14%" />
                            <col width="10%" />
                            <col width="12%" />
                            <col width="*" />
                            <col width="14%" />
                            <col width="9%" />
                            <col width="12%" />
                            <col width="6%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th>
                                    <label class="checkbox mb-0">
                                        <input type="checkbox" class="master check-lg" />
                                    </label>
                                </th>
                                <th class="sorting">ID</th>
                                <th class="sorting" data-toggle="tooltip" title="トランザクションType">Tx Type</th>
                                <th class="sorting">電話番号</th>
                                <th class="sorting">カード形状</th>
                                <th class="sorting">MNP予約</th>
                                <th class="sorting">料金プラン</th>
                                <th class="sorting">実行状態</th>
                                <th class="sorting">実行結果</th>
                                <th class="text-center">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($input_list)) :?>
                            <?php foreach ($input_list as $data) :?>
                            <tr>
                                <td class="text-center">
                                    <label class="checkbox mb-0">
                                        <input type="checkbox" class="child check-lg" value="<?php echo $data->UID;?>" />
                                    </label>
                                </td>
                                <td><?php echo $data->UID; ?></td>
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
                                <td>
                                <?php
                                    if (!empty($data->MNPyoyakusyakanji))
                                    {
                                        echo $data->MNPyoyakusyakanji;
                                    }
                                    else if (!empty($data->MNPyoyakusyakana))
                                    {
                                        echo $data->MNPyoyakusyakana;
                                    }
                                    else 
                                    {
                                        echo '-';
                                    }
                                ?>
                                </td>
                                <td>
                                <?php 
                                    if (isset($data->ryokinplan))
                                    {
                                        echo get_select_property('ryokinplan')[$data->ryokinplan];
                                    }
                                    else
                                    {
                                        echo '-';
                                    }
                                ?>
                                </td>
                                <td><?php echo $call_status_list[$data->CALL_STATUS];?></td>
                                <td>
                                <?php 
                                    if ($data->ACS_RESULT == RESULT_OK) {
                                        if ($data->API_STATUS == API_STATUS_REQUEST_OK)
                                        {
                                            echo 'ALADIN要求OK';
                                        }
                                        elseif ($data->API_STATUS == API_STATUS_RESPONSE_OK)
                                        {
                                            echo '完了';
                                        }
                                        elseif ($data->API_STATUS == API_STATUS_REQUEST_ERROR)
                                        {
                                            echo 'ALADIN要求エラー (' . $data->API_CODE . ')';
                                        }
                                        elseif ($data->API_STATUS == API_STATUS_ERROR)
                                        {
                                            echo '未知エラー';
                                        }
                                        else
                                        {
                                            echo 'ACS要求OK';
                                        }
                                    }
                                    elseif (!empty($data->ACS_RESULT))
                                    {
                                        echo 'ACS要求NG (' . $data->ACS_RESULT . ')';
                                    }
                                ?>
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-success btn-xs" href="<?php echo site_url('order/order_edit/' . $data->INPUT_DATA_UID . '/' . $data->UID);?>">
                                        <?php echo ($data->CALL_STATUS == CALL_STATUS_UNDO or $data->CALL_STATUS == CALL_STATUS_STOP) ? '編集' : '詳細'; ?>
                                    </a>
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
                    <div class="col-sm-4 d-none d-sm-block mt-2 text-secondary"><?php echo $total_rows;?>件中
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
        <?php endif;?>
    </div><!-- container end -->
    
    <!-- footer -->
    <?php $this->load->view('layout/footer'); ?>
</body>
</html>