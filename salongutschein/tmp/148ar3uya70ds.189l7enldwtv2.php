<!--<script src="https://www.google.com/recaptcha/api.js?render=6LdAaVMaAAAAABYEeIq-0L0Oz8CF79utem7g4ZWZ"></script>-->
<div class="col-md-10 offset-md-1">

    <article id="blooms-info-data" class="layout-blooms-info-data mt-4">

        <section id="leitung-view" class="layout-blooms-leitung-data">

            <div class="text-center"></div>

            <div id="leitung-data">


                <!--Office Staff-->
                <?php if ($STAFFINFO): ?>
                    <?php foreach (($STAFFINFO?:[]) as $istaffkey=>$data): ?>
                        <div class="clips-profiles text-center leitung-block" data-toggle="modal"
                            data-target="#StaffLeitungDataModal">

                            <a class="SeminareNozdryaStaff" href="#StaffLeitungNozdrya" data-slide-to="<?= ($istaffkey) ?>"
                                data-email="<?= ($data['id']) ?>">
                                <div class="img-icon">
                                    <img src="<?= ($data['Image']) ?>" class="img-hover-animation leitung-img" />
                                    <i class="fa fa-info-circle ohrecha toggle-modal-sam"></i>
                                </div>
                                <div class="cntct-sctn-ipls">
                                    <p class="over-image-text"><?= ($data['FirstName']) ?></p>
                                    <p><?= ($data['Position']) ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!--Admin staff--->
                <?php if ($ADMININFO): ?>
                    <?php foreach (($ADMININFO?:[]) as $ikey=>$data): ?>
                        <div class="clips-profiles text-center leitung-block" data-toggle="modal"
                            data-target="#AdminLeitungDataModal">

                            <a class="SeminareNozdryaAdmin" href="javascript:void(0)" data-slide-to="<?= ($ikey) ?>"
                                data-email="<?= ($data['id']) ?>">
                                <div class="img-icon">
                                    <img src="<?= ($data['Image']) ?>" class="img-hover-animation leitung-img" />
                                    <i class="fa fa-info-circle ohrecha toggle-modal-sam"></i>
                                </div>
                                <div class="cntct-sctn-ipls">
                                    <p class="over-image-text"><?= ($data['FirstName']) ?></p>
                                    <p><?= ($data['Position']) ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>




            </div>
        </section>
    </article>
</div>

