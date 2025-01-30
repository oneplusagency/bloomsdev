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

        <!-- add banner  @FIX by oppo * @Date: 24.07.2020 16:37 -->
        <?php if ($BANNERS): ?>
            

                <article id="page-slide-index">
                    <section class="layout-slider container mt-5 p-md-0 px-2">
                        <!-- BEGIN slider-images -->
                        <div id="slider-images" class="carousel slide carousel-fade" data-ride="carousel">

                            <div class="carousel-inner">
                                <?php foreach (($BANNERS?:[]) as $KEY=>$BANNER): ?>


                                    <?php if ($BANNER['type'] == 'img'): ?>

                                        <?php if ($KEY == 0): ?>
                                            
                                                <div data-interval="<?= ($BANNER['interval']) ?>" class="carousel-item-img carousel-item active"><img src="<?= ($BANNER['src']) ?>" class="d-block w-100" alt="Banner" /></div>
                                            
                                            <?php else: ?>
                                                <div data-interval="<?= ($BANNER['interval']) ?>" class="carousel-item-img carousel-item"><img src="<?= ($BANNER['src']) ?>" class="d-block w-100" alt="Banner" /></div>
                                            
                                        <?php endif; ?>
                                    <?php endif; ?>


                                    <?php if ($BANNER['type'] == 'youtube'): ?>

                                        <?php if ($KEY == 0): ?>
                                            
                                                <div data-interval="<?= ($BANNER['interval']) ?>" class="carousel-item--youtube carousel-item active">

                                                    <div class="embed-responsive embed-responsive-21by9 embed-responsive-16by9 embed-responsive-550">
                                                        <iframe allow="autoplay" class="provider-youtube embed-responsive-item" src="<?= ($BANNER['src']) ?>" height="550" frameborder="0" allowfullscreen="true" scrolling="no">
                                                        </iframe>
                                                    </div>

                                                </div>
                                            
                                            <?php else: ?>
                                                <div data-interval="<?= ($BANNER['interval']) ?>" class="carousel-item--youtube carousel-item">
                                                    <div class="embed-responsive embed-responsive-21by9 embed-responsive-16by9 embed-responsive-550">
                                                        <iframe allow="autoplay" class="provider-youtube embed-responsive-item" src="<?= ($BANNER['src']) ?>" height="550" frameborder="0" allowfullscreen="true" scrolling="no">
                                                        </iframe>
                                                    </div>
                                                </div>
                                            
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <ol class="carousel-indicators">

                                <?php foreach (($BANNERS?:[]) as $KEY=>$BANNER): ?>
                                    <?php if ($KEY == 0): ?>
                                        
                                            <li data-target="#slider-images" data-slide-to="<?= ($KEY) ?>" class="active"></li>
                                        
                                        <?php else: ?>
                                            <li data-target="#slider-images" data-slide-to="<?= ($KEY) ?>"></li>
                                        
                                    <?php endif; ?>
                                <?php endforeach; ?>

                            </ol>

                        </div>
                        <!-- END slider-images -->

                    </section>
                </article>
            
        <?php endif; ?>

        <article id="page-salon-info" class="container pt-md-1 mt-5 p-md-0">
            <section class="layout-salon-info text-center">
                <h3><?= ($SALON_DISPLAYNAME) ?></h3>
                <p><a href="tel:<?= ($SALON_PHONE) ?>"><span class="mr-sm-4"><i class="fa fa-phone"></i> <?= ($SALON_PHONE) ?></span></a>
                    <div class="clearfix"></div>
                </p>
            </section>

            <div class="text-center mt-sm-5" id="selection-tabs">

                <ul class="nav-noline nav nav-tabs justify-content-center" role="tablist">
                    <li class="nav-item">
                        <!-- <a class="btn btn-page-bloom text-uppercase active" href="#">Active</a> -->
                        <a class="btn btn-page-bloom text-uppercase active" data-toggle="tab" href="#team-view" aria-controls="team-view" aria-selected="false">Team</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-page-bloom text-uppercase" href="<?= ($BASE) ?>/preise/salon/<?= ($salonId) ?>">Preise</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-page-bloom text-uppercase" href="<?= ($BASE) ?>/termine/salon/<?= ($salonId) ?>">Termin</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-page-bloom text-uppercase" data-toggle="tab" href="#internal-map" aria-controls="internal-map" aria-selected="false">Anfahrt</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-page-bloom text-uppercase" data-toggle="tab" href="#business-hours" aria-controls="business-hours" aria-selected="false">Öffnungszeiten</a>
                    </li>
                </ul>
            </div>


            <section id="salon-info-data" class="tab-content layout-salon-info-data mt-4">

                <section id="team-view" class="tab-pane" role="tabpanel" aria-labelledby="team-view-tab">
                    <?php if ($SALONTEAM): ?>
                        
                            <?php foreach (($SALONTEAM?:[]) as $data): ?>
                                <!-- data-toggle="modal" data-target="#employeeModal"  -->
                                <div class="team-employee-profile text-center">
                                    <img src="<?= ($data['avatar']) ?>" id="<?= ($data['key_index']) ?>-prevsalo" class="prevsalo img-hover-animation" alt="<?= ($data['ALT']) ?>" data-index="<?= ($data['key_index']) ?>" data-employeeid="<?= ($data['Id']) ?>" data-salonid="<?= ($data['salonId']) ?>" data-termine_url="<?= ($BASE) ?>/termine/salon/<?= ($data['salonId']) ?>"
                                        data-img_employee="<?= ($data['webimages']) ?>" data-title_employee="<?= ($data['FirstName']) ?>" data-employee_description="<?= ($data['Description']) ?>" />
                                    <!-- Name replace to  FirstName -->
                                    <p class="over-image-text"><?= ($data['FirstName']) ?></p>

                                    <?php if ($data['Description']): ?>
                                        
                                            <i class="fa fa-info-circle ohrecha toggle-modal-sam"></i>
                                        
                                    <?php endif; ?>

                                </div>
                            <?php endforeach; ?>
                        
                        <?php else: ?>
                            <div class="alert">
                                You currently have no items in our Team.
                            </div>
                        
                    <?php endif; ?>
                    <div class="clearfix"></div>
                </section>
                <!-- END team-view -->

                <section id="internal-map" class="tab-pane fade layout-internal-map text-center" role="tabpanel" aria-labelledby="internal-map-tab">
                    <div class="row">
                        <div class="col-12 text-center p-0 rivnenko-560">

                            <iframe class="gray-map" src="<?= ($SALON_GOOGLE_MAP_URL) ?>" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                            <a class="mt-3 btn btn-submit position-relative btn-hotel" href="https://maps.google.de/maps?li=d&amp;hl=de&amp;f=d&amp;iwstate1=dir:to&amp;daddr=<?= ($SALON_ADDRESS) ?>" target="_blank"><!-- <img src="<?= ($ASSETS) ?>images/route_berechnen.jpg" width="204" height="34" border="0" /> -->
                                Route berchnen
                            </a>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </section>

                <!-- business hours (Geschäftszeiten, Öffnungszeiten, Schalterstunden)  -->
                <section id="business-hours" class="tab-pane fade layout-salon-hours text-center" role="tabpanel" aria-labelledby="business-hours-tab">
                    <p class="layout-salon-business-hours">
                        <span><?= ($SALON_OPENINGHOURS) ?></span>
                    </p>
                    <div class="clearfix"></div>
                </section>
                <!-- end business-hours -->


            </section>
            <!-- end salon-info-data -->
        </article>


        <!-- Modal -->
        <div class="modal modal-salon-subpage" id="employeeModal" tabindex="-1" role="dialog" aria-labelledby="stylistModalLabel" aria-hidden="true">
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
                <!-- MODAL-CONTENT  -->
                <div class="modal-content emp-modal">

                    <div class="modal-header border-0 p-0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-center text-white">

                        <div class="at-row">

                            <div class="full-col-img">

                                <div class="modal-employee-containpic">
                                    <a class="modal-employee-img text-center w-100" href="javascript:void(0);"></a>
                                </div>

                                <div class="full-col-name">
                                    <h3 class="mt-2 modal-title"><span> </span></h3>
                                </div>

                            </div>
                        </div>


                        <div class="mt-3 mb-3 text-left employee_description"></div>

                        <a class="mt-3 mb-3 termine-url btn btn-hotel" href="<?= ($BASE) ?>/termine.html">Termin vereinbaren</a>

                        <div id="stylebook-result"></div>
                        <div class="yakcho-nema mt-2">
                            <a id="stylebook-modal" class="mt-0 stylebook-modal" href="javascript:void(0)">
                                <strong>Stylebook anschauen</strong>
                            </a>

                            <div id="stylebook-data" class="row no-hide">
                                <div class="col-12 d-flex justify-content-center">
                                    <div id="stylist-slider" class="stylist-slider-modal mt-1 carousel slide carousel-fade" data-ride="carousel">
                                        <div class="carousel-inner">

                                        </div>
                                        <a class="carousel-control-prev" href="#stylist-slider" role="button" data-slide="prev">
                                            <i class="fa fa-angle-left" aria-hidden="true"></i>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#stylist-slider" role="button" data-slide="next">
                                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!--  25.05.2020 12:01 if not stylebook - hide -->
                        </div>

                    </div>

                </div>
                <!-- end modal content -->

            </div>
        </div>

    </div>
</div>