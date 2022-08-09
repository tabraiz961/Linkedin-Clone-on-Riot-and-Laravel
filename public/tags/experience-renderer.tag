<experience-renderer>
	<div class="myprofile-experience callout card profile-content-card border-rad-15 exp-rend">
		<div class="card-divider h5">
			<b onclick={runme}>Experience</b>
			<button type="button" class="add button" if={ opts.canedit } onclick={ openAdd }><i class="fas fa-plus"></i></button>
		</div>
		<div class="card-section pt-0">
			<div class="paragraph" each={ item in items }>
				<div>
					<div class="profile-exp-img">
						<img src="{ item.institute.institution_photo }" alt="">
					</div>
					<div class="profile-promotion-bar" if={ item.experiences.length > 1 }>
						<div class="promotion-circle"></div>
							<div class="promotion-line" style="height: 50px;"></div>
						 <div class="promotion-circle"></div>
					</div>
				</div>
				<div>
					<button if={ opts.canedit } type="button" class="button remove" onclick={ () => openRemoveInstitute(item)  }  ><i class="fas fa-times"></i></button>
					<!-- <button type="button" class="button secondary edit" if={ opts.canedit } onclick={ openEdit }><i class="fas fa-edit"></i></button> -->
					<a ><p class="h5"><strong>{ item.institute.institution_name }</strong></p></a>
					<p class="hint-color">
						<b>{ item.institute.duration }</b>
					</p>
					<p class="hint-color">
						<b>{ item.institute.address_custom }</b>
					</p>
					<div class="profile-titles-container job-multiple-expreiences">
						<div class="profile-titles-item" each={ experience in item.experiences } >
							<div>
								<strong><p>{ experience.experience_title }</p></strong>
								<p class="period hint-color">{ moment( experience.start ).format( 'MMM YY' ) } - { experience.end != null ? moment( experience.end ).format( 'MMM YY' ) : 'maintenant' }</p>
								<p class="hint-color">
									<b>{ experience.address_custom }</b>
								</p>
								<p class="description">{ experience.description }</p>
							</div>
							<div class="profile-titles-item-actions">
								<button type="button" class="button" if={ opts.canedit && item.experiences.length > 1 } data-experience-id={experience.experience_id} onclick={ ()=> openRemoveExperienceModal(experience) }><i class="fas fa-times"></i></button>
								<button type="button" class="button" if={ opts.canedit  } data-institute={ item.institute.institution_name } data-experience-id={experience.experience_id} onclick={ () => openEdit(this, experience, item)  }><i class="fas fa-edit"></i></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="paragraph" if={ items.length == 0 }>
				<p class="text-center">Insert your experiences to get connected with colleagues .</p>
			</div>
		</div>
	</div>

	<div ref="modal" class="reveal" id={ modalId } data-reveal data-close-on-click="true">
		<form onsubmit={ submit }>
			<div>
				<label for={ modalId + 'institution' } class="">Institution</label>
				<select onchange={getInstitution} ref="institution"  name="institution" id={ modalId + 'institution' }>
					
				</select>
				<!-- <input oninput={getInstitution} required ref="institution"  name="institution" type="text" id={ modalId + 'institution' }> -->
			</div>

			<div>
				<label for={ modalId + 'position' } class="">Position</label>
				<input required ref="position" class="" name="position" type="text" id={ modalId + 'position' }>
			</div>


			<div>
				<label for={ modalId + 'description' } class="">Description</label>
				<input required ref="description" class="" name="description" type="text" id={ modalId + 'description' }>
			</div>

			<div>
				<label for={ modalId + 'start' } class="">Start</label>
				<input required ref="start" class="" name="start" type="date" id={ modalId + 'start' }>
			</div>

			<div>
				<label for={ modalId + 'end' } class="">End</label>
				<input required ref="end" name="end" type="date" id={ modalId + 'end' }>
				<label>
					<input ref="currentWork" name="currentWork" type="checkbox" onclick={ currentWorkClicked }>
					I'm currently working
				</label>
			</div>

			<div>
				<input class="button expanded" type="submit" value={ edit ? 'Modifier' : 'Ajouter' }>
			</div>
		</form>

		<button class="close-button" data-close aria-label="Close reveal" type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

	<div ref="removeInstituteModal" class=" tiny reveal" id="removeInstituteModal" data-reveal data-close-on-click="true">
		<p class="criticalRed"> Warning: Also remove experiences associated with it! </p>
		<div class="removeInstituteBtns" >
			<button onclick={removeInstitute} class="button removeInstitute" style="background: red;" if={ removalInstitute && opts.canedit }>Remove { removalInstitute.institute.institution_name }</button>
			<button class="button ignore" onclick={closeRemoveInstituteModal} style="background: green;">No</button>
		</div>
		<button class="close-button" data-close aria-label="Close reveal" type="button">
	  		<span aria-hidden="true">&times;</span>
	  	</button>
	</div>

	<div ref="removeExperienceModal" class=" tiny reveal" id="removeExperienceModal" data-reveal data-close-on-click="true">
		<p class="criticalRed"> Warning: Removed data cannot be recovered! </p>
		<div class="removeInstituteBtns" >
			<button onclick={ removeExperience } class="button removeInstitute" style="background: red;" if={ removalExperience && opts.canedit }>Remove experience of { removalExperience.experience_title }</button>
			<button class="button ignore" onclick={ closeRemoveExperienceModal } style="background: green;">No</button>
		</div>
		<button class="close-button" data-close aria-label="Close reveal" type="button">
	  		<span aria-hidden="true">&times;</span>
	  	</button>
	</div>

	  <!-- <p><button class="button" data-open="removeConfirmation"></button></p> -->
	<style>
		.paragraph:not(:last-of-type) { 
			/* border-bottom: 1px solid #e6e6e6; */
		}

		.paragraph {
			position: relative;
		}

		.paragraph p {
			margin-bottom: 0;
		}
		.experience-renderer .paragraph p {
			min-height: 25px;
		}
		.paragraph p.period,
		.paragraph p.description {
			font-size: 0.9em;
			/* color: #666; */
		}
		.paragraph p.description {
			min-width: 612px;
		}
		button.add {
			font-size: 12px;
		}

		button.add,
		button.edit,
		button.remove {
			position: absolute;
			font-size: 12px;
			padding: 0.25rem;
		}

		button.edit,
		button.remove {
			top: 0;
		}

		button.add {
			right: 1rem;
		}

		button.edit {
			right: 0;
		}

		button.remove {
			right: 0rem;
			padding: 6px;
			border-radius: 3px;
			background: grey;
		}
		.profile-titles-item {
			margin: 15px 0px;
			display: flex;
    		justify-content: space-between;
		}
		.profile-promotion-bar {
			display: flex;
			margin-top: 8px;
			flex-direction: column;
			align-items: center;
		}
		.promotion-circle {
			height: 13px;
			width: 13px;
			border-radius: 50%;
			background: grey;
		}
		.promotion-line {
			width: 3px;
			height: 98px;
			background-color: gray;
		}
		.profile-titles-item-actions{
			display: flex;
			margin-top: 5px;
			height: 27px;
		}
		.profile-titles-item-actions button {
			margin: 0px 2px;
			border-radius: 4px;
			background: grey;
			width: 24px;
			padding: 0px;
		}
		.profile-titles-item-actions button.button i.fas {
			font-size: 12px;
		}
		.removeInstituteBtns {
			display: flex;
			justify-content: space-between;
		}
		.removeInstituteBtns button {
			border-radius: 5px;
		}
	</style>

	<script>
		this.modalId = Date.now();
		this.edit_experience_id = null;
		this.edit_experience_institution_id = null;
		this.edit = false;
		this.institution = [];
		this.items = [];
		this.removalInstitute = null;
		this.removalExperience = null;
		let that = this;
		runme(){
			that.promotionLinesCreator();
		}
		clear() {
			that.edit = false;
			that.edit_experience_id = null;
			$( that.refs.position ).val( '' );
			$( that.refs.description ).val( '' );
			$( that.refs.start ).val( '' );
			$( that.refs.end ).val( '' );
			$( that.refs.currentWork ).prop( 'checked', false );
			$( that.refs.end ).prop( 'disabled', false );
		}

		// removeExperience( experience ) {
		// 	console.log(experience);
		// 	// that.items.splice( that.items.indexOf( e.item ), 1 );
		// 	// that.update();
		// 	// that.save();
		// }

		add( item ) {
			that.save(item);
		}

		modify( item ) {
			// console.log(that.edit_experience_institution_id );
			// let imp_index = null;
			// for (let index = 0; index < that.items.length; index++) {
			// 	const element = that.items[index];
			// 	if( element.institute.institution_id == that.edit_experience_institution_id ){
			// 		for (let index2 = 0; index2 < element.experiences.length; index2++) {
			// 			const element2 = element.experiences[index2];
			// 			if(element2.experience_id == that.edit_experience_id){

			// 			}
			// 		}
			// 	}
			// }

			// that.items[ that.itemIndex ] = {};
			// for( let property in item ) {
			// 	if( item.hasOwnProperty( property ) ) {
			// 		that.items[ that.itemIndex ][ property ] = item[ property ];
			// 	}
			// }
			that.update();
			that.save(item);
		}

		openAdd( e ) {
			e.preventDefault();
			e.stopImmediatePropagation();
			that.edit = false;
			$( that.refs.modal ).foundation( 'open' );
		}

		openEdit(event, e ,e2) {
			// console.log(that.refs.institution.id);
			// console.log(e2.institute.institution_id);
			// console.log($( "#"+that.refs.institution.id + " option[value='"+e2.institute.institution_id+"']" ));
			// event.preventDefault();
			// event.stopImmediatePropagation();
			$( "#"+that.refs.institution.id + " option[value='"+e2.institute.institution_id+"']" ).attr("selected", "selected");
			$( that.refs.position ).val( e.experience_title );
			$( that.refs.description ).val( e.description );
			$( that.refs.start ).val( e.start );
			$( that.refs.end ).val( e.end );
			if( e.end == null ) {
				$( that.refs.currentWork ).prop( 'checked', true );
				that.currentWorkClicked();
			}
			that.edit = true;
			that.edit_experience_id = e;
			that.edit_experience_institution_id = e2.institute.institution_id;
			$( that.refs.modal ).foundation( 'open' );
		}
		openRemoveInstitute(item){
			that.removalInstitute = item;
			$( that.refs.removeInstituteModal ).foundation( 'open' );
		}
		removeInstitute(){
			$(that.refs.removeInstituteModal).foundation('close');
			window.axios.post( opts.basepath + '/user/experience/institute/remove/'+that.removalInstitute.institute.institution_id, { 'experience_type_id': 1 } )
			.then(function (response) {
				if (response.data && response.data.success) {
					that.items = that.items.filter(item => item.institute.institution_id != that.removalInstitute.institute.institution_id);
					that.update()
				}
			})
			.catch( function( error ) {
				console.log( error );
			});
		}
		openRemoveExperienceModal(item){
			that.removalExperience = item;
			$( that.refs.removeExperienceModal ).foundation( 'open' );
		}
		removeExperience(){
			$(that.refs.removeExperienceModal).foundation('close');
			window.axios.post( opts.basepath + '/user/experience/job/remove/'+that.removalExperience.experience_id, { 'experience_type_id': 1 } )
			.then(function (response) {
				console.log(response.data);
				if (response.data && response.data.success) {
					console.log(that.items);
					console.log(that.removalExperience.experience_id);
					that.items = that.items.filter(item => {
						item.experiences =  item.experiences.filter(item2 => {
							return item2.experience_id != that.removalExperience.experience_id
						});
						if(item.experiences.length == 0){
							return false;
						}
						return item;
					})
					that.update()
				}
			})
			.catch( function( error ) {
				console.log( error );
			});
		}
		closeRemoveInstituteModal(){
			$(that.refs.removeInstituteModal).foundation('close');
		}
		
		closeRemoveExperienceModal(){
			$(that.refs.removeExperienceModal).foundation('close');
		}
		submit( e ) {
			e.preventDefault();
			e.stopImmediatePropagation();
			if( !that.edit ) {
				that.add( that.generate() );
			} else {
				that.modify( that.generate(e) );
			}
			that.clear();
			$( that.refs.modal ).foundation( 'close' );
		}

		currentWorkClicked( e ) {
			$( that.refs.end ).prop( 'disabled', function( i, val ) { return !val; } );
		}

		generate(e) {
			
			let obj =  {
				institution_id: 		parseInt(document.getElementById(that.refs.institution.id).selectedOptions[0].value),
				is_currently_engaged: 	$( that.refs.currentWork ).prop( "checked" ) ? 1 : 0,
				experience_title: 		$( that.refs.position ).val(),
				description: 			$( that.refs.description ).val(),
				start: 					$( that.refs.start ).val(),
				end: 					$( that.refs.currentWork ).prop( "checked" ) ?  null : $( that.refs.end ).val()
			};
			if(that.edit_experience_id){
				obj['experience_id'] = that.edit_experience_id.experience_id;
				obj['experience_type_id'] = that.edit_experience_id.experience_type_id;
				obj['address_id'] = that.edit_experience_id.address_id;
			}else{
				obj['experience_type_id'] = 1;//job experience 
				obj['address_id'] = 0;
			}
			return obj;
		}

		save(item) {
			window.axios.post( opts.baseapipath + '/' + opts.username + '/experience', { 'data': item } )
			.then(function (response) {
				if(response.data && response.data.data){
					that.items = response.data.data;
					that.update();
					// that.promotionLinesCreator();
				}
			})
			.catch( function( error ) {
				console.log( error );
			});
		}
		promotionLinesCreator() {
			var _continer = document.getElementsByClassName('job-multiple-expreiences');
			for (let index = 0; index < _continer.length; index++) {
				var profile_promotion_bar = document.getElementsByClassName('exp-rend')[0].getElementsByClassName('profile-promotion-bar');
				for (let index2 = 0; index2 < profile_promotion_bar.length; index2++) {
					var element = _continer[index];
					var element2 = element.getElementsByClassName('profile-titles-item');
					if(element2.length>1 ){
						var cal_height = element2[element2.length-1].firstElementChild.getBoundingClientRect().y - element2[0].firstElementChild.getBoundingClientRect().y - 11;
						const bar = profile_promotion_bar[index2];
						if(bar.getElementsByClassName('promotion-line')){
							bar.getElementsByClassName('promotion-line')[0].style.height =  cal_height+"px";
							// document.getElementsByClassName('profile-promotion-bar')[index].getElementsByClassName('promotion-line')[0].style.height = cal_height+"px";
						}
					}
				}
			}
		}
		getandPopulateExperienceInstitution(){
			window.axios.get( opts.baseapipath + '/institutions/' + that.refs.institution.value)
			.then( function( response ) {
				if(response.data){
					response.data.forEach(element => {
						var option = document.createElement("option");
						option.text = element.institution_name;
						option.value = element.institution_id;
						that.refs.institution.add(option);
					});
				}
			})
			.catch( function( error ) {
				tag.error();
				console.log( error );
			} );
		}
		
		this.on( 'updated', function() {
			that.promotionLinesCreator();
		});
		this.on( 'mount', function() {
			if( opts.initialitems != null ) {
				that.items = opts.initialitems;
				that.update();
				that.promotionLinesCreator();
				that.getandPopulateExperienceInstitution();
			}
			$(that.refs.institution).trigger('change', true);
			$( that.refs.modal ).foundation();
			$(that.refs.removeInstituteModal).foundation()
			$(that.refs.removeExperienceModal).foundation()

		});
	</script>
</experience-renderer>