<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Model\Camp;
use Illuminate\Support\Facades\Auth;

class Topic extends Model {

    protected $table = 'topic';
    public $timestamps = false;

    public static function boot() {
        static::created(function ($model) {
            // while creating topic for very first time
            // this will not run when updating 
            if ($model->topic_num == '' || $model->topic_num == null) {
                $nextTopicNum = DB::table('topic')->max('topic_num');
                $nextTopicNum++;
                $model->topic_num = $nextTopicNum;
                $model->update();

                //create agreement 
                $camp = new Camp();
                $camp->topic_num = $model->topic_num;
                $camp->parent_camp_num = null;
                $camp->key_words ='';
                $camp->language= $model->language;
                $camp->note = $model->note;
                $camp->submit_time = time();
                $camp->submitter_nick_id = Auth::user()->id;
                $camp->go_live_time = $model->go_live_time;
                $camp->title = $model->topic_name;
                $camp->camp_name = Camp::AGREEMENT_CAMP;
                
                $camp->save();
                
            }
        });
        parent::boot();
    }

    public function camps() {
        return $this->hasMany('App\Model\Camp', 'topic_num', 'topic_num');
    }
    
    public function camps1() {
        return $this->hasMany('App\Model\Camp', 'topic_num', 'topic_num')->groupBy('camp_num');
    }
	public function topic() {
        return $this->hasOne('App\Model\Topic', 'topic_num', 'topic_num');
    }
	public function supports() {
        return $this->hasMany('App\Model\Support', 'topic_num', 'topic_num')->orderBy('support_order','ASC');
    }
	public function objectornickname() {
        return $this->hasOne('App\Model\Nickname', 'id', 'objector_nick_id');
    }
	public function submitternickname() {
        return $this->hasOne('App\Model\Nickname', 'id', 'submitter_nick_id');
    }
	
	public function scopeGetsupports($query,$topic_support_id,$userNickname=null) {
		
		return $supports = SupportInstance::where('topic_support_id',$topic_support_id)->groupBy('camp_num')->orderBy('support_order','ASC')->get();
		
	}
	
	public static function getHistory($topicnum,$filter=array()) {
		
		return self::where('topic_num',$topicnum)->latest('submit_time')->get();
	}

}
