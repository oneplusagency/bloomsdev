<form method="post" id="termineAjaxformWizard" class="form-panel-wizard w-100" role="form">
	<div class="d-md-none text-md-left text-left mb-4">
		<h3>Terminauswahl </h3>
	</div>
	<div class="row min-height">

		<div class="col-md-6 col-12">
			<div class="row">
				<div class="col-md-4 col-12">1. Salon</div>
				<div class="col-md-8 col-12">
					<?php if ($OPTION_SALON): ?>
						
							<select id="option_salon" name="option_salon" class="form-control rounded-0">
								<?php foreach (($OPTION_SALON?:[]) as $data): ?>
									<?= ($data)."
" ?>
								<?php endforeach; ?>
							</select>
						
						<?php else: ?>
							<div class="alert">
								You currently have no items in our preise.
							</div>
						
					<?php endif; ?>
				</div>
			</div>
			<div class="row" style="margin-top: 20px;">
				<div class="col-md-4 col-12">2. Mitarbeiter</div>
				<div class="col-md-8 col-12" id="mitarbeiterSelectField">
					<!-- chooseMitarbeiter disabled -->
					<select name="mitarbeiter" id="salonMitarbeiterField" class="form-control rounded-0" required
						aria-required="true" autocomplete="off">
						<option value="" disabled>Bitte zuerst Salon auswählen</option>
					</select>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-12">
			<div class="row exta-spec">
				<div class="col-md-4 col-12">3. Datum</div>
				<div class="col-md-8 col-12">
					<div class="input-group-100 date">
						<input type="text" id="datepicker" name="date" readonly="readonly"
							class="date_picker form-control rounded-0" placeholder="Bitte Datum auswählen" required
							value="" />
						<input name="iso_date" id="thealtdate" type="hidden" />
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4 col-12">4. Dienstleistung</div>
				<div class="col-md-8 col-12">
					<div id="servicePackageContainer">
						<select name="servicePackage" id="servicePackageField" class="form-control" required
							aria-required="true" autocomplete="off">
							<option value="Bitte Dienstleistung auswählen" selected disabled>Bitte Dienstleistung
								auswählen</option>
						</select>
					</div>
					<div id="infoContainer"> <i class="fa fa-info-circle dll" id="DienstleistungInfo" data-toggle="tooltip" data-placement="top" title=""></i></div>
				</div>

			</div>
		</div>

	</div>
</form>