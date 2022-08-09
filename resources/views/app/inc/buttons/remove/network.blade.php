{!! Form::open(['route' => [ 'user.network.remove', $username ]]) !!}
{{ method_field('DELETE') }}
<button type="submit" class="alert button small"><i class="fas fa-times"></i>Remove from network</button>
{!! Form::close() !!}
