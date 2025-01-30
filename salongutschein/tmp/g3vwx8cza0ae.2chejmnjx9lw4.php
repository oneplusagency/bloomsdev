<!-- menu_all -->
<?php foreach (($FOOTER_LINKS?:[]) as $URL_LINK=>$URL_LABEL): ?>
    <a class="nav-item-btn menu-blm-footer <?= ($ACTIVE && strpos($URL_LINK, $ACTIVE )!==false ? 'active' : '') ?>" target="_blank" href="<?= ($URL_LINK) ?>"><?= ($this->raw($URL_LABEL)) ?></a>
<?php endforeach; ?>