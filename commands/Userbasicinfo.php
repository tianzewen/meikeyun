<?php

namespace app\commands;

use yii\db\ActiveRecord;

class Userbasicinfo extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'userbasicinfo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'password', 'phone'], 'required'],
            [['id'], 'integer'],
            [['birthday', 'sex'], 'safe'],
			[['name'], 'string', 'length'=>[1,12]],
            [['password', 'mask'], 'string', 'max' => 60],
            [['phone'], 'string', 'max' => 11],
            [['phone'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'password' => '密码',
            'phone' => '账号',
            'name' => '姓名',
            'mask' => '头像',
            'sex' => '性别',
            'birthday' => '出生日期',
        ];
    }
}