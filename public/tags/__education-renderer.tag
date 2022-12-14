<education-renderer>
	<div class="myprofile-experience callout card profile-content-card border-rad-15 edu-rend">
		<div class="card-divider h5">
			<b>Education</b>
			<button type="button" class="add button" if={ opts.canedit } onclick={ openAdd }><i class="fas fa-pen-square"></i></button>
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
					<button type="button" class="button alert remove" if={ opts.canedit } onclick={ remove }><i class="fas fa-times"></i></button>
					<button type="button" class="button secondary edit" if={ opts.canedit } onclick={ openEdit }><i class="fas fa-edit"></i></button>
					<a ><p class="h5"><strong>{ item.institute.institution_name }</strong></p></a>
					<p class="hint-color">
						<b>{ item.institute.duration }</b>
					</p>
					<p class="hint-color">
						<b>{ item.institute.address_custom }</b>
					</p>
					<div class="profile-titles-container edu-multiple-expreiences">
						<div class="profile-titles-item" each={ experience in item.experiences }>
							<strong><p>{ experience.experience_title }</p></strong>
							<p class="period hint-color">{ moment( experience.start ).format( 'MMM YY' ) } - { experience.end != null ? moment( experience.end ).format( 'MMM YY' ) : 'maintenant' }</p>
							<p class="hint-color">
								<b>{ experience.address_custom }</b>
							</p>
							<p class="description">{ experience.description }</p>
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
				<label for={ modalId + 'title' } class="">Statut</label>
				<input required ref="title" class="" name="title" type="text" id={ modalId + 'title' }>
			</div>

			<div>
				<label for={ modalId + 'company' } class="">Entreprise</label>
				<input required ref="company" class="" name="company" type="text" id={ modalId + 'company' }>
			</div>

			<div>
				<label for={ modalId + 'description' } class="">Description</label>
				<input required ref="description" class="" name="description" type="text" id={ modalId + 'description' }>
			</div>

			<div>
				<label for={ modalId + 'start' } class="">De</label>
				<input required ref="start" class="" name="start" type="date" id={ modalId + 'start' }>
			</div>

			<div>
				<label for={ modalId + 'end' } class="">A</label>
				<input required ref="end" name="end" type="date" id={ modalId + 'end' }>
				<label>
					<input ref="currentWork" name="currentWork" type="checkbox" onclick={ currentWorkClicked }>
					J'y travaille actuellement
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
			right: 1.5rem;
		}
		.profile-titles-item {
			margin: 15px 0px;
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
	</style>

	<script>
		this.modalId = Date.now();
		this.items = [];
		this.edit = false;
		let that = this;

		clear() {
			that.edit = false;
			that.itemIndex = null;
			$( that.refs.title ).val( '' );
			$( that.refs.company ).val( '' );
			$( that.refs.description ).val( '' );
			$( that.refs.start ).val( '' );
			$( that.refs.end ).val( '' );
			$( that.refs.currentWork ).prop( 'checked', false );
			$( that.refs.end ).prop( 'disabled', false );
		}

		remove( e ) {
			that.items.splice( that.items.indexOf( e.item ), 1 );
			that.update();
			that.save();
		}

		add( item ) {
			that.items.push( item );
			that.update();
			that.save();
		}

		modify( item ) {
			that.items[ that.itemIndex ] = {};
			for( let property in item ) {
				if( item.hasOwnProperty( property ) ) {
					that.items[ that.itemIndex ][ property ] = item[ property ];
				}
			}
			that.update();
			that.save();
		}

		openAdd( e ) {
			e.preventDefault();
			e.stopImmediatePropagation();
			$( that.refs.modal ).foundation( 'open' );
		}

		openEdit( e ) {
			console.log( e.item );
			e.preventDefault();
			e.stopImmediatePropagation();
			$( that.refs.title ).val( e.item.item.title );
			$( that.refs.company ).val( e.item.item.company );
			$( that.refs.description ).val( e.item.item.description );
			$( that.refs.start ).val( e.item.item.from );
			$( that.refs.end ).val( e.item.item.to );
			if( e.item.item.end == null ) {
				$( that.refs.currentWork ).prop( 'checked', true );
				that.currentWorkClicked();
			}
			that.edit = true;
			that.itemIndex = that.items.indexOf( e.item.item );
			$( that.refs.modal ).foundation( 'open' );
		}

		submit( e ) {
			e.preventDefault();
			e.stopImmediatePropagation();
			if( !that.edit ) {
				that.add( that.generate() );
			} else {
				that.modify( that.generate() );
			}
			that.clear();
			$( that.refs.modal ).foundation( 'close' );
		}

		currentWorkClicked( e ) {
			$( that.refs.end ).prop( 'disabled', function( i, val ) { return !val; } );
		}

		generate() {
			return {
				title: $( that.refs.title ).val(),
				company: $( that.refs.company ).val(),
				description: $( that.refs.description ).val(),
				from: $( that.refs.start ).val(),
				to: $( that.refs.currentWork ).prop( "checked" ) ?  null : $( that.refs.end ).val()
			};
		}

		save() {
			window.axios.post( opts.baseapipath + '/' + opts.username + '/experience', { 'data': that.items } ).catch( function( error ) {
				console.log( error );
			});
		}
		promotionLinesCreator() {
			// var _continer = document.getElementsByClassName('edu-multiple-expreiences');
			// for (let index = 0; index < _continer.length; index++) {
			// 	var element = _continer[index];
			// 	var element2 = element.getElementsByClassName('profile-titles-item');
			// 	if(element2.length>1 ){
			// 		var cal_height = element2[element2.length-1].firstElementChild.getBoundingClientRect().y - element2[0].firstElementChild.getBoundingClientRect().y - 11;
			// 		var profile_promotion_bar = document.getElementsByClassName('edu-rend')[0].getElementsByClassName('profile-promotion-bar');
			// 		for (let index2 = 0; index2 < profile_promotion_bar.length; index2++) {
			// 			const bar = profile_promotion_bar[index2];
			// 			if(bar.getElementsByClassName('promotion-line')){
			// 				bar.getElementsByClassName('promotion-line')[0].style.height =  cal_height+"px";
			// 				// document.getElementsByClassName('profile-promotion-bar')[index].getElementsByClassName('promotion-line')[0].style.height = cal_height+"px";
			// 			}
			// 		}
			// 	}
			// }
			var _continer = document.getElementsByClassName('edu-multiple-expreiences');
			for (let index = 0; index < _continer.length; index++) {
				var profile_promotion_bar = document.getElementsByClassName('edu-rend')[0].getElementsByClassName('profile-promotion-bar');
				for (let index2 = 0; index2 < profile_promotion_bar.length; index2++) {
					var element = _continer[index2];
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
		this.on( 'mount', function() {
			if( opts.initialitems != null ) {
				that.items = opts.initialitems;
				that.update();
				that.promotionLinesCreator();
			}
			$( that.refs.modal ).foundation();
		});
	</script>
</education-renderer>