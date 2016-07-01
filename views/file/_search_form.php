<?php

use pendalf89\filemanager\Module;
use pendalf89\filemanager\models\Tag;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
?>
<?php $form = ActiveForm::begin(['action' => '?', 'method' => 'get']) ?>
<div class="row">

	<div class="col-xs-3 col-md-3">
		<?= $form->field($model, 'folder_id')->dropDownList(
			\pendalf89\filemanager\models\Folder::getSelectItems(null),
			[
				'id' => 'filemanager-folder-id',
				'prompt' => Module::t('main', 'Root directory'),
				'class' => 'form-control',
			]
		)->label(false) ?>
	</div>

	<div class="col-xs-4 col-md-4">
		<?= $form->field($model, 'tagIds')->widget(\kartik\select2\Select2::className(), [
			'maintainOrder' => true,
			'data' => ArrayHelper::map(Tag::find()->all(), 'id', 'name'),
			'options' => ['multiple' => true],
		])->label(false); ?>
	</div>

	<div class="col-xs-2 col-md-2">
		<?= Html::submitButton(Module::t('main', 'Search'), ['class' => 'btn btn-primary']) ?>
	</div>

</div>
<?php ActiveForm::end() ?>
