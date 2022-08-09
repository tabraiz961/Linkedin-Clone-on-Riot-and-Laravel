@php($sender = $notification->data['sender'] )
@php($name=$sender['name'] ." ".$sender['surname'])

<a href="{{ route( 'user.profile', [ 'username' => $sender['username'] ] ) }}"><b>{{ $name }}</b> a commenté une publication</a>