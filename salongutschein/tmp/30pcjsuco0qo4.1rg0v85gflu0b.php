<section class="layout-admin-login container pt-5 vh-100">
    <div class="row">
        <div class="col-md-8 m-auto text-center">
            <div id="LoginformContent" class="pcard pcard-container jumbotron-icon">
                <!-- <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" /> -->

                <div class="avatar"><i class="fa fa-user fa-3" aria-hidden="true"></i></div>

                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?= ($title) ?></h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post" action="<?= ($BASE. '/admin/auth') ?>" class="login" id="adminlogin">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Benutzername" id="user_id" name="user_id" type="hidden" value="admin" autofocus readonly aria-readonly="" />
                                </div>
                                <div class="form-group">
                                    <input class="form-control input-psswd" placeholder="Passwort" id="password" name="password" type="password" value="" aria-autocomplete="none" />
                                    <!-- <button type="button" data-id="password3">Zeigen</button> -->
                                    <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                </div>
                                <button type="submit" class="btn btn-lg btn-success btn-block">Einloggen</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
                <!-- end panel -->
            </div>
        </div>
    </div>
</section>