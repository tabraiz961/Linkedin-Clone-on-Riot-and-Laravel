<profile-skills>

	<virtual if={ opts.canedit || (items.top_appreciated && items.top_appreciated.length || items.followed && items.followed.length || items.others && items.others.length)  } >
		<div class="callout card profile-content-card border-rad-15 ">
			<div class="card-divider h5 space-between">
				<div>
					<b>Skills</b>
				</div>
				<div class="flex-container">
					<p class="maxSkillText mr2">Add Upto 20 Skills</p>
					<button type="button" class="addSkillsbtn border-rad-10 button" if={ opts.canedit } onclick={ openAdd }><i class="fas fa-plus"></i></button>
				</div>
			</div>
			<div class="card-section p-0">
				<div class="skills-block">
					<virtual >
						<section if={ items.top_appreciated && items.top_appreciated.length > 0 }>
							<h6>Top Appreciated</h6>
							<div class="tags_container">
								<div class={ (items.top_appreciated[0].is_owner ? 'owner': '') + ' text_beforeicon_tags'  }  onclick={ appreciate }>
									<div class="tag_text">{ items.top_appreciated[0].skill_name.skill_name }</div>
									<div class="tag_icon_counter">
										<img src="/images/icons/clap_hand_icon.png" alt="">
										<p>{ items.top_appreciated[0].appreciators.length }</p>
									</div>	
								</div>
								<!-- <img src="/images/icons/clap_hand_icon.png" alt="Clap Appreciate Icon"> -->
							</div>
						</section>
						
						<section  if={ items.followed && items.followed.length > 0 }>
							<h6>Follow Ups</h6>
							<div class="tags_container">
								<div each={ followtag in items.followed}  class={ (followtag.is_owner ? 'owner': '') + ' text_beforeicon_tags'  } onclick={ appreciate }>
									<div class="tag_text">{ followtag.skill_name.skill_name } </div>
									<div if={ followtag.appreciators.length } class="tag_icon_counter">
										<img src="/images/icons/clap_hand_icon.png" alt="">
										<p>{ followtag.appreciators.length }</p>
									</div>	
									
								</div>
							</div>
						</section>
						
						<section if={ items.others && items.others.length > 0 }>
							<h6>Others</h6>
							<div  class="tags_container">
								<div each={ othersTag in items.others} class={ (othersTag.is_owner ? 'owner': '') + ' text_beforeicon_tags'  } onclick={ appreciate }>
									<div class="tag_text">{ othersTag.skill_name.skill_name }  </div>
									<div if={ othersTag.appreciators.length } class="tag_icon_counter">
										<img src="/images/icons/clap_hand_icon.png" alt="">
										<p>{ othersTag.appreciators.length }</p>
									</div>	
								</div>
							</div>
						</section>
					</virtual>
				</div>
			</div>
		</div>
	</virtual>
	

	<div if={ opts.canedit  } ref="modal"  class="reveal border-rad-10" data-reveal data-close-on-click="true">
		<form onsubmit={ submit }>
			<div>
				<p style="color:red;">Carefull! Removing skills will also affect appreciations.</p>
				<label for="skills[]"></label>
				<select class="js-example-basic-multiple" id="profileSkillUpdate" name="skills[]" multiple="multiple">
				</select>
			</div>
			<div class="mt-20">
				<input class="border-rad-10 button " type="submit" value='Save'>
			</div>
		</form>

		<button class="close-button" data-close aria-label="Close reveal" type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

	<div  class="reveal tiny border-rad-10"   id="show-login-on-action" data-reveal >
		<div class="login_reveal_container flex-container flex-dir-column text-center" >
			<div><span>You need to Login to Appreciate <img src="/images/icons/clap_hand_icon.png" alt=""></span></div>
            <div class="mt-10"><a href="/" class="button border-rad-10">Login</a></div>
		</div>

		<button class="close-button" data-close aria-label="Close reveal" type="button">
			<span style="color: white;" aria-hidden="true">&times;</span>
		</button>
	</div>

	<style>
		button.addSkillsbtn {
			padding: 3px;
			font-size: 20px;
			color: #fefefe;
			border-radius: 5px;
			background: grey;
			right: 1rem;
			margin-top: 5px;
			margin-bottom: 0px;
		}
		p.maxSkillText{
			margin-bottom: 0px;
		}
		.skills-block {
			padding: 1rem;
		}
		.skills-block section h6 {
			font-size: 18px;
			font-weight: 600;
		}
		.skills-block section {
			margin-bottom: 16px;
		}
		
	</style>

	<script>
		this.items = [];
		let that = this;
		submit( e ) {
			e.preventDefault();
			e.stopImmediatePropagation();
			console.log($('#profileSkillUpdate').val());
			var data = {skills:$('#profileSkillUpdate').val()};
			$( that.refs.modal ).foundation( 'close' );
			window.axios.post( opts.skill_profile_update_link , data )
			.then(function (response) {
				if (response.data && response.data.success && response.data.data.profile_skills) {
					that.items = response.data.data.profile_skills;
					that.update();
				}
			})
			.catch( function( error ) {
				console.log( error );
			});
			return false;
		}
		openAdd(){
			$(that.refs.modal).foundation('open');
		}
		appreciate(e){
			if(opts.canedit){
				// Cannot Appreciate himself
				return;
			}
			if(opts.authenticated){
				// profile/{username}/appreciate/{skillId}
				window.axios.get( opts.skill_appreciate_link +'/'+ e.item[Object.keys(e.item)[0]].skill_id )
				.then(function (response) {
					console.log(response);
					if (response.data && response.data.success && response.data.data.profile_skills) {
						that.items = response.data.data.profile_skills;
						that.update();
					}
				})
				.catch( function( error ) {
					console.log( error );
				});
			}else{
				$('#show-login-on-action').foundation();
				$('#show-login-on-action').foundation('open');
			}
		}
		initialize(){
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
				width: '260px',
				minimumInputLength: 3,
				maximumInputLength: 20,
				minimumResultsForSearch: 10,
			});
			var itemKeys = Object.keys(that.items);
			for (let key = 0; key < itemKeys.length; key++) {
				for (let index = 0; index < that.items[itemKeys[key]].length; index++) {
					var option = new Option(that.items[itemKeys[key]][index].skill_name.skill_name, that.items[itemKeys[key]][index].skill_id, true, true);
					$('.js-example-basic-multiple').append(option).trigger('change');
				}
			}
		}
		this.on( 'mount', function() {
			if( opts.initialitems != null ) {
				that.items = opts.initialitems;
				if(opts.canedit){
					that.initialize();
					$( that.refs.modal ).foundation();
				}
			}
			that.update();
		});
	</script>
</profile-skills>