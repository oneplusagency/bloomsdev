<div class="row">
    <div class="col-md-12 home-blooms" id="home-blooms">

        <a class="skip-link d-none" href="#page-nav-index">Skip to main</a>

        <?php echo $this->render('layout/blooms-logo.html',NULL,get_defined_vars(),0); ?>
        <nav class="page-nav-others text-vanukin navbar navbar-dark navbar-expand-md mt-5 py-0">
            <div class="container p-md-0 w-100">
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#myTogglerNav"
                    aria-controls="#myTogglerNav" aria-label="Toggle Navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <section class="collapse navbar-collapse pr-3 order-md-1 order-2" id="myTogglerNav">
                    <div class="navbar-nav ml-auto w-100 justify-content-around px-5">
                        <button type="button" class="btn-close-mobile-nav d-md-none" data-toggle="collapse"
                            data-target="#myTogglerNav" aria-controls="#myTogglerNav" aria-label="Toggle Navigation">
                            X
                        </button>
                    </div>
                </section>
            </div>
        </nav>
        <article id="page-slide-index">
            <section class="layout-slider-index container mt-5 p-0">
                <!-- BEGIN slider-images -->
                <div id="slider-images" class="carousel slide carousel-fade" data-ride="carousel">
                    <?php if ($BANNERS): ?>
                        
                            <div class="carousel-inner">
                                <?php foreach (($BANNERS?:[]) as $KEY=>$BANNER): ?>


                                    <?php if ($BANNER['type'] == 'img'): ?>

                                        <?php if ($KEY == 0): ?>
                                            
                                                <div data-interval="<?= ($BANNER['interval']) ?>"
                                                    class="carousel-item-img carousel-item active"><img
                                                        src="<?= ($BANNER['src']) ?>" class="d-block w-100" alt="Banner" />
                                                </div>
                                            
                                            <?php else: ?>
                                                <div data-interval="<?= ($BANNER['interval']) ?>"
                                                    class="carousel-item-img carousel-item"><img
                                                        src="<?= ($BANNER['src']) ?>" class="d-block w-100" alt="Banner" />
                                                </div>
                                            
                                        <?php endif; ?>
                                    <?php endif; ?>


                                    <?php if ($BANNER['type'] == 'youtube'): ?>

                                        <?php if ($KEY == 0): ?>
                                            
                                                <div data-interval="<?= ($BANNER['interval']) ?>"
                                                    class="carousel-item--youtube carousel-item active">

                                                    <div
                                                        class="embed-responsive embed-responsive-21by9 embed-responsive-16by9 embed-responsive-550">
                                                        <iframe allow="autoplay"
                                                            class="provider-youtube embed-responsive-item"
                                                            src="<?= ($BANNER['src']) ?>" height="550" frameborder="0"
                                                            allowfullscreen="true" scrolling="no">
                                                        </iframe>
                                                    </div>

                                                </div>
                                            
                                            <?php else: ?>
                                                <div data-interval="<?= ($BANNER['interval']) ?>"
                                                    class="carousel-item--youtube carousel-item">
                                                    <div
                                                        class="embed-responsive embed-responsive-21by9 embed-responsive-16by9 embed-responsive-550">
                                                        <iframe allow="autoplay"
                                                            class="provider-youtube embed-responsive-item"
                                                            src="<?= ($BANNER['src']) ?>" height="550" frameborder="0"
                                                            allowfullscreen="true" scrolling="no">
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
                                        
                                            <li data-target="#slider-images" data-slide-to="<?= ($KEY) ?>" class="active">
                                            </li>
                                        
                                        <?php else: ?>
                                            <li data-target="#slider-images" data-slide-to="<?= ($KEY) ?>"></li>
                                        
                                    <?php endif; ?>
                                <?php endforeach; ?>

                            </ol>
                        
                    <?php endif; ?>
                </div>
                <!-- END slider-images -->

            </section>
        </article>
        <div class="row mt-3 gutscheine-skache">
            <div class="col-12">
                <div class="d-flex justify-content-end text-center mt-0">
                    <!-- button-first -->
                    <button id="start-button" onclick="location.href = 'gutscheine.html';"
                        class="btn btn-hotel btn-next mt-3 position-relative">Start</button>
                </div>
            </div>
            <div class="clr clearfix"></div>
        </div>
        <section class="layout-impressum-datenschutz  mt-5 text-left p-sm-0 pl-sm-3 <?= ($classfoot) ?>-UA">
            <!-- menu_all -->
            <?php foreach (($FOOTER_LINKS?:[]) as $URL_LINK=>$URL_LABEL): ?>
                <a class="nav-item-btn menu-blm-footer <?= ($ACTIVE && strpos($URL_LINK, $ACTIVE )!==false ? 'active' : '') ?>"
                    target="_blank" href="<?= ($URL_LINK) ?>"><?= ($this->raw($URL_LABEL)) ?></a>
            <?php endforeach; ?>
        </section>

    </div>
</div>