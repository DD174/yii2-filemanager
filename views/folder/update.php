<?php

use backend\components\Html;
use yii\widgets\ActiveForm;
use pendalf89\filemanager\assets\FilemanagerAsset;
use pendalf89\filemanager\Module;

/**
 * @var yii\web\View $this
 * @var pendalf89\filemanager\models\Folder $model
 * @var yii\widgets\ActiveForm $form
 * @var string $strictThumb
 */

$bundle = FilemanagerAsset::register($this);
?>
<?php $form = ActiveForm::begin([
    'action' => ['folder/update', 'id' => $model->id],
    'enableClientValidation' => false,
    'options' => ['id' => 'control-form'],
]); ?>

<?= $form->field($model, 'name') ?>

<?= $form->field($model, 'parent_id')->dropDownList(
    \pendalf89\filemanager\models\Folder::getSelectItems(null),
    ['prompt' => Module::t('main', 'Root directory')]
    ) ?>

<?= Html::submitButton(Yii::t('app', 'btn_save'), ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>