<!-- Admin section Modal -->
<div class="modal fade modal-leitung-data slider-paper-wrapper" id="AdminLeitungDataModal" tabindex="-1" role="dialog"
    aria-labelledby="clipModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-leitung modal-dialog-centered modal-dialog-fullscreen" role="document">

        <!-- MODAL-ARROW-LEFT -->
        <div class="modal-arrow modal-arrow-left">
            <button href="#AdminLeitungNozdrya" role="button" type="button" id="sssprev-button" aria-label="Next"
                class="b-carousel-control-prev moveCrScroll" data-form="kontaktcarouselAdmin" data-slide="prev">
                <svg width="44" height="60">
                    <polyline points="30 10 10 30 30 50" stroke="rgb(255,255,255)" stroke-width="4"
                        stroke-linecap="butt" fill="none" stroke-linejoin="round"></polyline>
                </svg>
            </button>
        </div>
        <!-- MODAL-ARROW-RIGHT -->
        <div class="modal-arrow modal-arrow-right">
            <button href="#AdminLeitungNozdrya" role="button" type="button" id="sssnext-button" aria-label="Next"
                class="b-carousel-control-next moveCrScroll" data-form="kontaktcarouselAdmin" data-slide="next">
                <svg width="44" height="60">
                    <polyline points="14 10 34 30 14 50" stroke="rgb(255,255,255)" stroke-width="4"
                        stroke-linecap="butt" fill="none" stroke-linejoin="round"></polyline>
                </svg>
            </button>
        </div>

        <div class="modal-content px-2 rounded-0">

            <div class="modal-header border-0 px-0 py-1">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- modal-body -->
            <div class="modal-body text-dark p-0 contxkt-mdl">

                <div id="AdminLeitungNozdrya" class="carousel slide" data-ride="carousel" data-type="multi"
                    data-interval="false" data-keyboard="true">

                    <div class="carousel-inner">
                        <?php if ($ADMININFO): ?>
                            <?php foreach (($ADMININFO?:[]) as $ikey=>$data): ?>

                                <div class="carousel-item" id="adminSlide_<?= ($ikey) ?>" data-email="<?= ($data['id']) ?>">

                                    <div class="at-row">
                                        <div class="full-col-img">
                                            <div class="modal-employee-containpic">
                                                <img src="<?= ($data['WebImage']) ?>" class="w-100" alt="<?= ($data['FirstName']) ?>">
                                            </div>
                                            <div class="full-col-name">
                                                <h3 class="mt-2 modal-title"><span><?= ($data['FirstName']) ?></span></h3>

                                            </div>
                                        </div>
                                    </div>
                                    <p style="margin-bottom: 0px;"><?= ($data['Description']) ?></p>
                                    <div class="text-center">
                                        <a class="mt-3 mb-3 termine-url btn btn-hotel"
                                            style="text-shadow: none !important;font-weight: normal !important;"
                                            href="javascript:showcarouselformAdmin(this);">
                                            Nachricht schreiben
                                        </a>
                                        <div class="btn-hotel-after" style="display: none;">Nachricht schreiben</div>
                                    </div>


                                </div>

                            <?php endforeach; ?>
                        <?php endif; ?>

                        <div style="clear:both;color: #fff;text-align: center;background: #000;">


                            <form id="kontaktcarouselAdmin" method="post"
                                style="padding: 15px;padding-top: 0px;display:none;" enctype="multipart/form-data">
                                <div class="text-center">
                                    <p style="margin-bottom: 0px; font-family: futuraBkBT; font-size: 15px;;">
                                        Für Reklamationen bitte <a href="./feedback.html">HIER</a> klicken
                                    </p>

                                    <p style="margin-bottom: 0px; font-family: futuraBkBT; font-size: 15px;">
                                        Für Terminstornierungen <a href="/abmeldung.html">HIER</a> klicken</a>
                                    </p>


                                </div>
                                <input class="form-control rounded-0" required="required" pattern=".{2,}" maxlenght="50"
                                    aria-required="true" aria-invalid="false" style="margin: 10px 0px;"
                                    placeholder="Name*" name="name" id="name" type="text">
                                <input class="form-control rounded-0" required="required" pattern=".{2,}" maxlenght="50"
                                    aria-required="true" aria-invalid="false" style="margin: 10px 0px;"
                                    placeholder="E-Mail*" name="emailstaff" id="emailstaff" type="text">
                                <input class="form-control rounded-0" required="required" pattern=".{2,}" maxlenght="50"
                                    aria-required="true" aria-invalid="false" style="margin: 10px 0px;"
                                    placeholder="Telefon*" name="telefon" id="telefon" type="text">
                                <textarea maxlenght="500" id="bloom_kontakt_message" name="message"
                                    class="form-control rounded-0 md-textarea" placeholder="Nachricht*" required=""
                                    style="margin: 10px 0px;" rows="4"></textarea>
                                <div class="row">
                                    <div class="col-lg-3 col-md-12 col-sm-12 upload-btn">
                                        <!------new file typw------>
                                        <input id="file" type="file" name="file" class="inputfile"
                                            data-multiple-caption="{count} files selected" multiple
                                            accept=".jpg,.pdf" />
                                        <label for="file"><span class="mwpl-upload-btn">Uplaod</span></label>
                                        <!----end new file typw---->
                                        <span class="pdf-jpgtxt">
                                            <p>PDF JPG</p>
                                        </span>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 mdl-div-sec">
                                        <div id="kontact_kontakt_status_admin"></div>
                                    </div>
                                    <div class="col-lg-3 col-md-12 col-sm-12 upload-btn">
                                        <input type="hidden" name="email" id="adminEmail" value="">
                                        <button id="kontact_sendbtn_admin" type="submit"
                                            style="background: #000;right: 0;border: 1px solid #fff;color: #fff;float: right;"
                                            class="form-control rounded-0 col-md-5">Senden</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
                <!-- LeitungNozdrya -->

            </div>
            <!-- end modal-body -->
        </div>
    </div>
</div>

