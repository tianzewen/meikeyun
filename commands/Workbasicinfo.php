<?php

namespace app\commands;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "workbasicinfo".
 *
 * @property string $makerid
 * @property string $id
 * @property string $image
 * @property string $title
 * @property string $tripe
 */
class Workbasicinfo extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'workbasicinfo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['makerid', 'id', 'title'], 'required'],
            [['makerid', 'id'], 'integer'],
            [['image'], 'string', 'max' => 120],
            [['title'], 'string', 'max' => 12],
            [['tag'], 'string', 'max' => 60],
            [['tripe'], 'string', 'max' => 300],
            [['id'], 'unique'],
            [['imglist'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'makerid' => '魅客ID',
            'id' => 'ID',
            'image' => '图片',
            'title' => '标题',
            'tag' => '标签',
            'tripe' => '简介',
        ];
    }
}
