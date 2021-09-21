<?php

use yii\db\Migration;

/**
 * Таблица "Загруженные файлы".
 */
class m210909_141724_create_table_uploaded_file extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%uploaded_file}}', [
            'id' => $this->primaryKey(),
            'path' => $this->string()->notNull()->comment('Путь к файлу'),
            'original_name' => $this->string()->notNull()->comment('Оригинальное наименование'),
            'uploaded_at' => $this->timestamp()->notNull()->comment('Дата загрузки'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%uploaded_file}}');
    }
}
