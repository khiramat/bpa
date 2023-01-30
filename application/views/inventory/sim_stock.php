<html lang="jp">
<head>
<?php $this->load->view('layout/import');?>
<title>BPA | インベントリ管理</title>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/inventory/sim_stock.js"></script>
</head>
<body class="cloak">
    <!-- header -->
    <?php $this->load->view('layout/header');?>
    
    <div class="container">
        <ul class="breadcrumb bg-white pt-0 pb-0 mb-0 pl-1">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item active">インベントリ管理 - SIM在庫表</li>
        </ul>
        
        <div class="ui divider"></div>
        
        <div class="card w-100">
            <div class="card-header p-2 font-gray"><i class="fas fa-search"></i>&nbsp;検索</div>
            <div class="card-body p-2">
                <form action="<?php echo site_url('inventory/sim_stock/do_search'); ?>" class="p-0 mb-0 search-container fsize-12" method="post">
                    <div class="row">
                        <div class="form-group col-4 col-lg-2 order-0">
                            <label class="mb-0">SIMタイプ</label>
                            <?php 
                                $sim_type_list = array('' => '全て') + get_select_property('sim_type');
                                // select list
                                echo form_dropdown('SIM_TYPE', $sim_type_list, $SIM_TYPE, 
                                    array('class' => 'form-control form-control-sm'));
                            ?>
                        </div>
                        <div class="form-group col-4 col-lg-2 order-2 order-lg-1">
                            <label class="mb-0">発送状態</label>
                            <?php 
                                $shipment_list = array('' => '全て') + get_select_property('shipment_flag');
                                // select list
                                echo form_dropdown('SHIPMENT_FLAG', $shipment_list, $SHIPMENT_FLAG, 
                                    array('class' => 'form-control form-control-sm'));
                            ?>
                        </div>
                        <div class="form-group col-8 col-lg-4 mb-0 order-1 order-lg-2">
                            <label class="mb-0">入荷日</label>
                            <div class="form-row">
                                <div class="col-5">
                                    <input type="text" data-toggle="datetimepicker" name="ARRIVAL_DATETIME_FROM" 
                                           value="<?php echo $ARRIVAL_DATETIME_FROM;?>" class="form-control form-control-sm" placeholder="入荷日(始)" maxlength="10">
                                </div>
                                <div class="col-2 text-center col-form-label">〜</div>
                                <div class="col-5">
                                    <input type="text" data-toggle="datetimepicker" name="ARRIVAL_DATETIME_TO"
                                           value="<?php echo $ARRIVAL_DATETIME_TO;?>" class="form-control form-control-sm" placeholder="入荷日(終)" maxlength="10">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-8 col-lg-4 mb-0 order-3">
                            <label class="mb-0">出荷日</label>
                            <div class="form-row">
                                <div class="col-5">
                                    <input type="text" data-toggle="datetimepicker" name="SHIPMENT_DATETIME_FROM" 
                                           value="<?php echo $SHIPMENT_DATETIME_FROM;?>" class="form-control form-control-sm" placeholder="出荷日(始)" maxlength="10">
                                </div>
                                <div class="col-2 text-center col-form-label">〜</div>
                                <div class="col-5">
                                    <input type="text" data-toggle="datetimepicker" name="SHIPMENT_DATETIME_TO"
                                           value="<?php echo $SHIPMENT_DATETIME_TO;?>" class="form-control form-control-sm" placeholder="出荷日(終)" maxlength="10">
                                </div>
                            </div>
                        </div>
                    </div><!-- row end -->
                    <div class="row">
                        <div class="col-8 col-lg-10 d-flex">
                            <div class="align-self-center mr-3 d-none d-lg-block">検索項目(スペースで、AND条件)</div>
                            <div class="flex-auto">
                                <input type="text" name="search_target" class="form-control form-control-sm"
                                       value="<?php echo $search_target;?>" placeholder="ID/製造番号/メーカー等" maxlength="40" />
                            </div>
                        </div>
                        <div class="col-4 col-lg-2">
                            <button class="btn btn-success btn-block btn-sm" type="submit">
                                <i class="fas fa-search"></i>&nbsp;検索する
                            </button>
                        </div>
                    </div><!-- row end -->
                </form>
            </div><!-- card-body end -->
        </div><!-- card end -->
        <!-- 検索 end -->
        
        <?php if (!isset($sim_stock) or count($sim_stock) ==0) : ?>
        <div class="card w-100 mt-3">
            <div class="card-body text-center d-flex align-items-center justify-content-center" style="height: 240px;">
                <h5 class="text-secondary">データが見つかりませんでした。</h5>
            </div>
        </div>
        <?php else :?>
        <!-- 統計情報 -->
        <div class="card w-100 mt-3">
            <div class="card-header p-2">
                <span class="text-secondary"><i class="fas fa-info-circle"></i>&nbsp;統計情報&nbsp;(SIM仕入先: docomo)</span>
            </div>
            <div class="card-body">
                <table class="ui-table content-fadeIn fsize-12">
                    <colgroup>
                        <col width="15%" />
                        <col width="17%" />
                        <col width="17%" />
                        <col width="17%" />
                        <col width="17%" />
                        <col width="*" />
                    </colgroup>
                    <thead>
                        <tr>
                            <td class="slanted">
                                <span class="bottom">メーカー</span>
                                <span class="top">SIM</span>
                            </td>
                            <th>標準(※)</th>
                            <th>micro(※)</th>
                            <th>マルチ(※)</th>
                            <th>Nano(※)</th>
                            <th>小計(※)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $total_arr = array(
                                    'SIM_ALL' => 0,
                                    'SIM_IN_STOCK' => 0,
                                    'MINI_ALL' => 0,
                                    'MINI_IN_STOCK' => 0,
                                    'MULTI_ALL' => 0,
                                    'MULTI_IN_STOCK' => 0,
                                    'NANO_ALL' => 0,
                                    'NANO_IN_STOCK' => 0,
                                    'TOTAL' => 0,
                                    'TOTAL_IN_STOCK' => 0
                            );
                        ?>
                        <?php foreach ($statistics as $statistics_info) :?>
                        <?php 
                            $total_arr['SIM_ALL'] += $statistics_info['SIM_ALL'];
                            $total_arr['SIM_IN_STOCK'] += $statistics_info['SIM_IN_STOCK'];
                            $total_arr['MINI_ALL'] += $statistics_info['MINI_ALL'];
                            $total_arr['MINI_IN_STOCK'] += $statistics_info['MINI_IN_STOCK'];
                            $total_arr['MULTI_ALL'] += $statistics_info['MULTI_ALL'];
                            $total_arr['MULTI_IN_STOCK'] += $statistics_info['MULTI_IN_STOCK'];
                            $total_arr['NANO_ALL'] += $statistics_info['NANO_ALL'];
                            $total_arr['NANO_IN_STOCK'] += $statistics_info['NANO_IN_STOCK'];
                            $total_arr['TOTAL'] += $statistics_info['TOTAL'];
                            $total_arr['TOTAL_IN_STOCK'] += $statistics_info['TOTAL_IN_STOCK'];
                        ?>
                        <tr>
                            <th><?php echo $statistics_info['MAKER'];?></th>
                            <td><?php echo $statistics_info['SIM_IN_STOCK'] . ' / ' . $statistics_info['SIM_ALL'];?></td>
                            <td><?php echo $statistics_info['MINI_IN_STOCK'] . ' / ' . $statistics_info['MINI_ALL'];?></td>
                            <td><?php echo $statistics_info['MULTI_IN_STOCK'] . ' / ' . $statistics_info['MULTI_ALL'];?></td>
                            <td><?php echo $statistics_info['NANO_IN_STOCK'] . ' / ' . $statistics_info['NANO_ALL'];?></td>
                            <td><?php echo $statistics_info['TOTAL_IN_STOCK'] . ' / '. $statistics_info['TOTAL'];?></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>合計</th>
                            <th><?php echo $total_arr['SIM_IN_STOCK'] . ' / ' . $total_arr['SIM_ALL'];?></th>
                            <th><?php echo $total_arr['MINI_IN_STOCK'] . ' / ' . $total_arr['MINI_ALL'];?></th>
                            <th><?php echo $total_arr['MULTI_IN_STOCK'] . ' / ' . $total_arr['MULTI_ALL'];?></th>
                            <th><?php echo $total_arr['NANO_IN_STOCK'] . ' / ' . $total_arr['NANO_ALL'];?></th>
                            <th class="text-danger"><?php echo $total_arr['TOTAL_IN_STOCK'] . ' / '. $total_arr['TOTAL'];?></th>
                        </tr>
                    </tfoot>
                </table>
                <p class="mt-1 mb-0 text-danger">※: 在庫数 / 総枚数</p>
            </div>
        </div><!-- card end -->
        <!-- 統計情報 end -->
        
        <div class="card w-100 mt-3">
            <div class="card-header p-2 text-secondary"><i class="fas fa-list"></i>&nbsp;結果詳細</div>
            <div class="card-body p-2">
                <div class="row">
                    <form action="<?php echo site_url('inventory/sim_stock/set_page_size');?>" method="post" class="col-4 col-lg-2 mb-2">
                        <div class="form-group mb-0">
                            <?php echo form_dropdown('per_page', get_select_property('page_size'), $per_page, 
                                array('class' => 'submit form-control form-control-sm')); ?>
                        </div>
                    </form>
                    <div class="col-4 col-lg-2 ml-auto">
                        <button type="button" class="btn btn-success btn-sm btn-block" data-toggle="dropdown">ダウンロード</button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item fsize-12" href="javascript:0" id="btn_download_all" data-url="<?php echo site_url('inventory/sim_stock/download/1');?>">全てダウンロード</a>
                            <a class="dropdown-item fsize-12" href="javascript:0" id="btn_download_portion" data-url="<?php echo site_url('inventory/sim_stock/download/2');?>">選択された部分</a>
                        </div>
                    </div>
                    <div class="col-2 col-lg-1 d-none">
                        <button type="button" class="btn btn-primary btn-sm btn-block">発送</button>
                    </div>
                    <div class="col-2 col-lg-1 d-none">
                        <button type="button" class="btn btn-danger btn-sm btn-block" id="removeList" data-url="<?php echo site_url('inventory/sim_stock/remove');?>">削除</button>
                    </div>
                </div><!-- row end -->
                 <div class="table-responsive content-fadeIn scroll-horizontal" id="dataList">
                    <table class="table table-bordered table-condensed table-striped middle table-hover mb-0 sortable fsize-12 nowrap">
                        <thead>
                            <tr class="bg-sky">
                                <th rowspan="2">
                                    <label class="checkbox mb-0">
                                        <input type="checkbox" class="master check-lg" />
                                    </label>
                                </th>
                                <th class="sorting" rowspan="2">ID</th>
                                <th class="sorting" rowspan="2">メーカー</th>
                                <th class="sorting" rowspan="2">製造番号</th>
                                <th class="sorting" rowspan="2">代表回線</th>
                                <th class="sorting" rowspan="2">電話番号</th>
                                <th class="sorting" rowspan="2">暗証番号</th>
                                <th class="sorting" rowspan="2">SIMタイプ</th>
                                <th class="sorting" rowspan="2">入荷日</th>
                                <th class="sorting" rowspan="2">発送状態</th>
                                <th class="sorting" rowspan="2">出荷日</th>
                                <th class="sorting" rowspan="2">納品希望日</th>
                                <th rowspan="2">出荷先</th>
                                <th rowspan="2">出荷先2</th>
                                <th class="sorting" rowspan="2">MVNO番号</th>
                                <th class="sorting" rowspan="2">Pool番号</th>
                                <th class="sorting" rowspan="2">半黒化日</th>
                                <th class="sorting" rowspan="2">開通日</th>
                                <th class="sorting" rowspan="2">再発行日</th>
                                <th class="sorting" rowspan="2">解約日</th>
                                <th class="sorting" rowspan="2">貸出日</th>
                                <th class="sorting" rowspan="2">RS返却日</th>
                                <th class="sorting" rowspan="2">ドコモ返却依頼日</th>
                                <th class="sorting" rowspan="2">料金プラン</th>
                                <th colspan="7" class="pt-1 pb-1">オプション</th>
                                <th rowspan="2">仕入価格</th>
                                <th rowspan="2">販売価格</th>
                                <th rowspan="2">マージン</th>
                                <th rowspan="2">開通手数料</th>
                                <th rowspan="2">備考</th>
                            </tr>
                            <tr class="bg-sky">
                                <th class="pt-1 pb-1" data-toggle="tooltip" title="国際電話WORLD　CALL">国際電話WC</th>
                                <th class="pt-1 pb-1" data-toggle="tooltip" title="国際ローミングWORLD　WING">国際ローミングWW</th>
                                <th class="pt-1 pb-1">課金情報機能</th>
                                <th class="pt-1 pb-1">キャッチホン</th>
                                <th class="pt-1 pb-1">転送でんわ</th>
                                <th class="pt-1 pb-1">国際着信転送</th>
                                <th class="pt-1 pb-1">留守番電話</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $tukehenhai_list = array(
                                    '0'=> '廃止',
                                    '1'=> '新付',
                                    '2'=> '変更',
                            );
                            ?>
                            <?php foreach ($sim_stock as $sim_info) : ?>
                            <tr>
                                <td class="text-center">
                                    <label class="checkbox mb-0">
                                        <input type="checkbox" class="child check-lg" value="<?php echo $sim_info->UID; ?>" />
                                    </label>
                                </td>
                                <!-- ID -->
                                <td><?php echo $sim_info->UID;?></td>
                                <!-- メーカー -->
                                <td><?php echo substr($sim_info->SEIZOBANGO, 0, 2);?></td>
                                <!-- 製造番号 -->
                                <td><?php echo $sim_info->SEIZOBANGO;?></td>
                                <!-- 代表回線 -->
                                <td>-</td>
                                <!-- 電話番号 -->
                                <td><?php echo empty($sim_info->denwabango) ? '-' : $sim_info->denwabango; ?></td>
                                <!-- 暗証番号 -->
                                <td><?php echo empty($sim_info->ansyobango) ? '-' : $sim_info->ansyobango; ?></td>
                                <!-- SIMタイプ -->
                                <td><?php echo $sim_type_list[$sim_info->SIM_TYPE];?></td>
                                <!-- 入荷日 -->
                                <td><?php echo date('Y/m/d', strtotime($sim_info->ARRIVAL_DATETIME));?></td>
                                <!-- 発送状態 -->
                                <td><?php echo get_select_property('shipment_flag')[$sim_info->SHIPMENT_FLAG]; ?></td>
                                <!--  出荷日-->
                                <td>
                                <?php 
                                if ($sim_info->SHIPMENT_FLAG == SHIPMENT_UNDO)
                                {
                                    echo '-';
                                }
                                else
                                {
                                    echo date('Y/m/d', strtotime($sim_info->SHIPMENT_DATETIME));
                                }
                                ?>
                                </td>
                                <!-- 納品希望日 -->
                                <td><?php echo empty($sim_info->DELIVERY_DATE) ? '-' : date('Y/m/d', strtotime($sim_info->DELIVERY_DATE));?></td>
                                <!-- 出荷先 -->
                                <td><?php echo empty($sim_info->SHIPMENT_DEST) ? '-' : $sim_info->SHIPMENT_DEST;?></td>
                                <!-- 出荷先2 -->
                                <td><?php echo empty($sim_info->SHIPMENT_DEST2) ? '-' : $sim_info->SHIPMENT_DEST2;?></td>
                                <!-- MVNO番号 -->
                                <td><?php echo empty($sim_info->MVNO_ID) ? '-' : $sim_info->MVNO_ID;?></td>
                                <!-- Pool番号 -->
                                <td><?php echo empty($sim_info->POOL_ID) ? '-' : $sim_info->POOL_ID;?></td>
                                <!-- 半黒化日 -->
                                <td><?php echo empty($sim_info->HB_DATE) ? '-' : date('Y/m/d', strtotime($sim_info->HB_DATE));?></td>
                                <!-- 開通日 -->
                                <td><?php echo empty($sim_info->OPEN_DATE) ? '-' : date('Y/m/d', strtotime($sim_info->OPEN_DATE));?></td>
                                <!-- 再発行日 -->
                                <td><?php echo empty($sim_info->REISSUE_DATE) ? '-' : date('Y/m/d', strtotime($sim_info->REISSUE_DATE));?></td>
                                <!-- 解約日 -->
                                <td><?php echo empty($sim_info->CANCELLATION_DATE) ? '-' : date('Y/m/d', strtotime($sim_info->CANCELLATION_DATE));?></td>
                                <!-- 貸出日 -->
                                <td><?php echo empty($sim_info->LOAN_DATE) ? '-' : date('Y/m/d', strtotime($sim_info->LOAN_DATE));?></td>
                                <!-- RS返却日 -->
                                <td><?php echo empty($sim_info->RS_RETURN_DATE) ? '-' : date('Y/m/d', strtotime($sim_info->RS_RETURN_DATE));?></td>
                                <!-- ドコモ返却依頼日 -->
                                <td><?php echo empty($sim_info->DOCOMO_RESULT_REQ_DATE) ? '-' : date('Y/m/d', strtotime($sim_info->DOCOMO_RESULT_REQ_DATE));?></td>
                                <!-- 料金プラン -->
                                <td><?php echo empty($sim_info->ryokinplan) ? '-' : get_select_property('ryokinplan')[$sim_info->ryokinplan];?></td>
                                <?php 
                                    // 割引プラン
                                    $sousaservice_arr = convert_sousaservice($sim_info->sousaservice);
                                ?>
                                <!-- 国際電話WC -->
                                <td><?php echo !str_empty($sim_info->WWtukehenhaiFLG) ? $tukehenhai_list[$sim_info->WWtukehenhaiFLG] : '-'; ?></td>
                                <!-- 国際ローミングWW -->
                                <td><?php echo !str_empty($sim_info->WCtukehenhaiFLG) ? $tukehenhai_list[$sim_info->WCtukehenhaiFLG] : '-'; ?></td>
                                <!-- 課金情報機能 -->
                                <td><?php echo isset($sousaservice_arr['C0324']) ? $tukehenhai_list[$sousaservice_arr['C0324']] : '-'; ?></td>
                                <!-- キャッチホン -->
                                <td><?php echo isset($sousaservice_arr['C0005']) ? $tukehenhai_list[$sousaservice_arr['C0005']] : '-'; ?></td>
                                <!-- 転送でんわ -->
                                <td><?php echo isset($sousaservice_arr['C0013']) ? $tukehenhai_list[$sousaservice_arr['C0013']] : '-'; ?></td>
                                <!-- 国際着信転送 -->
                                <td><?php echo isset($sousaservice_arr['C0007']) ? $tukehenhai_list[$sousaservice_arr['C0007']] : '-'; ?></td>
                                <!-- 留守番電話 -->
                                <td><?php echo isset($sousaservice_arr['C0020']) ? $tukehenhai_list[$sousaservice_arr['C0020']] : '-'; ?></td>
                                <!-- 仕入価格 -->
                                <td>-</td>
                                <!-- 販売価格 -->
                                <td>-</td>
                                <!-- マージン -->
                                <td>-</td>
                                <!-- 開通手数料 -->
                                <td>-</td>
                                <!-- 備考 -->
                                <td style="min-width: 200px;" class="pre-line"></td>
                            </tr>
                            <?php endforeach;?>
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
            </div><!-- card body end -->
        </div><!-- card end -->
        <?php endif;?>
        
    </div><!-- container end -->

    <!-- footer -->
    <?php $this->load->view('layout/footer'); ?>
</body>
</html>