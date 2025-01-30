<div class="row">
    <div class="col-md-12">
        <?php echo $this->render('layout/blooms-logo.html',NULL,get_defined_vars(),0); ?>
        <nav class="page-nav-others text-vanukin navbar navbar-dark navbar-expand-md mt-5 py-0">
            <div class="container p-md-0 w-100">
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#myTogglerNav" aria-controls="#myTogglerNav" aria-label="Toggle Navigation"> <span class="navbar-toggler-icon"></span>
                </button>
         
                <section class="collapse navbar-collapse pr-3 order-md-1 order-2" id="myTogglerNav">
                    <div class="navbar-nav ml-auto w-100 justify-content-around px-5">
                        <button type="button" class="btn-close-mobile-nav d-md-none" data-toggle="collapse" data-target="#myTogglerNav" aria-controls="#myTogglerNav" aria-label="Toggle Navigation">
                            X
                        </button>
                        <?php echo $this->render('layout/menu_all.html',NULL,get_defined_vars(),0); ?>
                    </div>
                </section>
            </div>
        </nav>
        <!-- add banner  @FIX by oppo * @Date: 27.07.2020 18:11 -->
        <!-- <div class="row ">
            <div class="col-md-6 col-12">
                <a href="fachpersonal.html" class="text-dark">
                    <img class="img-fluid" src="/bloomsnew/assets/images/bewerbung/Bluehen.jpg">
                </a>
            </div>
            <div class="col-md-6 col-12 ">
                <a href="azubi.html" class="text-dark">
                    <img src="/bloomsnew/assets/images/bewerbung/Bluehen.jpg" class="img-fluid">
                </a>
            </div>
        </div> -->

        <article id="page-blooms" class="container text-center bloomsSection">
            <section id="" class="layout-blooms-info-data ">
                <section id="" class="layout-blooms-facts-data ">
                    <center><h2 class="h1-responsive font-weight-bold text-center my-4 pt-md-5 mt-2">Bitte auswählen:</h2></center>
                    <div id="" class="bloomsSelectionList">
                        <a href="azubi.html" class="text-dark bloomsBlock">
                            <div class="row align-items-center data-from-api-1 item">
                                <div class="col-md-3 col-sm-3 col-12">
                                    <div class="icon">
                                        <img src="./assets/images/bewerbung/Ausbildungsstelle1.png" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-9 col-sm-9 col-12">
                                    <div class="detail">
                                        <span>Ich interessiere mich für eine</span>
                                        <h3 class="p-2">Ausbildungsstelle</h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="fachpersonal.html" class="text-dark bloomsBlock">
                            <div class="row align-items-center data-from-api-1 item">
                                <div class="col-md-3 col-sm-3 col-12">
                                    <div class="icon">
                                        <img src="./assets/images/bewerbung/Fachkraft1.png" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-9 col-sm-9 col-12">
                                    <div class="detail">
                                        <span>Ich bin gelernte/r</span>
                                        <h3 class="p-2">Friseur/in</h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="fachpersonal.html" class="text-dark bloomsBlock">
                            <div class="row align-items-center data-from-api-1 item">
                                <div class="col-md-3 col-sm-3 col-12">
                                    <div class="icon">
                                        <img src="./assets/images/bewerbung/Meister1.png" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-md-9 col-sm-9 col-12">
                                    <div class="detail">
                                        <span>Ich bin</span>
                                        <h3 class="p-2">Friseurmeister/in</h3>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div> 
                </section>
            </section>
        </article>
    </div>
</div>
<!-- END ROW -->
<!--modal phone-->
<div class="modal fade modal-stylist-modal" id="employeeModal" tabindex="-1" role="dialog" aria-labelledby="stylistModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center text-dark">
                <h3 class="mt-5 ml-5 text-left">01776596128</h3>
                <h3 class="mt-5 ml-5 text-left">Sie erreichen uns zwischen 9 und 18 Uhr.</h3>
                <h3 class="mt-5 ml-5 text-left">Vielen Dank.</h3>
                <h3 class="mt-5 ml-5 mb-5 text-left">Rückrufbitte via SMS</h3>
            </div>
        </div>
    </div>
