<?php $this->beginContent('@app/views/layouts/metronic.php'); ?>
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar-wrapper">
        <div class="page-sidebar md-shadow-z-2-i  navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <?= $this->render('@dektrium/user/views/settings/_menu') ?>
            <!-- END SIDEBAR MENU -->
        </div>
    </div>
    <!-- END SIDEBAR -->
    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <div class="page-content">
            <?= $content ?>
        </div>
    </div>
    <!-- END CONTENT -->
<?php $this->endContent();