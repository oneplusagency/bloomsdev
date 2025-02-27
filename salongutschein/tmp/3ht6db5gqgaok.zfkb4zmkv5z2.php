<?php if ($SESSION['success']): ?>
	<div class="alert myAlert-bottom alert-success alert-dismissible fade show" role="alert">
		<i class="fa fa-check" aria-hidden="true">&nbsp;&nbsp;&nbsp;</i> <?= ($SESSION['success'])."
" ?>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php endif; ?>
<?php if ($SESSION['error']): ?>
	<div class="alert myAlert-bottom alert-danger alert-dismissible fade show" role="alert">
		<i class="fa fa-times" aria-hidden="true">&nbsp;&nbsp;&nbsp;</i> <?= ($SESSION['error'])."
" ?>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php endif; ?>
<?php if ($SESSION['warning']): ?>
	<div class="alert myAlert-bottom alert-warning alert-dismissible fade show" role="alert">
		<i class="fa fa-warning" aria-hidden="true">&nbsp;&nbsp;&nbsp;</i> <?= ($SESSION['warning'])."
" ?>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php endif; ?>
<?php if ($SESSION['info']): ?>
	<div class="alert myAlert-bottom alert-info alert-dismissible fade show" role="alert">
		<i class="fa fa-info" aria-hidden="true">&nbsp;&nbsp;&nbsp;</i> <?= ($SESSION['info'])."
" ?>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
<?php endif; ?>