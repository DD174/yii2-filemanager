<?php

namespace pendalf89\filemanager\assets;

use yii\web\AssetBundle;

class UploadmanagerAsset extends AssetBundle
{
    public $sourcePath = '@vendor/pendalf89/yii2-filemanager/assets/source';
    public $js = [
        'js/uploadmanager.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
