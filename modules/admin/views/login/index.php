<div class="container">

    <div class="row">

        <div class="col-md-6 col-md-offset-3">

            <div class="jumbotron m-t-3 p-t-2 p-b-2 o-b-inverse-6">
                <h1><?php echo \admin\Module::t('login_pre_title', ['title' => Yii::$app->siteTitle]); ?></h1>
                <p class="lead">Please login with your credentials below.</p>
                <hr class="m-y-2">

                <!-- Normal login -->
                <form class="form clearfix" method="post" id="loginForm">
                    <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->csrfToken; ?>" />
                    <div class="form-group">
                        <label class="sr-only" for="email"><?php echo \admin\Module::t('login_mail'); ?></label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <input class="form-control" id="email" name="login[email]" type="email" placeholder="<?php echo \admin\Module::t('login_mail'); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="password"><?php echo \admin\Module::t('login_password'); ?></label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-key"></i>
                            </div>
                            <input class="form-control" id="password" name="login[password]" type="password" placeholder="<?php echo \admin\Module::t('login_password'); ?>">
                        </div>
                    </div>
                    <button class="btn btn-lg btn-success pull-right" type="submit">
                        <span class="submit-icon">
                            <?php echo \admin\Module::t('login_btn_login'); ?>
                            <i class="fa fa-sign-in"></i>
                        </span>
                        <i class="fa fa-cog fa-spin submit-spinner" style="display: none;"></i>
                    </button>
                </form>
                <!-- /Normal login -->

                <!-- /Secure form -->
                <form class="form clearfix" method="post" id="secureForm" style="display: none;">
                    <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->csrfToken; ?>" />
                    <div class="form-group">
                        <label class="sr-only" for="secure_form"><?php echo \admin\Module::t('login_securetoken'); ?></label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-key"></i>
                            </div>
                            <input class="form-control" name="secure_token" id="secure_token" type="text" placeholder="<?php echo \admin\Module::t('login_securetoken'); ?>">
                        </div>
                        <small><?php echo \admin\Module::t('login_securetoken_info'); ?></small>
                    </div>
                    <button class="btn btn-lg btn-success pull-right" type="submit">
                        <span class="submit-icon">
                            <?php echo \admin\Module::t('button_send'); ?>
                            <i class="fa fa-sign-in"></i>
                        </span>
                        <i class="fa fa-cog fa-spin submit-spinner" style="display: none;"></i>
                    </button>
                </form>
                <!-- /Secure form -->

            </div>

            <div id="errorsContainer" class="alert alert-danger" role="alert" style="display:none;"></div>

        </div>
    </div>

</div>