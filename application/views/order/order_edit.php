<html lang="jp">
<head>
<?php $this->load->view('layout/import');?>
<title>BPA | オーダー管理</title>
<?php if (!isset($err_msg)) : ?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/order/order_edit.js"></script>
<script type="text/javascript">
var acs_property = <?php echo json_encode(get_acs_property('open') + get_acs_property('modify'));?>;
</script>
<?php endif;?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/order/cascading_select_tag.js"></script>
</head>
<body class="cloak">
    <!-- header -->
    <?php $this->load->view('layout/header');?>
    
    <div class="container pb-2">
        <ul class="breadcrumb bg-white mb-2 pt-0 pb-0 pl-1">
            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="<?php echo site_url('order/order_list');?>">API新規オーダー一覧</a></li>
            <li class="breadcrumb-item active">データ編集</li>
        </ul>
        
        <?php if (isset($err_msg)) : ?>
        <div class="card">
            <div class="card-body text-center d-flex align-items-center justify-content-center" style="height: 240px;">
                <div>
                    <h5 class="text-secondary"><?php echo $err_msg;?></h5><br/>
                    <a href="<?php echo site_url('order/order_list');?>" class="btn btn-success">&emsp;新規オーダー一覧へ&emsp;</a>
                </div>
            </div>
        </div>
        <?php else :?>
        <div class="error-area"></div>
        <?php echo form_open('order/order_edit/update', array('class'=>'mainForm'));?>
        <input type="hidden" name="UID" value="<?php echo $input_list_info->UID;?>" />
        <input type="hidden" name="LINE_CNT" value="<?php echo $input_data_info->LINE_CNT;?>" />
        <input type="hidden" name="TENANT_ID" value="<?php echo $input_data_info->TENANT_ID;?>" />
        <!-- 入力データ -->
        <div class="grid-table">
        
            <!-- 入力データ情報 -->
            <div class="row">
                <div class="col-12 grid-td border-bottom-0"><i class="fas fa-info-circle text-success"></i>&nbsp;入力データ情報</div>
            </div>
            <div class="pl-4 pl-md-5 w-100">
                <div class="grid-table mb-0">
                    <div class="row">
                        <div class="col-4 col-md-2 grid-th">入力データID</div>
                        <div class="col-8 col-md-4 grid-td"><?php echo $input_data_info->UID;?></div>
                        <div class="col-4 col-md-2 grid-th">枚数</div>
                        <div class="col-8 col-md-4 grid-td"><?php echo $input_data_info->LINE_CNT;?></div>
                        <div class="col-4 col-md-2 grid-th">テナント名</div>
                        <div class="col-8 col-md-4 grid-td">
                        		<select id="s1" class="form-control form-control-sm" name="TENANT_ID"></select>
                        	</div>
                        <div class="col-4 col-md-2 grid-th">状態</div>
                        <div class="col-8 col-md-4 grid-td">
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
                    </div><!-- row end -->
                </div><!-- grid-table end -->
            </div><!-- pl-4 end -->
            <div class="row"><div class="col-12 grid-td"></div></div>
            <!-- 入力データ情報 end -->
        
            <!-- 基本情報 -->
            <div class="row">
                <div class="col-12 grid-td border-bottom-0"><i class="fas fa-info-circle text-success"></i>&nbsp;基本情報</div>
            </div>
            <div class="pl-4 pl-md-5 w-100">
                <div class="grid-table mb-0">
                    <div class="row">
                        <div class="col-4 col-md-2 grid-th">ID</div>
                        <div class="col-8 col-md-4 grid-td"><?php echo $input_list_info->UID;?></div>
                        <div class="col-4 col-md-2 grid-th">ステータス</div>
                        <div class="col-8 col-md-4 grid-td">
                            <?php echo get_select_property('call_status')[$input_list_info->CALL_STATUS];?>
                        </div>
                    </div><!-- row end -->
                </div><!-- grid-table end -->
            </div><!-- pl-4 end -->
            <!-- 基本情報 end -->
            
            <!-- 詳細情報 -->
            <div class="row">
                <div class="col-12 grid-td border-bottom-0 pt-4"><i class="fas fa-th-list text-success"></i>&nbsp;詳細情報</div>
            </div><!-- row end -->
            <div class="pl-4 pl-md-5 w-100">
                <div class="grid-table mb-0 main-content content-fadeIn">
                    <div class="row">
                        <div class="col-4 col-md-2 grid-th">作業種別<span class="font-red">[必須]</span></div>
                        <div class="col-8 col-md-10 grid-td">
                            <div class="col-md-5 p-0">
                                <?php 
                                    $transaction_type_list = get_select_property('transaction_type_new') + get_select_property('transaction_type_modify');
                                    unset($transaction_type_list['']);
                                    // select list
                                    echo form_dropdown('transactionTYPE', $transaction_type_list, $input_list_info->transactionTYPE, 
                                        array('class' => 'form-control form-control-sm'));
                                ?>
                            </div>
                        </div>
                    </div><!-- row end -->
                    <div class="row">
                        <div class="col-4 col-md-2 grid-th">電話番号</div>
                        <div class="col-8 col-md-10 grid-td">
                            <div class="col-md-5 p-0">
                                <input type="text" class="form-control form-control-sm" name="denwabango" maxlength="16" value="<?php echo $input_list_info->denwabango;?>" />
                            </div>
                        </div>
                    </div><!-- row end -->
                    <div class="row">
                        <div class="col-4 col-md-2 grid-th">検索項目</div>
                        <div class="col-8 col-md-10 grid-td">
                            <label class="form-check form-check-inline mb-0">
                                <input type="radio" class="form-check-input" name="kensakukoumoku" value="1" 
                                    <?php if ($input_list_info->kensakukoumoku == KENSAKU_KOUMOKU_MNP) echo 'checked';?> />MNP可否照会
                            </label>
                            <label class="form-check form-check-inline mb-0">
                                <input type="radio" class="form-check-input" name="kensakukoumoku" value="2"
                                    <?php if ($input_list_info->kensakukoumoku == KENSAKU_KOUMOKU_WW) echo 'checked';?> />WW累積額検索
                            </label>
                            <label class="form-check form-check-inline mb-0">
                                <input type="radio" class="form-check-input" name="kensakukoumoku" value="3"
                                    <?php if ($input_list_info->kensakukoumoku == KENSAKU_KOUMOKU_WC) echo 'checked';?> />WC累積額検索
                            </label>
                        </div>
                    </div><!-- row end -->
                    <div class="row">
                        <div class="col-4 col-md-2 grid-th">カード再発行</div>
                        <div class="col-8 col-md-4 grid-td">
                            <label class="form-check form-check-inline mb-0">
                                <input type="checkbox" value="1" class="form-check-input" name="cardsaihakoFLG"
                                    <?php if ($input_list_info->cardsaihakoFLG == CARD_SAIHAKO) echo 'checked';?>>再発行を行う
                            </label>
                        </div>
                        <div class="col-4 col-md-2 grid-th">NW未開通</div>
                        <div class="col-8 col-md-4 grid-td">
                            <label class="form-check form-check-inline mb-0"> 
                                <input type="checkbox" value="1" class="form-check-input" name="NWmikaituFLG" 
                                    <?php if ($input_list_info->NWmikaituFLG == NW_MIKAITU) echo 'checked';?>>未開通とする
                            </label>
                        </div>
                    </div><!-- row end -->
                    <div class="row">
                        <div class="col-4 col-md-2 grid-th">カード形状</div>
                        <div class="col-8 col-md-10 grid-td">
                            <label class="form-check form-check-inline mb-0">
                                <input type="radio" class="form-check-input" name="cardkeijo" value="0"
                                    <?php if ($input_list_info->cardkeijo == CARD_KEIJO_SIM) echo 'checked';?>/>通常SIMカード
                            </label>
                            <label class="form-check form-check-inline mb-0">
                                <input type="radio" class="form-check-input" name="cardkeijo" value="1"
                                    <?php if ($input_list_info->cardkeijo == CARD_KEIJO_MINI) echo 'checked';?>/>miniSIMカード
                            </label>
                            <label class="form-check form-check-inline mb-0">
                                <input type="radio" class="form-check-input" name="cardkeijo" value="3" 
                                    <?php if ($input_list_info->cardkeijo == CARD_KEIJO_NANO) echo 'checked';?>/>nanoSIMカード
                            </label>
                        </div>
                    </div><!-- row end -->
                    <div class="row">
                        <div class="col-4 col-md-2 grid-th">PINロック解除コードリセット</div>
                        <div class="col-8 col-md-10 grid-td">
                            <label class="form-check form-check-inline mb-0"> 
                                <input type="checkbox" value="1" class="form-check-input" name="PINlockkaijoreset"
                                    <?php if ($input_list_info->PINlockkaijoreset == PIN_LOCK_KAIJO_RESET) echo 'checked';?>>リセットする
                            </label>
                        </div>
                    </div><!-- row end -->
                    <div class="row">
                        <div class="col-4 col-md-2 grid-th">MNP予約番号</div>
                        <div class="col-8 col-md-10 grid-td">
                            <div class="col-md-5 p-0">
                                <input type="text" class="form-control form-control-sm" name="MNPyoyakubango" maxlength="10" value="<?php echo $input_list_info->MNPyoyakubango;?>" />
                            </div>
                        </div>
                    </div><!-- row end -->
                    <div class="row">
                        <div class="col-4 col-md-2 grid-th">MNP属性</div>
                        <div class="col-8 col-md-4 grid-td">
                            <?php echo form_dropdown('MNPzokusei', get_select_property('MNPzokusei'), $input_list_info->MNPzokusei,
                                               array('class' => 'form-control form-control-sm')); ?>
                        </div>
                        <div class="col-4 col-md-2 grid-th">MNP生年月日</div>
                        <div class="col-8 col-md-4 grid-td">
                            <input type="text" class="form-control form-control-sm" name="MNPseinengappi" maxlength="10"
                                   data-toggle="datetimepicker" data-max-date="{{moment().endOf('day')}}" value="<?php echo $input_list_info->MNPseinengappi;?>" />
                        </div>
                    </div><!-- row end -->
                    <div class="row">
                        <div class="col-4 col-md-2 grid-th">MNP予約者名カナ</div>
                        <div class="col-8 col-md-4 grid-td">
                            <input type="text" class="form-control form-control-sm" name="MNPyoyakusyakana" maxlength="25" value="<?php echo $input_list_info->MNPyoyakusyakana;?>" />
                        </div>
                        <div class="col-4 col-md-2 grid-th">MNP予約者名漢字</div>
                        <div class="col-8 col-md-4 grid-td">
                            <input type="text" class="form-control form-control-sm" name="MNPyoyakusyakanji" maxlength="25" value="<?php echo $input_list_info->MNPyoyakusyakanji;?>" />
                        </div>
                    </div><!-- row end -->
                    <div class="row">
                    <div class="col-4 col-md-2 grid-th">契約種別</div>
                    <div class="col-8 col-md-4 grid-td">
                    		<select id="s2" class="form-control form-control-sm" name="contracttype"></select>
                    </div>
                        <div class="col-4 col-md-2 grid-th">料金プラン</div>
                        <div class="col-8 col-md-4 grid-td">
                            <?php 
                            $ryokinplan_list = array('' => '選択してください') + get_select_property('ryokinplan');
                            echo form_dropdown('ryokinplan', $ryokinplan_list, 
                                $input_list_info->ryokinplan, array('class' => 'form-control form-control-sm')); 
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 col-md-2 grid-th">POOL GROUP</div>
                        <div class="col-8 col-md-10 grid-td">
                            <div class="col-md-3 p-0">
                            		<select id="s3" class="form-control form-control-sm" name="POOL_GROUP"></select>
                            </div>
                        </div>
                    </div><!-- row end -->
                    
                    <div class="row">
                        <div class="col-4 col-md-2 grid-th">暗証番号</div>
                        <div class="col-8 col-md-10 grid-td">
                            <div class="col-md-3 p-0">
                                <input type="text" class="form-control form-control-sm" name="ansyobango" value="<?php echo $input_list_info->ansyobango;?>" maxlength="4" />
                            </div>
                        </div>
                    </div><!-- row end -->
                    <div class="row">
                        <div class="col-4 col-md-2 grid-th">割引プラン/オプション</div>
                        <div class="col-8 col-md-10 grid-td">
                            <?php $sousaservice_list = convert_sousaservice($input_list_info->sousaservice);?>
                            <input type="hidden" name="sousaservice" />
                            <div class="row m-0 w-100">
                                <?php $i = 0; foreach (get_select_property('sousaservice') as $value => $text) : $i++;?>
                                <div class="col-12 col-md-6 col-lg-4 p-0 fsize-12 sousaservice-area">
                                    <label class="form-check form-check-inline mb-0"> 
                                        <input type="checkbox" value="<?php echo $value;?>" class="form-check-input"
                                            <?php if (isset($sousaservice_list[$value])) echo 'checked'; ?>><?php echo $text;?>
                                    </label>
                                    <div class="float-right pr-0 pr-md-3 pr-xl-5">
                                        <label class="form-check form-check-inline mb-0 mr-1">
                                            <input type="radio" class="form-check-input" name="tukehaiFlg_<?php echo $i;?>" 
                                                   value="0" <?php if (!isset($sousaservice_list[$value]) or $sousaservice_list[$value] == '0') echo 'checked'; ?> />廃
                                        </label>
                                        <label class="form-check form-check-inline mb-0">
                                            <input type="radio" class="form-check-input" name="tukehaiFlg_<?php echo $i;?>" 
                                                   value="1" <?php if (isset($sousaservice_list[$value]) and $sousaservice_list[$value] == '1') echo 'checked'; ?> />付
                                        </label>
                                    </div>
                                </div><!-- col end -->
                                <?php endforeach;?>
                            </div><!-- row end -->
                        </div>
                    </div><!-- row end -->
                    <div class="row">
                        <div class="col-4 col-md-2 grid-th">WW付変廃フラグ</div>
                        <div class="col-8 col-md-4 grid-td">
                            <label class="form-check form-check-inline mb-0">
                                <input type="radio" class="form-check-input" name="WWtukehenhaiFLG" value="0"
                                    <?php if ($input_list_info->WWtukehenhaiFLG == TUKEHENHAI_FLG_ABOLITION) echo 'checked';?> />廃止
                            </label>
                            <label class="form-check form-check-inline mb-0">
                                <input type="radio" class="form-check-input" name="WWtukehenhaiFLG" value="1"
                                    <?php if ($input_list_info->WWtukehenhaiFLG == TUKEHENHAI_FLG_NEW) echo 'checked';?> />新付
                            </label>
                            <label class="form-check form-check-inline mb-0">
                                <input type="radio" class="form-check-input" name="WWtukehenhaiFLG" value="2" 
                                    <?php if ($input_list_info->WWtukehenhaiFLG == TUKEHENHAI_FLG_CHANGE) echo 'checked';?> />変更
                            </label>
                        </div>
                        <div class="col-4 col-md-2 grid-th">WW利用停止目安額</div>
                        <div class="col-8 col-md-4 grid-td">
                            <?php echo form_dropdown('WWriyouteisimeyasugaku', get_select_property('WWriyouteisimeyasugaku'), 
                                            $input_list_info->WWriyouteisimeyasugaku, array('class' => 'form-control form-control-sm')); ?>
                        </div>
                        <div class="col-4 col-md-2 grid-th">WW第三国発信規制</div>
                        <div class="col-8 col-md-10 grid-td">
                            <label class="form-check form-check-inline mb-0">
                                <input type="radio" class="form-check-input" name="WWdaisankokuhassinkisei" value="0" 
                                    <?php if ($input_list_info->WWdaisankokuhassinkisei == HASSIN_KISEI_NO_REGULATION) echo 'checked';?> />規制しない
                            </label>
                            <label class="form-check form-check-inline mb-0">
                                <input type="radio" class="form-check-input" name="WWdaisankokuhassinkisei" value="1"
                                    <?php if ($input_list_info->WWdaisankokuhassinkisei == HASSIN_KISEI_REGULATION) echo 'checked';?> />規制する
                            </label>
                        </div>
                    </div><!-- row end -->
                    <div class="row">
                        <div class="col-4 col-md-2 grid-th">WC付変廃フラグ</div>
                        <div class="col-8 col-md-4 grid-td">
                            <label class="form-check form-check-inline mb-0">
                                <input type="radio" class="form-check-input" name="WCtukehenhaiFLG" value="0" 
                                    <?php if ($input_list_info->WCtukehenhaiFLG == TUKEHENHAI_FLG_ABOLITION) echo 'checked';?> />廃止
                            </label>
                            <label class="form-check form-check-inline mb-0">
                                <input type="radio" class="form-check-input" name="WCtukehenhaiFLG" value="1"
                                    <?php if ($input_list_info->WCtukehenhaiFLG == TUKEHENHAI_FLG_NEW) echo 'checked';?> />新付
                            </label>
                            <label class="form-check form-check-inline mb-0">
                                <input type="radio" class="form-check-input" name="WCtukehenhaiFLG" value="2"
                                    <?php if ($input_list_info->WCtukehenhaiFLG == TUKEHENHAI_FLG_CHANGE) echo 'checked';?> />変更
                            </label>
                        </div>
                        <div class="col-4 col-md-2 grid-th">WC利用停止目安額</div>
                        <div class="col-8 col-md-4 grid-td">
                            <?php echo form_dropdown('WCriyouteisimeyasugaku', get_select_property('WCriyouteisimeyasugaku'),
                                            $input_list_info->WCriyouteisimeyasugaku, array('class' => 'form-control form-control-sm')); ?>
                        </div>
                    </div><!-- row end -->
                    <div class="row">
                        <div class="col-4 col-md-2 grid-th">WC通話停止</div>
                        <div class="col-8 col-md-10 grid-td">
                            <label class="form-check form-check-inline mb-0">
                                <input type="radio" class="form-check-input" name="WCtuwateisi" value="0"
                                    <?php if ($input_list_info->WCtuwateisi == CALL_NOT_STOP) echo 'checked';?> />停止しない
                            </label>
                            <label class="form-check form-check-inline mb-0">
                                <input type="radio" class="form-check-input" name="WCtuwateisi" value="1"
                                    <?php if ($input_list_info->WCtuwateisi == CALL_STOP) echo 'checked';?> />停止する
                            </label>
                        </div>
                    </div><!-- row end -->
                </div><!-- grid-table -->
            </div><!-- pl-4 end -->
            <!-- 詳細情報 end -->
            
            <div class="row"><div class="col-12 grid-td"></div></div>
            
        </div><!-- grid table end -->
        
        <div class="row">
            <div class="col-12 grid-td text-center">
                <button type="button" onclick="location.href=document.referrer;" class="btn btn-secondary btn-sm mr-4">&emsp;戻る&emsp;</button>
                <button type="submit" class="btn btn-info btn-sm">&nbsp;送信する&nbsp;</button>
            </div>
        </div>
        <?php echo form_close();?>
        <?php endif;?>
        
    </div><!-- container end -->

<!-- 	既に設定されている値を各セレクトボックスに設定する -->
	<script type="text/javascript">
		var tenantId = "<?php echo $input_data_info->TENANT_ID;?>";
		var contractType = "<?php echo $input_list_info->contracttype;?>";
		var pool_group = "<?php echo $input_list_info->POOL_GROUP;?>";
		var ryokinPlan = "<?php echo $input_list_info->ryokinplan;?>";
		$(function(){
		    $('#s1').change(function(){
		      var data= $(this).val();
		    });
		    $('#s1').val(tenantId).trigger('change');
		}); // end of function
		
		$(function(){
		    $('#s2').change(function(){
		      var data= $(this).val();
		    });
		    $('#s2').val(contractType).trigger('change');
		}); // end of function
		
		$(function(){
		    $('#s3').change(function(){
		      var data= $(this).val();
		    });
		    $('#s3').val(pool_group).trigger('change');
		}); // end of function

		$(function(){
		    $('select[name=ryokinplan]').change(function(){
		      var data= $(this).val();
		    });
		    $('select[name=ryokinplan]').val(ryokinPlan).trigger('change');
		}); // end of function
	</script>

    
    <!-- footer -->
    <?php $this->load->view('layout/footer'); ?>
</body>
</html>