<div class="card">
    <div class="card-header pt-2 pb-2">SIM開通
        <small class="float-right fsize-12 text-secondary">
            <a class="text-secondary" href="<?php echo site_url('order/order_list');?>">新規オーダー一覧へ</a>
        </small>
    </div>
    <div class="card-body">
        <div class="error-area">
            <?php echo validation_errors('・'); ?>
        </div>
        <?php echo form_open('order/new_order/validate', array('class'=>'mainForm'));?>
            <div class="grid-table">
                <div class="row">
                    <div class="col-4 col-md-3 grid-th">開通種別選択</div>
                    <div class="col-8 col-md-9 grid-td">
                        <div class="col-md-6 p-0">
                        <?php echo form_dropdown('transactionTYPE', get_select_property('transaction_type_new'), 
                                            set_value('transactionTYPE'), array('class' => 'form-control form-control-sm')); ?>
                        </div>
                    </div>
                </div><!-- row end -->
                
                <div class="row">
                    <div class="col-4 col-md-3 grid-th">テナント選択</div>
                    <div class="col-8 col-md-9 grid-td">
                        <div class="col-md-6 p-0">
                            <select id="s1" class="form-control form-control-sm" name="TENANT_ID">
                            </select>
                        </div>
                    </div>
                </div><!-- row end -->
                
                
                <div class="row">
                    <div class="col-4 col-md-3 grid-th">枚数</div>
                    <div class="col-8 col-md-9 grid-td">
                        <div class="col-md-3 p-0">
                            <input type="text" class="form-control form-control-sm" name="LINE_CNT" maxlength="5" value="<?php echo set_value('LINE_CNT');?>" />
                        </div>
                    </div>
                </div><!-- row end -->
                <div class="row">
                    <div class="col-4 col-md-3 grid-th">電話番号</div>
                    <div class="col-8 col-md-9 grid-td">
                        <div class="col-md-6 p-0">
                            <input type="text" class="form-control form-control-sm" name="denwabango" maxlength="16" value="<?php echo set_value('denwabango');?>" />
                        </div>
                    </div>
                </div><!-- row end -->
                <div class="row">
                    <div class="col-4 col-md-3 grid-th">カード形状</div>
                    <div class="col-8 col-md-9 grid-td">
                        <label class="form-check form-check-inline mb-0">
                            <input type="radio" class="form-check-input" name="cardkeijo" value="0" <?php echo set_radio('cardkeijo', '0', TRUE);?> />通常SIMカード
                        </label>
                        <label class="form-check form-check-inline mb-0">
                            <input type="radio" class="form-check-input" name="cardkeijo" value="1" <?php echo set_radio('cardkeijo', '1');?> />miniSIMカード
                        </label>
                        <label class="form-check form-check-inline mb-0">
                            <input type="radio" class="form-check-input" name="cardkeijo" value="3" <?php echo set_radio('cardkeijo', '3');?> />nanoSIMカード
                        </label>
                    </div>
                </div><!-- row end -->
                <div class="row">
                    <div class="col-4 col-md-3 grid-th">MNP予約番号</div>
                    <div class="col-8 col-md-9 grid-td">
                        <div class="col-md-6 p-0">
                            <input type="text" class="form-control form-control-sm" name="MNPyoyakubango" maxlength="10" value="<?php echo set_value('MNPyoyakubango');?>" />
                        </div>
                    </div>
                </div><!-- row end -->
                <div class="row">
                    <div class="col-4 col-md-3 grid-th">MNP属性</div>
                    <div class="col-8 col-md-3 grid-td">
                        <?php echo form_dropdown('MNPzokusei', get_select_property('MNPzokusei'), 
                                            set_value('MNPzokusei'), array('class' => 'form-control form-control-sm')); ?>
                    </div>
                    <div class="col-4 col-md-3 grid-th">MNP生年月日</div>
                    <div class="col-8 col-md-3 grid-td">
                        <input type="text" class="form-control form-control-sm" name="MNPseinengappi" maxlength="8"
                               data-toggle="datetimepicker" data-max-date="{{moment().endOf('day')}}" value="<?php echo set_value('MNPseinengappi');?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 col-md-3 grid-th">MNP予約者名カナ</div>
                    <div class="col-8 col-md-3 grid-td">
                        <input type="text" class="form-control form-control-sm" name="MNPyoyakusyakana" maxlength="25" value="<?php echo set_value('MNPyoyakusyakana');?>" />
                    </div>
                    <div class="col-4 col-md-3 grid-th">MNP予約者名漢字</div>
                    <div class="col-8 col-md-3 grid-td">
                        <input type="text" class="form-control form-control-sm" name="MNPyoyakusyakanji" maxlength="25" value="<?php echo set_value('MNPyoyakusyakanji');?>" />
                    </div>
                </div><!-- row end -->
                <div class="row">
                    <div class="col-4 col-md-3 grid-th">契約種別</div>
                    <div class="col-8 col-md-3 grid-td">
                    <select id="s2" class="form-control form-control-sm" name="contracttype"></select>
                    </div>
                    <div class="col-4 col-md-3 grid-th">料金プラン</div>
                    <div class="col-8 col-md-3 grid-td">
                        <?php 
                            $ryokinplan_list = array('' => '選択してください') + get_select_property('ryokinplan');
                            echo form_dropdown('ryokinplan', $ryokinplan_list, 
                                            set_value('ryokinplan'), array('class' => 'form-control form-control-sm')); 
                        ?>
                    </div>
                </div><!-- row end -->
     
