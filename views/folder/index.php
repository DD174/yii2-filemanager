<?php

use yii\helpers\Url;
use yii\widgets\Menu;
use pendalf89\filemanager\Module;

/**
 * @var yii\web\View $this
 * @var array $items
 */
?>
<div class="filemanager__menu-folder" data-href="<?= Url::toRoute(['folder/delete']) ?>" data-message="<?= Module::t('main', 'To delete a folder?') ?>">
    <?= Menu::widget([
        'encodeLabels' => false,
        'items' => $items,
        'itemOptions' => ['class' => 'filemanager__menu-folder-item fa fa-folder-o', 'style' => 'display: block;'],
        'activeCssClass' => 'filemanager__menu-folder-item--active',
    ]) ?>
</div>
