<div class="edit-form">
	{!!  Form::model($user, ['route' => ['user.update', $user['username']], 'files' => true]) !!}

	<div>
		{{ Form::label('name', 'First name', ['class' => ($errors->has('name') ? 'is-invalid-label' : '')]) }}
		{{ Form::text('name', null, ['required' => true, 'class' => ($errors->has('name') ? 'is-invalid-input' : '')]) }}
		@if ($errors->has('name'))
			<span class="form-error is-visible">
				{{ $errors->first('name') }}
			</span>
		@endif
	</div>

	<div>
		{{ Form::label('surname', 'Last name', ['class' => ($errors->has('surname') ? 'is-invalid-label' : '')]) }}
		{{ Form::text('surname', null, ['required' => true, 'class' => ($errors->has('surname') ? 'is-invalid-input' : '')]) }}
		@if ($errors->has('surname'))
			<span class="form-error is-visible">
				{{ $errors->first('surname') }}
			</span>
		@endif
	</div>

	{{-- <div>
		{{ Form::label('username', 'Username', ['class' => ($errors->has('username') ? 'is-invalid-label' : ''), 'id' => 'username_register']) }}
		{{ Form::text('username', null, ['required' => true, 'class' => ($errors->has('username') ? 'is-invalid-input' : ''), 'id' => 'username_register']) }}
		@if ($errors->has('username'))
			<span class="form-error is-visible">
				{{ $errors->first('username') }}
			</span>
		@endif
	</div> --}}

	{{-- <div>
		{{ Form::label('birth_date', 'Date of Birth', ['class' => ($errors->has('surname') ? 'is-invalid-label' : '')]) }}
		{{ Form::date('birth_date', null, ['required' => true, 'class' => ($errors->has('birth_date') ? 'is-invalid-input' : '')]) }}
		@if ($errors->has('birth_date'))
			<span class="form-error is-visible">
				{{ $errors->first('birth_date') }}
			</span>
		@endif
	</div> --}}

	{{-- <div>
		{{ Form::label('email', 'Email', ['class' => ($errors->has('email') ? 'is-invalid-label' : '')]) }}
		{{ Form::email('email', null, ['required' => true, 'class' => ($errors->has('email') ? 'is-invalid-input' : '')]) }}
		@if ($errors->has('email'))
			<span class="form-error is-visible">
				{{ $errors->first('email') }}
			</span>
		@endif
	</div> --}}

	<div>
		{{ Form::label('title', 'Public Title', ['class' => ($errors->has('title') ? 'is-invalid-label' : '')]) }}
		{{ Form::text('title', null, [ 'class' => ($errors->has('title') ? 'is-invalid-input' : '')]) }}
		@if ($errors->has('title'))
			<span class="form-error is-visible">
				{{ $errors->first('title') }}
			</span>
		@endif
	</div>

	<div>
		{{ Form::label('profile_details[short_bio]', 'Short Bio', ['class' => ($errors->has('profile_details[short_bio]') ? 'is-invalid-label' : '')]) }}
		{{ Form::text('profile_details[short_bio]', isset($user['profile_details']['short_bio']) ? $user['profile_details']['short_bio'] : '', [ 'class' => ($errors->has('profile_details[short_bio]') ? 'is-invalid-input' : '')]) }}
		@if ($errors->has('profile_details[short_bio]'))
			<span class="form-error is-visible">
				{{ $errors->first('profile_details[short_bio]') }}
			</span>
		@endif
	</div>

	<div>
		{{ Form::label('profile_details[long_description]', 'Long Description', ['class' => ($errors->has('profile_details[long_description]') ? 'is-invalid-label' : '')]) }}
		{{ Form::text('profile_details[long_description]', isset($user['profile_details']['long_description']) ? $user['profile_details']['long_description'] : '', [ 'class' => ($errors->has('profile_details[long_description]') ? 'is-invalid-input' : '')]) }}
		@if ($errors->has('profile_details[long_description]'))
			<span class="form-error is-visible">
				{{ $errors->first('profile_details[long_description]') }}
			</span>
		@endif
	</div>
	<div>
		{{ Form::label('cv', 'CV', ['class' => ($errors->has('cv') ? 'is-invalid-label' : '')]) }}
		{{ Form::file('cv', ['class' => ($errors->has('cv') ? 'is-invalid-input' : '' ), 'accept' => 'application/pdf']) }}
		@if ($user['cv_url'] !=null)
			Le profil possède un CV
		@endif
		@if ($errors->has('cv'))
			<span class="form-error is-visible">
				{{ $errors->first('cv') }}
			</span>
		@endif
	</div>

	<div>
		{{ Form::label('photo_open', 'Photo', ['class' => ($errors->has('photo_path') ? 'is-invalid-label' : '')]) }}
		{{ Form::file('photo_path', ['class' => ($errors->has('photo_path') ? 'is-invalid-input' : '' ), 'accept' => 'image']) }}
		{{-- <button id="post_photo_selector" class="button" type="button" name="photo_open">
			{{trans('lang.profile_edit_select')}} <span id="post_photo_isselected" style="{{ $user['photo_id'] == null ? 'display: none' :'' }}">
				<i class="fa fa-check"></i>
			</span>
		</button> --}}
		{{-- <input type="hidden" name="photo_id" value="{{ $user['photo_id'] != null ? $user['photo_id'] : '' }}" /> --}}
		@if ($errors->has('photo_path'))
			<span class="form-error is-visible">
				{{ $errors->first('photo_path') }}
			</span>
		@endif
	</div>

	<div>
		{{ Form::label('cover_open', 'Cover picture', ['class' => ($errors->has('cover_path') ? 'is-invalid-label' : '')]) }}
		{{-- <button id="post_cover_selector" class="button" type="button" name="cover_open">
			{{trans('lang.profile_edit_select')}}
			 <span id="post_cover_isselected" style="{{ $user['cover_path'] == null ? 'display: none' :'' }}">
				<i class="fa fa-check"></i>
			</span>
		</button>
		<input type="hidden" name="cover_path" value="{{ $user['cover_path'] != null ? $user['cover_path'] : '' }}" /> --}}
		{{ Form::file('cover_path', ['class' => ($errors->has('cover_path') ? 'is-invalid-input' : '' ), 'accept' => 'image']) }}
		@if ($errors->has('cover_path'))
			<span class="form-error is-visible">
				{{ $errors->first('cover_path') }}
			</span>
		@endif
	</div>

	{{-- <div>
		{{ Form::label('password', 'Password', ['class' => ($errors->has('password') ? 'is-invalid-label' : ''), 'id' => 'username_register']) }}
		{{ Form::password('password', ['class' => ($errors->has('password') ? 'is-invalid-input' : '')]) }}
		@if ($errors->has('password'))
			<span class="form-error is-visible">
				{{ $errors->first('password') }}
			</span>
		@endif
	</div>

	<div>
		{{ Form::label('password_confirmation', 'Confirm password', ['class' => ($errors->has('password_confirmation') ? 'is-invalid-label' : ''), 'id' => 'username_register']) }}
		{{ Form::password('password_confirmation', ['class' => ($errors->has('password_confirmation') ? 'is-invalid-input' : '')]) }}
		@if ($errors->has('password_confirmation'))
			<span class="form-error is-visible">
				{{ $errors->first('password_confirmation') }}
			</span>
		@endif
	</div> --}}

	<div>
		{{ Form::submit(trans('lang.profile_update_select'), ['class' => 'button expanded']) }}
	</div>
	{!! Form::close() !!}
