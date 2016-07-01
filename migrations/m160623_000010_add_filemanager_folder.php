<?php

use yii\db\Migration;

class m160623_000010_add_filemanager_folder extends Migration
{
    public function up()
    {
        $this->createTable('filemanager_folder', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'name' => $this->string(100)->notNull(),
        ]);

        $this->addForeignKey(
            'filemanager_folder_parent_id__folder_id',
            'filemanager_folder',
            'parent_id',
            'filemanager_folder',
            'id',
            'CASCADE',
            'CASCADE'
            );

        $this->addColumn('filemanager_mediafile', 'folder_id', $this->integer());

        $this->addForeignKey(
            'filemanager_mediafile_folder_id__folder_id',
            'filemanager_mediafile',
            'folder_id',
            'filemanager_folder',
            'id',
            'SET NULL',
            'SET NULL'
        );
    }

    public function down()
    {
        $this->dropForeignKey('filemanager_mediafile_folder_id__folder_id', 'filemanager_mediafile');
        $this->dropForeignKey('filemanager_folder_parent_id__folder_id', 'filemanager_folder');
        $this->dropTable('filemanager_folder');
    }
}
