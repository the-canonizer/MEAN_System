@extends('layouts.app')
@section('content')

<div class="camp top-head">
    <h3><b>Topic:</b>  {{ $topic->title}}</h3>
	<h3><b>Camp:</b> {!! $parentcamp !!}</h3>  
     
</div>


<div class="page-titlePnl">
    <h1 class="page-title">Camp History</h1>
</div> 

@if(Session::has('error'))
<div class="alert alert-danger">
    <strong>Error! </strong>{{ Session::get('error')}}    
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success">
    <strong>Success! </strong>{{ Session::get('success')}} {{ to_local_time(Session::get('go_live_time')) }}   
</div>
@endif


<div class="right-whitePnl">
<div class="row">
    <div class="col-sm-12">
        <div class="notifySignPNL">
            <div class="col-sm-2">
                <div class="red-circle"></div>
                <div class="circle-txt">Objected</div>
            </div>
            <div class="col-sm-2">
                <div class="green-circle"></div>
                <div class="circle-txt">Live</div>
            </div>
            <div class="col-sm-2">
                <div class="yellow-circle"></div>
                <div class="circle-txt">Not Live</div>
            </div>
			<div class="col-sm-2">
                <div class="yellow-circle" style="background-color:#1514ed"></div>
                <div class="circle-txt">Old</div>
            </div>
        </div>
    </div>
</div>
<div>
    <div class="col-sm-12 margin-btm-2">
        <form action="{{ route('camp.save')}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="topic_num" name="topic_num" value="{{ $topic->topic_num }}">
            
               
			   <?php if(!empty($camps)) { 
			            $currentLive = 0;
						$currentTime = time();
			            foreach($camps as $key=>$data) { 
						   
						   if($data->objector_nick_id !== NULL)
							   $bgcolor ="rgba(255, 0, 0, 0.5);"; //red
						   else if($currentTime < $data->go_live_time && $currentTime >= $data->submit_time) {
							   $bgcolor ="rgba(255, 255, 0, 0.5);"; //yellow
						   }   
						   else if($currentLive!=1 && $currentTime >= $data->go_live_time) {
							   $currentLive = 1;
							   $bgcolor ="rgba(0, 128, 0, 0.5);"; // green
						   } else {
							   $bgcolor ="#4e4ef3;"; //blue
						   }	   
						
			        	
			    ?>
			    <div class="form-group CmpHistoryPnl" style="background-color:{{ $bgcolor }}">
                <div>
                  
				  <b>Camp Name :</b> {{ $data->camp_name }} <br/>
				  <b>Keyword :</b> {{ $data->key_words }} <br/>
				  <b>Note :</b> {{ $data->note }} <br/>
				  
				  <b>URL :</b> {{ $data->camp_about_url }} <br/>
				  <b>Submitter Nickname :</b> {{ isset($data->submitternickname->nick_name) ? $data->submitternickname->nick_name : 'N/A' }} <br/>
				  <b>Submitted on :</b> {{ to_local_time($data->submit_time) }} <br/>
				  <b>Go live Time :</b> {{ to_local_time($data->go_live_time)}} <br/>
                 @if($data->objector_nick_id !=null)
				  <b>Object Reason :</b> {{ $data->object_reason}} <br/>	
                  <b>Objector Nickname :</b> {{ $data->objectornickname->nick_name }} <br/> 			  
                 @endif 	 				 
               </div>    
               <div class="CmpHistoryPnl-footer">
				  <?php if($currentTime < $data->go_live_time && $currentTime >= $data->submit_time) { ?> 
				  <a id="object" class="btn btn-historysmt" href="<?php echo url('manage/camp/'.$data->id.'-objection');?>">Object</a>
                  <?php } ?>
				  <a id="update" class="btn btn-historysmt" href="<?php echo url('manage/camp/'.$data->id);?>">Submit Camp Update Based On This</a>				  
			      <a id="version" class="btn btn-historysmt" href="<?php echo url('topic/'.$data->topic_num.'/'.$data->camp_num.'?asof=bydate&asofdate='.date('Y/m/d H:i:s',$data->go_live_time));?>">View This Version</a>
				 
				</div> 	
			   </div>
			   <?php } 
			    } else {
					
					echo " No camp history available.";
				}
			   ?>
        </form>
</div>
</div>
</div>  <!-- /.right-whitePnl-->
    

    <script>
        $(document).ready(function () {
            $("#datepicker").datepicker({
                changeMonth: true,
                changeYear: true
            });
        })
    </script>


    @endsection

