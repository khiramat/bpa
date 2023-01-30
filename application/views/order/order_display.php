<html lang="jp">
<head>
<?php $this->load->view('layout/import');?>
<title>BPA | オーダー管理</title>
</head>
<body class="cloak">
    <!-- header -->
    <?php $this->load->view('layout/header');?>
    
    <div class="container pb-2">
        <ul class="breadcrumb bg-white mb-2 pt-0 pb-0 pl-1">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="<?php echo site_url('order/order_list');?>">API新規オーダー一覧</a></li>
            <li class="breadcrumb-item active">データ照会</li>
        </ul>
        
        <div class="ui divider"></div>
        
        <?php $display_propertys = (get_acs_property('open') + get_acs_property('modify'))[$input_list_info->transactionTYPE]; ?>
        
        <div class="row">
            <!-- 入力データ情報 -->
            <div class="col-lg-3 mb-3">
                <div class="card border-info">
                    <div class="card-header p-2 bg-info text-white">
                        <span>入力データ情報</span>
                    </div>
                    <div class="card-body p-2">
                        <div class="row fsize-12 mb-0">
                            <div class="form-group col-4 col-lg-12">
                                <label class="mb-0">ID</label>
                                <div class="basic-input bg-light"><?php echo $input_data_info->UID;?></div>
                            </div>
                            <div class="form-group col-5 col-lg-12">
                                <label class="mb-0">テナント名</label>
                                <div class="basic-input bg-light"><?php echo $input_data_info->TENANT_NAME;?></div>
                            </div>
                            <div class="form-group col-3 col-lg-12">
                                <label class="mb-0">総枚数</label>
                                <div class="basic-input bg-light"><?php echo $input_data_info->LINE_CNT;?></div>
                            </div>
                            <div class="form-group col-6 col-lg-12">
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
                            <div class="form-group col-6 col-lg-12">
                                <label class="mb-0">更新日時</label>
                                <div class="basic-input bg-light"><?php echo $input_data_info->UPDATE_DATETIME;?></div>
                            </div>
                        </div>
                    </div><!-- card-body end -->
                </div><!-- card end -->
            </div><!-- col end -->
            
            <div class="col-lg-9">
                <!-- 基本情報 -->
                <div class="card mb-3 border-info">
                    <div class="card-header p-2 bg-info text-white">
                        <span>基本情報</span>
                    </div>
                    <div class="card-body p-2">
                        <div class="row fsize-12 mb-0">
                             <div class="form-group col-6 col-lg-4">
                                <label class="mb-0">ID</label>
                                <div class="basic-input bg-light"><?php echo $input_list_info->UID;?></div>
                            </div>
                            <div class="form-group col-6 col-lg-4">
                                <label class="mb-0">オーダー番号</label>
                                <div class="basic-input bg-light"><?php echo $input_list_info->orderbango;?></div>
                            </div>
                            <div class="form-group col-6 col-lg-4">
                                <label class="mb-0">ステータス</label>
                                <div class="basic-input bg-light"><?php echo get_select_property('call_status')[$input_list_info->CALL_STATUS];?></div>
                            </div>
                            <div class="form-group col-6 col-lg-4">
                                <label class="mb-0">更新日時</label>
                                <div class="basic-input bg-light"><?php echo $input_list_info->UPDATE_DATETIME;?></div>
                            </div>
                            <div class="form-group col-lg-4">
                                <label class="mb-0">ACS結果</label>
                                <?php 
                                    if ($input_list_info->ACS_RESULT === NULL or $input_list_info->ACS_RESULT === '')
                                    {
                                        $acs_result_bg = " bg-light";
                                    }
                                    elseif ($input_list_info->ACS_RESULT == RESULT_OK)
                                    {
                                        $acs_result_bg = " bg-success text-white";
                                    }
                                    else
                                    {
                                        $acs_result_bg = " bg-danger text-white";
                                    }
                                ?>
                                <div class="basic-input<?php echo $acs_result_bg;?>">
                                    <?php
                                    if ($input_list_info->ACS_RESULT === NULL or $input_list_info->ACS_RESULT === '')
                                    {
                                        echo '-';
                                    }
                                    elseif ($input_list_info->ACS_RESULT == RESULT_OK)
                                    {
                                        echo 'ACS要求OK';
                                    }
                                    else
                                    {
                                        echo 'ACS要求NG (' . $input_list_info->ACS_RESULT .')';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <label class="mb-0">ALADIN結果</label>
                                <?php
                                if ($input_list_info->API_STATUS == API_STATUS_REQUEST_OK 
                                        or $input_list_info->API_STATUS == API_STATUS_RESPONSE_OK)
                                    {
                                        $aladin_result_bg = " bg-success text-white";
                                    }
                                    elseif ($input_list_info->API_STATUS == API_STATUS_REQUEST_ERROR
                                        or $input_list_info->API_STATUS == API_STATUS_ERROR)
                                    {
                                        $aladin_result_bg = " bg-danger text-white";
                                    }
                                    else
                                    {
                                        $aladin_result_bg = " bg-light";
                                    }
                                ?>
                                <div class="basic-input<?php echo $aladin_result_bg;?>">
                                    <?php 
                                        if ($input_list_info->API_STATUS == API_STATUS_REQUEST_OK)
                                        {
                                            echo 'ALADIN要求OK';
                                        }
                                        elseif ($input_list_info->API_STATUS == API_STATUS_RESPONSE_OK)
                                        {
                                            echo '完了';
                                        }
                                        elseif ($input_list_info->API_STATUS == API_STATUS_REQUEST_ERROR)
                                        {
                                            echo 'ALADIN要求エラー (' . $input_list_info->API_CODE . ')';
                                        }
                                        elseif ($input_list_info->API_STATUS == API_STATUS_ERROR)
                                        {
                                            echo '未知エラー';
                                        }
                                        else
                                        {
                                            echo '-';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div><!-- card-body end -->
                </div><!-- card end -->
                <!-- 基本情報 end -->
                
                 <!-- 詳細情報 -->
                <div class="card border-info">
                    <div class="card-header p-2 bg-info text-white">詳細情報</div>
                    <div class="card-body p-3">
                        <div class="grid-table content-fadeIn fsize-12">
                            <div class="row">
                                <div class="col-4 col-md-3 grid-th">作業種別</div>
                                <div class="col-8 col-md-9 grid-td">
                                <?php 
                                    $transaction_type_list = get_select_property('transaction_type_new') + get_select_property('transaction_type_modify');
                                    echo $transaction_type_list[$input_list_info->transactionTYPE];
                                ?>
                                </div>
                            </div><!-- row end -->
                            
                            <?php if (!empty($input_list_info->SEIZOBANGO)) : ?>
                            <div class="row">
                                <div class="col-4 col-md-3 grid-th">製造番号</div>
                                <div class="col-8 col-md-9 grid-td">
                                    <div class="col-md-5 p-0"><?php echo $input_list_info->SEIZOBANGO; ?></div>
                                </div>
                            </div><!-- row end -->
                            <?php endif;?>
                            
                            <?php if (!empty($input_list_info->denwabango)) : ?>
                            <div class="row">
                                <div class="col-4 col-md-3 grid-th">電話番号</div>
                                <div class="col-8 col-md-9 grid-td">
                                    <div class="col-md-5 p-0"><?php echo $input_list_info->denwabango; ?></div>
                                </div>
                            </div><!-- row end -->
                            <?php endif;?>
                            
                            <?php if (isset($display_propertys['kensakukoumoku'])) :?>
                            <div class="row">
                                <div class="col-4 col-md-3 grid-th">検索項目</div>
                                <div class="col-8 col-md-9 grid-td">
                                    <?php 
                                    if ($input_list_info->kensakukoumoku !== NULL and $input_list_info->kensakukoumoku !== '')
                                    {
                                        echo get_select_property('kensakukoumoku')[$input_list_info->kensakukoumoku];
                                    }
                                    ?>
                                </div>
                            </div><!-- row end -->
                            <?php endif;?>
                            
                            <?php if (isset($display_propertys['cardsaihakoFLG'])) :?>
                            <div class="row">
                                <div class="col-4 col-md-3 grid-th">カード再発行</div>
                                <div class="col-8 col-md-9 grid-td">
                                    <?php if ($input_list_info->cardsaihakoFLG == CARD_SAIHAKO) echo '再発行を行う';?>
                                </div>
                            </div>
                            <?php endif;?>
                            
                            <?php if (isset($display_propertys['cardkeijo'])) :?>
                            <div class="row">
                                <div class="col-4 col-md-3 grid-th">カード形状</div>
                                <div class="col-8 col-md-9 grid-td">
                                <?php 
                                if ($input_list_info->cardkeijo !== NULL and $input_list_info->cardkeijo !== '')
                                {
                                    echo get_select_property('cardkeijo')[$input_list_info->cardkeijo];
                                }
                                ?>
                                </div>
                            </div><!-- row end -->
                            <?php endif;?>
                            
                            <?php if (isset($display_propertys['NWmikaituFLG'])) :?>
                            <div class="row">
                                <div class="col-4 col-md-3 grid-th">NW未開通</div>
                                <div class="col-8 col-md-9 grid-td">
                                    <?php if ($input_list_info->NWmikaituFLG == NW_MIKAITU) echo '未開通とする';?>
                                </div>
                            </div>
                            <?php endif;?>
                            
                            <?php if (isset($display_propertys['PINlockkaijoreset'])) :?>
                            <div class="row">
                                <div class="col-4 col-md-3 grid-th fsize-12">PINロック解除コードリセット</div>
                                <div class="col-8 col-md-9 grid-td">
                                <?php if ($input_list_info->PINlockkaijoreset == PIN_LOCK_KAIJO_RESET) echo 'リセットする';?>
                                </div>
                            </div><!-- row end -->
                            <?php endif;?>
                            
                            <?php if (isset($display_propertys['MNPyoyakubango'])
                                    and $input_list_info->kensakukoumoku != KENSAKU_KOUMOKU_WC
                                    and $input_list_info->kensakukoumoku != KENSAKU_KOUMOKU_WW) :?>
                            <div class="row">
                                <div class="col-4 col-md-3 grid-th">MNP予約番号</div>
                                <div class="col-8 col-md-9 grid-td"><?php echo $input_list_info->MNPyoyakubango;?></div>
                            </div><!-- row end -->
                            <?php endif;?>
                            
                            <?php if (isset($display_propertys['MNPzokusei']) 
                                    and $input_list_info->kensakukoumoku != KENSAKU_KOUMOKU_WC
                                    and $input_list_info->kensakukoumoku != KENSAKU_KOUMOKU_WW) :?>
                            <div class="row">
                                <div class="col-4 col-md-3 grid-th">MNP属性</div>
                                <div class="col-8 col-md-3 grid-td">
                                <?php 
                                if ($input_list_info->MNPzokusei !== NULL and $input_list_info->MNPzokusei !== '')
                                {
                                    echo get_select_property('MNPzokusei')[$input_list_info->MNPzokusei];
                                }
                                ?>
                                </div>
                                <div class="col-4 col-md-3 grid-th">MNP生年月日</div>
                                <div class="col-8 col-md-3 grid-td">
                                <?php 
                                if (!empty($input_list_info->MNPseinengappi))
                                {
                                    echo date('Y/m/d', strtotime($input_list_info->MNPseinengappi));
                                }
                                ?>
                                </div>
                                <div class="col-4 col-md-3 grid-th">MNP予約者名カナ</div>
                                <div class="col-8 col-md-3 grid-td"><?php echo $input_list_info->MNPyoyakusyakana;?></div>
                                <div class="col-4 col-md-3 grid-th">MNP予約者名漢字</div>
                                <div class="col-8 col-md-3 grid-td"><?php echo $input_list_info->MNPyoyakusyakanji;?></div>
                            </div><!-- row end -->
                            <?php endif;?>
                            
                            <?php if (!empty($display_propertys['ryokinplan'])
                                    and $input_list_info->kensakukoumoku != KENSAKU_KOUMOKU_WC
                                    and $input_list_info->kensakukoumoku != KENSAKU_KOUMOKU_WW) :?>
                            <div class="row">
                                <div class="col-4 col-md-3 grid-th">契約種別</div>
                                <div class="col-8 col-md-3 grid-td">
                                    <?php 
                                        if (!empty($input_list_info->contracttype))
                                        {
                                            echo get_select_property('contracttype')[$input_list_info->contracttype];
                                        }
                                    ?>
                                </div>
                                <div class="col-4 col-md-3 grid-th">料金プラン</div>
                                <div class="col-8 col-md-3 grid-td">
                                    <?php 
                                        if (!empty($input_list_info->ryokinplan))
                                        {
                                            echo get_select_property('ryokinplan')[$input_list_info->ryokinplan];
                                        }
                                    ?>
                                </div>
                           </div>
                           <?php endif;?>
                           
                           <?php if (isset($display_propertys['ansyobango'])) :?>
                           <div class="row">
                                <div class="col-4 col-md-3 grid-th">暗証番号</div>
                                <div class="col-8 col-md-9 grid-td"><?php echo $input_list_info->ansyobango;?></div>
                            </div><!-- row end -->
                            <?php endif;?>
                            
                            <?php if (isset($display_propertys['sousaservice'])) :?>
                            <div class="row">
                                <div class="col-4 col-md-3 grid-th">割引プラン/オプション</div>
                                <div class="col-8 col-md-9 grid-td">
                                    <?php $sousaservice_list = convert_sousaservice($input_list_info->sousaservice);?>
                                    <div class="row m-0 w-100">
                                        <?php foreach (get_select_property('sousaservice') as $value => $text) :?>
                                        <?php if (isset($sousaservice_list[$value])) : ?>
                                        <div class="input-group mb-1 col-lg-6 pl-0">
                                            <div class="basic-input"><?php echo $text;?></div>
                                            <div class="input-group-append">
                                                <div class="input-group-text fsize-12" id="btnGroupAddon">
                                                    <?php echo $sousaservice_list[$value] == '0' ? '廃' : '付';?>
                                                </div>
                                            </div>
                                        </div><!-- input-group col end -->
                                        <?php endif;?>
                                        <?php endforeach;?>
                                    </div><!-- row end -->
                                </div>
                            </div><!-- row end -->
                            <?php endif;?>
                            
                            <?php if (isset($display_propertys['WWtukehenhaiFLG'])) :?>
                            <div class="row">
                                <div class="col-4 col-md-3 grid-th">WW付変廃フラグ</div>
                                <div class="col-8 col-md-3 grid-td">
                                    <?php 
                                    if ($input_list_info->WWtukehenhaiFLG !== NULL and $input_list_info->WWtukehenhaiFLG !== '')
                                    {
                                        if ($input_list_info->WWtukehenhaiFLG == TUKEHENHAI_FLG_ABOLITION)
                                        {
                                            echo '廃止';
                                        }
                                        elseif ($input_list_info->WWtukehenhaiFLG == TUKEHENHAI_FLG_NEW)
                                        {
                                            echo '新付';
                                        }
                                        else
                                        {
                                            echo '変更';
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="col-4 col-md-3 grid-th">WW利用停止目安額</div>
                                <div class="col-8 col-md-3 grid-td">
                                    <?php 
                                    if ($input_list_info->WWriyouteisimeyasugaku !== NULL and $input_list_info->WWriyouteisimeyasugaku !== '')
                                    {
                                        echo get_select_property('WWriyouteisimeyasugaku')[$input_list_info->WWriyouteisimeyasugaku];
                                    }
                                    ?>
                                </div>
                                <div class="col-4 col-md-3 grid-th">WW第三国発信規制</div>
                                <div class="col-8 col-md-9 grid-td">
                                    <?php 
                                    if ($input_list_info->WWdaisankokuhassinkisei !== NULL and $input_list_info->WWdaisankokuhassinkisei !== '')
                                    {
                                        if ($input_list_info->WWdaisankokuhassinkisei == HASSIN_KISEI_NO_REGULATION)
                                        {
                                            echo '規制しない';
                                        }
                                        else
                                        {
                                            echo '規制する';
                                        }
                                    }
                                    ?>
                                </div>
                            </div><!-- row end -->
                            <div class="row">
                                <div class="col-4 col-md-3 grid-th">WC付変廃フラグ</div>
                                <div class="col-8 col-md-3 grid-td">
                                    <?php 
                                    if ($input_list_info->WCtukehenhaiFLG !== NULL and $input_list_info->WCtukehenhaiFLG !== '')
                                    {
                                        if ($input_list_info->WCtukehenhaiFLG == TUKEHENHAI_FLG_ABOLITION)
                                        {
                                            echo '廃止';
                                        }
                                        elseif ($input_list_info->WCtukehenhaiFLG == TUKEHENHAI_FLG_NEW)
                                        {
                                            echo '新付';
                                        }
                                        else
                                        {
                                            echo '変更';
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="col-4 col-md-3 grid-th">WC利用停止目安額</div>
                                <div class="col-8 col-md-3 grid-td">
                                    <?php 
                                    if ($input_list_info->WCriyouteisimeyasugaku !== NULL and $input_list_info->WCriyouteisimeyasugaku !== '')
                                    {
                                        echo get_select_property('WCriyouteisimeyasugaku')[$input_list_info->WCriyouteisimeyasugaku];
                                    }
                                    ?>
                                </div>
                                <div class="col-4 col-md-3 grid-th">WC通話停止</div>
                                <div class="col-8 col-md-9 grid-td">
                                    <?php 
                                    if ($input_list_info->WCtuwateisi !== NULL and $input_list_info->WCtuwateisi !== '')
                                    {
                                        if ($input_list_info->WCtuwateisi == CALL_NOT_STOP)
                                        {
                                            echo '停止しない';
                                        }
                                        else
                                        {
                                            echo '停止する';
                                        }
                                    }
                                    ?>
                                </div>
                            </div><!-- row end -->
                            <?php endif;?>
                        </div><!-- grid-table -->
                        
                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <button type="button" onclick="location.href=document.referrer;" class="btn btn-secondary btn-sm">&emsp;戻る&emsp;</button>
                            </div>
                        </div>
                        
                    </div><!-- card-body end -->
                </div><!-- card end -->
                
            </div><!-- col end -->
            
        </div><!-- row end -->
        
        
    </div><!-- container end -->
    
    <!-- footer -->
    <?php $this->load->view('layout/footer'); ?>
</body>
</html>