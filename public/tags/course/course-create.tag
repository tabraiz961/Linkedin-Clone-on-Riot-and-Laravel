<course-create>

	<div >
		<h3>Create Course</h3>
	</div>

	<form action={opts.formSubmitLink} enctype="multipart/form-data" class="course-create" method="post" onsubmit={onsubmit} >
		<meta name="_token" type="hidden" value={ opts.csrfToken }>
		<meta name="csrf-token" content={ opts.csrfToken }>
		<div class="form-control"> 
			<label for="title">Title</label>
			<input  class="border-rad-10" ref="title" type="text" name="title" value={ course && course.course_title ? course.course_title : '' } required placeholder="Your Course Name">
			<p>Write a 30 characters long title of your course </p>
		</div>

		<div class="form-control">
			<label for="course_brief">Course Short Brief<span if={ briefReqError } style="color: red;">&nbsp; (Submit a short brief and few tag lines about the course)</span></label>
			<input ref="course_brief" class="border-rad-10" type="text"  name="course_brief" placeholder="Short Brief"  id="course_brief" value={ course && course.course_brief ? course.course_brief: ''} required>
			<p>A course brief is a short intro of 4 lines maximum of how course is important </p>
		</div>

		<div class="form-control">
			<label for="course-poster">Public course picture<span if={ picReqError } style="color: red;">&nbsp; (File Required)</span></label>
			<input data-max-files="1" type="file" name="course-poster"  accept="image/png, image/jpeg, image/gif" id="course-poster">
			<p>Upload a course picture that resembles the course topic </p>
		</div>
		
		<div class="form-control">
			<div class="space-between">
				<div>
					<label for="course-points">Brief points of what your viewers will learn in this course</label>
					<div class="flex-container" each={ point, index in points}>

						<input class="border-rad-10" type="text" placeholder="{index+1}." name="course-points[]" id="course-points{index}" value={ point } > 
						<button type="button" class="button border-rad-10 removeBtn" onclick={removePoint}>x</button>
					</div>
				</div>
				<div>
					<button type="button" class="button addBtnBig border-rad-10 addBtn" onclick={addPoint}>+</button>
				</div>
			</div>
			<p>Each point must be 20 characters long</p>
		</div>

		<div id="blocks_container" class="form-control flex-dir-column">
			<div class="space-between">
				<label id>Get started with course Material</label>
				<button if={ course && course.get_blocks } class="button border-rad-10 addBtn" type="button"  onclick={ toggleCourseMaterial }>+</button>
			</div>

			<div if={!course} class="text-center">
				<button type="button" class="button border-rad-10 addBtnBig" onclick={ toggleCourseMaterial }>+</button>
			</div>
			<div if={ course && course.get_blocks } class="custom-dropdowns">
				<div class="border-sides"  each={ block in course.get_blocks }>
					<div class="flex-container">
						<div onclick={ toggleBlock } id="block_{block.id}" class="block space-between">
							<div class="arrows">{ block.block_name }</div>
							<div>{ block.course_files.length} Files</div>
						</div>
						<div onclick={ toggleEditCourseMaterial } class="editBlockClass"><i  class="fas fa-trash"></i></div>
					</div>
					<ul id="toggle_{block.id}" style="display: none;">
						<li class="space-between" each={ files in block.course_files}>
							<div>
								<input placeholder="What discribes this video ?" class="border-rad-10" name="defined_detail_{files.id}" type="text" value={files.defined_detail} id="defined_detail_{files.id}" required>
								<span id="defined_detail_error_{files.id}" style="display: none;color: red;">File Detail is required!</span>
							</div>
							<div class="dottedSingleTxt text_width_6"><i class="fas fa-{files.info}"></i> { files.file_name.length > 47 ? files.file_name.substring(0, 47) : files.file_name}</div>
							<div>
								<button type="button" onclick={deleteFile} class="removeFileBtn border-rad-10 button m-0">
									<i class="fas fa-minus"></i>
								</button>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="form-control">
			<div class="space-between">
				<div>
					<label for="course-requirements">Course Requirements &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</label>
					<div class="flex-container" each={ requirement, index in requirements}>
						<input class="border-rad-10" type="text" placeholder="{index+1}." name="course-requirements[]" id="course-requirements{index}" value={requirement} > 
						<button type="button" class="button border-rad-10 removeBtn" onclick={removeRequirement}>x</button>
					</div>
				</div>
				<div>
					<button type="button" class="button border-rad-10 addBtn" onclick={addRequirement}>+</button>
				</div>
			</div>
			<p>Each point must be 20 characters long</p>
		</div>

		<div class="form-control">
			<label for="course-description">Description</label>
			<div name="course-description" id="course-description-editor" ></div>
		</div>
		<div class="form-control course-switch flex-container">
			<span>Active</span>
			<label class="switch-forms">
				<input if={course} ref="activeStatus" type="checkbox" checked={ course.status  }>
				<span class="slider round"></span>
			</label>
		</div>
		
		<button ref='submitBtn' type="button" onclick={onsubmit} class="button border-rad-10 mt-20">Submit</button>
	</form>


	<div  class="reveal border-rad-10 course-material-popup" id="course-material-popup" data-reveal data-close-on-click="false" data-close-on-esc="false">
		<div class="cv_required_modal">
			<div class="course-material-block mt-20">
				<input ref="block_name" required type="text" class="border-rad-10" name="BlockName" placeholder="Block Name">
				<p if={blockNameRequired} style="color:red;">Please Enter a Block Name</p>
			</div>
			<div class="form-control">
				<label for="course-material">Course Material allows 7 files max each block</label>
				<div>
					<!-- <input type="text" placeholder="Define Item" class="border-rad-10" each={material in coursesMaterials}> -->
					<input ref={course-material}  data-max-files="7" multiple  data-allow-reorder="true" type="file" name="course-material" id="course-material">
				</div>
			</div>
			<button type="button" class="button mt-20 border-rad-10" onclick={saveBlock}>Save</button>
		</div>

		<button class="close-button" onclick={delCourseBlockEmpty} data-close aria-label="Close reveal" type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

	<div  class="reveal border-rad-10 "  id="course-material-block-remove-popup" data-reveal data-close-on-click="true">
		<p>Are you sure you want to remove this block ? </p>
		<p class="font20" style="font-weight: 600;">{removeBlockName}</p>
		<div class="space-between">
			<button class="button border-rad-10" onclick={closePopUp}>No</button>
			<button type="button" class="button border-rad-10" onclick={removeBlock}>Yes</button>
		</div>

		<button class="close-button" onclick={} data-close aria-label="Close reveal" type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	
	<style>
		.form-control {
			margin: 33px 0px;
		}
		.course-create input, .course-create p {
			margin-bottom: 8px;
		}
		form.course-create label,#course-material-popup .form-control label {
			font-size: 17px;
			font-weight: 634;
		}
		button.addBtn, button.removeBtn {
			font-size: 35px;
			padding: 1px 10px;
		}
		button.removeBtn {
			margin-left: 10px;
		}

		.custom-dropdowns ul {
			list-style-type: none;
			padding: 0;
			margin: 0;
		}

		.custom-dropdowns ul li {
			border-bottom: 2px solid #ddd;
			margin-top: -1px; /* Prevent double borders */
			background-color: #f6f6f6;
			padding: 12px;
		}
		.block {
			padding: 8px 15px;
			cursor: pointer;
			font-size: 20px;
			border-bottom: 2px solid #c2c2c2;
			background: #f1f0ef;
			font-weight: 600;
			flex: 10 1 auto;
		}
		.block .arrows:before {
			content: '\02C4';
		}
		
		.block .arrows.down:before {
			content: '\02C5';
		}
		.block .arrows:before {
			margin-right: 10px;
		}
		.custom-dropdowns ul li i {
			margin-right: 7px;
		}

		.editBlockClass {
			flex: 1 0 auto;
			align-items: center;
			justify-content: center;
			border-bottom: 2px solid #c2c2c2;
			cursor: pointer;
			display: flex;
			background: #f1f0ef;
		}
		.editBlockClass:hover {
			background: #385072;
			color: white;
		}
		.removeFileBtn{
			padding: 3px;
    		background: black;
		}
		.removeFileBtn i {
			margin-right: 0px !important;
		}
	</style>

	<script>
	// 	min-LENGTH
		this.data = [];
		this.points = [];
		this.requirements = [];
		this.briefReqError = false;
		this.picReqError = false;
		this.pond1Block = null;
		this.pond2Block = null;
		this.blockId = null;
		this.removeBlockName = null;
		this.blockNameRequired = false;
		this.course = null;
		this.loading = true;
		this.coursesMaterials = [];
		this._editor = null;
		
		
		let that = this;
		toggleBlock(block){
			if(block.target.children[0]){
				let index = block.target.children[0].classList.contains('down');
				if(block.target.children[0].classList.contains('down'))
				{
					block.target.children[0].classList.remove('down');
				}else{
					block.target.children[0].classList.add('down');
				}
				$('#toggle_'+block.item.block.id).slideToggle();
			}
		}
		addPoint(){
			that.points.push('');
			that.update();
		}
		removePoint(e){
			that.points.splice(e.item.index, 1);
			that.update();
		}
		
		addRequirement(){
			that.requirements.push('');
			that.update();
		}
		
		removeRequirement(e){
			console.log(e.item.index);
			that.requirements.splice(e.item.index, 1);
			that.update();
		}
		onsubmit(e){
			let blockFilesInputs = [];
			let emptyFileInput = false;
			for (let index = 0; index < that.course.get_blocks.length; index++) {
				var block_obj = {};
				block_obj[that.course.get_blocks[index].id] = {};
				for (let index2 = 0; index2 < that.course.get_blocks[index].course_files.length; index2++) {
					let field = $('#defined_detail_'+that.course.get_blocks[index].course_files[index2].id);
					if(field.val() == '') {
						
						if(!$('#block_'+that.course.get_blocks[index].id).children()[0].classList.contains('down')) {
							$('#block_'+that.course.get_blocks[index].id).click();
						}
						$('#defined_detail_error_'+that.course.get_blocks[index].course_files[index2].id).css('display','block');
						emptyFileInput = true;
					}
					block_obj[that.course.get_blocks[index].id][that.course.get_blocks[index].course_files[index2].id] = field.val();
				}
				blockFilesInputs.push(block_obj);
			}
			if(emptyFileInput){
				$('html,body').animate({scrollTop: $('#blocks_container').offset().top},'slow');
				return false;
			}
			e.preventDefault();
            e.stopImmediatePropagation();
			that.update();
			var formData = new FormData();
			formData.append('title', that.refs.title.value);
			formData.append('block_inputs', JSON.stringify(blockFilesInputs));

			if(!that.pond1Block.getFile() || !that.refs.course_brief.value){
				if(!that.pond1Block.getFile()){
					that.picReqError = true;
					document.documentElement.scrollTop = 0
				}
				if(that.refs.course_brief.value == ''){
					that.briefReqError = true;
				}
				that.update();
				return false;
			}
			formData.append('status_checked', that.refs.activeStatus.checked);
			formData.append('course_picture', that.pond1Block.getFile().file);
			formData.append('course_brief', that.refs.course_brief.value);

			let temp_points = [];
			for (let index = 0; index < that.points.length; index++) {
				temp_points.push($('#course-points'+index).val()); 
			}
			formData.append('course-points',JSON.stringify(temp_points));
			
			let temp_requirements = []; 
			for (let index = 0; index < that.requirements.length; index++) {
				temp_requirements.push($('#course-requirements'+index).val());
			}
			formData.append('course-requirements',JSON.stringify(temp_requirements));

			formData.append('description_ck',that._editor.getData());

			// for (const value of formData.values()) {
			// 	console.log(value);
			// }
			that.refs.submitBtn.disabled = true;
			window.axios.post(opts.formSubmitLink, formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				}
			}).then(function (response) {
				if(response.data ){
					window.location.href = opts.mycourse_url;
				}
			}).catch(function (error) {
				that.refs.submitBtn.disabled = false;
				console.log(error);
			})
			return false;

		}
		closePopUp(){
			$('#course-material-block-remove-popup').foundation('close');
		}
		removeBlock(){
			console.log(opts.courseBlockDelUrl+that.blockId);
			window.axios.get(opts.courseBlockDelUrl+'/'+that.blockId).then(function (response) {
				if(response.data.success){
					that.updateCourseFiles();
				}
			}).catch(function (error) {
				console.log(error);
			})
			$('#course-material-block-remove-popup').foundation('close');
		}
		saveBlock(){
			// console.log(that.refs.block_name);
			if(that.refs.block_name.value == ''){
				that.blockNameRequired = true;
				return;
			}
			let tempServerIds = that.pond2Block.getFiles().map(function (params) {
				return params.serverId
			});
			
			// console.log(that.pond2Block.getFiles());
			// Fix first item null issue
			if(tempServerIds.length>0 && tempServerIds[0] == null){
				tempServerIds = [];
			}
			window.axios.post(opts.courseFilesconfirm,{'ids': tempServerIds, 'blockName': that.refs.block_name.value, 'blockId': that.blockId}).then(function (response) {
				if(response.data.success){
					$('#course-material-popup').foundation('close');
					
					// Remove pond files 
					var pond_ids = [];
					if (that.pond2Block.getFiles().length != 0) {  // "pond" is an object, created by FilePond.create 
						that.pond2Block.getFiles().forEach(function(file) {
							pond_ids.push(file.id);
						});
					}
					that.pond2Block.removeFiles(pond_ids);

					that.refs.block_name.value = '';
					that.blockId = null;
					that.update();
					that.updateCourseFiles();
				}
			}).catch(function (error) {
				console.log(error);
			})
		}
		updateCourseFiles(updateOtherData = false){
			// opts.courseBlocks
			window.axios.get(opts.courseBlocks).then(function (response) {
				if(response.data.data && response.data.success){
					that.course = response.data.data;
					console.log(that.course);
					if(updateOtherData){
						that.points = that.course.course_points ? JSON.parse(that.course.course_points) : [];
						that.requirements = that.course.course_requirements ?  JSON.parse(that.course.course_requirements) : [];
						that._editor.setData(that.course && that.course.course_description ? that.course.course_description : '');
					}
					
					// that.points = that.course.course_points.map( (item,index) => {
					// 	return index;
					// })
					that.update();

					// Set Auto saved poster
					if(that.course.course_image && that.pond1Block.getFiles().length == 0){
						// console.log(opts.courseStoragePath + '/'+ opts.course_id + '/course-poster/' + that.course.course_image);
						that.pond1Block.setOptions({
							files: [
								{
									source: opts.courseStoragePath + '/'+ opts.course_id + '/course-poster/' + that.course.course_image,
									options: {
										type: 'remote',
									}
								}
							],
							// server: {
							// 	load: (source, load, error, progress, abort, headers) => {
							// 		var myRequest = new Request(source);
							// 		fetch(myRequest).then(function(response) {
							// 			response.blob().then(function(myBlob) {
							// 				console.log(myBlob);
							// 				load(myBlob)
							// 			});
							// 		});
							// 	},
							// }
						});
					}
					
				}
			}).catch(function (error) {
				console.log(error);
			})
		}
		generateCourseBlockId() {
			window.axios.get(
				opts.courseBlockGenUrl, { params: { course_id: opts.course_id } }
			).then( function( response ) {
				if(response.data && response.data!=''){
					that.blockId = response.data;
					that.update();
					$('#course-material-popup').foundation('open');
					// that.delCourseBlockEmpty();
					that.initMultiFileBlock();
					that.update();
				}
			}).catch( function( error ) {
				console.log(error);
			});
			
		}
		delCourseBlockEmpty(){
			if(that.refs.block_name.value == '' && (that.pond2Block.getFiles() == 0)){
				window.axios.get(opts.courseBlockDelUrl+'/'+that.blockId).then(function (response) {
					if(response.data.success){
						that.updateCourseFiles();
					}
				}).catch(function (error) {
					console.log(error);
				})
			}
		}
		initMultiFileBlock(){
			const inputElement2 = document.querySelector('#course-material-popup input#course-material');
			if(that.pond2Block == null){
				that.pond2Block = FilePond.create(inputElement2, 
				{
					server: {
						url: window.origin,
						process: {
							url: opts.formFileAsyncPath.replace(window.origin,''),
							method: 'POST',
							withCredentials: false,
							headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
							timeout: 7000,
							onerror: null,
							onload: (response) => {
								// console.log(response);
								// if(response!=''){
								// 	that.coursesMaterials.push(that.coursesMaterials.length+1);
								// 	that.update();
								// }
								return response;
							},
							ondata: (formData) => {
								formData.append('id', that.blockId);
								// console.log(Object.keys(formData));
								return formData;
							},
						},
						revert: (uniqueFileId, load, error) => {
							// Should remove the earlier created temp file here
							console.log(uniqueFileId);
							// file is the actual file object to send
							const formData = new FormData();
	
							const request = new XMLHttpRequest();
							request.open('DELETE', opts.formFileAsyncPath.replace(window.origin,'')+'/'+uniqueFileId);
							request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
	
							// Should call the load method when done and pass the returned server file id
							// this server file id is then used later on when reverting or restoring a file
							// so your server knows which file to return without exposing that info to the client
							request.onload = function () {
								if (request.status >= 200 && request.status < 300) {
									
									// Should call the load method when done, no parameters required
									load();
								} else {
									// Can call the error method if something is wrong, should exit after
									error('oh no');
								}
							};
	
							request.send(formData);
	
							
						},
					},
				});
			}
		}
		toggleCourseMaterial(){
			
			that.generateCourseBlockId();
			
		}
		
		toggleEditCourseMaterial(block){
			$('#course-material-block-remove-popup').foundation('open');
			that.blockId = block.item.block.id;
			that.removeBlockName = block.item.block.block_name;
			console.log(block);
			// var coursesFiles = block.item.block.course_files.map(function (params) {
			// 	return opts.courseStoragePath + '/' + block.item.block.course_id + '/' + block.item.block.id + '/' + params.file_name
			// })
			// that.pond2Block.addFiles(coursesFiles);
			that.update();
		}
		deleteFile(item){
			console.log(item);
			window.axios.post(opts.courseFileDelUrl, {'fileId': item.item.files.id})
			.then(function (params) {
				if(params.data.success){
					that.updateCourseFiles();
				}
			}).catch(function (error) {
				console.log(error);
			})
		}
		init(){
			ClassicEditor
			.create( document.querySelector( 'form.course-create #course-description-editor' ) )
			.then( editor => {
				that._editor = editor;
			} )
			.catch( error => {
					console.error( error );
			} );

			FilePond.registerPlugin(
				FilePondPluginImagePreview,
				FilePondPluginFileValidateType
			);
			
			const inputElement = document.querySelector('form.course-create input#course-poster');
			that.pond1Block = FilePond.create(inputElement, {
				acceptedFileTypes: ['image/*'],
				fileValidateTypeDetectType: (source, type) =>
					new Promise((resolve, reject) => {
						resolve(type);
					}),
			});
			that.updateCourseFiles(true);
		}
		this.on( 'mount', function() {
			 // Get a reference to the file input element
			$('#course-material-popup').foundation();
			$('#course-material-block-remove-popup').foundation();
			that.init();
			if(that.data){
				that.update();
			}
		} );
		
		

	</script>
</course-create>