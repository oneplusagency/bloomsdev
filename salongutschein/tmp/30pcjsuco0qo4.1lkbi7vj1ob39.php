<div class="row">
    <div class="col-sm-12">
        <div class="wel-bs-component">

            <form action="<?= ($BASE) ?>/admin/settings/config/edit" method="POST" class="form-horizontal w-100 was-validated" id="setting-form">

                <?php foreach (($ini?:[]) as $section=>$values): ?>

                    <input type="hidden" value="<?= ($section) ?>" name="<?= ($section) ?>">

                    <?php foreach (($values?:[]) as $key=>$value): ?>

                        <?php if ($key == 'site'): ?>
                            
                                <legend>Allgemein</legend>

                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label">Seiten-Titel</label>
                                    <div class="col-sm-9 col-xs-12">
                                        <input type="text" name="<?= ($section) ?>[<?= ($key) ?>]" value="<?= ($value) ?>" class="form-control" data-validation="strength"
                                            data-validation-strength="2" required>
                                    </div>
                                </div>
                            
                        <?php endif; ?>


                        <?php if ($key == 'carousel_interval'): ?>
                            
                                <div class="form-group tt-form-group row hide hidden">

                                    <label for="input" class="col-sm-3 col-form-label">Sliding-Dauer</label>
                                    <div class="col-sm-5 col-xs-12">
                                        <!-- disabled -->
                                        <input type="number" min="0" max="20000" step="200" value="2000" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control"
                                            data-validation="strength" data-validation-strength="2" required />
                                        <div class="form-text text-muted small">Intervall ist in Millisekunden. 1000 = 1 Sekunde (Wenn auf 0 gesetzt, deaktiviert)
                                        </div>
                                    </div>

                                </div>
                            
                        <?php endif; ?>


                        <?php if ($key == 'site_debug'): ?>
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label">Debug-Modus</label>

                                    <div class="col-sm-5 col-xs-12">
                                        <div class="input-group" id="debug-mode">

                                            <select name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control">
                                                <option value="0" <?= ($value == '0' ? 'selected' : '') ?>>Kein Debug
                                                </option>
                                                <option value="1" <?= ($value == '1' ? 'selected' : '') ?>>1</option>
                                                <option value="2" <?= ($value == '2' ? 'selected' : '') ?>>2</option>
                                                <option value="3" <?= ($value == '3' ? 'selected' : '') ?>>3</option>
                                            </select>
                                            <!-- <span class="input-group-addon"></span> -->
                                        </div>
                                    </div>

                                </div>
                            
                        <?php endif; ?>

                        <?php if ($key == 'site_cache'): ?>
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label">Cache benutzen</label>

                                    <div class="col-sm-9 col-xs-12">
                                        <label class="radio-inline">
                                            <input type="radio" name="<?= ($section) ?>[<?= ($key) ?>]" value="1" <?= ($value == 1 ? 'checked' : '') ?>> Aktiv
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="<?= ($section) ?>[<?= ($key) ?>]" value="0" <?= ($value == 0 ? 'checked' : '') ?>> Inaktiv
                                        </label>
                                    </div>

                                </div>
                            
                        <?php endif; ?>



                        <?php if ($key == 'adminPassword'): ?>
                            
                                <hr>
                                <legend>Sicherheit</legend>

                                <div class="form-group tt-form-group row">

                                    <label for="input" class="col-sm-3 col-form-label">Admin Passwort</label>
                                    <div class="col-sm-5 col-xs-12">
                                        <!-- disabled -->
                                        <input type="text" value="<?= ($value) ?>" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control" data-validation="strength"
                                            data-validation-strength="2" required />
                                        <h6 class="pull-right form-text text-muted small">sicher speichern
                                        </h6>
                                    </div>
                                </div>
                            
                        <?php endif; ?>

                        <?php if ($key == 'mail_from_name'): ?>
                            
                                <hr>
                                <legend>E-Mail</legend>
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label">Absender-Name</label>
                                    <div class="col-sm-9 col-xs-12">
                                        <input type="text" value="<?= ($value) ?>" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control" data-validation="strength"
                                            data-validation-strength="2" required />
                                    </div>
                                </div>
                            
                        <?php endif; ?>

                        <?php if ($key == 'mail_from_email'): ?>
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label">Admin E-Mail</label>
                                    <div class="col-sm-9 col-xs-12">
                                        <input type="email" value="<?= ($value) ?>" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control" data-validation="email"
                                            data-validation-strength="2" required />
                                    </div>
                                </div>
                            
                        <?php endif; ?>

                        <?php if ($key == 'noreply_emal'): ?>
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label">Noreply E-Mail</label>
                                    <div class="col-sm-9 col-xs-12">
                                        <input type="email" value="<?= ($value) ?>" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control" data-validation="email"
                                            data-validation-strength="2" required />
                                    </div>
                                </div>
                            
                        <?php endif; ?>

                        <!-- Paypal -->
                        <?php if ($key == 'paypal_user'): ?>
                            
                                <hr />
                                <div class="pull-right small"><a target="_blank" href="https://developer.paypal.com/developer/accounts"><span
                                            class="small form-text text-muted">developer.paypal.com</span></a></div>
                                <!-- https://www.w3schools.com/bootstrap4/bootstrap_typography.asp -->
                                <legend>Paypal API Anmeldeinformationen</legend>

                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label"><span class="badge badge-warning">Paypal API Benutzername</span></label>
                                    <div class="col-sm-9 col-xs-12">
                                        <input type="text" value="<?= ($value) ?>" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control" data-validation="strength"
                                            data-validation-strength="2" required />
                                    </div>
                                </div>
                            
                        <?php endif; ?>
                        <?php if ($key == 'paypal_pass'): ?>
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label"><span class="badge badge-warning">Paypal API Passwort</span></label>
                                    <div class="col-sm-9 col-xs-12">
                                        <input type="text" value="<?= ($value) ?>" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control" data-validation="strength"
                                            data-validation-strength="2" required />
                                    </div>
                                </div>
                            
                        <?php endif; ?>

                        <?php if ($key == 'paypal_signature'): ?>
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label"><span class="badge badge-warning">Paypal API Signatur</span></label>
                                    <div class="col-sm-9 col-xs-12">
                                        <input type="text" value="<?= ($value) ?>" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control" data-validation="strength"
                                            data-validation-strength="2" required />
                                    </div>
                                </div>
                            
                        <?php endif; ?>

                        <?php if ($key == 'paypal_endpoint'): ?>
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label">Umgebungsmodus</label>

                                    <div class="col-sm-9 col-xs-12">
                                        <label class="radio-inline">
                                            <input type="radio" name="<?= ($section) ?>[<?= ($key) ?>]" value="sandbox" <?= ($value == 'sandbox' ? 'checked' : '') ?>> Sandbox
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="<?= ($section) ?>[<?= ($key) ?>]" value="production" <?= ($value == 'production' ? 'checked' : '') ?>> Produktion

                                        </label>
                                        <span class="small form-text text-muted">Die PayPal-Sandbox ist
                                            eine eigenständige virtuelle Testumgebung, die die Live-PayPal-Produktionsumgebung
                                            nachahmt.</span>
                                    </div>

                                </div>
                            
                        <?php endif; ?>


                        <?php if ($key == 'paypal_apiver'): ?>
                            <!-- paypal_apiver -->
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label">Paypal Version</label>
                                    <div class="col-sm-9 col-xs-12">
                                        <input type="text" value="<?= ($value) ?>" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control" data-validation="strength"
                                            data-validation-strength="2" required />
                                    </div>
                                </div>
                            
                        <?php endif; ?>


                        <?php if ($key == 'paypal_return'): ?>
                            <!-- paypal_return -->
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label">Rückleitungs-URL</label>
                                    <div class="col-sm-9 col-xs-12">
                                        <input type="text" value="<?= ($value) ?>" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control" data-validation="strength"
                                            data-validation-strength="2" readonly />
                                    </div>
                                </div>
                            
                        <?php endif; ?>


                        <?php if ($key == 'paypal_cancel'): ?>
                            <!-- paypal_cancel -->
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label">Storno-URL url</label>
                                    <div class="col-sm-9 col-xs-12">
                                        <input type="text" value="<?= ($value) ?>" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control" data-validation="strength"
                                            data-validation-strength="2" />
                                    </div>
                                </div>
                            
                        <?php endif; ?>

                        <?php if ($key == 'paypal_log'): ?>
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label">Paypal Log</label>

                                    <div class="col-sm-9 col-xs-12">
                                        <label class="radio-inline">
                                            <input type="radio" name="<?= ($section) ?>[<?= ($key) ?>]" value="1" <?= ($value == '1' ? 'checked' : '') ?>> Aktiv
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="<?= ($section) ?>[<?= ($key) ?>]" value="0" <?= ($value == '0' ? 'checked' : '') ?>> Inaktiv
                                        </label>
                                    </div>

                                </div>
                            
                        <?php endif; ?>

                        <?php if ($key == 'paypal_emal'): ?>
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label"><kbd>Paypal E-Mail
                                            ID</kbd></label>

                                    <div class="col-sm-9 col-xs-12">
                                        <input type="email" value="<?= ($value) ?>" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control" data-validation="email" />
                                    </div>

                                </div>
                            
                        <?php endif; ?>

                        <?php if ($key == 'paypal_account_id'): ?>
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label"><kbd>Paypal Konto
                                            ID</kbd></label>

                                    <div class="col-sm-9 col-xs-12">
                                        <input type="text" value="<?= ($value) ?>" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control" data-validation="strength"
                                            data-validation-strength="2" />
                                    </div>

                                </div>
                            
                        <?php endif; ?>

                        <?php if ($key == 'paypal_client_id'): ?>
                            <!-- Client ID -->
                            <!-- Default Application App display name:Default Application  SANDBOX API CREDENTIALS -->
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label"><kbd>Kunden ID</kbd></label>

                                    <div class="col-sm-9 col-xs-12">
                                        <input type="text" value="<?= ($value) ?>" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control" data-validation="strength"
                                            data-validation-strength="2" />
                                    </div>

                                </div>
                            
                        <?php endif; ?>

                        <!-- Paypal Plus -->

                        <?php if ($key == 'paypal_plus_brand_name'): ?>
                            
                                <hr />
                                <div class="pull-right small"><a target="_blank" href="https://developer.paypal.com/developer/accounts"><span
                                            class="small form-text text-muted">developer.paypal.com</span></a></div>
                                <!-- https://www.w3schools.com/bootstrap4/bootstrap_typography.asp -->
                                <legend style="border-radius: 0.2rem; padding: 0.2rem 0.4rem;" class="text-danger badge-warning">Paypal Plus API Anmeldeinformationen</legend>
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label"><span class="badge badge-warning">Brand Name</span></label>
                                    <div class="col-sm-9 col-xs-12">
                                        <input type="text" value="<?= ($value) ?>" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control" data-validation="strength"
                                            data-validation-strength="2" required />
                                            <span class="small form-text text-muted">This will be displayed as your brand / company name on the PayPal checkout pages.</span>
                                    </div>
                                </div>
                            
                        <?php endif; ?>

                        <?php if ($key == 'paypal_plus_checkout_logo'): ?>
                            <!-- paypal_plus_checkout_logo -->
                            <!-- https://github.com/victorjonsson/jQuery-Form-Validator/blob/master/test/form.html -->
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label">PayPal Checkout Logo (190x60px)</label>
                                    <div class="col-sm-9 col-xs-12">
                                        <input placeholder="assets/images/blooms-logo.png" type="text" value="<?= ($value) ?>" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control" data-validation="extension required"
                                        data-validation-error-msg="You must write a file name with extension jpg|png"
                                        data-validation-allowing="jpg, png" />

                                        <span class="small form-text text-muted">Set the absolute patch for a logo (without site url) , to be displayed on the PayPal checkout pages. <br/> Use https and max 127 characters.</span>
                                    </div>
                                </div>
                            
                        <?php endif; ?>


                        <?php if ($key == 'paypal_plus_endpoint'): ?>
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label">Umgebungsmodus</label>

                                    <div class="col-sm-9 col-xs-12">
                                        <label class="radio-inline">
                                            <input type="radio" name="<?= ($section) ?>[<?= ($key) ?>]" value="sandbox" <?= ($value == 'sandbox' ? 'checked' : '') ?>> Sandbox
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="<?= ($section) ?>[<?= ($key) ?>]" value="production" <?= ($value == 'production' ? 'checked' : '') ?>> Produktion

                                        </label>
                                        <span class="small form-text text-muted">Die PayPal-Sandbox ist
                                            eine eigenständige virtuelle Testumgebung, die die Live-PayPal-Produktionsumgebung
                                            nachahmt.<p style="display: none;">Demo: <a target="_blank" href="https://www.sandbox.paypal.com/myaccount"><kbd>sandbox.paypal</kbd></a>
                                                <br><kbd>Email ID:kupi@webiprog.com</kbd>
                                                <br><kbd>Pass:oleg@webiprog.de</kbd></p></span>
                                    </div>

                                </div>
                            
                        <?php endif; ?>


                        <?php if ($key == 'paypal_plus_return'): ?>
                            <!-- paypal_plus_return -->
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label">Rückleitungs-URL</label>
                                    <div class="col-sm-9 col-xs-12">
                                        <input type="text" value="<?= ($value) ?>" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control" data-validation="strength"
                                            data-validation-strength="2" readonly />
                                    </div>
                                </div>
                            
                        <?php endif; ?>


                        <?php if ($key == 'paypal_plus_cancel'): ?>
                            <!-- paypal_plus_cancel -->
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label">Storno-URL url</label>
                                    <div class="col-sm-9 col-xs-12">
                                        <input type="text" value="<?= ($value) ?>" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control" data-validation="strength"
                                            data-validation-strength="2" />
                                    </div>
                                </div>
                            
                        <?php endif; ?>

                        <?php if ($key == 'paypal_plus_log'): ?>
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label">Paypal Plus Log</label>

                                    <div class="col-sm-9 col-xs-12">
                                        <label class="radio-inline">
                                            <input type="radio" name="<?= ($section) ?>[<?= ($key) ?>]" value="1" <?= ($value == '1' ? 'checked' : '') ?>> Aktiv
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="<?= ($section) ?>[<?= ($key) ?>]" value="0" <?= ($value == '0' ? 'checked' : '') ?>> Inaktiv
                                        </label>
                                    </div>

                                </div>
                            
                        <?php endif; ?>

                        <?php if ($key == 'paypal_plus_emal'): ?>
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label"><kbd>Paypal Plus E-Mail
                                            ID</kbd></label>

                                    <div class="col-sm-9 col-xs-12">
                                        <input type="email" value="<?= ($value) ?>" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control" data-validation="email" />
                                    </div>

                                </div>
                            
                        <?php endif; ?>

                        <?php if ($key == 'paypal_plus_client_id'): ?>
                            <!-- Client ID -->
                            <!-- Default Application App display name:Default Application  SANDBOX API CREDENTIALS -->
                            
                                <legend style="border-radius: 0.2rem; padding: 0.2rem 0.4rem;" class="text-danger badge-warning">Paypal Plus Daten für API</legend>
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label"><kbd class="badge-danger">Kunden ID</kbd></label>


                                    <div class="col-sm-9 col-xs-12">
                                        <input type="text" value="<?= ($value) ?>" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control custom-select-sm input-sm" data-validation="strength"
                                            data-validation-strength="2" />
                                        <span class="small form-text text-danger text-warning text-info">Client id obtained from the developer portal</span>
                                    </div>

                                </div>
                            
                        <?php endif; ?>

                        <?php if ($key == 'paypal_plus_account_id'): ?>
                            
                                <div class="form-group tt-form-group row">
                                    <label for="input" class="col-sm-3 col-form-label"><kbd class="badge-danger">Geheimer Clientschlüssel</kbd></label>

                                    <div class="col-sm-9 col-xs-12">
                                        <input type="text" value="<?= ($value) ?>" name="<?= ($section) ?>[<?= ($key) ?>]" class="form-control custom-select-sm input-sm" data-validation="strength"
                                            data-validation-strength="2" />
                                        <span class="small form-text text-danger text-warning text-info">Client secret obtained from the developer portal</span>
                                    </div>

                                </div>
                            
                        <?php endif; ?>


                    <?php endforeach; ?>
                    <!-- <br /> -->
                <?php endforeach; ?>
                <!-- btn-block btn-sm -->
                <div class="card-footer-text-muted">
                    <input type="submit" class="btn btn-dark btn-next mt-3 position-relative" value="Änderungen speichern" />
                </div>

            </form>
        </div>
    </div>
</div>

<style type="text/css">
    .form-group .badge {
        font-size: 100%;
    }
</style>