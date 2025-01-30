<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="de">
<!--<![endif]-->

<head>
    <?php if (isset($title)): ?>
        
            <title><?= ($title . ' | ' . $site) ?></title>
        
        <?php else: ?>
            <title><?= ($site) ?></title>
        
    <?php endif; ?>

    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1" />
    <meta name="format-detection" content="telephone=no" />
    <meta property="og:title" content="bloom's Friseure" />
    <meta property="og:site_name" content="bloom's Friseure" />
    <meta property="og:description" content="bloom's Friseure" />
    <meta property="og:locale" content="de_De" />
    <meta property="og:type" content="website" />
    <meta name="description" content="bloom's Friseur! Ihr Top Friseur für die besten Frisurentrends und den besten Haarschnitt.  Ihr Friseur für Langhaarfrisuren und Kurzhaarfrisuren. Friseurtermine online buchen!" />
    <meta name="keywords" content="Friseur, Mannheim, Ludwigshafen, Mainz, Wiesbaden, Speyer, Landau, Worms, Heidelberg, Mannheim-innenstadt, Wiesbaden-innenstadt, Mainz-innenstadt, Friseure, Frisuren, Frisurentrends, Langhaarfrisuren, Kurzhaarfrisuren, Frisur, Trend, Frisurentrend, Haarschnitt" />
    <meta name="author" content="1Plus Agency GmbH" />
	<meta name="copyright" content="bloom's 2020" />
    <meta name="info" content="" />
    <meta name="robots" content="all" />

    <link rel="icon" href="<?= ($ASSETS) ?>icon/favicon.ico" type="image/x-icon" />
    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" sizes="152x152" href="<?= ($ASSETS) ?>icon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="144x144" href="<?= ($ASSETS) ?>icon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="120x120" href="<?= ($ASSETS) ?>icon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="114x114" href="<?= ($ASSETS) ?>icon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="76x76" href="<?= ($ASSETS) ?>icon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="72x72" href="<?= ($ASSETS) ?>icon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="57x57" href="<?= ($ASSETS) ?>icon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="60x60" href="<?= ($ASSETS) ?>icon/favicon.ico" />
    <meta name="apple-mobile-web-app-title" content="bloom's Friseure" />
    <base href="<?= ($BASE) ?>/" />

    <link rel="stylesheet" href="<?= ($ASSETS) ?>css/bootstrap.css" />
    <link rel="stylesheet" href="<?= ($ASSETS) ?>css/style.css?<?= ($BLOOMS_VERSION) ?>" />
    <link rel="stylesheet" href="<?= ($ASSETS) ?>css/font-awesome.min.css" />
    <link rel="stylesheet" href="<?= ($ASSETS) ?>js/select2/select2.min.css" />

    <?php if ($view!='home.html'): ?>
        <?php else: ?>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.8.1/baguetteBox.min.css" />
        
    <?php endif; ?>
    <?php if (isset($addstyles) && is_array($addstyles)): ?>
        
            <?php foreach (($addstyles?:[]) as $style): ?>
                <link rel="stylesheet" href="<?= ($ASSETS . $style) ?>" type="text/css" />
            <?php endforeach; ?>
        
    <?php endif; ?>
    <?php if (isset($headaddscripts) && is_array($headaddscripts)): ?>
        
            <?php foreach (($headaddscripts?:[]) as $script): ?>
                <script src="<?= ($ASSETS . $script) ?>"></script>
            <?php endforeach; ?>
        
    <?php endif; ?>

    <script type="text/javascript" charset="utf-8">
        const BloombaseUrl = "<?= ($PAGE_HOST) ?><?= ($BASE) ?>";
        const CarouselInterval = "<?= ($carousel_interval) ?>";
    </script>

</head>

<body class="wpsn-snappable <?= ($view) ?>-body <?= ($classfoot) ?>-nenka">
    
        <!--[if lt IE 8]>
                <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
            <![endif]-->
	<div class="cookie-hint-wrapper" data-cookie-show="false">
    <div class="cookies-content">
        <div class="cookie-text">
            <p>Diese Webseite erhebt Nutzungsdaten mittels Cookies. Weitere Informationen zu dieser Datenverarbeitung und zu Ihrem Widerspruchsrecht gegen diese Verarbeitung, finden Sie <a href="./datenschutz.html" target="_blank">hier</a>.</p>
        </div>
        <div class="cookie-button">
            <div class=" website accept-btn">
                <a href="#" class="btn position-relative btn-hotel">SCHLIEßEN</a>
            </div>
        </div>

    </div>
</div>
<div class="wrapper">
        <?php echo $this->render('layout/social-links.html',NULL,get_defined_vars(),0); ?>
        <div class="container main_container">
            <?php echo $this->render('layout/messages.html',NULL,get_defined_vars(),0); ?>