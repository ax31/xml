<?php

namespace app\models;

/**
 * This is the model class for table "tag".
 *
 * @property int $file_id
 * @property string $tagname
 * @property int $number
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_id', 'tagname', 'number'], 'required'],
            [['file_id', 'number'], 'integer'],
            [['tagname'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'file_id' => 'File ID',
            'tagname' => 'Tagname',
            'number' => 'Number',
        ];
    }
}
