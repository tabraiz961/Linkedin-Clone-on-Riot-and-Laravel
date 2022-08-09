<li data-notification-id="{{ $notification['id'] }}" style={{ $notification['read_at'] ? '' : 'background-color:#e7e7e7'}}>
    <div class="grid-x">
        <div class="cell medium-1 notification_pic"><img src={{$notification['data']['sender']['photo_id']}}></div>
        <div class="cell medium-<?php echo(in_array($filename, ['friendrequestreceived']) ? '7':'9') ?> notification_details">
            <div class="noti_det_child">
                <a href="{{route('user.profile',$notification['data']['sender']['username'])}}"><b>{{ $notification['data']['sender']['curated_name'] }}</b> {{ trans('lang.friend_request_received') }}</a>
                <p> {{ \Carbon\Carbon::parse($notification['created_at'])->diffForhumans() }}</p>
            </div>
        </div>
        @if (in_array($filename, ['friendrequestreceived']) )
            <div class="cell medium-2 grid-y">
                <button  onclick="accept('{{$notification['data']['sender']['username']}}', this )">Accept</button>													
            </div>
            <div class="cell medium-2 grid-y">
                <button>Ignore</button>													
            </div>
        @endif
    </div>
</li>



