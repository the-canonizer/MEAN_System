<div class="panel panel-default">
    <div class="panel-heading">

        <div class="level">
            <a href="#">
                {{ $reply->owner->nick_name }}
            </a> replied {{ $reply->created_at->diffForHumans() }}
            <br><br>
        </div>

    </div>

    <div class="panel-body" style="word-break:break-word">
        {!! html_entity_decode($reply->body) !!}
    </div>
</div>
