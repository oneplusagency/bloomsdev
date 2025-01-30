<div id="termine-section" class="row">

    <div class="col-md-12">
    
        <!-- progressWizard -->
      <!--   <div id="progressWizard" class="progress progress-info progress-striped mt-5">
            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
        </div> -->
        <!-- page-termine -->
        <article id="page-termine" class="panel-wizard pt-4">
            <section id="page-termine-section" class="layout-termine-form container p-0 mt-3 overflow-hidden">

                <ul class="nav nav-pills nav-justified text-danger nav-wizard nav-disabled-click" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="termine-tab" data-toggle="tab" href="#pills-termine" role="tab" aria-controls="pills-termine" aria-selected="true"><span class="tab-number">1</span><span class="tab-title">. Terminauswahl</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="mein-termin-tab" data-toggle="tab" href="#pills-mein-termin" role="tab" aria-controls="pills-profile" aria-selected="false"><span class="tab-number">2</span><span class="tab-title">. Mein Termin</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="personliche-daten-tab" data-toggle="tab" href="#pills-personaliche-daten" role="tab" aria-controls="pills-contact" aria-selected="false">
                            <span class="tab-number">3</span><span class="tab-title">. Meine Daten</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="terminbuchung-tab" data-toggle="tab" href="#pills-terminbuchung" role="tab" aria-controls="pills-contact" aria-selected="false"><span class="tab-number">4</span><span class="tab-title">. Zusammenfassung</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="fertig-tab" data-toggle="tab" href="#pills-fertig" role="tab" aria-controls="pills-contact" aria-selected="false"><span class="tab-number">5</span><span class="tab-title">. Fertig</span></a>
                    </li>
                </ul>
                <!-- tab-content -->
                <div class="tab-content px-3" id="pills-tabContent">

                    <!-- BEGIN FIRST TAB :: . Terminauswahl -->
                    <div class="tab-pane fade show active p-md-4 py-3 pl-pr-0" id="pills-termine" role="tabpanel" aria-labelledby="pills-home-tab">
                        <!-- termineformWizard -->
                        <?php echo $this->render('akademie-buchung/akademie-buchung-tab-1.html',NULL,get_defined_vars(),0); ?>

                        <div class="d-none hide row mt-3 termine-skache">
                            <div class="col-12">
                                <div class="d-flex justify-content-end text-center mt-0">
                                    <!-- button-first -->
                                    <button id="termine-button-1" class="btn  btn-hotel btn-next mt-3 position-relative">Weiter</button>
                                </div>
                            </div>
                            <div class="clr clearfix"></div>
                        </div>


                    </div>
                    <!-- END 1 FIRST TAB -->

                    <!-- BEGIN 2 TWO TAB :: Mein Termin -->
                    <div class="tab-pane fade p-md-4 py-3" id="pills-mein-termin" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <!-- begin termine-data akademie-buchung-tab-2.html -->
                        <?php echo $this->render('akademie-buchung/akademie-buchung-tab-2.html',NULL,get_defined_vars(),0); ?>

                        <div class="row mt-3 termine-skache">
                            <div class="col-12">
                                <div class="d-flex justify-content-between text-center mt-0">
                                    <button class="btn  btn-hotel btn-previous mt-3 mr-md-4 position-relative">Zurück</button>
                                    <button id="termine-button-2" class="btn  btn-hotel btn-next mt-3 position-relative">Weiter</button>
                                </div>
                            </div>
                        </div>

                        <!-- end termine-data -->
                    </div>
                    <!-- END 2 TWO TAB -->

                    <!-- BEGIN 3 Three TAB  :: Meine Daten -->
                    <div class="tab-pane fade p-md-4 py-3" id="pills-personaliche-daten" role="tabpanel" aria-labelledby="pills-personaliche-daten-tab">
                        <!-- begin termine-data akademie-buchung-tab-3.html -->
                        <?php echo $this->render('akademie-buchung/akademie-buchung-tab-3.html',NULL,get_defined_vars(),0); ?>

                        <div class="row mt-3 termine-skache">
                            <div class="col-12" id="termin-confirm-li">
                                <div class="d-flex justify-content-between text-center mt-0">
                                    <button class="btn  btn-hotel btn-previous mt-3 mr-md-4 position-relative">Zurück</button>
                                    <button data-original-text="Weiter" data-loading-text="Laden..." id="termin-confirm" type="button" class="btn  btn-hotel btn-submit position-relative mt-3">Weiter</button>
                                </div>
                            </div>
                        </div>

                        <!-- end termine-data -->
                    </div>
                    <!-- END 3 Three TAB :: Meine Daten -->

                    <!-- BEGIN 4 four TAB  :: Meine Buchung -->
                    <div class="tab-pane fade p-md-4 py-3" id="pills-terminbuchung" role="tabpanel" aria-labelledby="pills-profile-tab-buchung">
                        <!-- begin termine-data akademie-buchung-tab-4.html -->
                        <?php echo $this->render('akademie-buchung/akademie-buchung-tab-4.html',NULL,get_defined_vars(),0); ?>

                        <div class="row mt-3 termine-skache">
                            <div class="col-12">
                                <div class="d-flex justify-content-between text-center mt-0">
                                    <button class="btn  btn-hotel btn-previous mt-3 mr-md-4 position-relative">Zurück</button>
                                    <button id="hide-button" class="hide btn  btn-hotel btn-next mt-3 position-relative">Weiter</button>
                                    <button id="getCodeBtn" onclick="APPOINTMENT_MAKER.sendCode()" data-original-text="SMS-Code anfordern und Termin bestätigen" data-loading-text="Bitte warten ..." class="btn btn-hotel  mt-3  position-relative">SMS Code anfordern</button>
                                </div>
                            </div>
                        </div>
                        <!-- end termine-data -->
                    </div>
                    <!-- END 4 four TAB :: Meine Buchung -->

                    <!-- BEGIN 5 five TAB :: . Fertig finish -->
                    <div class="tab-pane fade p-md-4 py-3" id="pills-fertig" role="tabpanel" aria-labelledby="pills-profile-tab-fertig">
                        <!-- begin termine-data akademie-buchung-tab-5.html -->
                        <?php echo $this->render('akademie-buchung/akademie-buchung-tab-5.html',NULL,get_defined_vars(),0); ?>
                        <!-- end termine-data -->
						<div class="row mt-3 termine-skache">
                            <div class="col-12" id="termin-beenden-li">
                                <div class="text-md-right mt-0">
                                   
                                    <button data-original-text="Beenden" data-loading-text="Laden..." type="button" onclick="APPOINTMENT_MAKER.beenden()"  class="btn  btn-hotel btn-submit position-relative mt-3">Beenden</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END 5 five TAB -->

                </div>
            </section>

            <!-- button Termin finden -->
            <div class="row mt-3">
                <!-- Grid column -->
                <div class="col-md-12">
                    <div id="termin-finden-li" class="hide termin-finden-li text-center text-md-center">
                        <button data-original-text="Termin finden" data-loading-text="Laden..." id="termin-finden" type="button" class="btn btn-submit position-relative btn-hotel">Termin finden</button>
						
                    </div>
                </div>
            </div>

            <!-- end button Termin finden -->
            <!-- navigation -->
            <!-- <ul class="list-unstyled wizard mt-0 overflow-hidden">
                <li class="pull-left previous prigunzi"><button type="button" class="btn btn-dark btn-previous position-relative">Zurück</button></li>
                <li class="pull-right next prigunzi"><button type="button" class="btn btn-dark btn-next position-relative">Weiter</button></li>
                <li id="termin-finden-li" class="pull-right termin-finden-li">
                    <button data-original-text="Termin finden" data-loading-text="Laden..." id="termin-finden" type="button" class="btn btn-dark btn-submit position-relative">Termin finden</button>
                </li>

                <li id="termin-confirm-li" class="pull-right termin-confirm-li">
                    <button data-original-text="Weiter" data-loading-text="Laden..." id="termin-confirm" type="button" class="btn btn-dark btn-submit position-relative">Weiter</button>
                </li>
            </ul> -->
            <!-- end navigation -->
        </article>
        <!-- END page-termine -->
        <article id="termine-info" class="mt-5">
        </article>

        <article id="termine-data" class="mt-0">
            <section id="layout-termine-data" class="container p-md-0 p-2 text-center">
                <div class="row">
                    <div class="col-12 position-relative">
                        <form id="dateSelect" action="" method="post">
                            <div class="pasolya" id="termine-ajax">
                                <!-- begin termine-data ajax -->

                                <!-- end termine-data -->
                            </div>
                            <!-- <input type="hidden" name="layout" value="meintermin" /> -->
                        </form>
                    </div>
                </div>
            </section>
        </article>
    </div>
</div>
<!-- END ROW -->
<!-- begin termine-modal -->
<?php echo $this->render('akademie-buchung/akademie-buchung-modal.html',NULL,get_defined_vars(),0); ?>
<?php echo $this->render('akademie-buchung/akademie-buchung-modal-hair.html',NULL,get_defined_vars(),0); ?>
<!-- end termine-modal -->