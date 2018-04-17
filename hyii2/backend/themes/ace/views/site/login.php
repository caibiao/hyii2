<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '登录';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="main-container">
	<div class="main-content">
		<div class="row">
			<div class="col-sm-10 col-sm-offset-1">
				<div class="login-container">
					<div class="center">
						<h1>
							<i class="icon-leaf green"></i>
							<span class="red">Hyii2</span>
							<span class="white">后台管理系统</span>
						</h1>
						<h4 class="blue">&copy; Yii中文网</h4>
					</div>

					<div class="space-6"></div>

					<div class="position-relative">
						<div id="login-box" class="login-box visible widget-box no-border">
							<div class="widget-body">
								<div class="widget-main">
									<h4 class="header blue lighter bigger">
										<i class="icon-coffee green"></i> hyii2后台管理系统
									</h4>

									<div class="space-6"></div>

									<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
    									<fieldset>
                                            <?= $form->field($model, 'username',[
                                                'inputOptions' => [
                                                    'placeholder' => '请输入账户',               
                                                ],
                                                'inputTemplate' => '<label class="block clearfix"><span class="block input-icon input-icon-right">{input}<i class="icon-user"></i></span></label>',
                                            ])->label(false) ?>
                                            <?= $form->field($model, 'password',[
                                                'inputOptions' => [
                                                    'placeholder' => '请输入密码',               
                                                ],
                                                'inputTemplate' => '<label class="block clearfix"><span class="block input-icon input-icon-right">{input}<i class="icon-lock"></i></span></label>',
                                            ])->passwordInput()->label(false) ?>
                                            <div class="space"></div>
                                            <div class="clearfix">
                                                <label class="inline">
                                                    <input type="checkbox" class="ace" />
                                                    <span class="lbl">记住我</span>
                                                </label>
                                    
                                                <button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
                                                    <i class="icon-key"></i>登录
                                                </button>
                                            </div>                                    
                                            <div class="space-4"></div>
                                        </fieldset>
                                    <?php ActiveForm::end(); ?>

									<div class="social-or-login center">
										<span class="bigger-110">第三方登录</span>
									</div>

									<div class="social-login center">
										<a class="btn btn-primary">
											<i class="icon-facebook"></i>
										</a>

										<a class="btn btn-info">
											<i class="icon-twitter"></i>
										</a>

										<a class="btn btn-danger">
											<i class="icon-google-plus"></i>
										</a>
									</div>
								</div><!-- /widget-main -->
							</div><!-- /widget-body -->
						</div><!-- /login-box -->
					</div><!-- /position-relative -->
				</div>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div>
</div><!-- /.main-container -->
		
	
  