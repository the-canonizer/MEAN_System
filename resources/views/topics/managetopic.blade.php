@extends('layouts.app')
@section('content')
<div class="page-titlePnl">
    <h1 class="page-title">
	 <?php if($objection=="objection") { ?> 
	Object to this proposed update
	 <?php } else { ?>
	Topic update
	 <?php } ?>
	</h1>
</div> 

@if(Session::has('error'))
<div class="alert alert-danger">
    <strong>Error! </strong>{{ Session::get('error')}}    
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success">
    <strong>Success! </strong>{{ Session::get('success')}}    
</div>
@endif


<div class="right-whitePnl">
   <div class="row col-sm-12 justify-content-between">
    <div class="col-sm-6 margin-btm-2">
        <form action="{{ url('/topic')}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" id="topic_num" name="topic_num" value="{{ $topic->topic_num }}">
			<input type="hidden" id="submitter" name="submitter" value="{{ $topic->submitter_nick_id }}">
			<?php if($objection=="objection") { ?>
			 <input type="hidden" id="objection" name="objection" value="1">
			 <input type="hidden" id="objection_id" name="objection_id" value="{{ $topic->id}}">
			<?php } ?>
			
			<div class="form-group">
                <label for="camp_name">Nick Name <span style="color:red">*</span></label>
                <select name="nick_name" id="nick_name" class="form-control">
                    @foreach($nickNames as $nick)
                    <option value="{{ $nick->id }}">{{ $nick->nick_name}}</option>
                    @endforeach
					
                </select>
                 @if ($errors->has('nick_name')) <p class="help-block">{{ $errors->first('nick_name') }}</p> @endif
				<?php if(count($nickNames) == 0) { ?> <a href="<?php echo url('settings/nickname');?>">Add New Nick Name </a><?php } ?>
             </div> 
			<div class="form-group">
                <label for="topic name">Topic Name ( Limit 30 char ) <span style="color:red">*</span></label>
                <input type="text" name="topic_name" class="form-control" id="topic_name" value="{{ $topic->topic_name}}">
				@if ($errors->has('topic_name')) <p class="help-block">{{ $errors->first('topic_name') }}</p> @endif
            </div> 
			<?php if($objection=="objection") { ?>			
            <div class="form-group">
                <label for="topic name">Your Objection Reason <span style="color:red">*</span></label>
                <input type="text" name="objection_reason" class="form-control" id="objection_reason" value="">
				@if ($errors->has('objection_reason')) <p class="help-block">{{ $errors->first('objection_reason') }}</p> @endif
            </div> 			
            <?php } else { ?>
                       
            <div  class="form-group">
                <label for="namespace">Namespace <span style="color:red">*</span> (General is recommended, unless you know otherwise)</label>
                <select  onchange="selectNamespace(this)" name="namespace" id="namespace" class="form-control">
                   
                    @foreach($namespaces as $namespace)
                    <option value="{{ $namespace->id }}" @if($topic->namespace_id == $namespace->id) selected @endif>{{$namespace->label}}</option>
                    @endforeach
                    <option value="other" @if(old('namespace') == 'other') selected @endif>Other</option>
                </select>
                <!--
                <input type="text" name="namespace" class="form-control" id="" value="">-->
                @if ($errors->has('namespace')) <p class="help-block">{{ $errors->first('namespace') }}</p> @endif
			</div>
            <div id="other-namespace" class="form-group" >
                <label for="namespace">Other Namespace Name</label>
                
                <input type="text" name="create_namespace" class="form-control" id="create_namespace" value="">
                <span class="note-label"><strong>Note</strong>: Name space is categorization of your topic, it can be something like: General,crypto_currency etc.</span>
                @if ($errors->has('create_namespace')) <p class="help-block">{{ $errors->first('create_namespace') }}</p> @endif
			</div>
         
           
            <div class="form-group">
                <label for="">Additional Note <span style="color:red">*</span></label>
                <textarea class="form-control" rows="4" name="note" id="note"> </textarea>
				@if ($errors->has('note')) <p class="help-block">{{ $errors->first('note') }}</p> @endif
            </div>
            <?php } ?>
            <button type="submit" id="submit" class="btn btn-login">
			<?php if($objection=="objection") { ?> Submit Objection <?php } else {?>
			Submit Update<?php } ?></button>
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

