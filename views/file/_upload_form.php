<?php
/** @var \dosamigos\fileupload\FileUploadUI $this */
use pendalf89\filemanager\Module;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use pendalf89\filemanager\models\Tag;

$context = $this->context;
?>
    <!-- The file upload form used as target for the file upload widget -->
<?= Html::beginTag('div', $context->options); ?>
    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
    <div class="row fileupload-buttonbar">
        <div class="col-lg-7">
            <!-- The fileinput-button span is used to style the file input field as button -->
            <span class="btn btn-success fileinput-button">
                <i class="glyphicon glyphicon-plus"></i>
                <span><?= Yii::t('fileupload', 'Add files') ?>...</span>

                <?= $context->model instanceof \yii\base\Model && $context->attribute !== null
                    ? Html::activeFileInput($context->model, $context->attribute, $context->fieldOptions)
                    : Html::fileInput($context->name, $context->value, $context->fieldOptions);?>

            </span>
            <a class="btn btn-primary start">
                <i class="glyphicon glyphicon-upload"></i>
                <span><?= Yii::t('fileupload', 'Start upload') ?></span>
            </a>
            <a class="btn btn-warning cancel">
                <i class="glyphicon glyphicon-ban-circle"></i>
                <span><?= Yii::t('fileupload', 'Cancel upload') ?></span>
            </a>
            <a class="btn btn-danger delete">
                <i class="glyphicon glyphicon-trash"></i>
                <span><?= Yii::t('fileupload', 'Delete') ?></span>
            </a>
            <input type="checkbox" class="toggle">
            <!-- The global file processing state -->
            <span class="fileupload-process"></span>
        </div>
        <div class="col-lg-7">

        </div>
        <!-- The global progress state -->
        <div class="col-lg-5 fileupload-progress fade">
            <!-- The global progress bar -->
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
            </div>
            <!-- The extended global progress state -->
            <div class="progress-extended">&nbsp;</div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?= \kartik\select2\Select2::widget([
                'id' => 'filemanager-tagIds',
                'name' => 'tagIds',
                'maintainOrder' => true,
                'data' => ArrayHelper::map(Tag::find()->all(), 'id', 'name'),
                'options' => ['multiple' => true],
                'pluginOptions' => [
                    'tags' => true,
                    'maximumInputLength' => 10,
                    // нельзя создавать теги с числовым именем
                    'createTag' => new \yii\web\JsExpression("function (params) {
                    if (/^\d+$/.test(params.term)) {
                        return null;
                    }
                    return {
                      id: params.term,
                      text: params.term
                    };
                }"),
                ],
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 form-inline">
            <div class="form-group">
                <label class="control-label" for="filemanager-folder-id"><?= Module::t('main', 'Select a folder') ?></label>
                <?= Html::dropDownList(
                    'folder_id',
                    null,
                    \pendalf89\filemanager\models\Folder::getSelectItems(null),
                    [
                        'id' => 'filemanager-folder-id',
                        'prompt' => Module::t('main', 'Root directory'),
                        'class' => 'form-control',
                    ]
                ) ?>
                <?= Html::button('', ['class' => 'add-folder glyphicon glyphicon-plus btn btn-default btn-sm', 'title' => Module::t('main', 'Add a subfolder')]) ?>
                <?= Html::textInput('folder_name', '', ['id' => 'filemanager-folder-name', 'class' => 'form-control hidden']) ?>
            </div>
        </div>
    </div>
    <!-- The table listing the files available for upload/download -->
    <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
<?= Html::endTag('div');?>
