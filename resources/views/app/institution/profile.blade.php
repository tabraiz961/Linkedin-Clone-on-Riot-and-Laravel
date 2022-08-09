
@extends('layouts.app')

@section('content')

	<div class="grid-container">
		<div class="grid-x grid-padding-x grid-margin-y institution-profile">
			<div class="large-8 medium-8 cell">
				<div class="callout no-border p-0">
                    <institution-basic-profile></institution-basic-profile>
				</div>

				<div class="callout no-border p-0">
					<institution-job-profile></institution-job-profile>
				</div>

				@auth
					{{-- <experience-renderer></experience-renderer>
					<education-renderer></education-renderer> --}}
					{{-- <skill-renderer></skill-renderer> --}}
				@endauth
			</div>

			{{-- <div class="large-4 medium-4 cell">
			</div> --}}
			{{-- @auth --}} 
			<div class="large-4 medium-4 cell ">
				<tag-profile-institution></tag-profile-institution>
			</div>
			{{-- @endauth --}}
		</div>
	</div>

	@auth
		@if($data2['institution']['owner_id'] == Auth::id())
			<div class="reveal" id="institutionEditModal" data-reveal data-close-on-click="true"  >
				@include( 'app.inc.institution.forms.institution-edit', [ 'institution' => $data2['institution'] ])

				{{-- <button class="close-button" data-close aria-label="Close reveal" type="button">
					<span aria-hidden="true">&times;</span>
				</button> --}}
			</div>
		@endif
	@endauth
@endsection

@section('scripts')
	@parent
	@include('app.inc.institution.all_tags')
            
		<script>
			
			let data = JSON.parse('{!!  json_encode($data2)  !!}');
			let optionalParams = {
				basepath: '{{ url( '/' ) }}', 
				institution_job_create_link: '{{route('institution.job.create')}}',
				job_listing_link: '{{route('job.list')}}',
			};
			let optionalParams2 = optionalParams;
			if(data['institution']){
				optionalParams.institution = data['institution'];
				optionalParams.user = data['user'];
			}
			if(data['StepTwo']){
				
				optionalParams.stepTwo = data['StepTwo'];
				$( '#institutionEditModal' ).attr("data-close-on-click", false);
				setTimeout(function(){
					$( '#editInstitutiontrigger' ).click();
				}, 2000);
				$('#public_bio').val('');
				$('#long_overview_description').val('');
			}
			optionalParams2.load = getnewInstitutions;

			function getnewInstitutions(last_id,tag) {
				window.axios.get( '{{ route( 'api.networks.institutions' ) }}')
					.then( function( response ) {
						let _res = response.data;
						if(_res.success){
							
							__post__loadAdditional( '{{ url( 'api' ) }}', _res.institutions, tag.addItems, function( error ) {
								tag.error();
								console.log( error );
							} );
						}
						
					}).catch( function( error ) {
						tag.error();
					console.log( error );
				} );
			}
			window.riot.mount( 'institution-basic-profile',  optionalParams );
			window.riot.mount( 'institution-job-profile',  optionalParams );
			window.riot.mount( 'tag-profile-institution',  optionalParams2 );
			
        </script>
@endsection