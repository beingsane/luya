<?php
use \luya\Module as Luya;
use \luya\helpers\Url;

$user = Yii::$app->adminuser->getIdentity();
$this->beginPage()
?>

<!DOCTYPE html>
<html ng-app="zaa" ng-controller="LayoutMenuController">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title><?php echo Yii::$app->siteTitle; ?> // {{currentItem.alias}}</title>

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?php echo Url::base(true); ?>/admin" />

    <style type="text/css">
        [ng:cloak],
        [ng-cloak],
        [data-ng-cloak],
        [x-ng-cloak],
        .ng-cloak,
        .x-ng-cloak {
            display: none !important;
        }
    </style>

    <?php $this->head(); ?>
</head>

<body style="padding-top: 54px;" ng-cloak>
    <?php $this->beginBody(); ?>

    <!-- .navbar -->
    <nav class="navbar navbar-fixed-top navbar-dark bg-inverse">
        <div class="container-fluid">

            <a class="navbar-brand" href="#">LUYA</a>
            <ul class="nav navbar-nav">
                <li class="nav-item" ng-repeat="item in items" ng-class="{'active' : isActive(item) }">
                    <a class="nav-link" ng-click="click(item);">
                        <i class="fa fa-{{ item.icon }}"></i> {{ item.alias }} <span ng-if="isActive(item)" class="sr-only">(current)</span>
                    </a>
                </li>
            </ul>

            <div class="pull-right">

                <button type="button" class="btn btn-primary-outline" ng-click="reload()">
                    <i class="fa fa-refresh"></i>
                </button>

                <button type="button" class="btn btn-info-outline" ng-mouseenter="showDebugContainer=1" ng-mouseleave="showDebugContainer=0">
                    <i class="fa fa-flask"></i>
                </button>

                <button type="button" class="btn btn-success-outline" ng-mouseenter="showOnlineContainer=1" ng-mouseleave="showOnlineContainer=0">
                    {{ notify.length }} <i class="fa fa-users"></i>
                </button>

                <a class="btn btn-danger-outline" href="<?php echo Yii::$app->urlManager->createUrl(['admin/default/logout']); ?>">
                    <i class="fa fa-sign-out"></i>
                </a>

            </div>

        </div>
    </nav>
    <!-- /navbar -->

    <!-- navbar container -->

    <!-- debug-container -->
    <div ng-show="showDebugContainer" class="info-container">
        <table class="table table-inverse">
            <thead>
                <tr>
                    <th><?php echo Luya::t('admin', 'layout_debug_table_key'); ?></th>
                    <th><?php echo Luya::t('admin', 'layout_debug_table_value'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr><td><?php echo Luya::t('admin', 'layout_debug_luya_version'); ?>:</td><td><?php echo Luya::VERSION; ?></td></tr>
                <tr><td><?php echo Luya::t('admin', 'layout_debug_id'); ?>:</td><td><?php echo Yii::$app->id ?></td></tr>
                <tr><td><?php echo Luya::t('admin', 'layout_debug_sitetitle'); ?>:</td><td><?php echo Yii::$app->siteTitle ?></td></tr>
                <tr><td><?php echo Luya::t('admin', 'layout_debug_remotetoken'); ?>:</td><td><?php echo $this->context->colorizeValue(Yii::$app->remoteToken, true); ?></td></tr>
                <tr><td><?php echo Luya::t('admin', 'layout_debug_assetmanager_forcecopy'); ?>:</td><td><?php echo $this->context->colorizeValue(Yii::$app->assetManager->forceCopy); ?></td></tr>
                <tr><td><?php echo Luya::t('admin', 'layout_debug_transfer_exceptions'); ?>:</td><td><?php echo $this->context->colorizeValue(Yii::$app->errorHandler->transferException); ?></td></tr>
                <tr><td><?php echo Luya::t('admin', 'layout_debug_yii_debug'); ?>:</td><td><?php echo $this->context->colorizeValue(YII_DEBUG); ?></td></tr>
                <tr><td><?php echo Luya::t('admin', 'layout_debug_yii_env'); ?>:</td><td><?php echo YII_ENV; ?></td></tr>
                <tr><td><?php echo Luya::t('admin', 'layout_debug_app_language'); ?>:</td><td><?php echo Yii::$app->language; ?></td></tr>
                <tr><td><?php echo Luya::t('admin', 'layout_debug_luya_language'); ?>:</td><td><?php echo Yii::$app->luyaLanguage; ?></td></tr>
                <tr><td><?php echo Luya::t('admin', 'layout_debug_yii_timezone'); ?>:</td><td><?php echo Yii::$app->timeZone; ?></td></tr>
                <tr><td><?php echo Luya::t('admin', 'layout_debug_php_timezone'); ?>:</td><td><?php echo date_default_timezone_get(); ?></td></tr>
                <tr><td><?php echo Luya::t('admin', 'layout_debug_php_ini_memory_limit'); ?>:</td><td><?php echo ini_get('memory_limit'); ?></td></tr>
                <tr><td><?php echo Luya::t('admin', 'layout_debug_php_ini_max_exec'); ?>:</td><td><?php echo ini_get('max_execution_time'); ?></td></tr>
                <tr><td><?php echo Luya::t('admin', 'layout_debug_php_ini_post_max_size'); ?>:</td><td><?php echo ini_get('post_max_size'); ?></td></tr>
                <tr><td><?php echo Luya::t('admin', 'layout_debug_php_ini_upload_max_file'); ?>:</td><td><?php echo ini_get('upload_max_filesize'); ?></td></tr>
            </tbody>
        </table>
    </div>
    <!-- /debug-container -->

    <!-- useronline-container -->
    <div ng-show="showOnlineContainer" class="info-container">
        <table class="table table-inverse">
            <thead>
            <tr>
                <th></th>
                <th><?php echo Luya::t('admin', 'layout_useronline_name'); ?></th>
                <th><?php echo Luya::t('admin', 'layout_useronline_mail'); ?></th>
                <td></td>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="row in notify" ng-class="{ 'green lighten-3' : row.is_active, 'red lighten-3' : !row.is_active }">
                <td>
                    <i class="fa fa-power-off text-success" ng-show="row.is_active"></i>
                    <i class="fa fa-power-off text-danger" ng-show="!row.is_active"></i>
                </td>
                <td>{{row.firstname}} {{row.lastname}}</td>
                <td>{{row.email}}</td>
                <td>
                    <small ng-hide="row.is_active">
                        <b><?php echo Luya::t('admin', 'layout_useronline_inactivesince'); ?></b><br />
                        {{row.inactive_since}}
                    </small>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- /useronline-container -->

    <!-- /navbar container -->

    <!-- LUYA CONTAINER -->
    <div class="luya-container module-{{currentItem.moduleId}}" ui-view></div>
    <!-- /LUYA CONTAINER -->

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>