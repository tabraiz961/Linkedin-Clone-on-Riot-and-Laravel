<li data-notification-id="{{ $notification['id'] }}" style={{ $notification['read_at'] ? '' : 'background-color:#e7e7e7'}}>
	<div class="grid-x">
		<div class="cell medium-1 notification_pic"><img src={{$notification['data']['sender']['photo_id']}}></div>
		<div class="cell medium-9 notification_details">
			<div class="noti_det_child">
				<a  href="{{route('user.profile', $notification['data']['sender']['username'])}}"><b>{{ $notification['data']['sender']['curated_name'] }}</b> {{ trans('lang.react_comment') }}</a>
				<p> {{ \Carbon\Carbon::parse($notification['created_at'])->diffForhumans() }}</p>
			</div>
		</div>
	</div>
</li>

