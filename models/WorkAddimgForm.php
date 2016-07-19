<?php

namespace app\models;

use yii\base\Model;
use Yii;

use yii\data\Pagination;
use app\commands\Workbasicinfo;
use app\helpers\Tool;
use app\alidayu\Alidayu;

class WorkAddimgForm extends Model
{
    //public $id;
	private $work;
	
	public $id;
	public $makerid;
    public $imglist;

    public function rules()
    {
        return [
			[['id'], 'exist', 'targetClass' => 'app\commands\Workbasicinfo', 'message' => '作品不存在'],
            [['makerid'], 'integer'],
            [['imglist'], 'string', 'max' => 1000],
        ];
    }
	
	//add
	public function addWorkimg(){
		$imglist_new = array();
		
		foreach($this->imglist as $key => $value )
		{
			$this->imglist[$key]['name'] = explode(".", $this->imglist[$key]['name']);
			$this->imglist[$key]['name'] = $this->imglist[$key]['name'][1];
			$image_path = '../uploadfile/work_imglist/'.Tool::timeToMillisecond(microtime()).'.'.$this->imglist[$key]['name'];
			if(Tool::uploadimage( $this->imglist[$key], $image_path ) !== true){
				//删除图片
				foreach($imglist_new as $key => $value){
					@unlink($value['path']);
				}
				return Tool::return_json( false, 'image', '图片保存失败' );
			}
			$imglist_new[$key]['order'] = $key;
			$imglist_new[$key]['path'] = $image_path;
		}
		$this->imglist = JSON_encode($imglist_new);
		
		$work = new Workbasicinfo();
		$work = Workbasicinfo::findOne(['id' => $this->id, 'makerid' => $this->makerid]);
		$work->imglist = $this->imglist;
		
		if( $work->update() ){
			return Tool::return_json( true );
		}
		
		return Tool::return_json( true, 'database', '未知错误保存失败' );
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'makerid' => '魅客ID',
            'id' => 'ID',
            'imglist' => '图片列表',
        ];
    }
}