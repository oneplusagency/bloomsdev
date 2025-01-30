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
                        <?php echo $this->render('layout/menu_all.html',NULL,get_defined_vars(),0); ?>
                    </div>
                </section>

            </div>
        </nav>

        <article id="page-stylebook-gallery" class="container px-0 mt-5">

            <h3 class="text-center text-light futuraLtBk"><?= ($TITLE_EMPLOEE) ?></h3>



            <?php if ($STYLEBOOK_PICTURE): ?>
                
                    <div id="stylebook-gallery-chlen" class="layout-stylebook-gallery mt-5 text-center">

                        <?php foreach (($STYLEBOOK_PICTURE?:[]) as $data): ?>
                            <!-- data-toggle="modal" data-target="#stylistModal" -->

                            <div class="stylebook-gallery-grid-img stylebook-container">
                                <div class="prevsalo position-relative d-flex justify-content-center w-100" id="<?= ($data['key_index']) ?>-prevsalo" data-index="<?= ($data['key_index']) ?>" data-employeeId="<?= ($data['Id']) ?>" data-salonid="<?= ($data['SALONID']) ?>" data-termine_url="<?= ($BASE) ?>/termine/salon/<?= ($data['SALONID']) ?>"
                                    data-title_employee="<?= ($data['Name']) ?>" data-img_employee="<?= ($data['PICTURE']) ?>">
                                    <!-- data-stylebook_src -->
                                    <img class="gop-image" src="<?= ($data['TH_PICTURE']) ?>" alt="<?= ($data['ALT']) ?>" />

                                    <?php if ($data['Name']): ?>
                                        
                                            <p style="cursor: pointer;" class="over-image-text-stylebook-grid-img">Stylist: <?= ($data['Name']) ?></p>
                                        
                                    <?php endif; ?>

                                </div>
                            </div>

                        <?php endforeach; ?>
                    </div>
                
                <?php else: ?>
                    <div class="alert text-center mt-2 text-warning">
                        Sie haben derzeit keine Sachen in unserem Stylebook.
                    </div>
                
            <?php endif; ?>

        </article>

    </div>
</div>
<!-- END ROW -->

<!-- Modal -->
<div class="modal fade modal-salon-subpage" id="stylistModal" tabindex="-1" role="dialog" aria-labelledby="stylistModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

        <!-- MODAL-ARROW-LEFT -->
        <div class="modal-arrow modal-arrow-left">
            <button type="button" id="" aria-label="Next" class="">
                        <svg width="44" height="60">
                            <polyline points="30 10 10 30 30 50" stroke="rgb(255,255,255)" stroke-width="4" stroke-linecap="butt" fill="none" stroke-linejoin="round"></polyline>
                        </svg>
                    </button>
        </div>
        <!-- MODAL-ARROW-RIGHT -->
        <div class="modal-arrow modal-arrow-right">
            <button type="button" id="next-button" aria-label="Next" class="">
                        <svg width="44" height="60">
                            <polyline points="14 10 34 30 14 50" stroke="rgb(255,255,255)" stroke-width="4" stroke-linecap="butt" fill="none" stroke-linejoin="round"></polyline>
                        </svg>
                    </button>
        </div>


        <div class="modal-content">

            <div class="modal-header border-0 p-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center text-white">

                <div class="modal-stylebook-containpic">
                    <a class="modal-stylebook-img stylist-modal-img text-center w-100" href="javascript:void(0);"></a>
                </div>

                <!-- Selina -->
                <h3 class="mt-2 modal-title">Stylist <span> </span></h3>

                <a class="btn btn-hotel mt-5 termine-url" href="<?= ($BASE) ?>/termine.html"><strong>Termin vereinbaren</strong></a>
                <!-- <a class="btn btn-dark mt-2 stylebook-url" href="<?= ($BASE) ?>/stylebook.html"><strong>Stylebook anschauen</strong></a> -->
                <!-- ALEX told hide 06.02.2020 13:11 -->
                <div class="mt-2 text-left employee_description"></div>

                <!-- end gallery -->

            </div>
        </div>

    </div>
</div>