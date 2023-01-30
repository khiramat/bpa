<div class="card">
    <div class="card-header pt-2 pb-2">ファイルアップロード
        <small class="float-right fsize-12 text-secondary">
            <a class="text-secondary" href="<?php echo site_url('order/order_list');?>">新規オーダー一覧へ</a>
        </small>
    </div>
    <div class="card-body">
        <div class="dropzone-area" data-url="<?php echo site_url('order/new_order/upload_file');?>">
            <div class="dropzone-description dz-message">
                <i class="glyphicon glyphicon-open-file" aria-hidden="true"></i>
                <span>[ excel ] ファイルをアップロード</span>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-5 offset-1 col-md-3 offset-md-3">
                <button type="button" class="btn btn-secondary btn-block" id="btn_reset">クリア</button>
            </div>
            <div class="col-5 col-md-3">
                <button type="button" class="btn btn-primary btn-block" id="btn_upload">アップロード</button>
            </div>
        </div>
    </div>
</div>