<nav id="toolbar" class="navbar navbar-default navbar-fixed-bottom no-bg no-border" role="navigation">
    <div class="container-fluid">

        <a href="<?= ($BASE) ?>/admin" type="button" class="btn btn-default tool-btn">
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

<section class="layout-admin-index container pt-1 m-auto vh-100">
    <div class="row">
        <div class="col-md-12 m-auto text-center">
            <h3 title="Front page banners Manager" class="text-center">Preis Manager</h3>
            <br />
            <div class="col-md-12 m-auto text-right">
                <!-- <span class="fa fa-arrow-right"></span>  -->
                <button type="button" name="add_category" id="add_category" class="btn btn-success" data-toggle="modal"
                    data-target="#addCategoryModal"><i class="fa fa-bookmark" aria-hidden="true"></i> Kategorie
                    hinzufügen</button>
                <button type="button" name="add_item" id="add_item" class="btn btn-warning"><i class="fa fa-money"
                        aria-hidden="true"></i> Leistung hinzufügen</button>
            </div>
            <br />
            <div class="card">
                <table class="table" id="banner_price_new_table">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">Kategorie</th>
                            <th width="60%">Leistung</th>
                            <th width="15%">Preis</th>
                            <th width="10%">Aktion</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Add Category Modal Window -->
<div class="modal fade modal-banner" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kategorie hinzufügen</h5>
            </div>
            <div class="modal-body">
                <form id="add_category_form" method="post">

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Name</span>
                        </div>
                        <input type="text" name="new_category_name" id="new_category_name" class="form-control"
                            placeholder="name" aria-label="name" aria-describedby="basic-addon1">
                    </div>


                    <div id="add-category-error-box" class="alert alert-warning hide" role="alert">
                    </div>
                    <div id="add-category-success-box" class="alert alert-success hide" role="alert">
                    </div>

                    <input type="hidden" name="action" id="category_action" value="insert" />
                    <input type="hidden" name="type" value="category" />
                    <!-- <input type="submit" name="insert_category" id="insert_category" value="Einfügen"
                        class="btn btn-info" /> -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="add_category_form" class="btn btn-default">Einfügen</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<!-- Add Item Modal Window -->
<div class="modal fade modal-banner" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Leistung hinzufügen</h5>
            </div>
            <div class="modal-body">
                <form id="add_item_form" method="post">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Kategorie</span>
                        </div>
                        <select id="category_id" name="category_id" class="form-control rounded-0 ">
                        </select>
                    </div>
                    <p>Leistungsdetails :</p>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Titel</span>
                        </div>
                        <input type="text" class="form-control" id="item_title" name="title" aria-label="Titel"
                            aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">Preis&nbsp;<i class="fa fa-eur"
                                    aria-hidden="true"></i> </span>
                        </div>
                        <input type="text" class="form-control" id="item_price" name="price" aria-label="Preis"
                            aria-describedby="basic-addon1">
                    </div>
                    <hr />
                    <p>Zusatzinformation : <button type="button" class="btn btn-primary btn-sm"
                            onclick="addExtraInfo()">+</button></p>
                    <div id="extra_info_container"></div>

                    <div id="add-item-error-box" class="alert alert-warning hide" role="alert">
                    </div>
                    <div id="add-item-success-box" class="alert alert-success hide" role="alert">
                    </div>

                    <input type="hidden" name="action" id="item_action" value="insert" />
                    <input type="hidden" name="type" value="price-banner" />
                    <!-- <input type="submit" name="insert_item" id="insert_category" value="Einfügen"
                        class="btn btn-info" /> -->
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="add_item_form" class="btn btn-default">Einfügen</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<style>
    .hide {
        display: none;
    }

    .show {

        display: block;
    }
</style>