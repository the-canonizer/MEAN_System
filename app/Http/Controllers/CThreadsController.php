<?php
namespace App\Http\Controllers;

use DB;
use App\CThread;
use App\Model\Camp;
use App\Model\Topic;
use App\Model\Nickname;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;

use App\Mail\ForumThreadCreatedMail;
use App\Model\Support;
use App\CommonForumFunctions;

/**
 * CThreadsController Class Doc Comment
 *
 * @category Class
 * @package  MyPackage
 * @author   Ashutosh Kukreti <kukreti.ashutosh@gmail.com>
 * @license  GNU General Public License
 * @link     http://example.com
 */

class CThreadsController extends Controller
{
    /**
     * Auth middleware is applied to all except index and show
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($topicid, $topicname, $campnum)
    {
        $userNicknames = Nickname::topicNicknameUsed($topicid);

        if ((camp::where('camp_num', $campnum)->where('topic_num', $topicid)->value('camp_name')))
        {
            if (request('by') == 'me') {

                $threads = CThread::where('camp_id', $campnum)->
                                    where('topic_id', $topicid)->
                                    where('user_id', $userNicknames[0]->id)->
                                    latest()->paginate(10);

            }

            elseif (request('by') == 'participate') {
                $threads = CThread::join('post', 'thread.id', '=', 'post.c_thread_id' )->
                                    select('thread.*')->
                                    where('camp_id', $campnum)->
                                    where('topic_id', $topicid)->
                                    where('post.user_id', $userNicknames[0]->id)->
                                    latest()->paginate(10);
            }

            else {
                $threads = CThread::where('camp_id', $campnum)->
                                    where('topic_id', $topicid)->
                                    latest()->paginate(10);
            }
        }
        else {
            return (
                'Validation Error!!!!'.
                'Topic ID is not related to Camp.'
            );
        }

        $topic = getArray($topicid, $topicname, $campnum);

        $camp  = Camp::getLiveCamp($topicid,$campnum);

        return view(
            'threads.index',
            $topic,
            [
                'threads'          => $threads,
                // Return the name of the camp to index View
                'campname'         => camp::where('camp_num', $campnum)
                                                    ->where('topic_num', $topicid)
                                                    ->value('camp_name'),
                // Return the name of the Topic to index View
                'topicGeneralName' => Topic::where('topic_num', $topicid)
                                             ->orderBy('go_live_time', 'desc')
                                             ->first()->topic_name,
                'parentcamp'       => Camp::campNameWithAncestors($camp,''),
            ],
            compact('threads')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function topicindex($topicid, $topicname) {

        if (Topic::find($topicid)->topic_name){
            $threads = CThread::where('topic_id', $topicid)->latest()->get();
        }
        else {
            return (
                'Validation Error!!!!'.
                'Either Topic ID is not related to Camp or Invalid Topic Name.'
            );
        }

        $campnum = 1;

        $topic = getArray($topicid, $topicname, 1);

        return view(
            'threads.index',
            $topic,
            [
                'threads'          => $threads,
                // Return the name of the camp to index View
                'campname'         => camp::where('camp_num', $campnum)
                                            ->where('topic_num', $topicid)
                                            ->value('camp_name'),
                // Return the name of the Topic to index View
                'topicGeneralName' => Topic::find($topicid)->topic_name
            ],
            compact('threads')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($topicid, $topicname, $campnum)
    {

        $userNicknames = Nickname::topicNicknameUsed($topicid);

        $topic = getArray($topicid, $topicname, $campnum);

        $topicGeneralName = Topic::where('topic_num', $topicid)
                                     ->orderBy('go_live_time', 'desc')
                                     ->first()->topic_name;

        return view(
            'threads.create',
            $topic,
            compact('threads', 'userNicknames', 'topicGeneralName')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @parameter \Illuminate\Http\Request  $request
     * @return    \Illuminate\Http\Response
     */
    public function store(Request $request, $topicid, $topicname, $campnum)
    {
        //Validate the request for Error Handling

        $this->validate(
            $request, [
                'title'    => 'required|unique:thread|max:100',
                'nick_name' => 'required'
                //'body'     => 'required',
            ]
        );

        $thread = CThread::create(
            [
            'user_id'  => request('nick_name'),
            'title'    => request('title'),
            'body'     => request('title'),
            'camp_id'  => $campnum,
            'topic_id' => $topicid
            ]
        );

        // Return Url after creating thread Successfully
        $return_url = 'forum/'.$topicid.'-'.$topicname.'/'.$campnum.'/threads';

        CommonForumFunctions::sendEmailToSupportersForumThread($topicid, $campnum,
                              $return_url,request('title'), request('nick_name'), $topicname);

        return redirect($return_url)->with('success', 'Thread Created Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @parameter $CThread
     * @return    \Illuminate\Http\Response
     */
    public function show($topicid, $topicname, $campnum, $CThread)
    {
        $topic = getArray($topicid, $topicname, $campnum);

        return view(
            'threads.show',
             $topic, [
                'userNicknames' => (auth()->check()) ? Nickname::topicNicknameUsed($topicid) : array(),
                'threads' => CThread::findOrFail($CThread),
                'replies' => CThread::findOrFail($CThread)
                                            ->replies()
                                            ->paginate(10),
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @parameter \App\CThread  $CThread
     * @return    \Illuminate\Http\Response
     */
    public function edit(CThread $CThread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @parameter \Illuminate\Http\Request  $request
     * @parameter \App\CThread  $CThread
     * @return    \Illuminate\Http\Response
     */
    public function update(Request $request, CThread $CThread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @parameter \App\CThread  $CThread
     * @return    \Illuminate\Http\Response
     */
    public function destroy(CThread $CThread)
    {
        //
    }
}

/**
 * Return the array to the respective callers to save time
 *
 * @parameter integer  $topicid
 */
function getArray($topicid, $topicname, $campnum)
{
    return array(
        'topicname' => $topicid.'-'.$topicname,
        'campnum'   => $campnum,
    );
}


?>
