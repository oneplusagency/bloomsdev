<nav id="toolbar" class="navbar navbar-default navbar-fixed-bottom no-bg no-border" role="navigation">
    <div class="container-fluid">

        <a href="<?= ($BASE) ?>/admin" type="button" class="hide btn btn-default tool-btn">
            <span class="fa fa-arrow-left"></span> &nbsp; Zurück
        </a>
        <!-- <button type="button" class="btn btn-default tool-btn" data-toggle="modal" data-target="#youtubeModal">
            <span class="fa fa-video-camera"></span> &nbsp; Video
        </button>
        -->
        <a href="<?= ($BASE) ?>" type="button" class="btn btn-default tool-btn pull-right">
            <span class="fa fa-arrow-right"></span> &nbsp; Titelseite
        </a>
    </div>
</nav>
<section id="layout-admin-index" class="layout-admin-index container pt-5 m-auto vh-100">
    <div class="row">
        <div class="col-md-12 text-left">
            <div class="card text-white bg-secondary border-dark bg-dark aus-front">
                <div class="card-body">
                    <a class="btn btn-danger" href="<?= ($BASE) ?>/admin/logout">Ausloggen</a>
                </div>
            </div>
            <div class="row mt-6 card-stats">

                <div class="col-md-4">
                    <div class="card text-white bg-secondary border-dark bg-dark mt-3 shadow text-center">
                        <div class="card-title card-title text-muted my-2">
                            <h5 class="card-title text-center"><i class="fa fas fa-image"></i> Banner Manager Home</h5>

                        </div>
                        <div class="card-body">
                            <p class="py-0  mb-0 text-muted text-sm"><a class="btn btn-outline-success" href="<?= ($BASE) ?>/admin/banner">&raquo; Banner bearbeiten</a></p>

                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-white bg-secondary border-dark bg-dark mt-3 shadow text-center">
                        <div class="card-title card-title text-muted my-2">
                            <h5 class="card-title text-center"><i class="fa fas fa-image"></i> Banner Manager Preise</h5>

                        </div>
                        <div class="card-body">
                            <p class="py-0  mb-0 text-muted text-sm"><a class="btn btn-outline-success" href="<?= ($BASE) ?>/admin/bannerprice">&raquo; Banner bearbeiten</a></p>

                        </div>
                    </div>
                </div>
                <!-- http://localhost/f3-blooms/admin/bannersalons -->

                <!-- Salons -->
                <div class="col-md-4">
                    <div class="card text-white bg-secondary border-dark bg-dark mt-3 shadow text-center">
                        <div class="card-title card-title text-muted my-2">
                            <h5 class="card-title text-center"><i class="fa fas fa-image"></i> Banner Manager Salons</h5>

                        </div>
                        <div class="card-body">
                            <p class="py-0  mb-0 text-muted text-sm"><a class="btn btn-outline-success" href="<?= ($BASE) ?>/admin/bannersalons">&raquo; Banner bearbeiten</a></p>

                        </div>
                    </div>
                </div>

                <!-- Akademie -->
                <div class="col-md-4">
                    <div class="card text-white bg-secondary border-dark bg-dark mt-3 shadow text-center">
                        <div class="card-title card-title text-muted my-2">
                            <h5 class="card-title text-center"><i class="fa fas fa-image"></i> Banner Manager Akademie</h5>

                        </div>
                        <div class="card-body">
                            <p class="py-0  mb-0 text-muted text-sm"><a class="btn btn-outline-success" href="<?= ($BASE) ?>/admin/bannerakademie">&raquo; Banner bearbeiten</a></p>

                        </div>
                    </div>
                </div>

                <!-- bewerbung -->
                <div class="col-md-4">
                    <div class="card text-white bg-secondary border-dark bg-dark mt-3 shadow text-center">
                        <div class="card-title card-title text-muted my-2">
                            <h5 class="card-title text-center"><i class="fa fas fa-image"></i> Banner Manager Bewerbung</h5>

                        </div>
                        <div class="card-body">
                            <p class="py-0  mb-0 text-muted text-sm"><a class="btn btn-outline-success" href="<?= ($BASE) ?>/admin/bannerbewerbung">&raquo; Banner bearbeiten</a></p>

                        </div>
                    </div>
                </div>


                <!-- Blooms -->
                <div class="col-md-4">
                    <div class="card text-white bg-secondary border-dark bg-dark mt-3 shadow text-center">
                        <div class="card-title card-title text-muted my-2">
                            <h5 class="card-title text-center"><i class="fa fas fa-image"></i> Banner Manager Blooms</h5>

                        </div>
                        <div class="card-body">
                            <p class="py-0  mb-0 text-muted text-sm"><a class="btn btn-outline-success" href="<?= ($BASE) ?>/admin/bannerblooms">&raquo; Banner bearbeiten</a></p>

                        </div>
                    </div>
                </div>
                <!-- Kontakt -->
                <div class="col-md-4">
                    <div class="card text-white bg-secondary border-dark bg-dark mt-3 shadow text-center">
                        <div class="card-title card-title text-muted my-2">
                            <h5 class="card-title text-center"><i class="fa fas fa-image"></i> Banner Manager Kontakt</h5>

                        </div>
                        <div class="card-body">
                            <p class="py-0  mb-0 text-muted text-sm"><a class="btn btn-outline-success" href="<?= ($BASE) ?>/admin/bannerkontakt">&raquo; Banner bearbeiten</a></p>

                        </div>
                    </div>
                </div>



                <div class="col-md-4">
                    <div class="card text-white bg-secondary border-dark bg-dark mt-3 shadow text-center">
                        <div class="card-title card-title text-muted my-2">
                            <h5 class="card-title text-center"><i class="fa fas fa-support"></i> Einstellungen</h5>
                        </div>
                        <div class="card-body">
                            <p class="py-0  mb-0 text-muted text-sm"><a class="btn btn-outline-success" href="<?= ($BASE) ?>/admin/settings/config">&raquo; Einstellungen
                                    bearbeiten</a></p>

                        </div>
                    </div>
                </div>

                <!-- /admin/settings/config -->

                <!-- https://developservice.de/kunden/blooms/1plus/stylebook/importStylebookimg -->
                <div class="col-md-8 hide">
                    <div class="card text-white bg-secondary border-dark bg-dark mt-3 shadow">
                        <div class="card-title card-title text-muted my-2">
                            <h5 class="card-title">Bilder für Stylebook hochladen</h5>
                            <div class="alert alert-warning" role="alert">
                                <p>Bitte Navigieren Sie nicht zu einer anderen Seite, es sei denn, es wird eine Abschluss- oder Fehlermeldung angezeigt.</p>
                                <p>Bitte warten Sie diesen Vorgang dauert eine lange Zeit.</p>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="py-0  mb-0 text-muted text-sm"><a target="_blank" class="btn btn-outline-success" href="<?= ($BASE) ?>/stylebook/importStylebookimg">&raquo;
                                    Start (Öffnen in einem neuen Fenster)</a></p>
                        </div>
                    </div>
                </div>
                <!-- END importStylebookimg -->

            </div>

        </div>
    </div>
</section>
<style>
    #layout-admin-index .card {
        background-color: #202940 !important;
        color: #606477;
    }
    
    .aus-front {
        background: linear-gradient(60deg, #2a2d3f, #492825cc);
    }
    
    .card-stats a.btn-outline-success {
        color: #a9afbbd1 !important;
    }
    
    .card-stats a.btn-outline-success:hover {
        color: #fefeffd1 !important;
    }
    
    .card-stats .card-title {
        color: #8b92a9;
        margin-top: 0;
        margin-bottom: 3px;
    }
    
    .card-stats .card-body {
        padding: 0.5rem 1.15rem;
    }
    
    .card-stats .fas {
        color: #f7d168;
    }
    
    .card-stats .fas.fa-support {
        color: #62f735;
    }
</style>