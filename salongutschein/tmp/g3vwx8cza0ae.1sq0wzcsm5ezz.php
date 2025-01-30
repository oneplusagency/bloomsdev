<div class="row" id="akademie-wrap">
    <div class="col-md-12">
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
                        <?php echo $this->render('layout/menu_all.html',NULL,get_defined_vars(),0); ?>
                    </div>
                </section>

            </div>
        </nav>

        <!-- add banner  @FIX by oppo * @Date: 27.07.2020 18:11 -->
        <?php if ($BANNERS): ?>
            
                <article id="page-slide-index">
                    <section class="layout-slider container mt-5 p-md-0 px-2">
                        <!-- BEGIN slider-images -->
                        <div id="slider-images" class="carousel slide carousel-fade" data-ride="carousel">

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
                        </div>
                        <!-- END slider-images -->
                    </section>
                </article>
            
        <?php endif; ?>

        <article id="page-akademia" class="container p-md-0 mt-5">
            <section class="layout-akademia">
                <div class="image-akademia text-center">
                    <!-- <img src="<?= ($ASSETS) ?>images/image-example.svg" alt="Image" class="img-fluid"> -->

                    <h3 class="mt-5">Erfolg ist nur durch Bildung möglich!</h3>
                    <p class="layout-akademia-info-text mx-auto mt-5">Die bloom´s Akademie im Herzen Mannheims steht für
                        beste Weiterbildung.</p>

                    <p>Wir freuen uns auf Sie!</p>

                </div>


                <section class="layout-akademia-info-team mt-5" id="selection-tabs">

                    <div class="text-center">
                        <button class="btn btn-page-bloom text-uppercase btn-active"
                            onclick="showData(this,'#termine-section');">MODELLTERMINE
                        </button>
                        <button class="btn btn-page-bloom text-uppercase"
                            onclick="showData(this,'#page-preise');">Modellpreise
                        </button>
                        <button class="btn btn-page-bloom text-uppercase d-none"
                            onclick="showData(this,'#seminare-data');">SEMINARE
                        </button>
                        <button class="btn btn-page-bloom text-uppercase" onclick="showData(this,'#clip-data');"
                            style="display:none;">
                            CLIPS
                        </button>
                        <button class="btn btn-page-bloom text-uppercase"
                            onclick="showData(this,'#bilder-data');">BILDER
                        </button>


                    </div>

                    <div class="px-txt-main">

                        <?php echo $this->render('akademie-buchung.html',NULL,get_defined_vars(),0); ?>

                        <?php echo $this->render('akademie/akademie-tab-seminare.html',NULL,get_defined_vars(),0); ?>

                        <?php echo $this->render('akademie/akademie-tab-bilder.html',NULL,get_defined_vars(),0); ?>

                        <?php echo $this->render('akademie/akademie-tab-priese.html',NULL,get_defined_vars(),0); ?>

                    </div>

                </section>
            </section>

        </article>
    </div>
</div>
<!-- END ROW -->






<!-- <script src="<?= ($ASSETS) ?>js/baguetteBox.min.js"></script>
<script>
    baguetteBox.run('.tz-gallery');
</script> -->