@php
	$resume_required = ['yes', 'no'];
	$questions = isset($data2['edit_job_data']) ? json_decode($data2['edit_job_data']['questions']) : old('questions');
	// echo('<pre>');print_r($data2['edit_job_data']);echo('</pre>');die('call');
@endphp

<div class="job-form card-form-entities">
	@if( isset($data2['edit_job_data'])  )
		{{-- {!! Form::model($data2['edit_job_data'], ['route' => ['job.store', $data2['edit_job_data']['job_id']], 'files' => true,]]) !!} --}}
		{!! Form::open(['route' => ['job.store', $data2['edit_job_data']['job_id']], 'files' => true, 'id' => 'job-create-form'	]) !!}
	@else
		{!! Form::open(['route' => 'job.store', 'files' => true, 'id' => 'job-create-form'	]) !!}
	@endif
	{{ method_field('PUT') }}

	<div>
		{{ Form::label('position', 'Job Title', ['class' => ($errors->has('position') ? 'is-invalid-label ' : ''). 'input-heading']) }}
		{{ Form::text('position',( isset($data2['edit_job_data']) ? $data2['edit_job_data']['position']: null ), ['required' => true, 'class' => ($errors->has('position') ? 'is-invalid-input' : '').'input-value']) }}
		@if ($errors->has('position'))
			<span class="form-error is-visible">
				{{ $errors->first('position') }}
			</span>
		@endif
	</div>
		
	<div class="grid-x grid-padding-x">
		<fieldset class="large-5 ">
			{{ Form::label('job_type', 'Job Type', ['class' => ($errors->has('job_type') ? 'is-invalid-label ' : ''). 'input-heading']) }}
			@foreach ($data2['jobs']['types'] as $type)
				<input id="{{$type}}" type="radio" name="job_type" value="{{$type}}" {{ (isset($data2['edit_job_data']) && $data2['edit_job_data']['job_type'] == $type) ? 'checked': ''}}  {{old('job_type') == $type ? 'checked': ''}} <?php echo ($type == "fulltime"?"required":'')?> >
				<label for="{{$type}}">{{ ucfirst($type) }}</label>
			@endforeach
			
			@if($errors->has('job_type'))
				<span class="form-error is-visible">
					{{ $errors->first('job_type') }}
				</span>
			@endif
		</fieldset>
	</div>
	
	<div class="grid-x grid-padding-x">
		<fieldset class="large-5 ">
			{{ Form::label('job_location', 'Job Location', ['class' => ($errors->has('job_location') ? 'is-invalid-label ' : ''). 'input-heading']) }}
			@foreach ($data2['jobs']['job_locations'] as $type)
				<input id="{{$type}}" type="radio" name="job_location" value="{{$type}}" {{ (isset($data2['edit_job_data']) && $data2['edit_job_data']['job_location'] == $type) ? 'checked': ''}}  {{old('job_location') == $type ? 'checked': ''}} <?php echo ($type == "onsite"?"required":'')?> >
				<label for="{{$type}}">{{ ucfirst($type) }}</label>
			@endforeach
			
			@if($errors->has('job_location'))
				<span class="form-error is-visible">
					{{ $errors->first('job_location') }}
				</span>
			@endif
		</fieldset>
	</div>

	<div class="grid-x grid-padding-x">
		<fieldset class="large-5" >
			{{ Form::label('skills', 'Skills', ['class' => ($errors->has('skills') ? 'is-invalid-label ' : ''). 'input-heading']) }}
			<select class="js-example-basic-multiple" name="skills[]" multiple="multiple">
			</select>
		</fieldset>
	</div>

	<div class="grid-x grid-padding-x">
		<fieldset class="large-7 ">
			{{ Form::label('education_req', 'Education Required', ['class' => ($errors->has('education_req') ? 'is-invalid-label ' : ''). 'input-heading']) }}
			@foreach ($data2['education_req'] as $type)
				<input id="{{$type}}" type="radio" name="education_req" {{ (isset($data2['edit_job_data']) && $data2['edit_job_data']['education_req'] == $type) ? 'checked': ''}}  value="{{$type}}" {{old('education_req') == $type ? 'checked': ''}} <?php echo ($type == "onsite"?"required":'')?> >
				<label for="{{$type}}">{{ ucfirst($type) }}</label>
			@endforeach
			
			@if($errors->has('education_req'))
				<span class="form-error is-visible">
					{{ $errors->first('education_req') }}
				</span>
			@endif
		</fieldset>
	</div>
	<div class="grid-x grid-padding-x" style="margin-top: 20px">
		{{ Form::label('description', 'Description', ['class' => ($errors->has('description') ? 'is-invalid-label' : '').'input-heading']) }}
		{{ Form::textarea('description', ( isset($data2['edit_job_data']) ? $data2['edit_job_data']['description']: null ), ['required' => true, 'class' => ($errors->has('description') ? 'is-invalid-input' : '').'input-value']) }}
		@if ($errors->has('description'))
			<span class="form-error is-visible">
				{{ $errors->first('description') }}
			</span>
		@endif
	</div>

	<div class="grid-x grid-padding-x">
		<fieldset class="large-5 ">
			{{ Form::label('salary_type', 'Salary', ['class' => ($errors->has('salary_type') ? 'is-invalid-label ' : ''). 'input-heading']) }}
			@foreach ($data2['salary_type'] as $type)
				<input id="{{$type}}" type="radio" name="salary_type" value="{{ $type }}" {{ (isset($data2['edit_job_data']) && $data2['edit_job_data']['salary_type'] == $type) ? 'checked': ''}} onclick="salaryToggle('{{ $type }}')" {{old('salary_type') == $type ? 'checked': ''}} <?php echo ($type == "month"?"required":'')?> >
				<label for="{{$type}}">{{ 'Per '.ucfirst($type) }}</label>
			@endforeach
			
			@if($errors->has('salary_type'))
				<span class="form-error is-visible">
					{{ $errors->first('salary_type') }}
				</span>
			@endif
		</fieldset>
	</div>
	
	<div class="grid-x grid-padding-x">
		<div class="mr2">
			{{ Form::label('fixed', 'Fixed', ['class' => ($errors->has('fixed') ? 'is-invalid-label' : '').'input-heading ']) }}
			{{ Form::number('fixed', isset($data2['edit_job_data']) ? $data2['edit_job_data']['fixed'] : (old('fixed') > 0 ? old('fixed') : 0), ['required' => true, 'class' => ($errors->has('fixed') ? 'is-invalid-input' : '').'input-value',  'placeholder' => 'Fixed salary']) }}
			@if ($errors->has('fixed'))
				<span class="form-error is-visible">
					{{ $errors->first('fixed') }}
				</span>
			@endif
		</div>

		<div class="mr2">
			{{ Form::label('min', 'Min', ['class' => ($errors->has('min') ? 'is-invalid-label' : '').'input-heading mr2']) }}
			{{ Form::number('min', isset($data2['edit_job_data']) ? $data2['edit_job_data']['min'] : (old('min') > 0 ? old('min') : 0), ['required' => true, 'class' => ($errors->has('min') ? 'is-invalid-input' : '').'input-value',  'placeholder' => 'Min salary']) }}
			@if ($errors->has('min'))
				<span class="form-error is-visible">
					{{ $errors->first('min') }}
				</span>
			@endif
		</div>

		<div>
			{{ Form::label('max', 'Max', ['class' => ($errors->has('max') ? 'is-invalid-label' : '').'input-heading']) }}
			{{ Form::number('max', isset($data2['edit_job_data']) ? $data2['edit_job_data']['max'] : (old('max') > 0 ? old('max') : 0), ['required' => true, 'class' => ($errors->has('max') ? 'is-invalid-input' : '').'input-value',  'placeholder' => 'Min salary']) }}
			@if ($errors->has('max'))
				<span class="form-error is-visible">
					{{ $errors->first('max') }}
				</span>
			@endif
		</div>
	</div>

	<div class="grid-x grid-padding-x">
		<fieldset class="large-5">
			{{ Form::label('gender_prefer', 'Gender', ['class' => ($errors->has('gender_prefer') ? 'is-invalid-label ' : ''). 'input-heading']) }}
			@foreach ($data2['gender_prefer'] as $type)
				<input id="{{$type}}" type="radio" name="gender_prefer" {{old('gender_prefer') == $type ? 'checked': ''}} {{ (isset($data2['edit_job_data']) && $data2['edit_job_data']['gender_prefer'] == $type) ? 'checked': ''}}  value="{{ $type }}" <?php echo ($type == "male"?"required":'')?> >
				<label for="{{$type}}">{{ ucfirst($type) }}</label>
			@endforeach
			
			@if($errors->has('gender_prefer'))
				<span class="form-error is-visible">
					{{ $errors->first('gender_prefer') }}
				</span>
			@endif
		</fieldset>
	</div>
	<div class="grid-x  grid-padding-x questionaire">
		<div style="display: flex; align-items: baseline;">
			Ask a new question
			<button class="button" type="button" onclick="addQuestion()" style="margin-left: 10px;">
				<i class="fas fa-plus"></i>    
			</button>
		</div>
		<div id="question-container">
			@if (isset($questions) && count($questions) > 0)
				@foreach ($questions as $item)
				<div id="inputFormRow">
					<div class="input-group mb-3">
						<label for="questions">{{$loop->iteration}})</label>
						<input type="text" name="questions[]"  placeholder="Enter Questions" autocomplete="off" value="{{$item}}" required></input>
						<div class="input-group-append"></div>
						<button id="removeRow" type="button" class="button">Remove</button>
					</div>
				</div>
				@endforeach
			@endif
			
		</div>	
	</div>

	<div class="grid-x grid-padding-x">
		<fieldset class="large-5">
			{{ Form::label('job_poster_file', 'Job Poster or Video', ['class' => ($errors->has('job_poster_file') ? 'is-invalid-label ' : ''). 'input-heading']) }}
			{{ Form::file('job_poster_file', $attributes = array())}}
			
			@if($errors->has('job_poster_file'))
				<span class="form-error is-visible">
					{{ $errors->first('job_poster_file') }}
				</span>
			@endif
		</fieldset>
	</div>
	
	<div class="grid-x grid-padding-x">
		<fieldset class="large-5">
			{{ Form::label('cv_required', 'CV/Resume Required', ['class' => ($errors->has('cv_required') ? 'is-invalid-label ' : ''). 'input-heading']) }}
			@foreach ($resume_required as $type)
				<input id="{{'resume'.$type}}" type="radio" name="cv_required" value="{{ $type }}" {{ (isset($data2['edit_job_data']) && ($data2['edit_job_data']['cv_required'] == 1 && $type == 'yes' || $data2['edit_job_data']['cv_required'] == 0 && $type == 'no')) ? 'checked': ''}} {{old('cv_required') == $type ? 'checked': ''}} <?php echo ($type == "no"?"required":'')?> >
				<label for="{{'resume'.$type}}">{{ ucfirst($type) }}</label>
			@endforeach
			
			@if($errors->has('cv_required'))
				<span class="form-error is-visible">
					{{ $errors->first('cv_required') }}
				</span>
			@endif
		</fieldset>
	</div>

	
	<div class="grid-x grid-padding-x">
		<fieldset class="large-5">
			{{ Form::label('job_start_date', 'Job Start Date', ['class' => ($errors->has('job_start_date') ? 'is-invalid-label ' : ''). 'input-heading']) }}
			<div class="flex-container">
				{{ Form::checkbox('job_start_date_checkbox', 'job_start_date_checkbox', true)}}
				<p class="flex-container" style="margin-left: 11px;">
					Date: <input name="job_start_date" {{ (isset($data2['edit_job_data']) && isset($data2['edit_job_data']['job_start_date'])) ? 'value='.date("m/d/Y",strtotime($data2['edit_job_data']['job_start_date'])).'': ''}}  {{old('job_start_date') == $type ? 'checked': ''}} type="text" id="job_start_date" required>
				</p>
			</div>
			@if($errors->has('job_start_date'))
				<span class="form-error is-visible">
					{{ $errors->first('job_start_date') }}
				</span>
			@endif
		</fieldset>
	</div>

	<div class="grid-x grid-padding-x" style="margin-bottom: 15px;">
		<fieldset class="large-5" >
			{{ Form::label('languages', 'Languages Required', ['class' => ($errors->has('languages') ? 'is-invalid-label ' : ''). 'input-heading']) }}
			<select class="js-example-basic-multiple-languages" name="languages[]" multiple="multiple">
			</select>
		</fieldset>
	</div>

	<div>
		@if( isset($data2['edit_job_data']))
			{{ Form::submit('Modifier', ['class' => 'button expanded']) }}
		@else
			{{ Form::submit('Create', ['class' => 'button expanded']) }}
		@endif
	</div>
	{!! Form::close() !!}
</div>