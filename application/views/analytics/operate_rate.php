<html lang="jp">
<head>
	<?php $this->load->view('layout/import');?>
	<title>BPA | 開通率</title>
</head>
<body class="cloak">
<!-- header -->
<?php $this->load->view('layout/header');?>

<div class="container">
	<ul class="breadcrumb bg-white mb-2 pt-0 pb-0 pl-1">
		<li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
		<li class="breadcrumb-item active">各種分析 / SIM開通率</li>
	</ul>

	<div class="ui divider"></div>

	<div class="row">
		<!-- acs menu -->
		<div class="col-lg-3 acs_menu mb-3">
			<div class="row stretched">
				<div class="col-12">
					<div class="card">
						<div class="card-header d-none d-lg-inline-block pt-2 pb-2">テナント別 SIM 開通率</div>
						<div class="card-body p-0 p-lg-3">
							<div>
							</div>
						</div>
					</div><!-- card end -->
				</div><!-- col end -->
			</div><!-- row end -->
		</div><!-- acs menu end -->
	</div><!-- row end -->
</div><!-- container end -->
<!-- footer -->
<?php $this->load->view('layout/footer'); ?>
</body>
</html>