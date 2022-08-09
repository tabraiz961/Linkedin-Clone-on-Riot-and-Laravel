<job-selected>
	<job-item-loader if={ !job_details }></job-item-loader>
	<div if={ job_details } class="selectedJob cell medium-12 large-12 callout border-rad-10 nopadding">
		<div class="head" >
			<div class="selected-job-details-actions" >
				<div class="selected-details">
					<div class="job-details-position"> <b>{ job_details.position_curated}</b></div>
					<div class="job-details-company"> <a href={'/institution/'+job_details.slug}><b>{ job_details.institution_name_curated}</b></a></div>
					<div class="job-details-loc-type" > 
						<span>{job_details.job_location_curated} </span>
							<p>·</p> 
						<span>{job_details.job_type_curated}</span>
					</div>
					<div class="job-details-loc-type" > 
						<span if={ job_details.min != 0 && job_details.max != 0 }>
							{ "USD "+ job_details.min + ' - ' + 'USD ' + ' ' + job_details.max } 
						</span>
						<span if={ job_details.fixed &&  job_details.fixed != 0 }>
							{ "USD "+ job_details.fixed  } 
						</span>
							<p>·</p> 
						<span>{ job_details.salary_type == "month" ? "MONTHLY":"HOURLY" }</span>
					</div>
					
					<div  class="gender_prefer"  > 
						<span>{ job_details.gender_prefer_curated }</span>
					</div>
				</div>
				<div class="actions"> 
					<div >{job_details.applied_users.length > 0 ? job_details.applied_users.length + ' Applicants': "Be the first to Apply" }</div>
					<div>
						<div if={!job_details.is_owner_requested}>
							<button if={ !hasApplied } onclick={applyJob} class="border-rad-10 button m-0">Easy Apply</button>
							<button if={ hasApplied } onclick={applyJob} class="border-rad-10 button m-0" disabled>Applied</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="body">

			<div if={ job_details.skills.length || job_details.languages.length } class="description_heading">
				<h4>Other Requirements</h4>
				<div if={ job_details.skills.length }>
					<div  ><h5>Skills</h5></div>
					<div  class="flex-container">
						<virtual each={item, index in job_details.skills}>
							<span class="text_tags1">
								{ item.skill_name.skill_name.replaceAll('-', ' ') }
							</span>
							<br>
						</virtual>
					</div>
				</div>
				<div if={ job_details.languages.length }>
					<div><h5>Languages</h5></div>
					<div  class="flex-container">
						<virtual each={item, index in job_details.languages}>
							<span class="text_tags1">
								{ item.languages_name.Name }
							</span>
							<br>
						</virtual>
					</div>
				</div>
			</div>

			<div class="description_heading mt-10"><h4>Description</h4></div>
			<div class="job_description">
				<span>
					{ job_details.description }
				</span>
			</div>
			
			<div class="job_questions">
				<div>Questions</div>
				<virtual if={ questions } each={ item, index in questions }>
					<span class="ques">
						{(index+1)+ ". "+ item }
					</span>
					<br>
				</virtual>
			</div>
			<div class="curated_created_time">
				<span>{job_details.created_at_curated}</span>
			</div>
		</div>
	</div>

	<div if={ job_details } class="tiny reveal" id="cvRequiredModal" class="border-rad-10" data-reveal data-close-on-click="true">
		<div  class="cv_required_modal">
			<p if={ job_details.cv_required } style="color:red;">CV is required.</p>
			<p if={ !opts.curr_user.cv } >Upload Your CV via your Profile(Will be automatically uploaded as your default CV)</p>
			<form id="formJobApply" ref="formJobApply" name="jobApplyForm" enctype="multipart/form-data"> 
				<input ref="cv" type="file" id="cv_job" name="cv" required>
				<button class="button border-rad-10" type="submit">
					<i if={ isbusy } class="fa fa-spinner fa-spin"></i>
					Apply With
				</button>
			</form> 
		</div>
		<div if={ job_details && opts.curr_user.cv  } >
			<a class="thumbnail" style="display: block;" href={ opts.curr_user.cv } download={ opts.curr_user.cv }> 
				<div class="downloadFile flex-container"> 
					<div>{ opts.curr_user.cv }</div> 
					<div class="timeline-file-icon"> <img src="images/timeline_file_default.png"> </div> 
				</div> 
			</a>
		</div>

		<button class="close-button" data-close aria-label="Close reveal" type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

	
	<div if={ !opts.authenticated } class="tiny reveal border-rad-10" id="loginModal" data-reveal data-close-on-click="true">
		<div  class="cv_required_modal">
			<p>You need to be Logged in to apply for Jobs</p>
			<a href={opts.home_url}>Click here</a>
		</div>

		<button class="close-button" data-close aria-label="Close reveal" type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

	<div if={ job_details && questions.length } class="tiny reveal" id="fillQuestions" class="border-rad-10" data-reveal data-close-on-click="true">
		<div  class="cv_required_modal">
			<p style="color:red;">Please Answers the following questions before submitting.</p>
			<form id="idFormQuestions" ref="refFillQuestions" name="nameFormQuestions" enctype="multipart/form-data"> 
				<virtual each={ question, index in questions }>
					<label for="q_{ index }">{ question }</label>
					<input type="text" name="q_{ index }">
				</virtual>
				<button onclick={ submitAnswers } ref="questionApply" class="button border-rad-10" type="menu">
					<i if={ isbusy } class="fa fa-spinner fa-spin"></i>
					Apply</button>
			</form> 
		</div>

		<button class="close-button" data-close aria-label="Close reveal" type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

	<style>
		.selectedJob .job-details-position {
			font-size:19px
		}
		.selectedJob .head {
			box-shadow: 0px 10px 5px -3px rgba(0,0,0,0.4);
			-webkit-box-shadow: 0px 10px 5px -3px rgba(0,0,0,0.4);
			-moz-box-shadow: 0px 10px 5px -3px rgba(0,0,0,0.4);
		}
		.selectedJob .body {
			/* background-color: darkgray; */
			overflow-y: scroll;
		}
		.selectedJob {
			position: fixed;
			width: 690px;
		}
		.selected-job-details-actions {
			display: flex;
			justify-content: space-between;
		}
		.selected-job-details-actions .selected-details div {
			margin-bottom: 7px;
		}
		.selectedJob .head, .selectedJob .body {
			padding: 1rem;
		}
		.selectedJob .actions {
			display: flex;
			flex-direction: column;
			justify-content: space-between;
			align-items: center;
		}
		.selectedJob .job_questions {
			display: flex;
			flex-direction: column;
			margin-top: 15px;
		}
		.selectedJob .job_questions .ques {
			font-weight: 600;
		}
		#formJobApply i.fa.fa-spinner.fa-spin {
			margin-right: 10px;
		}
		#idFormQuestions input[type="text"] {
			margin-bottom: 20px;
		}
	</style>

	<script>
		this.init = true;
		this.job_details = null;
		this.hasError = false;
		this.isbusy = false;
		this.answers = [];
		let hasApplied = false;
		let questions = [];
		let that = this;

		
		

		applyJob(e){
			
			if(!opts.authenticated){
				$( '#loginModal' ).foundation('open');
				return;
			}

			if(that.job_details.cv_required && !opts.curr_user.cv){
				$( '#cvRequiredModal' ).foundation('open');
				if(that.job_details){
					that.refs.formJobApply.onsubmit = function(e) {
						
						$('#formJobApply button[type="submit"]').disabled = true;
						that.isbusy = true;
						that.update();

						let formData = new FormData($('#formJobApply')[0]);
						formData.append('is_ajax', true);

						window.axios.post( opts.basePath+'/user/'+ opts.curr_user.username , formData, { headers: { 'Content-Type': 'multipart/form-data' } } )
							.then( function( response ) {
								if(response.data.success){
									that.isbusy = false;
									$.notify("CV Uploaded Successfully", {position:'bottom-right', className: 'success'});
									$( '#cvRequiredModal' ).foundation('close');
									opts.curr_user.cv = response.data.data.cv;
									that.update();
									that.verifyQuestions();
								}
							}).catch( function( error ) {
								console.log( error );
								that.isbusy = false;
								$('#formJobApply button[type="submit"]').disabled = false;
							});

						return false;
					};
				}
			}else{
				that.verifyQuestions();
			}
			
		}
		verifyQuestions(){
			if(that.questions.length){
				$( '#fillQuestions' ).foundation('open');
			}else{
				that.applyJobFinal();
			}
		}
		applyJobFinal(){
			let data ={ 'answers': that.answers };
			if(that.answers.length) {
				that.refs.questionApply.disabled = true;
			}
			that.isbusy = true;
			that.update();
			window.axios.post( '/job/'+this.job_details.job_id+'/apply', { 'data': data })
			.then( function( response ) {
				if(response.data.success){
					$.notify("Applied To Job Successfully", {position:'bottom-right', className: 'success'});
					that.isbusy = false;
					if(that.answers.length) {
						that.refs.questionApply.disabled = false;
						$( '#fillQuestions' ).foundation('close');
					}
					that.hasApplied = true;
					opts.reloadListings();
					that.update();
				}
			}).catch( function( error ) {
				console.log( error );
				$.notify("Something went wrong while applying", {position:'bottom-right', className: 'error'});
				that.isbusy = false;
					that.update();
				$('#formJobApply button[type="submit"]').disabled = false;
			});
		}
		function mount() {
			that.loadInitial();
		}
		loadInitial() {
			that.update();
			
		}
		this.on( 'job_selected_event', function(job) {
			that.job_details = job;
			that.hasApplied = that.job_details.applied_users.some(function(appplied) {
				return appplied.user_id === opts.curr_user.user_id;
			});
			that.questions = that.job_details.questions ? JSON.parse(that.job_details.questions) : [];
			that.update();
			$( '#cvRequiredModal' ).foundation();
			$( '#fillQuestions' ).foundation();
			$( '#loginModal' ).foundation();
			if($('.selectedJob .body')[0]){
				$('.selectedJob .body').height($(window.top).height()-350);
			}
			if(that.questions && that.questions.length){
				that.refs.refFillQuestions.onsubmit = function(e) {
					$("#idFormQuestions input[type='text']").each(function (params, item) {
						that.answers.push(item.value);
					})
					that.applyJobFinal();
					return false;
				}
			}
		} );
		this.on( 'mount', function() {
			mount();
			opts.callback(that);
			// For selected Job 
			// create an Observer instance
			const resizeObserver = new ResizeObserver(entries => {
				$('.selectedJob .body')[0] ? $('.selectedJob .body').height((entries[0].target.clientHeight-350)+"px") : ''; 
			})
			// start observing a DOM node
			resizeObserver.observe(document.body);
			if(that.init){
				that.init = !that.init;
			}
		} );
		
		
	</script>
</job-selected>