</div>
<!--modal mail-->
<div class="modal fade modal-stylist-modal" id="employeeModal2" tabindex="-1" role="dialog" aria-labelledby="stylistModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center text-dark modal-biluy">
                <div class="w-100">
                    <div class="row justify-content-center">
                        <!-- col-md-6 col-12 order-md-0 order-1 -->
                        <div class="col-12 col-md-12 pb-0">
                            <!--Form with header-->
                            <form role="form" method="post" id="bloom_kontakt_form" class="w-100 has-validator has-recaptcha-v3">
                                <div class="card border-light rounded-0">
                                    <div class="card-header p-0">
                                        <div class="bg-dark text-white text-center py-2">
                                            <h3><i class="fa fa-envelope"></i> Kontakt</h3>
                                            <p class="m-0">Einfach Mail schreiben oder anrufen <a title="Rufen Sie +496215680444 an" class="free-addr addr text-white" href="tel:+496215680444"><span class="text-white">0621 5680444</span></a>.</p>
                                        </div>
                                    </div>
                                    <!-- ajax message -->
                                    <div class="mt-2" id="bloom_kontakt_status"></div>
                                    <div class="card-body p-3">
                                        <!--Body-->
                                        <div class="form-group">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-user text-secondary"></i>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Name und Nachname" required="required" pattern=".{2,}" maxlenght="50" aria-required="true" aria-invalid="false" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-envelope text-secondary"></i>
                                                    </div>
                                                </div>
                                                <input type="email" name="email" id="jform_email" placeholder="E-Mail" class="form-control input-lg required" required="required" maxlenght="50" autocomplete="on" aria-required="true" aria-invalid="false" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-map-marker text-secondary"></i>
                                                    </div>
                                                </div>
                                                <input type="text" name="strasse" id="strasse" maxlenght="50" class="form-control" required placeholder="Straße" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-map-marker text-secondary"></i>
                                                    </div>
                                                </div>
                                                <input type="text" name="city" id="city" maxlenght="50" class="form-control" required placeholder="Stadt" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-phone text-secondary"></i>
                                                    </div>
                                                </div>
                                                <input type="tel" name="phone" id="phone" maxlenght="20" class="form-control" required placeholder="Telefon" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-tag prefix text-secondary"></i>
                                                    </div>
                                                </div>
                                                <input type="text" id="professional" name="professional" maxlenght="50" class="form-control" required placeholder="Fachkraft vs. Azubi" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-comment text-secondary"></i>
                                                    </div>
                                                </div>
                                                <textarea maxlenght="500" id="bloom_kontakt_message" name="message" class="form-control" placeholder="Bemerkung:" required></textarea>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" data-original-text="Senden" data-loading-text="Laden..." id="bloom_kontakt_submit" class="rounded-0 py-2 btn btn-dark btn-submit position-relative">Senden</button>
                                        </div>
                                        <div class="row gutscheinauswahl-form-agb">
                                            <div class="col-12">
                                                <div class="mt-3">
                                                    <p class="mb-0 mt-md-0 mt-4">Bitte nehmen Sie unsere <a href="<?= ($BASE) ?>/datenschutz.html">Datenschutzerklärung</a> zur Kenntnis.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!--Form with header-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--modal facebook-->
<div class="modal fade modal-stylist-modal" id="employeeModal3" tabindex="-1" role="dialog" aria-labelledby="stylistModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center text-dark">
                <h3 class="mt-5 ml-5 text-left">Facebook</h3>
            </div>
        </div>
    </div>
</div>
<!--modal chat -->
<div class="modal fade modal-stylist-modal" id="employeeModal4" tabindex="-1" role="dialog" aria-labelledby="stylistModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center text-dark">
                <h3 class="mt-5 ml-5 text-left">Chatfenster</h3>
            </div>
        </div>
    </div>
</div>