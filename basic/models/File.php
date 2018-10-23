<?php

namespace app\models;

/**
 * This is the model class for table "file".
 *
 * @property int $id
 * @property string $date_upload
 * @property string $title
 */
class File extends \yii\db\ActiveRecord
{
    public $xml;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_upload'], 'safe'],
            [['title', 'xml'], 'required'],
            [['title'], 'string'],
            [['xml'], 'file', 'extensions' => 'xml']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date_upload' => 'Date Upload',
            'title' => 'Title',
            'xml' => 'XML Файл'
        ];
    }

    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['file_id' => 'id'])->orderBy('tagname');
    }
}
