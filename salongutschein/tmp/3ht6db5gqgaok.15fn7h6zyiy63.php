<!-- add banner  @FIX by oppo * @Date: 13.01.2021 11:40 -->
<?php if ($BANNERS): ?>
    
       
            <article class="mb-4" id="page-slide-index">
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
<!-- end banner  @FIX by oppo * @Date: 13.01.2021 11:40 -->