<?php

namespace app\models;

use yii\base\Model;
use Yii;

use yii\data\Pagination;
use app\commands\Workbasicinfo;
use app\helpers\Tool;
use app\alidayu\Alidayu;

class WorkAddForm extends Model
{
    //public $id;
	private $work;
	
	public $makerid;
    public $image;
    public $title;
    public $tag;
    public $tripe;

    public function rules()
    {
        return [
            [['makerid', 'title'], 'required'],
            [['makerid'], 'integer'],
            [['image'], 'string', 'max' => 120],
            [['title'], 'string', 'max' => 12],
            [['tag'], 'string', 'max' => 60],
            [['tripe'], 'string', 'max' => 300],
        ];
    }
	
	//add
	public function addWork(){
		$this->image['name'] = explode(".", $this->image['name']);
		$this->image['name'] = $this->image['name'][1];
		$image_path = '../uploadfile/work_title_image/'.Tool::timeToMillisecond(microtime()).'.'.$this->image['name'];
		if(Tool::uploadimage( $this->image, $image_path ) !== true){
			return Tool::return_json( false, 'image', '图片保存失败' );
		}
		//实例化Workbasicinfo的AR
		$work = new Workbasicinfo();
		$workid = Tool::timeToMillisecond(microtime());
		$work->setAttributes([
			'makerid' => $this->makerid,
			'id' => $workid,
			'image' => $image_path,
			'title' => $this->title,
			'tag' => $this->tag,
			'tripe' => $this->tripe,
		]);
		//添加作品信息
		if( $work->save() ){
			return Tool::return_json( true, 'workid', $workid );
		}
		//删除图片
		@unlink($image_path);
		return Tool::return_json( false, $work->getFirstErrors() );
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
            'tag' => '标签',
            'title' => '标题',
            'tripe' => '简介',
        ];
    }
}