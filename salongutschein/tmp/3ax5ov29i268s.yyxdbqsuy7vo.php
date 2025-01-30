<div class="row gutscheinauswahl-second">

    <div class="col-md-8 offset-md-2 col-12 pt-3">

        <form class="form mt-0 w-100" role="form" autocomplete="off" id="gutschein-design" action="" method="post">

            <div class="text-md-left text-cente-r mb-1">
                <h3>Gutscheindesign</h3>
            </div>

            <p>Wählen Sie Ihr Wunschdesign aus</p>

            <div class="row radio-group mt-2 no-padd-form">

                <div class="col-md-4 tt-form-group">
                    <div class="form-check">
                        <input class="radio-btn radio-btn-design" type="radio" name="design" value="1" id="defaultCheck1"  />
                        <label class="form-check-label" for="defaultCheck1">
                        Geburtstag
                    </label>
                    </div>
                    <div class=" mt3 ">
                        <img src="<?= ($ASSETS) ?>images/email_layout_1.jpg" alt="Email layout 1">
                    </div>
                </div>

                <div class="col-md-4 tt-form-group">
                    <div class="form-check mt-md-0 mt-3">
                        <input class="radio-btn radio-btn-design" type="radio" name="design" value="2" id="defaultCheck2" aria-checked="checked" />
                        <label class="form-check-label" for="defaultCheck2">
                        Neutral
                    </label>
                    </div>
                    <div class=" mt3 text-center">
                        <img src="<?= ($ASSETS) ?>images/email_layout_2.jpg" alt="Email layout 2">
                    </div>
                </div>

<!--                <div class="col-md-4 tt-form-group">
                    <div class="form-check mt-md-0 mt-3">
                        <input class="radio-btn radio-btn-design" type="radio" name="design" value="3" id="defaultCheck3" />
                        <label class="form-check-label" for="defaultCheck3">
                        Weihnachten
                    </label>
                    </div>
                    <div class=" mt3 text-center">
                        <img src="<?= ($ASSETS) ?>images/email_layout_3.jpg" alt="Email layout 3">
                    </div>
                </div>-->
            </div>
            <!-- end radio-group -->

            <div class="row greetings-text">
                <div class="col-12">
                    <div class="form-group">
                        <label class="mt-2" for="textarea">Schreiben Sie ihren
                        Grußtext</label>
                        <textarea class="form-control rounded-0" id="textarea" name="greetings" style="resize: none" rows="3"></textarea>
						<div class="charleft originalTextareaInfo warningTextareaInfo" id="greetingscount">noch 3 Zeichen von max. 255</div>
                    </div>
                </div>
            </div>
            <!-- end greetings-text -->
        </form>

    </div>
</div>
<div class="clr"></div>