</div>

@section('scripts')
	@parent

	<script>
		var photo_id = $( 'input[name="photo_id"]' );
		var cover_path = $( 'input[name="cover_path"]' );
		var photo_is_selected = $( '#post_photo_isselected' );
		var cover_is_selected = $( '#post_cover_isselected' );

		function photosGet( tag ) {
			tag.isLoading();
			window.axios.get( '{{ route( 'api.user.images', [ 'username' => $user['username'] ] ) }}' )
				.then( function( response ) {
					tag.notLoading();
					let items = response.data;
					for( let i = 0; i < items.length; i++ ) {
						if( items[ i ].post_id == photo_id.val() ) {
							items[ i ].selected = true;
						}
					}
					tag.setItems( items );
				}).catch( function( error ) {
					tag.notLoading();
					console.log( error );
				});
		}

		function coversGet( tag ) {
			tag.isLoading();
			window.axios.get( '{{ route( 'api.user.images', [ 'username' => $user['username'] ] ) }}' )
				.then( function( response ) {
					tag.notLoading();
					let items = response.data;
					for( let i = 0; i < items.length; i++ ) {
						if( items[ i ].post_id == cover_path.val() ) {
							items[ i ].selected = true;
						}
					}
					tag.setItems( items );
				}).catch( function( error ) {
				tag.notLoading();
				console.log( error );
			});
		}

		function onPhotoSelected( images ) {
			if( images.length > 0 ) {
				photo_id.val( images[ 0 ].post_id );
				photo_is_selected.show();
			} else  {
				photo_id.val();
				photo_is_selected.hide();
			}
		}

		function onCoverSelected( images ) {
			if( images.length > 0 ) {
				cover_path.val( images[ 0 ].post_id );
				cover_is_selected.show();
			} else  {
				cover_path.val();
				cover_is_selected.hide();
			}
		}

		function onPhotoCancelled() {
			photo_id.val();
			photo_is_selected.hide();
		}

		function onCoverCancelled() {
			cover_path.val();
			cover_is_selected.hide();
		}

		function openImage( selectorTag ) {
			function onImageSubmit( image, tag ) {
				tag.disable();
				image.append( '_method', 'PUT' );
				window.axios.post(
					'{{ route( 'api.user.image.create', [ 'username' => $user['username'] ] ) }}',
					image,
					{
						headers: { 'content-type': 'multipart/form-data' },
						onUploadProgress: function( progressEvent ) {
							tag.setProgress( Math.round( ( progressEvent.loaded * 100 ) / progressEvent.total ) );
						}
					}
				).then( function( response ) {
					selectorTag.onAdd();
					tag.unmount();
				} ).catch( function( error ) {
					tag.enable();
					tag.setProgress( 0 );
					console.log( error );
				} );
			}

			$( 'body' ).append( $( '<image-form></image-form>' ) );
			window.riot.mount( 'image-form', { onSelected: onImageSubmit } );
		}

		$( '#post_photo_selector' ).click( function( e ) {
			e.preventDefault();
			e.stopImmediatePropagation();
			$( 'body' ).append( $( '<tag-selector></tag-selector>' ) );
			window.riot.mount( 'tag-selector', {
				onSelected: onPhotoSelected,
				onCancelled: onPhotoCancelled,
				component: 'image-renderer',
				itemGetInitier: photosGet,
				hasAdd: true,
				add: openImage,
				unique: true
			});
		});

		$( '#post_cover_selector' ).click( function( e ) {
			e.preventDefault();
			e.stopImmediatePropagation();
			$( 'body' ).append( $( '<tag-selector></tag-selector>' ) );
			window.riot.mount( 'tag-selector', {
				onSelected: onCoverSelected,
				onCancelled: onCoverCancelled,
				component: 'image-renderer',
				itemGetInitier: coversGet,
				hasAdd: true,
				add: openImage,
				unique: true
			});
		});
	</script>
@endsection