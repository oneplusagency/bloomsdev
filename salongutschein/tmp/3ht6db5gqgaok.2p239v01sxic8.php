<!-- menu_all -->
<?php foreach (($LINKS?:[]) as $URL_LINK=>$URL_LABEL): ?>
    <a class="nav-item nav-link px-0 main-menu-blm-top <?= ($ACTIVE && strpos($URL_LINK, $ACTIVE )!==false ? 'active' : '') ?>" href="<?= ($URL_LINK) ?>"><?= ($this->raw($URL_LABEL)) ?></a>
<?php endforeach; ?>