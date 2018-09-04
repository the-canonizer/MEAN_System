@extends('layouts.app')
@section('content')

<div class="camp top-head">
    <h3><b>Topic:</b>  {{ $topic->title}}</h3>
    <h3><b>Camp:</b> {!! $parentcamp !!}</h3>  
</div>


<div class="page-titlePnl">
    <h1 class="page-title">Statement Update</h1>
</div> 

@if(Session::has('error'))
<div class="alert alert-danger">
    <strong>Error!</strong>{{ Session::get('error')}}    
</div>
@endif

@if(Session::has('success'))
<div class="alert alert-success">
    <strong>Success!</strong>{{ Session::get('success')}}    
</div>
@endif


<div class="right-whitePnl">
<div class="row col-sm-12 justify-content-between">
    <div class="col-sm-5 margin-btm-2">
        <form action="{{ route('statement.save')}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="topic_num" name="topic_num" value="{{ $topic->topic_num }}">
            <input type="hidden" id="parent_camp_num" name="parent_camp_num" value="{{ $parentcampnum }}">
			<input type="hidden" id="camp_num" name="camp_num" value="{{ $statement->camp_num }}">
			<input type="hidden" id="submitter" name="submitter" value="{{ $statement->submitter_nick_id }}">
            <?php if($objection=="objection") { ?>
			<input type="hidden" id="objection" name="objection" value="1">
			<?php } ?>
               
             <div class="form-group">
                <label for="camp_name">Nick Name <span style="color:red">*</span></label>
                <select name="nick_name" id="nick_name" class="form-control">
                    @foreach($nickNames as $nick)
                    <option value="{{ $nick->id }}">{{ $nick->nick_name}}</option>
                    @endforeach
					
                </select>
                 @if ($errors->has('nick_name')) <p class="help-block">{{ $errors->first('nick_name') }}</p> @endif
				 <a id="add_new_nickname" href="<?php echo url('settings/nickname');?>">Add New Nickname </a>
             </div> 			   
             <div class="form-group">
                <label for="">Statement <span style="color:red">*</span></label>
                <textarea class="form-control" rows="6" id="name" name="statement">{{ $statement->value}}</textarea>
                @if ($errors->has('statement')) <p class="help-block">{{ $errors->first('statement') }}</p> @endif
             </div> 
            
            <div class="form-group">
                <label for="title">Note <span style="color:red">*</span></label>
                 <textarea class="form-control" id="note" rows="4" name="note">{{ old('note')}}</textarea>
                @if ($errors->has('note')) <p class="help-block">{{ $errors->first('note') }}</p> @endif
            </div> 
           
		   
            <?php if($objection=="objection") { ?> 
            <div class="form-group">
                <label for="topic name">Your Objection Reason </label>
                <input type="text" name="object_reason" class="form-control" id="object_reason" value="">
				@if ($errors->has('object_reason')) <p class="help-block">{{ $errors->first('object_reason') }}</p> @endif
            </div> 				
            <?php } ?>  
            <button type="submit" id="submit" class="btn btn-login">Submit Update</button>
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

