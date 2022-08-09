<div class="edit-form">
	{!!  Form::model($institution, ['route' => ['institutions.update', $institution['slug']], 'files' => true]) !!}

	<div>
		{{ Form::label('institution_name', 'Institution Name', ['class' => ($errors->has('institution_name') ? 'is-invalid-label' : '')]) }}
		{{ Form::text('institution_name', $institution['institution_name'] ? $institution['institution_name']: '', ['required' => true, 'class' => ($errors->has('institution_name') ? 'is-invalid-input' : '')]) }}
		@if ($errors->has('institution_name'))
			<span class="form-error is-visible">
				{{ $errors->first('institution_name') }}
			</span>
		@endif
	</div>

	<div>
		{{ Form::label('public_bio', 'Public Bio', ['class' => ($errors->has('public_bio') ? 'is-invalid-label' : '')]) }}
		{{ Form::text('public_bio', $institution['public_bio'] ? $institution['public_bio'] :'', [ 'class' => ($errors->has('public_bio') ? 'is-invalid-input' : '')]) }}
		@if ($errors->has('public_bio'))
			<span class="form-error is-visible">
				{{ $errors->first('public_bio') }}
			</span>
		@endif
	</div>

	<div>
		{{ Form::label('long_overview_description', 'Long Overview Description', ['class' => ($errors->has('long_overview_description') ? 'is-invalid-label' : '')]) }}
		{{ Form::text('long_overview_description', $institution['long_overview_description'] ? $institution['long_overview_description']:'', [ 'class' => ($errors->has('long_overview_description') ? 'is-invalid-input' : '')]) }}
		@if ($errors->has('long_overview_description'))
			<span class="form-error is-visible">
				{{ $errors->first('long_overview_description') }}
			</span>
		@endif
	</div>

	

	<div>
		{{ Form::label('website_link', 'Website Link', ['class' => ($errors->has('website_link') ? 'is-invalid-label' : '')]) }}
		{{ Form::text('website_link', $institution['website_link']? $institution['website_link']: '', [ 'class' => ($errors->has('website_link') ? 'is-invalid-input' : '')]) }}
		@if ($errors->has('website_link'))
			<span class="form-error is-visible">
				{{ $errors->first('website_link') }}
			</span>
		@endif
	</div>

    <select class="js-example-templating" name="address_selected" id="address_selector_us" style="width: 50%">
        @if (isset($institution['address_details']['curated_address']))
            <option value="{{ $institution['address_details']['id'] }}" selected="selected">{{$institution['address_details']['curated_address']}}</option>
        @endif
    </select>

    <div>
        <p>Founded Date: <input name="founded_date" value="{{ $institution['founded_date'] ? $institution['founded_date']: '' }}"  type="text" id="founded_date"></p>
		@if ($errors->has('founded_date'))
			<span class="form-error is-visible">
				{{ $errors->first('founded_date') }}
			</span>
		@endif
	</div>

	<div>
		{{ Form::label('institution_photo', 'Institution Photo', ['class' => ($errors->has('institution_photo') ? 'is-invalid-label' : '')]) }}
		{{ Form::file('institution_photo', ['class' => ($errors->has('institution_photo') ? 'is-invalid-input' : '' ), 'accept' => 'image']) }}
		
		@if ($errors->has('institution_photo'))
			<span class="form-error is-visible">
				{{ $errors->first('institution_photo') }}
			</span>
		@endif
	</div>

	<div>
		{{ Form::label('institution_cover', 'Institution Cover', ['class' => ($errors->has('institution_cover') ? 'is-invalid-label' : '')]) }}
		
		{{ Form::file('institution_cover', ['class' => ($errors->has('institution_cover') ? 'is-invalid-input' : '' ), 'accept' => 'image']) }}
		@if ($errors->has('institution_cover'))
			<span class="form-error is-visible">
				{{ $errors->first('institution_cover') }}
			</span>
		@endif
	</div>


	<div>
		{{ Form::submit(trans('lang.profile_update_select'), ['class' => 'button expanded']) }}
	</div>
	{!! Form::close() !!}
</div>

@section('scripts')
	@parent

	<script>
        $(".js-example-templating").select2({
            templateResult: this.formatState,
            width: '250',
            ajax: {
                url: '<?php echo route("search.getAddress" ) ?>',
                dataType: 'json'
            },
            processResults: function (data) {
				return {
					results: data
				};
            }
        });
        $( function() {
            $( "#founded_date" ).datepicker({
                changeMonth: true,
                changeYear: true
            });
            $( "#founded_date" ).datepicker("option", "dateFormat", 'yy-mm-dd' );
			$( "#founded_date" ).datepicker("setDate", "{{ $institution['founded_date'] ? $institution['founded_date'] : '' }}" );
		} );

        function formatState (state) {
            if (!state.id) {
                return state.text;
            }
            var $state = $(
                '<span> ' + state.text + '</span>'
            );
            return $state;
        };
	</script>
@endsection