<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Library\General;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use DB;
use App\Model\Camp;
use App\Model\Support;
use App\Model\Topic;
use App\Model\Statement;
use Illuminate\Database\Eloquent\Collection;

class Nickname extends Model {

    protected $table = 'nick_name';
    public $timestamps = false;

    public function camps() {
        return $this->hasMany('App\Model\Camp', 'nick_name_id', 'nick_name_id');
    }

    public function supports() {
        return $this->hasMany('App\Model\Support', 'nick_name_id', 'nick_name_id')->orderBy('support_order', 'ASC');
    }

    public static function personNickname() {

        $userid = Auth::user()->id;
        $encode = General::canon_encode($userid);

        return DB::table('nick_name')->select('id', 'nick_name')->where('owner_code', $encode)->orderBy('nick_name', 'ASC')->get();
    }

    public static function personNicknameArray() {

        $userNickname = array();
        $nicknames = self::personNickname();

        foreach ($nicknames as $nickname) {

            $userNickname[] = $nickname->id;
        }
        return $userNickname;
    }

    public function getSupportCampList() {

        $as_of_time = time();
        $as_of_clause = '';

        $namespace = isset($_REQUEST['namespace']) ? $_REQUEST['namespace'] : 1;

        if (isset($_REQUEST['asof']) && $_REQUEST['asof'] == 'review') {
            
        } else if (isset($_REQUEST['asof']) && $_REQUEST['asof'] == 'bydate') {
            $as_of_time = strtotime(date('Y-m-d H:i:s', strtotime($_REQUEST['asofdate'])));
            $as_of_clause = "and go_live_time < $as_of_time";
        } else {
            $as_of_clause = 'and go_live_time < ' . $as_of_time;
        }

        $sql = "select u.topic_num, u.camp_num, u.title,u.camp_name, p.support_order, p.delegate_nick_name_id from support p, 
        (select s.title,s.topic_num,s.camp_name,s.submit_time,s.go_live_time, s.camp_num from camp s,
            (select topic_num, camp_num, max(go_live_time) as camp_max_glt from camp
                where objector_nick_id is null $as_of_clause group by topic_num, camp_num) cz,
                (select t.topic_num, t.topic_name, t.namespace, t.go_live_time from topic t,
                    (select ts.topic_num, max(ts.go_live_time) as topic_max_glt from topic ts
                        where ts.namespace_id=$namespace and ts.objector_nick_id is null $as_of_clause group by ts.topic_num) tz
                            where t.namespace_id=$namespace and t.topic_num = tz.topic_num and t.go_live_time = tz.topic_max_glt) uz
                where s.topic_num = cz.topic_num and s.camp_num=cz.camp_num and s.go_live_time = cz.camp_max_glt and s.topic_num=uz.topic_num) u
        where u.topic_num = p.topic_num and ((u.camp_num = p.camp_num) or (u.camp_num = 1)) and p.nick_name_id = {$this->id} and
        (p.start < $as_of_time) and ((p.end = 0) or (p.end > $as_of_time)) and u.go_live_time < $as_of_time order by u.submit_time DESC";
        $results = DB::select($sql);
        $supports = [];
        foreach ($results as $rs) {
            $topic_num = $rs->topic_num;
            $camp_num = $rs->camp_num;
            $title = preg_replace('/[^A-Za-z0-9\-]/', '-', $rs->camp_name);
            $topic_id = $topic_num . "-" . $title;
            if ($rs->delegate_nick_name_id) {
                
            } else if ($camp_num == 1) {
                $supports[$topic_num]['camp_name'] = ($rs->camp_name != "") ? $rs->camp_name : $rs->title;

                $supports[$topic_num]['link'] = url('camp/history/' . $topic_num . '/' . $camp_num);
            } else {
                $supports[$topic_num]['array'][$rs->support_order][] = ['camp_name' => $rs->camp_name, 'camp_num' => $camp_num, 'link' => url('camp/history/' . $topic_num . '/' . $camp_num)];
            }
        }

        return $supports;
    }

    /* get user data based on owner_code */

    public function getUser() {

        $userId = \App\Library\General::canon_decode($this->owner_code);
        return \App\User::find($userId);
    }

    public static function getUserByNickName($nick_id) {

        $nickname = self::find($nick_id);

        $userId = \App\Library\General::canon_decode($nickname->owner_code);
        return \App\User::find($userId);
    }

    public static function getNickName($nick_id) {

        return $nickname = self::find($nick_id);
    }

    /* Enforce single nickname to be used */

    /* Return single nickname used in any activity for that topic and if no nickname used then it will return all user nicknames */

    public static function topicNicknameUsed($topic_num) {

        $personNicknameArray = self::personNicknameArray();
        $usedNickid = 0;
        $mysupports = Support::select('nick_name_id')->where('topic_num', $topic_num)->whereIn('nick_name_id', $personNicknameArray)->where('end', '=', 0)->groupBy('topic_num')->orderBy('support_order', 'ASC')->first();

        if (empty($mysupports)) {
            $mycamps = Camp::select('submitter_nick_id')->where('topic_num', $topic_num)->whereIn('submitter_nick_id', $personNicknameArray)->orderBy('submit_time', 'DESC')->first();

            if (empty($mycamps)) {
                $mystatement = Statement::select('submitter_nick_id')->where('topic_num', $topic_num)->whereIn('submitter_nick_id', $personNicknameArray)->orderBy('submit_time', 'DESC')->first();
                if (empty($mystatement)) {
                    $mytopic = Topic::select('submitter_nick_id')->where('topic_num', $topic_num)->whereIn('submitter_nick_id', $personNicknameArray)->orderBy('submit_time', 'DESC')->first();
                    if (empty($mytopic)) {

                        $mythread = \App\CThread::select('user_id')->where('topic_id', $topic_num)->whereIn('user_id', $personNicknameArray)->orderBy('created_at', 'DESC')->first();
                        if (!empty($mythread)) {
                            $usedNickid = $mythread->user_id;
                        }
                    } else {
                        $usedNickid = $mytopic->submitter_nick_id;
                    }
                } else {

                    $usedNickid = $mystatement->submitter_nick_id;
                }
            } else {
                $usedNickid = $mycamps->submitter_nick_id;
            }
        } else {
            $usedNickid = $mysupports->nick_name_id;
        }

        if ($usedNickid) {

            return self::where('id', '=', $usedNickid)->get();
        } else
            return self::personNickname();
    }

    public static function personNicknameIds() {
        if (Auth::check()) {
            $userid = Auth::user()->id;

            $encode = General::canon_encode($userid);

            return DB::table('nick_name')->where('owner_code', $encode)->orderBy('nick_name', 'ASC')->pluck('id')->toArray();
        }
        return [];
    }

    public static function getUserIDByNickName($nick_id) {

        $nickname = self::find($nick_id);
        if (!empty($nickname) && count($nickname) > 0) {
            $ownerCode = $nickname->owner_code;
            return $userId = \App\Library\General::canon_decode($ownerCode);
        }

        return null;
    }

}
