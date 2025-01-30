<form id="dateConfirm" class="form-confirm-wizard w-100" action="" method="post">
    <div class="row" id="confirm-tab-2">

        <!-- <div class="col-md-8 offset-md-2 col-12"></div> -->
        <div class="col-md-8 offset-md-2 col-12 pt-3">
            <div class="text-md-left text-left mb-4">
                <h3 class="futuraItBk">Mein Termin</h3>
            </div>

            <div class="row mt-2">
                <div class="col-lg-8 col-12">


                    <div class="row">
                        <div class="col-2 wo-wann">
                            <p><strong>Wo</strong></p>
                        </div>
                        <div class="col-10 text-wowann">
                            <p id="ConfirmWo">bloom´s, <span id="ConfirmTerminAddress"></span></p>
                        </div>
                    </div>
                    <!-- row -->
                    <div class="row">
                        <div class="col-2 wo-wann">
                            <p><strong>Wann</strong></p>
                        </div>
                        <div class="col-10 text-wowann">
                            <!-- Freitag, 22. November 2019 um 11:00 AM Uhr -->
                            <p id="ConfirmWann"> <span id="ConfirmTerminDate"></span> um <span
                                    id="ConfirmTerminTime"></span> Uhr</p>
                        </div>
                    </div>
                    <!-- row -->
                    <div class="row">
                        <div class="col-2 wo-wann">
                            <p><strong>Was</strong></p>
                        </div>
                        <div class="col-10 text-wowann">
                            <!-- Finish Langhaar -->
                            <p id="dienstleistungDisplayName"><span id="ConfirmTerminServiseName"></span> <span
                                    id="s2tooltip" data-toggle="tooltip" class="fa fa-info-circle" data-placement="top"
                                    title=""></span>
                            </p>
                        </div>
                    </div>
                    <!-- row -->
                    <div class="row mt-4">
                        <div class="termine-data-radio-box col-12">
                            <p class="mb-0">Bei Ausfall des Mitarbeiters</p>
                            <input type="radio" name="vertretung" id="terminradio1" class="radio-btn" value="true"
                                checked />
                            <label class="text-wowann" for="terminradio1"> Vertretung durch Kollegen</label>
                            <br />
                            <input type="radio" name="vertretung" id="terminradio2" class="radio-btn" value="false" />
                            <label class="text-wowann" for="terminradio2">Neuen Termin vereinbaren</label>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-12 mt-md-0 mt-2">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-lg-end justify-content-center">
                            <div class="termine-data-img-box text-center text-light">
                                <img src="" class="termine-data-img" id="mitarbeiterImg" alt="avatar" />
                                <p id="mitarbeiterFirstName" class="over-image-text"></p>
                            </div>
                        </div>
                    </div>
                    <!-- row -->
                </div>
            </div>

        </div>
    </div>
</form>