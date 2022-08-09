{!! Form::open(['route' => [ 'user.friend.add', $username ]]) !!}
{{ method_field('PUT') }}
<button type="submit" class="success button small"> Accept friend request</button>
{!! Form::close() !!}
