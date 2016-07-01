<?php
use dosamigos\fileupload\FileUploadUI;
use pendalf89\filemanager\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel pendalf89\filemanager\models\Mediafile */

\pendalf89\filemanager\assets\UploadmanagerAsset::register($this);
?>

<header id="header"><span class="glyphicon glyphicon-upload"></span> <?= Module::t('main', 'Upload manager') ?></header>

<div id="uploadmanager">
    <p><?= Html::a('← ' . Module::t('main', 'Back to file manager'), ['file/filemanager']) ?></p>
    <?= FileUploadUI::widget([
        'model' => $model,
        'attribute' => 'file',
        'clientOptions' => [
            'autoUpload'=> Yii::$app->getModule('filemanager')->autoUpload,
        ],
        'clientEvents' => [
            'fileuploadsubmit' => "function (e, data) {
            var tags = $('#filemanager-tagIds').val();
            data.formData = [];
            data.formData = [
                {name: 'folderId', value: $('#filemanager-folder-id').val() },
                {name: 'folderNewName', value: $('#filemanager-folder-name').val() },
            ];
            if (tags) {
                data.formData.push({name: 'tagIds', value: tags });
            }
            }",
        ],
        'url' => ['upload'],
        'gallery' => false,
        'formView' => '/file/_upload_form',
    ]) ?>
</div>
