<div class="row">
    <div class="col-md-12 gutscheine-content">
        <!-- gutscheine-content" -->
        <?php echo $this->render('layout/blooms-logo.html',NULL,get_defined_vars(),0); ?>
        <!-- nav -->
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
        <!-- END nav -->

        <!-- progressWizard -->
        <!--     <div id="progressWizard" class="progress progress-info progress-striped mt-5">
            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
        </div> -->

        <!-- page-gutscheine ID -->
        <article id="page-gutscheine" class="panel-wizard mt-5 overflow-hidden">

            <section class="layout-gutscheine container p-0 overflow-hidde-n">

                <ul class="nav nav-pills nav-justified nav-wizard nav-disabled-click" id="pills-tab" role="tablist">

                    <li class="nav-item <?= ($ACTIVE_TAB  && strpos($ACTIVE_TAB , 'pills-gutscheinauswhal' )!==false ? 'active' : '') ?>">
                        <a class="nav-link <?= ($ACTIVE_TAB  && strpos($ACTIVE_TAB , 'pills-gutscheinauswhal' )!==false ? 'active' : '') ?>" id="pills-gutscheinauswhal-tab"
                            data-toggle="tab" href="#pills-gutscheinauswhal" role="tab" aria-controls="pills-home" aria-selected="true"><span class="tab-number">1</span><span
                                class="tab-title">. Gutscheinauswahl</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-gutscheindesign-tab" data-toggle="tab" href="#pills-gutscheindesign" role="tab" aria-controls="pills-profile"
                            aria-selected="false"><span class="tab-number">2</span><span class="tab-title">. Gutscheindesign</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-personaliche-daten-tab" data-toggle="tab" href="#pills-personaliche-daten" role="tab" aria-controls="pills-abgeschlossen"
                            aria-selected="false"><span class="tab-number">3</span><span class="tab-title">. Daten</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-zusammenfassung-tab" data-toggle="tab" href="#pills-zusammenfassung" role="tab" aria-controls="pills-abgeschlossen"
                            aria-selected="false"><span class="tab-number">4</span><span class="tab-title">. Zusammenfassung</span></a>
                    </li>

                    <?php if (false): ?>
                        <!-- !! disable Credit Card -->
                        <!-- <li class="nav-item">
                        <a class="nav-link" id="pills-bezahlung-tab" data-toggle="tab" href="#pills-bezahlung" role="tab" aria-controls="pills-abgeschlossen" aria-selected="false"><span class="tab-number">5</span><span class="tab-title">. Bezahlung</span></a>
                    </li> -->
                    <?php endif; ?>

                    <li class="nav-item <?= ($ACTIVE_TAB  && strpos($ACTIVE_TAB , 'pills-abgeschlossen' )!==false ? 'active' : '') ?>">
                        <a class="nav-link <?= ($ACTIVE_TAB  && strpos($ACTIVE_TAB , 'pills-abgeschlossen' )!==false ? 'active' : '') ?>" id="pills-abgeschlossen-tab" data-toggle="tab"
                            href="#pills-abgeschlossen" role="tab" aria-controls="pills-abgeschlossen" aria-selected="false"><span class="tab-number">5</span><span
                                class="tab-title">. Fertig</span></a>
                    </li>
                </ul>
                <!-- all tab-content -->
                <div class="tab-content px-3 cstm-pd" id="pills-gutscheine">

                    <!-- 1. Gutscheinauswahl -->
                    <div class="p-md-4 py-3 tab-pane fade <?= ($ACTIVE_TAB  && strpos($ACTIVE_TAB , 'pills-gutscheinauswhal' )!==false ? 'show active' : '') ?>"
                        id="pills-gutscheinauswhal" role="tabpanel" aria-labelledby="pills-home-tab">
                        <!-- begin gutscheine-tab-1.html -->
                        <?php echo $this->render('gutscheine/gutscheine-tab-1.html',NULL,get_defined_vars(),0); ?>

                        <!-- end gutscheine-data -->
                        <div class="row mt-3 gutscheine-skache">
                            <div class="col-12">
                                <div class="d-flex justify-content-end text-center mt-0">
                                    <!-- button-first -->
                                    <button id="gutscheinauswhal-button" class="btn btn-hotel btn-next mt-3 position-relative">Weiter</button>
                                </div>
                            </div>
                            <div class="clr clearfix"></div>
                        </div>
                        <!-- <div class="clr clearfix"></div> -->
                    </div>

                    <!-- 2. Gutscheindesign -->
                    <div class="tab-pane fade p-md-4 py-3" id="pills-gutscheindesign" role="tabpanel" aria-labelledby="pills-gutscheindesign-tab">
                        <!-- begin gutscheine-data gutscheine-tab-2.html -->
                        <?php echo $this->render('gutscheine/gutscheine-tab-2.html',NULL,get_defined_vars(),0); ?>

                        <!-- end gutscheine-data -->
                        <div class="row mt-3 gutscheine-skache">
                            <div class="col-12">
                                <div class="d-flex justify-content-between text-center mt-0">
                                    <button class="btn btn-hotel btn-previous mt-3 mr-md-4 position-relative">Zurück
                                    </button>
                                    <button id="gutscheindesign-button" class="btn btn-hotel btn-next mt-3 position-relative">Weiter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END 2 TWO TAB -->

                    <!-- BEGIN 3 Three TAB -->
                    <!-- 3. Persönliche Daten -->
                    <div class="tab-pane fade p-md-4 py-3" id="pills-personaliche-daten" role="tabpanel" aria-labelledby="pills-personaliche-daten-tab">
                        <!-- begin gutscheine-data gutscheine-tab-3.html -->
                        <?php echo $this->render('gutscheine/gutscheine-tab-3.html',NULL,get_defined_vars(),0); ?>
                        <!-- end gutscheine-data -->
                        <div class="row mt-3 gutscheine-skache">
                            <div class="col-12">
                                <div class="d-flex justify-content-between text-center mt-0">
                                    <button class="btn btn-hotel btn-previous mt-3 mr-md-4 position-relative">Zurück
                                    </button>
                                    <button id="personaliche-daten-button" class="btn btn-hotel btn-next mt-3  position-relative">Weiter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END 3 Three TAB -->

                    <!-- BEGIN 4 four TAB -->
                    <!-- 4. Zusammenfassung -->
                    <div class="tab-pane fade p-md-4 py-3" id="pills-zusammenfassung" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <!-- begin gutscheine-data gutscheine-tab-4.html -->
                        <?php echo $this->render('gutscheine/gutscheine-tab-4.html',NULL,get_defined_vars(),0); ?>




                    </div>
                    <!-- END 4 four TAB -->


                    <?php if (false): ?>
                        <!-- !! disable Credit Card -->
                        <!-- BEGIN 5 five TAB -->
                        <!-- 5. Bezahlung -->
                        <div class="tab-pane fade p-md-4 py-3" id="pills-bezahlung" role="tabpanel" aria-labelledby="pills-personaliche-daten-tab">
                            <!-- begin gutscheine-data gutscheine-tab-5.html -->
                            <?php echo $this->render('gutscheine/gutscheine-tab-5.html',NULL,get_defined_vars(),0); ?>
                            <!-- end gutscheine-data -->
                            <div class="row mt-3 gutscheine-skache">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between text-center mt-0">
                                        <button class="btn btn-hotel btn-previous mt-3 mr-md-4 position-relative">Zurück
                                        </button>
                                        <a href="<?= ($BASE) ?>/gutscheine/sale" id="submitDataPaypal" data-original-text="Gutschein kaufen" data-loading-text="Bitte warten ..."
                                            class="btn-paypal btn btn-hotel btn-next-brex mt-3 position-relative"> <i style="color:#139AD6"
                                                class="fa fa-paypal text-navy mid-icon"></i> Gutschein kaufen</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END 5 five TAB -->
                    <?php endif; ?>

                    <!-- 6. pills-abgeschlossen -->
                    <div class="tab-pane fade p-md-4 py-3 <?= ($ACTIVE_TAB  && strpos($ACTIVE_TAB , 'pills-abgeschlossen' )!==false ? 'show active' : '') ?>" id="pills-abgeschlossen"
                        role="tabpanel" aria-labelledby="pills-personaliche-daten-tab">
                        <!-- begin gutscheine-data gutscheine-tab-5.html -->
                        <?php echo $this->render('gutscheine/gutscheine-tab-6.html',NULL,get_defined_vars(),0); ?>
                        <!-- end gutscheine-data -->
                        <div class="row mt-3 gutscheine-skache">
                            <div class="col-12">
                                <div class="text-md-right mt-0">
                                    <button data-original-text="Beenden" data-loading-text="Laden..." type="button" onclick="location.href='gutscheine.html'"
                                        class="btn  btn-hotel btn-submit position-relative mt-3">Beenden</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END 5 five TAB -->

                </div>
                <div style="margin-top: -30px;" class="d-flex justify-content-center align-items-center position-relative w-100">
                    <div id="loader_pplus_layer"></div>
                    <div id="loader_pplus"></div>
                    <div id="ppplus" style="display: none;" class="col-md-8">

                        <script type="text/javascript">
                            // document.getElementById("loader_pplus").style.display = "block";
                            // document.querySelector('iframe').addEventListener('load', function () {
                            //     document.getElementById("loader_pplus").style.display = "none";
                            // });

                            document.write("iframe wird geladen...");
                        </script>
                        <noscript>
                            <!-- in case the shop works without javascript and the user has really disabled it and gets to the merchant's checkout page -->
                            <iframe src="https://www.paypalobjects.com/webstatic/ppplusbr/ppplusbr.min.js/public/pages/pt_BR/nojserror.html"
                                style="height: 400px; border: none;"></iframe>
                        </noscript>
                    </div>
                </div>
                <!-- end gutscheine-data -->

                <!-- end all tab-content -->
            </section>
        </article>
        <div class="row mt-3 gutscheine-skache" id="paylalOldOption" style="display: none; margin-top:-10%; padding-top:-10%">
            <div class="col-12" style="margin-top: -11%;">

                <div class="d-flex justify-content-between text-center mt-0">
                    <button class="btn btn-hotel  btn-previous mt-3 mr-md-4 position-relative">Zurück</button>

                    <button style="display: none;" id="submitDataPaypalOLD" data-original-text="Gutschein kaufen" data-loading-text="Bitte warten ..."
                        class="btn btn-hotel  btn-next-brex mt-3 position-relative"><i style="color:#139AD6" class="fa fa-paypal text-navy mid-icon"></i> Gutschein kaufen</button>

                    <a style="display: none;" href="<?= ($BASE) ?>/gutscheine/sale" id="submitPostPaypal" class="btn-paypal btn btn-hotel btn-next-brex mt-3 position-relative"> <i
                            class="fa fa-paypal text-navy mid-icon"></i> Gutschein kaufen</a>
                </div>
            </div>
        </div>
        <!-- END page-gutscheine -->
        <!-- <div class="menu-overlay"></div> -->
    </div>
</div>
<div id="ScriptDiv"></div>
<!-- END ROW -->

<style type="text/css">
    #loader_pplus_layer {
        background: rgba(0, 0, 0, 0.5);
        width: 100%;
        height: 100%;
        position: absolute;
        padding: 0;
        margin: 0;
        top: 0;
        left: 0;
        z-index: 100;
        display: none;
    }

    /* Center the loader_pplus */
    #loader_pplus {
        position: absolute;
        left: 50%;
        top: 50%;
        z-index: 1;
        margin: -75px 0 0 -75px;
        border: 16px solid #f3f3f3;
        border-radius: 60% !important;
        border-top: 16px solid #3498db;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
        display: none;
        -webkit-border-radius: 60px !important;
        -moz-border-radius: 60px !important;
    }

    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>