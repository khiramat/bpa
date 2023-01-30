<div class="container pb-1">
    <div class="nav-content">
        <a class="logo-text navbar-brand" href="#">BPA</a>
        <div class="dropdown">
            <a href="javascript:void(0)" class="dropdown-toggle pt-1" data-toggle="dropdown">
                <i class="fas fa-user"></i>
                <span>ユーザー1 on <?php echo $_SERVER["SERVER_NAME"]; ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="pl-4 pr-4 pt-2 pb-2" style="width: 240px;">
                    <div class="row">
                        <div class="col-3 "><i class="fas fa-user fsize-40"></i></div>
                        <div class="col-9">
                            <p class="fsize-12 mb-1">前回ログイン日時</p>
                            <p class="fsize-12">2018/02/01 10:10:10</p>
                        </div>
                        <div class="ui divider mb-3"></div>
                        <div class="col-12">
                            <button class="btn btn-danger btn-block btn-sm" id="logout">ログアウト</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $current_url = uri_string(); ?>
    <div id="nav-menu">
        <ul>
	        <li class="<?php if (stripos($current_url, 'home/') === 0) echo 'active'; ?>">
		        <a href="<?php echo site_url('home/index');?>">Home</a>
		        <div class="sub-menu">
		        </div>
	        </li>
            <li class="<?php if (stripos($current_url, 'order/') === 0) echo 'active'; ?>">
                <a href="<?php echo site_url('order/new_order');?>">オーダー管理</a>
                <div class="sub-menu">
