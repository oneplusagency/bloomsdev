<div id="dateUramap" class="row">
    <div class="col-md-8 offset-md-2 col-12 pt-3">

        <div class="text-md-left text-left mb-4">
            <h3 class="futuraItBk">Fertig</h3>
        </div>

        <p class="mt-2 termine-des">Ihr Termin wurde verbindlich gebucht. Vielen Dank. Wir freuen uns auf Sie!</p>
        <!-- <p>Sie erhalten Ihre Buchungsbestätigung auch per E-Mail.</p>
        <p>Vielen Dank, dass Sie sich für uns entschieden haben.</p> -->

        <div class="row mt-5 mb-2">
            <div class="col-lg-6 col-12 ">
                <a href="<?= ($BASE) ?>/json/pdf/print" target="_blank" class="btn form-control px-2 mt-4"><i
                        class="fa fa-clipboard"></i>
                    Termin drucken</a>
                <a href="<?= ($BASE) ?>/json/pdf/download" class="btn form-control px-2 mt-3"><i
                        class="fa fa-file-pdf-o"></i> Termin als PDF speichern</a>
                <a href="<?= ($BASE) ?>/json/icalendar" class="btn form-control px-2  mt-3"><i class="fa fa-file-pdf-o"></i>
                    Termin in Kalender eintragen</a>

                <!-- <div class="my-3">
                    <p class="mb-1">bloom´s</p>
                    <p class="mb-1"><span id="addressFive"></span></p>
                    <p class="mb-1"><span id="mobilenumberFive"></span></p>
                </div> -->
            </div>
            <div id="output-container-map" class="col-lg-6 col-12 d-flex align-self-end">
                <div style="width:100%;height:200px!important;" id="output-map"></div>
            </div>
        </div>
        <p class="termine-des">Bitte beachten: In der Akademie ist nur Kartenzahlung möglich!</p>
        <!-- <a href="<?= ($BASE) ?>/termine.html" class="btn btn-dark mt-lg-0 mt-3">Fertig</a> -->
    </div>
</div>