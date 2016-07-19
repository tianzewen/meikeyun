<?php

namespace app\commands;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "Worksinfo".
 *
 * @property string $makerid
 * @property string $id
 * @property string $image
 * @property string $title
 * @property string $tripe
 */
class Worksinfo extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'show_work';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['workid'], 'unique'],
            [['worktitle', 'workimg', 'worktag', 'worktripe', 'workimglist'], 'safe'],
            [['makerid'], 'integer'],
            [['makerphone', 'makername', 'makermask', 'makersex', 'makerbirthday'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'workid' => '作品',
        ];
    }
}
