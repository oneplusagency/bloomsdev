<?php if ($POMILKA): ?>
	
	<?php echo $this->render('layout/header_err.html',NULL,get_defined_vars(),0); ?>
	
<?php endif; ?>
	<div class="container_err">
		<div class="row">
			<div class="col-12 text-center align-self-center">
				<div class="row">
					<div class="col-12 text-center">
						<h1 class="error-page-code"><?= ($ERROR['code']) ?></h1>
						<h2 class="error-page-message"><?= ($ERROR['text']) ?></h2>
					</div>
				</div> <a class="btn btn-backtohome" href="<?= ($BASE) ?>" role="button">Zurück auf die Startseite</a>
			</div>
		</div>
		<div>
		</div>
	</div>

</div>
<script src="<?= ($ASSETS) ?>js/jquery.min.js"></script>
<script type="text/javascript">
    (function($) {
        $(document.body).addClass('err_kaz');
    })(jQuery);

</script>
</body>