<!-- Added by zz 0 -->
                <div class="row">
                    <div class="col-4 col-md-3 grid-th">Pool Group選択</div>
                    <div class="col-8 col-md-9 grid-td">
                        <div class="col-md-6 p-0">
                        <select id="s3" class="form-control form-control-sm" name="POOL_GROUP"></select>
                        </div>
                    </div>
                </div><!-- row end -->
<!-- Added by zz 1 -->
                
                <div class="row">
                    <div class="col-4 col-md-3 grid-th">暗証番号</div>
                    <div class="col-8 col-md-9 grid-td">
                        <div class="col-md-3 p-0">
                            <input type="text" class="form-control form-control-sm" name="ansyobango" value="<?php echo set_value('ansyobango');?>" maxlength="4" />
                        </div>
                    </div>
                </div><!-- row end -->
                <div class="row">
                    <div class="col-4 col-md-3 grid-th">割引プラン/オプション</div>
                    <div class="col-8 col-md-9 grid-td">
                        <input type="hidden" name="sousaservice" />
                        <div class="row m-0 w-100">
                            <?php $i = 0; foreach (get_select_property('sousaservice') as $value => $text) : $i++;?>
                            <div class="col-12 col-lg-6 p-0 fsize-12 sousaservice-area">
                                <label class="form-check form-check-inline mb-0"> 
                                    <input type="checkbox" value="<?php echo $value;?>" class="form-check-input"><?php echo $text;?>
                                </label>
                                <div class="float-right pr-0 pr-lg-2 pr-xl-4">
                                    <label class="form-check form-check-inline mb-0 mr-1">
                                        <input type="radio" class="form-check-input" name="tukehaiFlg_<?php echo $i;?>" value="0" checked disabled />廃
                                    </label>
                                    <label class="form-check form-check-inline mb-0">
                                        <input type="radio" class="form-check-input" name="tukehaiFlg_<?php echo $i;?>" value="1" disabled />付
                                    </label>
                                </div>
                            </div><!-- col end -->
                            <?php endforeach;?>
                        </div><!-- row end -->
                    </div>
                </div><!-- row end -->
                <div class="row">
                    <div class="col-4 col-md-3 grid-th">WW付変廃フラグ</div>
                    <div class="col-8 col-md-3 grid-td">
                        <label class="form-check form-check-inline mb-0">
                            <input type="radio" class="form-check-input" name="WWtukehenhaiFLG" value="0" <?php echo set_radio('WWtukehenhaiFLG', '0');?> />廃止
                        </label>
                        <label class="form-check form-check-inline mb-0">
                            <input type="radio" class="form-check-input" name="WWtukehenhaiFLG" value="1" <?php echo set_radio('WWtukehenhaiFLG', '1');?> />新付
                        </label>
                        <label class="form-check form-check-inline mb-0">
                            <input type="radio" class="form-check-input" name="WWtukehenhaiFLG" value="2" <?php echo set_radio('WWtukehenhaiFLG', '2');?> />変更
                        </label>
                    </div>
                    <div class="col-4 col-md-3 grid-th">WW利用停止目安額</div>
                    <div class="col-8 col-md-3 grid-td">
                        <?php echo form_dropdown('WWriyouteisimeyasugaku', get_select_property('WWriyouteisimeyasugaku'), 
                                        set_value('WWriyouteisimeyasugaku'), array('class' => 'form-control form-control-sm')); ?>
                    </div>
                </div><!-- row end -->
                <div class="row">
                    <div class="col-4 col-md-3 grid-th">WW第三国発信規制</div>
                    <div class="col-8 col-md-9 grid-td">
                        <label class="form-check form-check-inline mb-0">
                            <input type="radio" class="form-check-input" name="WWdaisankokuhassinkisei" value="0" <?php echo set_radio('WWdaisankokuhassinkisei', '0');?> />規制しない
                        </label>
                        <label class="form-check form-check-inline mb-0">
                            <input type="radio" class="form-check-input" name="WWdaisankokuhassinkisei" value="1" <?php echo set_radio('WWdaisankokuhassinkisei', '1');?> />規制する
                        </label>
                    </div>
                </div><!-- row end -->
                <div class="row">
                    <div class="col-4 col-md-3 grid-th">WC付変廃フラグ</div>
                    <div class="col-8 col-md-3 grid-td">
                        <label class="form-check form-check-inline mb-0">
                            <input type="radio" class="form-check-input" name="WCtukehenhaiFLG" value="0" <?php echo set_radio('WCtukehenhaiFLG', '0');?> />廃止
                        </label>
                        <label class="form-check form-check-inline mb-0">
                            <input type="radio" class="form-check-input" name="WCtukehenhaiFLG" value="1" <?php echo set_radio('WCtukehenhaiFLG', '1');?> />新付
                        </label>
                        <label class="form-check form-check-inline mb-0">
                            <input type="radio" class="form-check-input" name="WCtukehenhaiFLG" value="2" <?php echo set_radio('WCtukehenhaiFLG', '2');?> />変更
                        </label>
                    </div>
                    <div class="col-4 col-md-3 grid-th">WC利用停止目安額</div>
                    <div class="col-8 col-md-3 grid-td">
                        <?php echo form_dropdown('WCriyouteisimeyasugaku', get_select_property('WCriyouteisimeyasugaku'),
                                        set_value('WCriyouteisimeyasugaku'), array('class' => 'form-control form-control-sm')); ?>
                    </div>
                </div><!-- row end -->
                <div class="row">
                    <div class="col-4 col-md-3 grid-th">WC通話停止</div>
                    <div class="col-8 col-md-9 grid-td">
                        <label class="form-check form-check-inline mb-0">
                            <input type="radio" class="form-check-input" name="WCtuwateisi" value="0" <?php echo set_radio('WCtuwateisi', '0');?> />停止しない
                        </label>
                        <label class="form-check form-check-inline mb-0">
                            <input type="radio" class="form-check-input" name="WCtuwateisi" value="1" <?php echo set_radio('WCtuwateisi', '1');?> />停止する
                        </label>
                    </div>
                </div><!-- row end -->
            </div><!-- grid table end -->
            
            <div class="row mt-30 buttons">
                <!-- 検証 -->
                <div class="col-md-4 offset-md-4" id="validate">
                    <button type="submit" class="btn btn-primary btn-block">確認</button>
                </div>
                <!-- 入力内容確認 -->
                <div class="col-6 col-md-3 offset-md-3" style="display: none;" id="reenter">
                    <button type="button" class="btn btn-secondary btn-block">再入力</button>
                </div>
                <div class="col-6 col-md-3" style="display: none;" id="confirm" data-url="<?php echo site_url('order/new_order/sim_open');?>">
                    <button type="button" class="btn btn-info btn-block">オーダー</button>
                </div>
            </div>
        <?php echo form_close();?>
    </div><!-- card body -->
</div><!-- card end -->