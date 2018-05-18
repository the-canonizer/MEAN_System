@extends('layouts.app')

@section('content')
	
    <div class="camp top-head">
    <h3><b><a href="#">{{ $threads->creator->first_name }}  </a> started this thread : 
                            "{{ $threads->title }}"</b></h3>
	</div>
    <div class="right-whitePnl">
   

            <div style="margin-bottom:20px;">

                <div class="panel panel-default">
                   <div class="panel-body">
                        <span> Thread Post at {{ $threads->created_at->diffForHumans() }} 
                            by <a href="#"> {{ $threads->creator->first_name }} </a> 
                        </span><br />

                        <span> 
                            Number of Post in this thread : {{ $threads->replies()->count() }}                            
                        </span>

                    </div>
                   {{--  <div class="panel-body">
                        {{ $threads->body}}
                    </div>  --}}
                  
                </div>

<!-- Replies To Thread -->        
                <!-- ?php $replies = $threads->replies()->paginate('10'); ?-->
                <div class="pagination">
                    <a class="active item">
                        <ul class ="list-group">
                            @foreach ($replies as $reply)
                            <li class = "list-group-item">
                                @include('threads.replies')
                            </li>
                            @endforeach
                        </ul>
                    </a>

                </div>
               

                {{ $replies->links() }}

                @if(auth()->check())

                    <form method="POST" action="{{ URL::to('/')}}/forum/{{ $topicname }}/{{ $campnum }}/threads/{{ $threads->id }}/replies">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <br>
                            <textarea name="body" id="body" class="form-control" placeholder="Reply to thread Here" rows="5"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>

                    </form>
                @else
                    Please <a href="{{ url('/login') }}">Sign In</a> to comment on this Thread
                @endif

            </div>
            
            

        
    </div>
@endsection
