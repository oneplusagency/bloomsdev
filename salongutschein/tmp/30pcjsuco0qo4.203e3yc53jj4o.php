<div class="layout-akademia-clip-data mt-5" id="seminare-data">


    <?php if ($SEMINARES): ?>
        
            <?php foreach (($SEMINARES?:[]) as $data): ?>

                <div class="clips-profiles text-center akademie-seminare-block" data-toggle="modal" data-target="#SeminareDataModal">

                    <a class="SeminareNozdrya" href="#SeminareNozdrya" data-slide-to="<?= ($data['key_index']) ?>">

                        <img src="<?= ($data['thumb']) ?>" alt="<?= ($data['title']) ?>" class="img-hover-animation akademie-img" />

                        <p class="over-image-text"><?= ($data['title']) ?></p>
                    </a>
                </div>

            <?php endforeach; ?>
        
    <?php endif; ?>

</div>


<?php if ($SEMINARES): ?>
    
        <!-- MODAL DAVAL -->
        <div class="modal fade modal-seminare-data slider-paper-wrapper" id="SeminareDataModal" tabindex="-1" role="dialog" aria-labelledby="clipModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-seminare modal-dialog-centered modal-dialog-fullscreen" role="document">

                <!-- MODAL-ARROW-LEFT -->
                <div class="modal-arrow modal-arrow-left">
                    <button href="#SeminareNozdrya" role="button" type="button" id="sssprev-button" aria-label="Next" class="b-carousel-control-prev" data-slide="prev">
                        <svg width="44" height="60">
                            <polyline points="30 10 10 30 30 50" stroke="rgb(255,255,255)" stroke-width="4" stroke-linecap="butt" fill="none" stroke-linejoin="round"></polyline>
                        </svg>
                    </button>
                </div>
                <!-- MODAL-ARROW-RIGHT -->
                <div class="modal-arrow modal-arrow-right">
                    <button href="#SeminareNozdrya" role="button" type="button" id="sssnext-button" aria-label="Next" class="b-carousel-control-next" data-slide="next">
                        <svg width="44" height="60">
                            <polyline points="14 10 34 30 14 50" stroke="rgb(255,255,255)" stroke-width="4" stroke-linecap="butt" fill="none" stroke-linejoin="round"></polyline>
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
                    <div class="modal-body text-dark p-0">

                        <div id="SeminareNozdrya" class="carousel slide" data-ride="carousel" data-type="multi" data-interval="false" data-keyboard="true">

                            <div class="carousel-inner">

                                <?php foreach (($SEMINARES?:[]) as $KEY=>$data): ?>

                                    <?php if ($KEY == 0): ?>
                                        
                                            <div class="carousel-item active"><img src="<?= ($data['src']) ?>" class="d-block w-100" alt="<?= ($data['title']) ?>"></div>
                                        
                                        <?php else: ?>
                                            <div class="carousel-item sb_active"><img src="<?= ($data['src']) ?>" class="d-block w-100" alt="<?= ($data['title']) ?>"></div>
                                        
                                    <?php endif; ?>


                                <?php endforeach; ?>

                            </div>

                        </div>
                        <!-- SeminareNozdrya -->

                    </div>
                    <!-- end modal-body -->
                </div>
            </div>
        </div>
    
<?php endif; ?>