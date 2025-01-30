<div class="row">
    <div class="col-md-12">
        <?php echo $this->render('layout/blooms-logo.html',NULL,get_defined_vars(),0); ?>

        <nav class="page-nav-others text-vanukin navbar navbar-dark navbar-expand-md mt-5 py-0">
            <div class="container p-md-0 w-100">
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#myTogglerNav" aria-controls="#myTogglerNav" aria-label="Toggle Navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <section class="collapse navbar-collapse pr-3 order-md-1 order-2" id="myTogglerNav">
                    <div class="navbar-nav ml-auto w-100 justify-content-around px-5">
                        <button type="button" class="btn-close-mobile-nav d-md-none" data-toggle="collapse" data-target="#myTogglerNav" aria-controls="#myTogglerNav"
                            aria-label="Toggle Navigation">
                            X
                        </button>
                        <?php echo $this->render('layout/menu_all.html',NULL,get_defined_vars(),0); ?>
                    </div>
                </section>

            </div>
        </nav>

        <!-- Section: Contact v.2 -->
        <section id="kontakt-wraper" class="mb-4">

            <!-- Section heading-->
            <h2 class="h1-responsive font-weight-bold text-left my-4 pt-md-5 mt-2">Kontaktformular</h2>
            <!--Section description-->
            <!-- <p class="text-center w-responsive mx-auto mb-5">Einfach Mail schreiben oder anrufen <a title="Rufen Sie +496215680444 an" class="free-addr addr" href="tel:+496215680444">0621 5680444</a></p>-->

            <!-- ajax message -->
            <div class="mt-2" id="bloom_kontakt_status"></div>

            <div class="row">

                <!-- Grid left column -->
                <div class="col-md-5 text-center text-left">
                    <ul class="list-unstyled mb-0 text-left">
                        <li>
                            <p class="z-addr"><i class="fa fas fa-map-marker fa-1.5x"></i> bloom´s Zentrale<br /> N7, 8
                                Kunststraße<br />68161 Mannheim</p>
                        </li>

                        <li>
                            <p class="z-addr"><i class="addr fa fas fa-phone mt-4 fa-1.5x"></i> <a title="Rufen Sie +496215680444 an" class="free-addr addr"
                                    href="tel:+496215680444">0621 5680444</a></p>
                        </li>

                        <li>
                            <p class="z-addr addr">
                                Für Friseurtermine bitte im jeweiligen <a href="<?= ($BASE) ?>/salons.html">Salon anrufen</a>
                                oder nutzen Sie unsere <a href="<?= ($BASE) ?>/termine.html">Online-Terminvereinbarung</a>
                            </p>
                        </li>
                    </ul>
                </div>
                <!-- end Grid left column -->

                <!-- Grid right column -->
                <div class="col-md-7 mb-md-0 mb-5 text-left">

                    <form method="post" name="kontaktformularForm" id="kontaktformularForm" class=" hlo w-100 has-validator has-recaptcha-v3" enctype="multipart/form-data">

                        <!-- Grid row -->
                        <div class="row">

                            <!-- Grid column -->
                            <div class="col-md-12">
                                <div class="md-form mb-0">
                                    <!--  <label for="fullname" class="">Name *</label> -->
                                    <input type="text" class="form-control rounded-0" id="fullname" name="name" placeholder="Name *" required="required" pattern=".{2,}"
                                        maxlenght="50" aria-required="true" aria-invalid="false" />

                                </div>
                            </div>
                            <!-- Grid column -->

                            <!-- Grid column -->
                            <div class="col-md-12">
                                <div class="md-form mb-0">
                                    <!-- <label for="jform_email" class="">E-Mail *</label> -->
                                    <input type="email" name="email" id="jform_email" placeholder="E-Mail *" class="form-control rounded-0 input-lg required" required="required"
                                        maxlenght="50" autocomplete="on" aria-required="true" aria-invalid="false" />
                                </div>
                            </div>
                            <!-- Grid column -->

                        </div>
                        <!-- Grid row -->

                        <!-- Grid row -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="md-form mb-0">
                                    <!-- <label for="phone" class="f-telefon"></label> -->
                                    <input type="tel" name="phone" id="phone" maxlenght="20" class="form-control rounded-0" required placeholder="Telefon *" />
                                </div>
                            </div>
                        </div>
                        <!-- Grid row -->

                        <!-- Grid row -->
                        <div class="row">

                            <!-- Grid column -->
                            <div class="col-md-12">
                                <div class="md-form">
                                    <!-- <label for="bloom_kontakt_message">Nachricht *</label> -->
                                    <textarea maxlenght="500" id="bloom_kontakt_message" name="message" rows="4" class="form-control rounded-0 md-textarea" placeholder="Nachricht*"
                                        required></textarea>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <!-- Grid column -->
                            <div class="col-md-12 text-right text-md-left mb-3">

                                <div class="text-right text-md-left mt-3">
                                    <!-- Bitte wählen Sie mehrere Dateien, indem Sie SCHIFT/CTRL auf Ihrer Tastatur betätigen. -->
                                    <p class="d-none alert alert-warning fix_alert">* Nur PDF und JPG zulässig</p>
                                    <div class="upload-btn">
                                        <!-- {count}  -->
                                        <!------ new file typw ------>
                                        <div class="entry input-group upload-input-group">
                                            <input class="alexzol" name="file[]" type="file" accept="image/jpeg,image/jpg,image/png,application/pdf"
                                                data-multiple-caption="Dateien hochladen (JPG oder PDF)" />
                                            <label class="form-control">
                                                <span data-add-error="bitte wählen Sie die Datei aus" data-span-caption="Dateien hochladen (JPG oder PDF)" class="text-nowrap mwpl-upload-btn">Dateien hochladen (JPG oder PDF)</span>
                                            </label>
                                            <button title="neue Datei hinzufügen" class="btn btn-upload btn-success btn-add" type="button">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                        <!----end  new file typw ---->
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-7 col-lg-7  col-sm-12 mdl-div-sec p2-2">
                                <div class="text-left text-md-left mt-3 w-100" id="kontact_kontakt_status">&nbsp;</div>
                            </div>

                            <div class="offset-md-1 offset-lg-1 col-md-4 text-right text-md-right">
                                <div class="text-right text-md-right mt-3">
                                    <button type="submit" data-original-text="Senden" data-loading-text="Laden..." id="bloom_kontakt_submit"
                                        class="rounded-0 py-2 btn position-relative btn-hotel btn-submit position-relative">Senden</button>
                                </div>
                            </div>
                        </div>

                    </form>

                    <div id="status"></div>
                </div>
                <!-- end rid right column -->

            </div>

            <!-- end row -->
        </section>
        <!-- Section: Contact v.2 -->
    </div>
    <!-- end col-md-12 -->
</div>
<!-- end row -->

<style type="text/css">
    .kontaktformular-nenka section.layout-impressum-datenschutz {
        position: inherit !important;
        bottom: 20px;
    }
    .d-none {
        display: none !important;
    }
    .fix_alert {
        padding: 0.35rem 0.45rem;
        margin-bottom: 0.5rem;
        border-radius: 0;
    }
    .btn-upload {
        height: 40px;
        width: 40px;
        margin-left: 6px;
        margin-top: 0;
        border-radius: 0;
        /* font-family: 'FuturaBT-ExtraBlack' !important; */
        outline: none !important;
    }
    .input-group>.form-control:not(:last-child) {
        height: 40px;
    }
</style>