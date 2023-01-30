<div class="container">
<div class="row pt-5">
        <div class="col-sm">&copy; 2018 Ranger Systems Co., Ltd. All rights reserved.</div>
        <div class="col-sm">Powered by Platform technical division</div>
</div>
</div>
<!-- page-spinner -->
<div class="page-spinner">
    <div class="spinner"></div>
    <div class="spinner-text">
        <i class="fas fa-spinner"></i>
        <p>loading...</p>
    </div>
</div>

<!-- confirm -->
<div class="modal fade confirm-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h5 class="mb-4"><i class="fas fa-exclamation-triangle text-warning fsize-20"></i> 処理前の確認</h5>
                <div class="modal-text text-secondary"></div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger mr-3 btn-sm" data-dismiss="modal">キャンセル</button>
                <button type="button" class="btn btn-success confirm btn-sm">&emsp;確定&emsp;</button>
            </div>
        </div>
    </div>
</div>

<!-- logger -->
<div class="modal logger-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="icon-area info">
                    <i class="fas fa-info text-info"></i>
                </div>
                <div class="icon-area success">
                    <i class="fas fa-check text-success"></i>
                </div>
                <div class="icon-area warning">
                    <i class="fas fa-exclamation text-warning"></i>
                </div>
                <div class="icon-area error">
                    <i class="fas fa-times text-danger"></i>
                </div>
                <h5 class="mb-4 modal-title"></h5>
                <div class="modal-text text-secondary"></div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-success btn-sm pl-3 pr-3"></button>
            </div>
        </div>
    </div>
</div>

<!-- Brootstrap Modal -->
<div class="modal fade" id="bpaModalCenter" tabindex="-1" role="dialog" aria-labelledby="bpaModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bpaModalLongTitle"></h5>
<!--         <button type="button" class="close" data-dismiss="modal" aria-label="Close"> -->
<!--           <span aria-hidden="true">&times;</span> -->
<!--         </button> -->
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">確認</button>
<!--         <button type="button" class="btn btn-primary">確認</button> -->
      </div>
    </div>
  </div>
</div>