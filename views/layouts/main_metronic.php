<?php

/* @var $this \yii\web\View */
/* @var $content string */
namespace yii\bootstrap\Nav;
use Yii;
use yii\bootstrap\Nav;

use yii\helpers\Html;
use app\assets\MetronicAsset;
use yii\widgets\Breadcrumbs;
use yii\widgets\Menu;

MetronicAsset::register($this);
?>
<?php $this->beginPage() ?>

<!DOCTYPE html>
<!--[if IE 8]>
<html lang="<?= Yii::$app->language ?>" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="<?= Yii::$app->language ?>" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="<?= Yii::$app->language ?>">
<!--<![endif]-->

<!-- BEGIN HEAD -->
<head>
    <title><?= Html::encode($this->title) ?></title>

    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <?= Html::csrfMetaTags() ?>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
          type="text/css">
    <link href="/metronic/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="/metronic/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet"
          type="text/css">
    <link href="/metronic/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/metronic/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
    <link href="/metronic/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet"
          type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN THEME STYLES -->
    <link href="/metronic/global/css/components-md.css" id="style_components" rel="stylesheet" type="text/css"/>
    <link href="/metronic/global/css/plugins-md.css" rel="stylesheet" type="text/css"/>
    <link href="/metronic/admin/layout4/css/layout.css" rel="stylesheet" type="text/css"/>
    <link id="style_color" href="/metronic/admin/layout4/css/themes/light.css" rel="stylesheet" type="text/css"/>
    <link href="/metronic/admin/layout4/css/custom.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/427298f4/css/bootstrap.css" rel="stylesheet">
    <link href="/assets/789be7ca/themes/smoothness/jquery-ui.css" rel="stylesheet">
    <link href="/css/site.css" rel="stylesheet">
    <!-- END THEME STYLES -->
    <link rel="shortcut icon" href="favicon.ico"/>

    <?php $this->head() ?>
    <script src="/metronic/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
</head>
<!-- END HEAD -->

<!-- BEGIN BODY --><?php $this->beginBody() ?>
<body class="page-md page-header-fixed page-sidebar-closed-hide-logo ">

<!-- BEGIN HEADER -->
<div class="page-header md-shadow-z-1-i navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="/">
                <img src="/metronic/admin/layout4/img/logo-light.png" alt="logo" class="logo-default"/>
            </a>
            <div class="menu-toggler sidebar-toggler">
                <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
            </div>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
           data-target=".navbar-collapse">
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->

        <!-- BEGIN PAGE TOP -->
        <div class="page-top">
            <div class="top-menu">
                <?php echo $this->render('@app/views/layouts/metronic/header_'.( Yii::$app->user->isGuest ? 'guest' : 'user' )) ?>
            </div>
        </div>
        <!-- END PAGE TOP -->
    </div>
    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []
    ]) ?>
    <?= $content ?>
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="page-footer-inner">
        <?= date('Y') ?> &copy;
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="/metronic/global/plugins/respond.min.js"></script>
<script src="/metronic/global/plugins/excanvas.min.js"></script>
<![endif]-->
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="/metronic/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
 <script src="/metronic/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
<script src="/metronic/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"
        type="text/javascript"></script>
<script src="/metronic/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/metronic/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/metronic/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="/metronic/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="/metronic/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<script src="/metronic/global/scripts/metronic.js" type="text/javascript"></script>
 <script src="/metronic/admin/layout4/scripts/layout.js" type="text/javascript"></script>
<script src="/metronic/admin/layout4/scripts/demo.js" type="text/javascript"></script>
 <script src="/assets/6ba489c7/jquery.js"></script>
<script src="/assets/9772d3d5/yii.js"></script>
<script src="/assets/789be7ca/jquery-ui.js"></script>
<script>
    jQuery(document).ready(function () {
        // initiate layout and plugins
        Metronic.init(); // init metronic core components
        Layout.init(); // init current layout
        Demo.init(); // init demo features
    });
</script>
<?php $this->endBody() ?>
</body>
<!-- END BODY -->
</html>
<?php $this->endPage() ?>
