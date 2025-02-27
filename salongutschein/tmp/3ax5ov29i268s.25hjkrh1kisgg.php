<div class="row" id="meine-buchung">
    <div class="col-md-8 offset-md-2 col-12 pt-3">

        <div class="row mt-2">
            <div class="col-lg-8 col-12" id="persoenliche-daten">
                <div class="row">
                    <div class="text-md-left text-left mb-4 col-12">
                        <h3 class="futuraItBk">Zusammenfassung</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2 wo-wann">
                        <p><strong>Wo</strong></p>
                    </div>
                    <div class="col-10 text-wowann">
                        <p>bloom´s <span id="meinebuchungsalonAddress"></span></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2 wo-wann">
                        <p><strong>Wann</strong></p>
                    </div>
                    <div class="col-10 text-wowann">
                        <p><span id="meinebuchungTerminDate"></span> um <span id="meinebuchungTerminTime"></span> Uhr</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2">
                        <p><strong>Was</strong></p>
                    </div>
                    <div class="col-10">
                        <p id="meinebuchungDienstleistungData"><span id="meinebuchungTerminServiseName"></span> <i class="fa fa-info-circle" data-toggle="tooltip"
                                data-placement="top" title=""></i>
                        </p>
                    </div>
                </div>


                <!-- <h3 class="mt-2"><strong>Persönliche Daten</strong></h3> -->
                <!-- Lorenz Buchheim -->
                <div class="row mt-4">
                    <div class="col-2">
                        <p><strong>Daten</strong></p>
                    </div>
                    <div class="col-10">
                        <p class="personal-data personal-name"></p>
                        <!-- 01645854290 -->
                        <p class="personal-data personal-phone"></p>
                        <!-- test@gmail.com -->
                        <p class="personal-data personal-email"></p>
						
                    </div>
                </div>
				<BR><BR>
<center><p style="font-size:14px!important" ><strong>Zur verbindlichen Buchung erhalten Sie einen SMS Code auf Ihr Handy.</strong> (Versand nur innerhalb Deutschlands).</p></center>
            </div>
            <div class="col-lg-4 col-12 mt-md-0 mt-2">
                <div class="row">
                    <div class="col-12 d-flex justify-content-lg-end justify-content-center">
                        <div class="termine-data-img-box text-center text-light">
                            <img src="" class="termine-data-img" id="meinebuchungMitarbeiterImg" alt="avatar" />
                            <p id="mitarbeiterFirstName" class="over-image-text"></p>
                        </div>
                    </div>
                </div>
                <!-- row -->
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center">
                <!--  <button id="getCodeBtn" onclick="APPOINTMENT_MAKER.sendCode()" data-original-text="SMS-Code anfordern und Termin bestätigen" data-loading-text="Bitte warten ..." class="btn btn-hotel  mt-3 mr-md-4 position-relative">SMS-Code anfordern und Termin bestätigen</button> -->
            </div>
            <div class="col-12">
		
                <!-- <div class="mt-3">
                    <h3 class="mt-2"><strong>Verbindliche Buchung</strong></h3>
                    <p class="text-thirty lesen mt-md-0 mt-4">
                        <strong>Zur verbindlichen Buchung Ihres Wunsch-Termins müssen Sie diesen mit einem SMS-CODE bestätigen.</strong>
                    </p>
                    <p class="text-thirty lesen">
                        Den Code erhalten Sie als SMS auf Ihr Handy (Versand nur innerhalb Deutschlands). Bitte nehmen Sie unsere <a class="brw" href="<?= ($BASE) ?>/datenschutz.html" target="_blank"><u>Datenschutzerklärung</u></a> zur Kenntnis.</p>
                </div> -->
            </div>
        </div>
    </div>

    <div id="errorAuswahl" style="display: none; text-align: center;">
        <div></div>
    </div>
</div>

<div class="row">

    <form action="<?= ($BASE) ?>/termine.html" id="form_appointment_id" class="back_form" style="display:none;" method="post">
        <input id="appointment_id" type="hidden" name="appointment_id" value="" />
        <input id="resend_code" type="hidden" name="resend_code" value="" />
    </form>

    <div class="getCode" style="position:relative;display:none;">
        <!-- onclick="openCodeDialog();" -->
        <input id="getCode" type="button" value="Code anfordern" name="getCode" style="cursor: pointer;" />
        <i class="arrow-right" style="right: 80px;"></i>
    </div>

    <div id="codeSendSecond" style="display: none;">
        <div class="mobilNrTxt"><b>Handynummer</b></div>
        <div class="mobilNrInput"><input class="inputMobilenumberTxt" type="tel" name="mobilenumberSecond" id="mobilenumberSecond"></div>
        <div class="getCodeSecond">
            <input id="getCodeSecond" type="text" value="Code erneut anfordern" name="getCodeSecond" disabled="disabled" style="cursor: default;">
        </div>
        <input id="h_appointmentId" type="hidden" name="appointmentId" value="">
    </div>
</div>

<script>
    function openCodeDialog() {

    }
</script>