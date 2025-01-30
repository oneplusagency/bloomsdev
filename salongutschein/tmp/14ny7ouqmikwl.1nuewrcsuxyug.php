<div class="row gutscheinauswahl-third">
	<div class="col-md-8 offset-md-2 col-12 pt-3">
		<form class="form mt-0 w-100" role="form" autocomplete="on" id="persDataConfirm" name="persDataConfirm"
			action="" novalidate="" method="POST">
			<div class="text-md-left text-left mb-4">
				<h3 class="futuraItBk">Meine Daten</h3>
			</div>
			<!-- Main Details  -->
			<div class="row">
				<div class="col-12">
					<!-- Col Left  -->
					<div class="custom-row">
						<div class="custom-form-group custom-order-1 form-group tt-form-group">
							<select name="salutation" id="salutation" class="form-control rounded-0" required>
								<option value="" selected disabled>Anrede *</option>
								<?php foreach (($OPTION_ANREDE?:[]) as $KK=>$VAL): ?>
									<option value="<?= ($KK) ?>"><?= ($VAL) ?></option>
								<?php endforeach; ?>
							</select>
						</div>

						<div class="custom-form-group custom-order-4 form-group tt-form-group">
							<input type="text" placeholder="Straße und Hausnummer *" name="adresse" id="adresse"
								class="form-control" aria-describedby="gutscheineAdresseInput" required />
						</div>

						<div class="custom-form-group custom-order-2 form-group tt-form-group">
							<input type="text" placeholder="Vorname *" name="vorname" id="vorname" class="form-control"
								aria-describedby="gutscheineVornameInput" required />
						</div>

						<div class="custom-form-group custom-order-5 form-group tt-form-group">
							<input type="text" placeholder="PLZ*" autocomplete='postal code' name="plz" id="plz"
								class="form-control" required />
						</div>

						<div class="custom-form-group custom-order-3 form-group tt-form-group">
							<input type="text" placeholder="Nachname *" name="nachname" id="nachname"
								class="form-control" aria-describedby="gutscheineNachnameInput" required />
						</div>

						<div class="custom-form-group custom-order-6 form-group tt-form-group">
							<input type="text" placeholder="Ort *" name="ort" id="ort" class="form-control"
								aria-describedby="gutscheineOrtInput" required />
						</div>

						<div class="custom-form-group custom-order-8 form-group tt-form-group">
							<input type="email" placeholder="E-Mail *" name="email" id="email" class="form-control"
								aria-describedby="gutscheineEmailInput" required />
						</div>

						<div class="custom-form-group custom-order-9 form-group tt-form-group">
							<input type="email" name="emailConfirm" class="form-control" id="emailConfirm"
								aria-describedby="gutscheineEmail2Input" required="" placeholder="E-Mail Wiederholung *"
								aria-required="true">
						</div>

						<div class="custom-form-group custom-order-7 form-group tt-form-group">
							<input type="tel" placeholder="Handynummer *" name="phone" id="phone" class="form-control"
								aria-describedby="gutscheinePhoneInput" required />
						</div>
					</div>
					<!-- End Col Left  -->
				</div>
			</div>
			<!-- End Main Details  -->
			<!-- Different Details ? -->
			<!-- <div class="row mt-5 mb-2" id="diffAddressContainer">
				<div class="col-md-12 col-12">
					<p class="mb-0">Abweichende Versandadresse?</p>
					<div class="form-check pl-0">
						<div class="d-inline mr-4">
							<input type="radio" name="diffAdress" value="1" class="radio-btn diffAdress"
								id="diffAddress1" />
							<label class="form-check-label" for="diffAddress1">
								Ja
							</label>
						</div>
						<div class="d-inline">
							<input type="radio" name="diffAdress" value="0" class="radio-btn diffAdress"
								id="diffAddress2" aria-checked="checked" />
							<label class="form-check-label" for="diffAddress2">
								Nein
							</label>
						</div>
					</div>
				</div>
			</div> -->
			<!-- End Different Details ? -->
			<!-- Different Details -->
			<div class="row" id="diffdeliverpost">
				<div class="col-md-6 col-12">
					<!-- Col Left  
					<div class="form-group tt-form-group">
						<select name="diffsalutation" id="diffsalutation" class="form-control rounded-0" required>
							<option value="" selected disabled>Anrede *</option>
							<?php foreach (($OPTION_ANREDE?:[]) as $KK=>$VAL): ?>
								<option value="<?= ($KK) ?>"><?= ($VAL) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group tt-form-group">
						<input type="text" placeholder="Vorname *" name="diffvorname" id="diffvorname"
							class="form-control" required=required />
					</div>
					<div class="form-group tt-form-group">
						<input type="text" placeholder="Nachname *" name="diffnachname" id="diffnachname"
							class="form-control" aria-describedby="gutscheinediffNachnameInput" required />
					</div>
					<div class="form-group tt-form-group">
						<input type="tel" placeholder="Handynummer *" name="diffphone" id="diffphone"
							class="form-control" aria-describedby="gutscheineCPhoneInput" required />
					</div>
					 End Col Left  -->
				</div>
				<div class="col-md-6 col-12">
					<!-- Col Right  
					<div class="form-group tt-form-group">
						<input type="text" placeholder="Straße und Hausnummer *" name="diffadresse" id="diffadresse"
							class="form-control" aria-describedby="gutscheinediffAdresseInput" required />
					</div>

					<div class="form-group tt-form-group">
						<input type="text" placeholder="PLZ *" autocomplete='postal code' name="diffplz" id="diffplz"
							class="form-control" required />
					</div>
					<div class="form-group tt-form-group">
						<input type="text" placeholder="Ort *" name="diffort" id="diffort" class="form-control"
							aria-describedby="gutscheinediffOrtInput" required />
					</div>


					 End Col Right  -->
				</div>
			</div>
			<div class="row" id="diffdeliveremail">
				<div class="col-md-6 col-12">
					<!-- Col Left  
					<div class="form-group tt-form-group">
						<input type="email" placeholder="E-Mail *" name="diffEmail" id="diffEmail" class="form-control"
							aria-describedby="gutscheineCEmailInput" required />
					</div>

					End Col Left  -->
				</div>
				<div class="col-md-6 col-12">
					<!-- Col Right  
					<div class="form-group tt-form-group">
						<input type="email" name="diffEmailConfirm" class="form-control" id="diffEmailConfirm"
							aria-describedby="gutscheineCEmail2Input" required="" placeholder="E-Mail Wiederholung *"
							aria-required="true">
					</div>
					End Col Right  -->
				</div>

			</div>
		</form>
	</div>
</div>
<!-- end gutscheinauswahl-third -->
<div class="clr"></div>