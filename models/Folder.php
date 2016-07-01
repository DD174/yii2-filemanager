<?php

namespace pendalf89\filemanager\models;

use Yii;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\imagine\Image;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Inflector;
use pendalf89\filemanager\Module;
use pendalf89\filemanager\models\Owners;
use Imagine\Image\ImageInterface;

/**
 * This is the model class for table "filemanager_folder".
 *
 * @property integer $id
 * @property integer|null $parent_id
 * @property string $name
 *
 * relations
 * @property Mediafile[] $mediafiles
 */
class Folder extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'filemanager_folder';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Module::t('main', 'Folder'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (Folder::findOne(['parent_id' => $this->id])) {
            $this->addError('ALL', Module::t('main', 'You can not delete if there are sub-categories'));

            return false;
        }

        foreach (Mediafile::findAll(['folder_id' => $this->id]) as $mediafile) {
            Yii::$app->controller->run('/filemanager/file/delete', ['id' => $mediafile->id]);
        }

        return parent::beforeDelete();
    }

    /**
     * @param $parentId
     * @return $this
     */
    public function getChildren($parentId)
    {
        return Folder::find()->andWhere(['parent_id' => $parentId]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMediafiles() {
        return $this->hasMany(Mediafile::className(), ['id' => 'folder_id']);
    }

    /**
     * @param null|integer $current
     * @return array
     * @throws \Exception
     */
    public static function getMenu($current = null)
    {
        return self::getMenuItemRecursive(null, $current);
//        $items = self::getDb()->cache(function() use ($current) {
            $items = [];
            foreach (ArrayHelper::map(self::find()->all(), 'id', 'name') as $key => $name ) {
                $items[] = [
                    'url' => '?' . Html::getInputName(new MediafileSearch(), 'folder_id') . '=' . $key,
                    'label' => $name,
                    'active' => $current ? $current == $key : null,
                    'options' => ['data-folder-id' => $key],
                ];
            }

            return $items;
//        }, 1, new TagDependency(['tags' => self::className()]));

//        return $items ?: [];
    }

    public static function getMenuItemRecursive($parentId, $current = null)
    {
        $items = [];
        foreach (self::findAll(['parent_id' => $parentId]) as $folder ) {
            $items[] = [
                'url' => '?' . Html::getInputName(new MediafileSearch(), 'folder_id') . '=' . $folder->id,
                'label' => $folder->name . ' '
                    . Html::tag(
                        'small',
                        '',
                        [
                            'class' => 'glyphicon glyphicon-edit',
                            'role' => 'folder-edit',
                            'title' => Module::t('main', 'Edit folder'),
                            'data-href' => Url::toRoute(['folder/update', 'id' => $folder->id]),
                        ]
                    )
                    . ' '
                    . Html::tag(
                        'small',
                        '',
                        [
                            'class' => 'glyphicon glyphicon-trash',
                            'role' => 'folder-delete',
                            'data-id' => $folder->id,
                            'title' => Module::t('main', 'Delete the folder')
                        ]
                    ),
                'active' => $current ? $current == $folder->id : null,
                'options' => ['data-folder-id' => $folder->id],
                'items' => self::getMenuItemRecursive($folder->id, $current),
            ];
        }

        return $items;
    }

    /**
     * опции в виде дерева для списка
     * @param $parentId
     * @param int $level
     * @return array
     */
    public static function getSelectItems($parentId, &$level = 0)
    {
        $level++;
        $pref = str_repeat('-', $level);
        $items = [];
        foreach (self::findAll(['parent_id' => $parentId]) as $folder ) {
            $items[$folder->id] = $pref . $folder->name;
            $items = ArrayHelper::merge($items, self::getSelectItems($folder->id, $level));
        }
        $level--;

        return $items;
    }
}
