@extends('admin.app')
@section('content')
<div class="row">
    <div class="col-md-12 panel-warning">
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible"> 
                <a href="#" class="close" data-dismiss="alert" aria-label="close" onclick="closeAlert(this)">&times;</a>
                {!! session('success') !!}
            </div>
        @endif
        
        
        <div class="content-box-header panel-heading">
            <div class="panel-title ">Shares</div>
            <div class="panel-options">
                    <a href="{{ url('/admin/shares/create') }}" data-rel="collapse"><i class="fa fa-plus"></i> Add Share</a>
            </div>

        </div>
        <div class="content-box-large box-with-header">
            <table class="table table-row">
                <tr>
                    <th>Nick Name</th>
                    <th>Date</th>
                    <th>Value</th>
                    <th>Action</th>
                </tr>
                @if(isset($shares) && count($shares) > 0)
                @foreach($shares as $share)
                <tr>
                    <td>{{ $share->usernickname->nick_name }}</td>
                    <td>{{ $share->as_of_date }}</td>
                    <td>{{ $share->share_value}}</td>
                    <td>
                        <a href="{{ url('/admin/shares/edit/'.$share->id) }}"><i class="fa fa-edit"></i>&nbsp;&nbsp;Edit</a>
                        &nbsp;&nbsp;<a href="javascript:void(0)" onClick='deleteShare("{{$share->id }}")'><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete</a>
                    </td>
                </tr>
                @endforeach
                @else
                <tr><td colspan="5"><span>No Share data found!</span></td></tr>                
                @endif
            </table>
            {{ $shares->links() }}

        </div>
    </div>
</div>
<script>
   function deleteShare(id){
     var delete_url = "<?php echo url('/admin/shares/delete') ?>/"+id
       var check = confirm("Are you sure to delete this record?");
        if(check == true){
            window.location.href = delete_url;
        }else{
            console.log('no')
        }
    }

   function closeAlert(e){
      $(e).parents('div.alert').remove();
   }

</script>
@endsection