<!--Staff section-->
<div class="modal fade modal-leitung-data slider-paper-wrapper" id="StaffLeitungDataModal" tabindex="-1" role="dialog"
    aria-labelledby="clipModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-leitung modal-dialog-centered modal-dialog-fullscreen" role="document">

        <!-- MODAL-ARROW-LEFT -->
        <div class="modal-arrow modal-arrow-left">
            <button href="#StaffLeitungNozdrya" role="button" type="button" id="ssssprev-button" aria-label="Next"
                class="b-carousel-control-prev moveCrScroll" data-form="kontaktcarouselStaff" data-slide="prev">
                <svg width="44" height="60">
                    <polyline points="30 10 10 30 30 50" stroke="rgb(255,255,255)" stroke-width="4"
                        stroke-linecap="butt" fill="none" stroke-linejoin="round"></polyline>
                </svg>
            </button>
        </div>
        <!-- MODAL-ARROW-RIGHT -->
        <div class="modal-arrow modal-arrow-right">
            <button href="#StaffLeitungNozdrya" role="button" type="button" id="ssssnext-button" aria-label="Next"
                class="b-carousel-control-next moveCrScroll" data-form="kontaktcarouselStaff" data-slide="next">
                <svg width="44" height="60">
                    <polyline points="14 10 34 30 14 50" stroke="rgb(255,255,255)" stroke-width="4"
                        stroke-linecap="butt" fill="none" stroke-linejoin="round"></polyline>
                </svg>
            </button>
        </div>

        <div class="modal-content px-2 rounded-0">

            <div class="modal-header border-0 px-0 py-1">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- modal-body -->
            <div class="modal-body text-dark p-0 contxkt-mdl">

                <div id="StaffLeitungNozdrya" class="carousel slide" data-ride="carousel" data-type="multi"
                    data-interval="false" data-keyboard="true">

                    <div class="carousel-inner">
                        <?php if ($STAFFINFO): ?>
                            <?php foreach (($STAFFINFO?:[]) as $sikey=>$data): ?>

                                <div class="carousel-item" id="staffSlide_<?= ($sikey) ?>" data-email="<?= ($data['id']) ?>">
                                    <div class="at-row">
                                        <div class="full-col-img">
                                            <div class="modal-employee-containpic">
                                                <img src="<?= ($data['WebImage']) ?>" class="w-100" alt="<?= ($data['FirstName']) ?>">
                                            </div>
                                            <div class="full-col-name">
                                                <h3 class="mt-2 modal-title"><span><?= ($data['FirstName']) ?></span></h3>

                                            </div>
                                        </div>
                                    </div>
                                    <p style="margin-bottom: 0px;"><?= ($data['Description']) ?></p>
                                    <div class="text-center">
                                        <a class="mt-3 mb-3 termine-url btn btn-hotel"
                                            style="text-shadow: none !important;font-weight: normal !important;"
                                            href="javascript:showcarouselformStaff(this);">
                                            Nachricht schreiben
                                        </a>
                                        <div class="btn-hotel-after" style="display: none;">Nachricht schreiben</div>
                                    </div>

                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <div style="clear:both;color: #fff;text-align: center;background: #000;">


                            <form id="kontaktcarouselStaff" style="padding: 15px;padding-top: 0px;display:none;"
                                enctype="multipart/form-data">
                                <div class="text-center">
                                    <p style="margin-bottom: 0px; font-family: futuraBkBT; font-size: 15px;">
                                        Für Reklamationen bitte <a href="./feedback.html"
                                            style="text-decoration: underline">HIER</a> klicken
                                    </p>
                                    <p style="margin-bottom: 0px; font-family: futuraBkBT; font-size: 15px;">
                                        Für Terminstornierungen <a href="/abmeldung.html"
                                            style="text-decoration: underline">HIER</a> klicken</a>
                                    </p>

                                </div>
                                <input class="form-control rounded-0" required="required" pattern=".{2,}" maxlenght="50"
                                    aria-required="true" aria-invalid="false" style="margin: 10px 0px;"
                                    placeholder="Name*" name="name" id="nameStaff" type="text">
                                <input class="form-control rounded-0" required="required" pattern=".{2,}" maxlenght="50"
                                    aria-required="true" aria-invalid="false" style="margin: 10px 0px;"
                                    placeholder="Telefon*" name="telefon" id="telefonStaff" type="text">
                                <input class="form-control rounded-0" required="required" pattern=".{2,}" maxlenght="50"
                                    aria-required="true" aria-invalid="false" style="margin: 10px 0px;"
                                    placeholder="E-Mail*" name="emailstaff" id="emailstaff" type="text">
                                <textarea maxlenght="500" id="bloom_kontakt_message_staff" name="message"
                                    class="form-control rounded-0 md-textarea" placeholder="Nachricht*" required=""
                                    style="margin: 10px 0px;" rows="4"></textarea>
                                <div class="row">
                                    <div class="col-lg-3 col-md-12 col-sm-12 upload-btn">
                                        <!------new file typw------>
                                        <input id="fileStaff" type="file" name="file" class="inputfile"
                                            data-multiple-caption="{count} files selected" multiple
                                            accept=".jpg,.pdf" />
                                        <label for="fileStaff"><span class="mwpl-upload-btn">Uplaod</span></label>
                                        <!----end new file typw---->
                                        <span class="pdf-jpgtxt">
                                            <p>PDF JPG</p>
                                        </span>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 mdl-div-sec">
                                        <div id="kontact_kontakt_status"></div>
                                    </div>

                                    <div class="col-lg-3 col-md-12 col-sm-12 upload-btn">
                                        <input type="hidden" name="email" id="staffEmail" value="">
                                        <button id="kontact_sendbtn" type="submit"
                                            style="background: #000;right: 0;border: 1px solid #fff;color: #fff;float: right;"
                                            class="form-control rounded-0 col-md-5">
                                            Senden
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
                <!-- LeitungNozdrya -->

            </div>
            <!-- end modal-body -->
        </div>
    </div>
</div>