@php
	$title = trans('lang.create_job_web_title')
@endphp

@extends('layouts.app')

@section('content')

<div id="home" class="grid-container">
	<div class="grid-x">
		<div class="cell small-12 medium-8 large-9">
			<div class="border-rad-15 callout card">
				{{-- <div class="card-divider">
					Create a job offer
				</div> --}}
				<div class="card-section">
					@include( 'app.inc.forms.job' , ['data2' => $data2])
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('scripts')
	@parent
	@include( 'app.inc.tags' )
	<script>
		var apiurl = '{{ url( 'api' ) }}';

		$(document).ready(function() {
			$('input[name=job_start_date_checkbox]:checkbox').bind('change', function(e) {
				if ($(this).is(':checked')) {
					$('#job_start_date').prop('disabled', false);
				}
				else {
					$('#job_start_date').prop('disabled', true);
					$('#job_start_date').removeProp('value');
					$('#job_start_date').val('');
				}
			})
			$( "#job_start_date" ).datepicker();

			$('.js-example-basic-multiple').select2({
				ajax: {
					url: window.location.origin+'/search/skills',
					dataType: 'json'
				},
				processResults: function (data) {
					return {
						results: data
					};
				},
				minimumInputLength: 3,
				maximumInputLength: 20,
				minimumResultsForSearch: 10,
				// allowClear: true
			});
			$('.js-example-basic-multiple-languages').select2({
				ajax: {
					url: window.location.origin+'/search/languages',
					dataType: 'json'
				},
				processResults: function (data) {
					return {
						results: data
					};
				},
				minimumInputLength: 2,
				maximumInputLength: 20,
				minimumResultsForSearch: 10,
				// allowClear: true
			});
			
		<?php
			if (isset($data2['edit_job_data'])) {
		?>
		
					// create the option and append to Select2
					<?php 
					foreach($data2['edit_job_skills'] as $edit_skills){
					?>
						var option = new Option("<?php echo($edit_skills['skill_name']) ?>", "<?php echo($edit_skills['id']) ?>", true, true);
						$('.js-example-basic-multiple').append(option).trigger('change');
					<?php
					}
					?>
					
					<?php 
					foreach($data2['edit_job_languages'] as $edit_languages){
					?>
						var option = new Option("<?php echo($edit_languages['Language']) ?>", "<?php echo($edit_languages['id']) ?>", true, true);
						$('.js-example-basic-multiple-languages').append(option).trigger('change');
					<?php
					}
					?>
		<?php 
			}
		?>
		
			
		
			$('#job-create-form').submit(function (params) {
				if(parseInt($("input[name=min]").val()) > parseInt($("input[name=max]").val())){
					alert('Minimum cannot be greater than Maximum');
					$("input[name=min]").val(0);
					$("input[name=max]").val(0);
					return false;
				}
			})
		});

	</script>
	{{ Html::script( 'js/form-job.js?t='.time() ) }}
@endsection