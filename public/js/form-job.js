// var entity_id = $( 'input[name="entity_id"]' );
// var entity_is_selected = $( '#post_entity_isselected' );

// function entitiesGet( tag ) {
// 	tag.isLoading();
// 	window.axios.get( apiurl + '/entity' )
// 	.then( function( response ) {
// 		tag.notLoading();
// 		let items = response.data;
// 		for( let i = 0; i < items.length; i++ ) {
// 			if( items[ i ].entity_id == entity_id.val() ) {
// 				items[ i ].selected = true;
// 			}
// 		}
// 		tag.setItems( items );
// 	}).catch( function( error ) {
// 		tag.notLoading();
// 		console.log( error );
// 	});
// }

// function onEntitySelected( entities ) {
// 	if( entities.length > 0 ) {
// 		entity_id.val( entities[ 0 ].entity_id );
// 		entity_is_selected.show();
// 	} else  {
// 		entity_id.val();
// 		entity_is_selected.hide();
// 	}
// }

// function onEntityCancelled() {
// 	entity_id.val();
// 	entity_is_selected.hide();
// }

// $( '#post_entity_selector' ).click( function( e ) {
// 	e.preventDefault();
// 	e.stopImmediatePropagation();
// 	$( 'body' ).append( $( '<tag-selector></tag-selector>' ) );
// 	window.riot.mount( 'tag-selector', {
// 		onSelected: onEntitySelected,
// 		onCancelled: onEntityCancelled,
// 		component: 'entity-renderer',
// 		itemGetInitier: entitiesGet,
// 		hasAdd: false,
// 		unique: true
// 	});
// }); 
 

function salaryToggle(params) {
		var disabled = false;
		if(params == 'hour'){
			disabled = true;
		}else{
			$("input[name=fixed]").prop('disabled', false);
			$("input[name=min]").prop('disabled', false);
			$("input[name=max]").prop('disabled', false);
			return;
		}
		$("input[name=fixed]").prop('disabled', !disabled);
		$("input[name=min]").prop('disabled', disabled);
		$("input[name=max]").prop('disabled', disabled);
		$("input[name=min]").val(0);
		$("input[name=max]").val(0);
	}
	$("input[name=fixed]").keyup(function (params) {
		$("input[name=min]").val(0);
		$("input[name=max]").val(0);
	})
	
	$("input[name=min], input[name=max]").keyup(function (params) {
		$("input[name=fixed]").val(0);
		
	})
$(document).on('click', '#removeRow', function () {
	$(this).closest('#inputFormRow').remove();
});
function addQuestion(params) {
	// console.log(params);
	// var question = $("input[name=newQuestion]").val();
	// if(question!=''){
		var prefix = $("#question-container")[0].children.length + 1;
		var html = '';
		html += '<div id="inputFormRow">';
		html += '<div class="input-group mb-3">';
		html += '<label for="questions">'+prefix+") "+'</label>';
		html += '<input type="text" name="questions[]"  placeholder="Enter Questions" autocomplete="off" required>';
		html += '<div class="input-group-append">';
		html += '<button id="removeRow" type="button" class="button">Remove</button>';
		html += '</div>';
		html += '</div>';
	
		$("#question-container").append(html);
		// $("input[name=newQuestion]").val('');
	// }
	// $('#jobQuestionCreate').foundation('close');
}