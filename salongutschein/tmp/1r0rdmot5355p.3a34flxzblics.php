<div class="row">
    <div class="col-md-12">
        <?php echo $this->render('layout/blooms-logo.html',NULL,get_defined_vars(),0); ?>

        <nav class="page-nav-others text-vanukin navbar navbar-dark navbar-expand-md mt-md-5 mt-0 py-0">
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


        <article id="page-salons" class="container p-md-0 mt-md-5 mt-0">

            <div class="mt-md-5 mt-2 w-100">

                <section class="layout-salons d-flex flex-wrap justify-content-center">
                    <?php if (isset($SALONS)): ?>
                        
                            <?= ($SALONS)."
" ?>
                        
                    <?php endif; ?>
                </section>
            </div>

        </article>

    </div>
</div>