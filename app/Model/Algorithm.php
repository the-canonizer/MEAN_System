<?php

namespace App\Model;
use DB;
use Illuminate\Support\Facades\Cache;

class Algorithm{

    /**
    @return all the available algorithm list
    */
    public static function getList(){
        return array(
            'blind_popularity'=>'One Person One Vote',
            'mind_experts'=>'Mind Experts',
            'computer_science_experts'=>'Computer Science Experts',
            'PhD'=>'Ph.D.',
            'christian'=>'Christian',
            'secular'=>'Secular / Non Religious',
            'mormon'=>'Mormon',
            'uu'=>'Universal Unitarian',
            'atheist'=>'Atheist',
            'transhumanist'=>'Transhumanist'
        );
    }
    
    /**
        Returns camp_count
        @nick_name_id , $condition
    */
    public static function camp_count($nick_name_id,$condition){
    
        $as_of_time = time();
        $cacheWithTime = false; 
        if(isset($_REQUEST['asof']) && $_REQUEST['asof'] == 'bydate'){
            if(isset($_REQUEST['asofdate']) && !empty($_REQUEST['asofdate'])){
                $as_of_time = strtotime($_REQUEST['asofdate']);
                $cacheWithTime = true;
            }
        }

        $sql = "select count(*) as countTotal from support where nick_name_id = $nick_name_id and (" .$condition.")";
        $sql2 ="and ((start < $as_of_time) and ((end = 0) or (end > $as_of_time)))
         ";
        /* Cache applied to avoid repeated queries in recursion */
        if($cacheWithTime){
            $result = Cache::remember("$sql $sql2", 2, function () use($sql,$sql2) {
                return DB::select("$sql $sql2");
            });
            return isset($result[0]->countTotal) ? $result[0]->countTotal : 0;
        }else{
            $result = Cache::remember("$sql", 1, function () use($sql,$sql2) {
                return DB::select("$sql $sql2");
            });
            return isset($result[0]->countTotal) ? $result[0]->countTotal : 0;
        }
    }

    public static function blind_popularity($nick_name_id = null){
        return 1;
    }

    public static function mind_experts(){
        
        return 1;
    }

     public static function special_mind_experts(){
        return 1;
    }

    public static function computer_science_experts(){
        return 1;
    }

    /**
        Transhumanist - Algorithm
    */

    public static function transhumanist($nick_name_id){
         $condition = '(topic_num = 40 and camp_num = 2) or ' .
			     '(topic_num = 41 and camp_num = 2) or ' .
			     '(topic_num = 42 and camp_num = 2) or ' .
			     '(topic_num = 42 and camp_num = 4) or ' .
			     '(topic_num = 43 and camp_num = 2) or ' .
			     '(topic_num = 44 and camp_num = 3) or ' .
			     '(topic_num = 45 and camp_num = 2) or ' .
			     '(topic_num = 46 and camp_num = 2) or ' .
			     '(topic_num = 47 and camp_num = 2) or ' .
			     '(topic_num = 48 and camp_num = 2) or ' .
			     '(topic_num = 48 and camp_num = 3) or ' .
			     '(topic_num = 49 and camp_num = 2) ';

        return self::camp_count($nick_name_id,$condition);
    }

    public static function atheist($nick_name_id){
        $condition = '(topic_num = 54 and camp_num = 2) or ' .
				'(topic_num = 2 and camp_num = 2) or ' .
				'(topic_num = 2 and camp_num = 4) or ' .
				'(topic_num = 2 and camp_num = 5)';
        return self::camp_count($nick_name_id,$condition);
    }

    public static function uu($nick_name_id){
        $condition = '(topic_num = 54 and camp_num = 15)';
        return self::camp_count($nick_name_id,$condition);
    }

    public static function secular($nick_name_id){
        $condition = '(topic_num = 54 and camp_num = 3)';
        return self::camp_count($nick_name_id,$condition);
    }

    public static function mormon($nick_name_id){
        $condition = '(topic_num = 54 and camp_num = 7) or ' .
				'(topic_num = 54 and camp_num = 8) or ' .
				'(topic_num = 54 and camp_num = 10) or ' .
				'(topic_num = 54 and camp_num = 11)';
        return self::camp_count($nick_name_id,$condition);
    }

    public static function christian($nick_name_id){
        $condition = '(topic_num = 54 and camp_num = 4) or ' .
			     '(topic_num = 54 and camp_num = 5) or ' .
			     '(topic_num = 54 and camp_num = 6) or ' .
			     '(topic_num = 54 and camp_num = 7) or ' .
			     '(topic_num = 54 and camp_num = 8) or ' .
			     '(topic_num = 54 and camp_num = 9) or ' .
			     '(topic_num = 54 and camp_num = 10) or ' .
			     '(topic_num = 54 and camp_num = 11) or ' .
			     '(topic_num = 54 and camp_num = 18)';
        return self::camp_count($nick_name_id,$condition);
    }

    public static function PhD($nick_name_id){
        $condition = '(topic_num = 55 and camp_num =  5) or ' .
						   '(topic_num = 55 and camp_num = 10) or ' .
						   '(topic_num = 55 and camp_num = 11) or ' .
						   '(topic_num = 55 and camp_num = 12) or ' .
						   '(topic_num = 55 and camp_num = 14) or ' .
						   '(topic_num = 55 and camp_num = 15) or ' .
						   '(topic_num = 55 and camp_num = 17)';
        return self::camp_count($nick_name_id,$condition);
    }

    
}