<!--                    <a href="<?php echo site_url('order/device_ordering');?>" class="<?php if (stripos($current_url, 'order/device_ordering') === 0) echo 'active'; ?>">
                        <i class="fas fa-plus-circle"></i>&emsp;IoTデバイス発注管理
                    </a> -->
                    <a href="<?php echo site_url('order/new_order');?>" class="<?php if (stripos($current_url, 'order/new_order') === 0) echo 'active'; ?>">
                        <i class="fas fa-plus-circle"></i>&emsp;各種オーダー
                    </a>
                    <a href="<?php echo site_url('order/order_list');?>" class="<?php if (stripos($current_url, 'order/order_list') === 0 or stripos($current_url, 'order/order_input_list') === 0 or stripos($current_url, 'order/order_edit') === 0) echo 'active'; ?>">
                        <i class="fas fa-list-ul"></i>&emsp;登録済みオーダー一覧&emsp;&emsp;
                    </a>
                    <a href="<?php echo site_url('order/open_waiting');?>" class="<?php if (stripos($current_url, 'order/open_waiting') === 0) echo 'active'; ?>">
                        <i class="fas fa-list-ul"></i>&emsp;半黒SIM 開通待ち一覧&emsp;&emsp;
                    </a>
                </div>
            </li>
            <li class="<?php if (stripos($current_url, 'inventory/') === 0) echo 'active'; ?>">
                <a href="<?php echo site_url('inventory/sim_storage');?>">インベントリ管理</a>
                <div class="sub-menu">
                    <a href="<?php echo site_url('order/sim_ordering');?>" class="<?php if (stripos($current_url, 'order/sim_ordering') === 0) echo 'active'; ?>" >
                        <i class="fas fa-plus-circle"></i>&emsp;SIM 発注管理
                    </a>
                    <a href="<?php echo site_url('inventory/sim_storage');?>" class="<?php if (stripos($current_url, 'inventory/sim_storage') === 0) echo 'active'; ?>">
                        <i class="fas fa-plus-circle"></i>&emsp;SIM 入庫処理
                    </a>
                    <a href="<?php echo site_url('inventory/sim_stock');?>" class="<?php if (stripos($current_url, 'inventory/sim_stock') === 0) echo 'active'; ?>">
                        <i class="fas fa-plus-circle"></i>&emsp;SIM 在庫表
                    </a>
                 <!--   <a href="<?php echo site_url('inventory/device_storage');?>" class="<?php if (stripos($current_url, 'inventory/device_storage') === 0) echo 'active'; ?>">
                        <i class="fas fa-plus-circle"></i>&emsp;IoTデバイス入庫処理
                    </a>
                    <a href="<?php echo site_url('inventory/device_stock');?>" class="<?php if (stripos($current_url, 'inventory/device_stock') === 0) echo 'active'; ?>">
                        <i class="fas fa-plus-circle"></i>&emsp;IoTデバイス在庫表
                    </a> -->
                </div>
            </li>
            <li class="<?php if (stripos($current_url, 'logistics/') === 0) echo 'active'; ?>">
                <a href="<?php echo site_url('logistics/sim_shipping');?>">ロジスティック管理</a>

                <div class="sub-menu">
	                <!--  <a href="<?php echo site_url('logistics/sim_shipping');?>" class="<?php if (stripos($current_url, 'logistics/sim_shipping') === 0) echo 'active'; ?>">
                        <i class="fas fa-plus-circle"></i>&emsp;顧客別 SIM 発送管理
                    </a> -->
               <!--     <a href="<?php echo site_url('logistics/device_shipping');?>" class="<?php if (stripos($current_url, 'logistics/device_shipping') === 0) echo 'active'; ?>">
                        <i class="fas fa-plus-circle"></i>&emsp;顧客別IoTデバイス発送
                    </a> -->
                </div>
            </li>
	        <!--
            <li class="<?php if (stripos($current_url, 'status/') === 0) echo 'active'; ?>">
                <a href="<?php echo site_url('status/sim');?>">ステータス管理</a>
                <div class="sub-menu">
                    <a href="<?php echo site_url('status/sim');?>" class="<?php if (stripos($current_url, 'status/sim') === 0) echo 'active'; ?>">
                        <i class="fas fa-plus-circle"></i>&emsp;SIMステータス一覧
                    </a>
                   <a href="<?php echo site_url('status/device');?>" class="<?php if (stripos($current_url, 'status/device') === 0) echo 'active'; ?>">
                        <i class="fas fa-plus-circle"></i>&emsp;IoTデバイスステータス一覧
                    </a>
               </div>
            </li> -->
            <li class="<?php if (stripos($current_url, 'claim/') === 0) echo 'active'; ?>">
                <a href="<?php echo site_url('claim/data_output');?>">請求管理</a>
                <div class="sub-menu">
	                  <a href="<?php echo site_url('claim/data_input');?>" class="<?php if (stripos($current_url, 'claim/data_input') === 0) echo 'active'; ?>">
                        <i class="fas fa-plus-circle"></i>&emsp;請求データアップロード
                    </a>
                    <a href="<?php echo site_url('claim/data_output');?>" class="<?php if (stripos($current_url, 'claim/data_output') === 0) echo 'active'; ?>">
                        <i class="fas fa-plus-circle"></i>&emsp;請求データ出力
                    </a>
               </div>
            </li>
            <li class="<?php if (stripos($current_url, 'analysis/') === 0) echo 'active'; ?>">
                <a href="<?php echo site_url('analysis/operate_rate');?>">各種分析</a>
                <div class="sub-menu">
                    <a href="<?php echo site_url('analysis/operate_rate');?>" class="<?php if (stripos($current_url, 'analysis/operate_rate') === 0) echo 'active'; ?>">
                        <i class="fas fa-chart-line"></i>&emsp;SIM 稼働率
                    </a>
                    <a href="<?php echo site_url('#');?>" class="<?php if (stripos($current_url, 'analysis/traffic') === 0) echo 'active'; ?>">
                        <i class="fas fa-chart-bar"></i>&emsp;各社レポートサイト
                    </a>
                    <a href="<?php echo site_url('#');?>" class="<?php if (stripos($current_url, 'analysis/forecast') === 0) echo 'active'; ?>">
                        <i class="fas fa-chart-bar"></i>&emsp;各種フォーキャスト
                    </a>
                </div>
            </li>
        </ul>
    </div>
    <div class="ui divider"></div>
</div>
