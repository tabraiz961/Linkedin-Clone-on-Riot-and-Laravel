<job-application-select>
	<div class="cell medium-12 large-12 hide-for-small-only">
		<div class="cell callout border-rad-10">
			<div class="bottom-border"><h3>Job Applications</h3></div>
			
			<select  if={data} name="job_select" id="job_select" class="border-rad-10 mt-20">
				<option each={jobs in data} onclick={selected} value={jobs.job_id}>{jobs.position}</option>
			</select>
			<div class="job-applications-users-container mt-20">
				<div if={!loading} >
					<table id="table_id" class="display">
						<thead>
							<tr>
								<th>Users</th>
								<th>Top Matching Skills</th>
								<th>Last Experience</th>
								<th style="vertical-align: initial;">CV</th>
								<th >Answers</th>
							</tr>
						</thead>
						<tbody each={user in users }>
							<tr>
								<td >{user.curated_name}</td>
								<td if={user.profile && user.profile.profile_skills && user.profile.profile_skills.length} class="tags_container">
									<div  class="text_beforeicon_tags { highlightingSkillsIds.indexOf(skill.skill_id) >=0 ? 'matching': ''}" each={skill in user.profile.profile_skills}>
										<div  class="tag_text">{ skill.skill_name.skill_name }  </div>
										<div if={ skill.appreciators.length } class="tag_icon_counter">
											<img src="/images/icons/clap_hand_icon.png" alt="">
											<p>{ skill.appreciators.length }</p>
										</div>	
									</div>
								</td>
								<td> 
									<virtual if={user.job_experience && user.job_experience[0] && user.job_experience[0].experiences && user.job_experience[0].experiences[0]}>
										{ user.job_experience[0].experiences[0].experience_title }  
									</virtual>
								</td>
								<td>
									<virtual if={user.cv_url}>
										<a href={user.cv_url} download={user.cv_url.split('/').pop()}>Download</a>
									</virtual>
								</td>
								<td >
									<a onclick={revealAnswers}>Show</a>
								</td>
							</tr>
							<!-- <tr>
								<td>Row 2 Data 1</td>
								<td>Row 2 Data 2</td>
								<td>Row 2 Data 2</td>
							</tr> -->
						</tbody>
					</table>
				</div>
				<div if={loading}>
					<div class="loading-container" >
						<ul class="loading-list-item-container">
							<li class="border-rad-10"></li>
						</ul>
					</div>
					<style>
						.loading-container{
							margin-bottom: 15px;
						}
						.loading-container .loading-list-item-container {
							margin: 0px;
							width: auto; 
							background: #fff;
							border-radius: 5px;
							box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
							padding: 0px;
						}
						.loading-container .loading-list-item-container li {
							list-style-type: none;
							height: 264.97px;
							border-bottom: 1px solid #f5f7f8;
							background: #c2c2c2;
							background: linear-gradient(110deg, #c2c2c2 8%, #d7d4d4 18%, #c2c2c2 33%);
							background-size: 200% 100%;
							animation: 1s shine linear infinite; 
						}
						
						@keyframes shine {
							to {
							background-position-x: -200%;
							}
						}
					</style>
				</div>
			</div>
			
		</div>

	</div>

	<div if={ jobselected && jobselected.questions && revealAnswersUser  } class="tiny reveal border-rad-10" ref={filledquestion} id="fillQuestionsAnswers" data-reveal data-close-on-click="true">
		<div  class="cv_required_modal">
			<p if={!jobselected.questions}>
				No Questions
			</p>
			<form if={ jobselected.questions.length } id="idFormQuestions" ref="refFillQuestions" name="nameFormQuestions" enctype="multipart/form-data"> 
				<virtual each={ question, index in jobselected.questions }>
					<label for="q_{ index }">{(index+1)+'. '+ question }</label>
					<input type="text" if={revealAnswersUser && revealAnswersUser.answers && revealAnswersUser.answers[index]} name="a_{ index }" value="{ revealAnswersUser.answers[index] }">
				</virtual>
			</form> 

		</div>

		<button class="close-button" data-close aria-label="Close reveal" type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

	<!-- 
	
	<div if={ opts.job.is_owner_requested } class="tiny reveal" id="deleteJob" class="border-rad-10" data-reveal data-close-on-click="true">
		<div  class="cv_required_modal">
			<p style="color:red;">Are you sure you want to delete this job?</p>
			<div class="space-around">
				 <button class="button border-rad-10" onclick={confirmDelete}>Yes</button>
				 <button class="button border-rad-10">No</button>
			</div>
		</div>

		<button class="close-button" data-close aria-label="Close reveal" type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div> -->

	<style>
		.job-applications-users-container {
			min-width: 1031px;
		}

		select#job_select {
			max-width: 402px;
		}

	</style>

	<script>
	// 	min-LENGTH
	// hourly salary layout
		this.data = [];
		this.jobselected = null;
		this.highlightingSkillsIds = [];
		this.revealAnswersUser = null;
		this.loading = true;
		this.users = [];
		this.datatable = null;
		let that = this;
	
		// selected(e){
		// 	console.log(e);
		// }

		revealAnswers(user){
			that.revealAnswersUser = user.item.user;
			that.update();
			console.log(that.revealAnswersUser.answers);
			console.log(that.jobselected.questions);
			if(that.revealAnswersUser && that.jobselected.questions){
				$('#fillQuestionsAnswers').foundation();
				$('#fillQuestionsAnswers').foundation('open');
			}
			that.update()
		}
		this.on( 'mount', function() {
			
			that.table = $('#table_id').DataTable({
				"language": {
					"emptyTable":     "No applications received yet!"
				}
			});
			if(opts.jobsListing){
				that.data = opts.jobsListing;
				that.update();
				
				var job = that.getSelectedJob();
				that.jobselected = (job && job[0]) ? job[0] : null;
				
				that.highlightingSkillsIds = that.jobselected.skills.map(function(item){return item.skill_id;});
				
				that.getSelectedJobUsers();
				$("#job_select").change(function(){
					that.loading = true;
					that.update();
					var job = that.getSelectedJob();
					that.jobselected = (job && job[0]) ? job[0] : null;
					that.getSelectedJobUsers();
				});
				
				that.update();
			}
		} );
		
		getSelectedJob() {
			var optionId = $( "#job_select" ).val();
			
			return that.data.filter(function (params) {
				return params.job_id == optionId
			})
		}
		
		getSelectedJobUsers(){
			if(that.jobselected){
				that.loading = true;
				window.axios.get(opts.getJobDetailsLink +'/' +that.jobselected.job_id).then( function( response ) {
					if(response.data && response.data.data && response.data.success ){
						that.users = response.data.data.users;
						that.highlightingSkillsIds = that.jobselected.skills.map(function(item){return item.skill_id;});
						// Move to top matching skills 
						for (let user = 0; user < that.users.length; user++) {
							if(that.users[user] && that.users[user].profile && that.users[user].profile.profile_skills){
								var moveToTopSkills  =[];
								// Reverse necessary
								for (var index = that.users[user].profile.profile_skills.length -1; index >= 0; index--) {
									if(that.highlightingSkillsIds.indexOf(that.users[user].profile.profile_skills[index].skill_id) >= 0 ){
										moveToTopSkills.push(that.users[user].profile.profile_skills.splice(index, 1)[0]);
									}
								}
								that.users[user].profile.profile_skills.unshift(...moveToTopSkills);
							}
						}
					}
					that.loading = false;
					that.table.draw();
					that.update();
					
				}).catch( function( error ) {
					that.loading = false;
					that.table.draw()
					that.update();
					console.log( error );
				});
			}
		}


	</script>
</job-application-select>