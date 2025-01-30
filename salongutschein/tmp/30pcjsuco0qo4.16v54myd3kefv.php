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
                        <button type="button" class="btn-close-mobile-nav d-md-none" data-toggle="collapse" data-target="#myTogglerNav" aria-controls="#myTogglerNav" aria-label="Toggle Navigation">
                            X
                        </button>
                        <?php echo $this->render('layout/menu_all.html',NULL,get_defined_vars(),0); ?>
                    </div>
                </section>

            </div>
        </nav>


        <article id="page-preise" class="container mt-md-5 mt-2 p-md-0 px-2">
            <section class="layout-preise">
                <!--  <p class="text-center m-0">Aussage Qualität: ausführliche ehrliche Beratung, handwerkliche Perfektion.Wir freuen uns auf Sie! Essenz aus dem Preisvideo</p> -->


                <section class="layout-preise-select-salon mx-auto">
                    <div class="text-center">
                        <!-- data-variable="salonId" -->
                        <?php if ($OPTION_SALON): ?>
                            
                                <div class="mt-sm-4 mt-md-3 mt-0 mx-auto">
                                    <select id="option_salon" name="option_salon" class="form-control rounded-0 ">
                                        <?php foreach (($OPTION_SALON?:[]) as $data): ?>
                                            <?= ($data)."
" ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mt-sm-4 mt-3 mx-auto">
                                    <select id="service_category" name="service_category" class="cancelationDaysRow form-control rounded-0 ">
                                        <option disabled selected>Auswahl Dienstleistungskategorie</option>
                                    </select>
                                </div>
                            
                            <?php else: ?>
                                <div class="alert">
                                    You currently have no items in our preise.
                                </div>
                            
                        <?php endif; ?>


                    </div>

                    <div class="cancelationDaysRow layout-preise-select-salon-info mt-5" id="salon-info">

                        <div id="price-table" class="table-responsive"></div>

                        <p class="text-center mt-3">Preise können je nach Haarlänge und Farbverbrauch variieren.</p>
                        <div class="text-center mt-3">
                            <a class="btn position-relative btn-hotel" id="termin-link" href="<?= ($BASE) ?>/termine.html"><strong>Termin vereinbaren</strong></a>
                        </div>
                    </div>
                </section>
                <!-- end section layout-preise-select-salon -->


            </section>
        </article>


        <!-- add banner  @FIX by oppo * @Date: 2020-03-05 19:35:48 -->
        <?php if ($BANNERS): ?>
            
                <article id="page-slide-index">
                    <section class="layout-slider-index container mt-2 p-0">

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

<!--
                                    <?php if ($BANNER['type'] == 'youtube'): ?>

                                        <?php if ($KEY == 0): ?>
                                            
                                                <div data-interval="<?= ($BANNER['interval']) ?>" class="carousel-item--youtube carousel-item active">

                                                    <div class="embed-responsive embed-responsive-21by9 embed-responsive-16by9 embed-responsive-550">
                                                        <iframe allow="autoplay" class="provider-youtube embed-responsive-item" src="<?= ($BANNER['src']) ?>" height="550" frameborder="0" allowfullscreen="true" scrolling="no">
                                                        </iframe>
                                                    </div>

                                                </div>
                                            
                                            <?php else: ?>
<!--                                                <div data-interval="<?= ($BANNER['interval']) ?>" class="carousel-item--youtube carousel-item">
                                                    <div class="embed-responsive embed-responsive-21by9 embed-responsive-16by9 embed-responsive-550">
                                                        <iframe allow="autoplay" class="provider-youtube embed-responsive-item" src="<?= ($BANNER['src']) ?>" height="550" frameborder="0" allowfullscreen="true" scrolling="no">
                                                    </iframe>
                                                    </div>
                                                </div>-->
                                            

                                        <?php endif; ?>

                                    <?php endif; ?> -->

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

                    </section>
                </article>
            
        <?php endif; ?>

    </div>
</div>