<post-form>
	<form ref="form" class="form-container" enctype="multipart/form-data" onsubmit={ submit }>
		<div class="img_selected_container">
			<input type="file" id="post_files" class="hide">
			<!-- <input > -->
			<span ref="text_content" style="cursor: text;" name="description" class="text-content textarea" required
				role="textbox" contenteditable></span>
			<!--<div if={ opts.addsopts.fileDragActive } style="background: url('/images/drop-here-timeline-default.png') no-repeat;height: 226px;"></div> -->
			<button class="button float-right post_submit_btn" type="submit"><i class="fas fa-paper-plane"></i></button>
		</div>
		<div class="toolbar flex-container" if={ opts && opts.addsopts && opts.addsopts.post_visibility}>
			<!-- <button ref="photo_button" class="button" type="button" name="photo_button" onclick={ openImageSelector }><i
					class="fa fa-image"></i></button> -->
			<button class="button" type="button" name="form_image_button" onclick={ openfileUpload }
				id='form_image_button'>
				<i class="fa fa-image"></i>
				<p>Photo</p>
			</button>
			<button ref="video_button" class="button" type="button" name="video_button" onclick={ openfileUpload }>
				<i class="fa fa-video"></i>
				<p>Video</p>
			</button>
			<button class="button" type="button" onclick={ openfileUpload } name="form_file_button">
				<i class="fa fa-file"></i>
				<p>File</p>
			</button>
			<div data-toggle="post-visiblity-selector">
				<button ref="visibility_button" class="button " name="view_post_members_button" type="button">
					<i class="fa fa-globe" ref="visibility_icon"></i>
					<p name="post_visibility_selected">Public</p>
				</button>
			</div>
			<!-- <button class="button float-right" type="submit"><i class="fas fa-paper-plane"></i></button> -->
			<div style="z-index: 4;" aria-autoclose="false" ref="visibility_dropdown" data-position="bottom"
				data-alignment="left" class="dropdown-pane visibility-dropdown" id="post-visiblity-selector"
				data-dropdown data-auto-focus="true">
				<label>
					<input ref="visibility_checbox_PUBLIC" type="radio" class="visilibity-selector" name="visibility"
						value="PUBLIC" checked onchange={ visibilityClicked } />
					<span class="visibility-label">
						<i class="fa fa-globe"></i> { opts.addsopts.post_visibility[0].visibletxt }
					</span>
				</label>
				<label>
					<input ref="visibility_checbox_NETWORKMEMBERS" type="radio" class="visilibity-selector"
						name="visibility" value="NETWORKMEMBERS" onchange={ visibilityClicked } />
					<span class="visibility-label">
						<i class="fa fa-suitcase"></i> { opts.addsopts.post_visibility[1].visibletxt }
					</span>
				</label>
				<!-- <label>
					<input ref="visibility_checbox_FRIENDS" type="radio" class="visilibity-selector" name="visibility" value="FRIENDS" onchange={
						   visibilityClicked }/>
					<span class="visibility-label">
						<i class="fa fa-users"></i> { opts.addsopts.post_visibility[2].visibletxt }
					</span>
				</label> -->
				<label onclick={ restrictedClicked }>
					<input ref="visibility_checbox_RESTRICTED" type="radio" class="visilibity-selector"
						name="visibility" value="RESTRICTED" onchange={ visibilityClicked } />
					<span class="visibility-label">
						<i class="fa fa-user"></i> { opts.addsopts.post_visibility[3].visibletxt }
					</span>
				</label>
			</div>
		</div>
		<div class="progress" ref="progressBar"></div>
	</form>

	<style>
		:scope {
			display: block;
		}

		post-form .form-container {
			border: 1px solid #c2c2c2;
		}

		post-form .form-container input {
			border-radius: 13px;
			border: 1px solid black;
			min-width: -webkit-fill-available;
			padding-left: 10px;
			padding-right: 45px;
		}

		button.post_submit_btn:hover {
			color: #385072;
			background: none;
		}

		.text-content {
			border: 0;
			border-bottom: 1px solid hsla(0, 0%, 4%, .25);
			margin: 0;
			min-height: 45px;
		}

		.toolbar {
			font-size: 0;
			justify-content: space-around;
		}

		.toolbar>*,
		.toolbar .button {
			display: inline-block;
			margin: 0;
		}

		.visibility-dropdown {
			padding: 0;
			width: auto;
		}

		.visilibity-selector {
			display: none;
		}

		.visibility-label {
			display: block;
			width: 100%;
			padding: 1em;
			background: white;
			cursor: pointer;
		}

		.visilibity-selector:checked+.visibility-label {
			background: #3589ee;
			color: white;
		}

		.visilibity-selector:hover+.visibility-label {
			background: #70abf3;
			color: white;
		}

		.progress {
			display: none;
			height: 4px;
			width: 0;
			padding: 0;
			margin: 0;
			background: #23a3ba;
			transition: width 0.25s ease-in;
		}

		post-form button.post_submit_btn {
			position: absolute;
			right: 6px;
			color: #3388EE;
			background: none;
			top: 2px;
		}

		post-form .toolbar button[name="photo_button"],
		post-form .toolbar button[name="video_button"],
		.toolbar button[name="form_image_button"],
		.toolbar button[name="form_file_button"],
		post-form .toolbar button[name="view_post_members_button"] {
			background: none;
			color: #385072;
			padding: 9px;
		}

		.toolbar button[name="video_button"] p,
		.toolbar button[name="form_image_button"] p,
		.toolbar button[name="form_file_button"] p,
		post-form .toolbar button[name="view_post_members_button"] p {
			margin-bottom: 0rem;
		}

		post-form .toolbar button[name="view_post_members_button"] {
			padding-right: 5px;
		}

		post-form .toolbar button[name="photo_button"]:hover,
		post-form .toolbar button[name="video_button"]:hover,
		.toolbar button[name="form_image_button"]:hover,
		.toolbar button[name="form_file_button"]:hover,
		.toolbar button[name="view_post_members_button"]:hover {
			background: none;
			color: #3589ee;
		}

		post-form .toolbar p[name="post_visibility_selected"] {
			color: black;
			background: none;
			padding-left: 0px;
			margin-top: 1px;
		}

		post-form .toolbar p[name="post_visibility_selected"]:hover {
			background-color: none;
			cursor: default;
		}

		.textarea {
			display: block;
			width: 100%;
			overflow: hidden;
			resize: both;
			min-height: 40px;
			line-height: 20px;
			padding-left: 10px;
			padding-right: 45px;
			padding-top: 10px;
		}

		.textarea[contenteditable]:empty::before {
			content: "What are your thoughts ? Drag an image here.";
			color: gray;
		}

		span.text-content.textarea:focus-visible {
			outline: none;
		}

		div#post_img_drag_container {
			text-align: center;
			position: relative;
		}
	</style>

	<script>

		openfileUpload(e){
			switch (e.target.innerText) {
				case 'Photo':
					$('#post_files').attr("accept", 'image/*');
					break;
				case 'Video':
					$('#post_files').attr("accept", 'video/*');
					break;
				case 'File':
					$('#post_files').attr("accept", '.doc,.docx,.csv,.xlsx');
					break;

				default:
					break;
			}
			$('#post_files').click();
		};
		this.form = {
			"photo_ids": [],
			"video_ids": [],
			"post_visibility_user_ids": [],
			"files": []
		};
		this.isRestricted = false;
		this.extensionAllowed = true;
		this.post_id = null;
		this.error = false;
		this.icons = {};
		this.reader = null;
		for (let index = 0; index < opts.addsopts.post_visibility.length; index++) {
			this.icons[opts.addsopts.post_visibility[index].value] = opts.addsopts.post_visibility[index].icon;
		}
		let that = this;

		function initFileReader(file, type) {
			removeFileContainer();
			that.reader = null;
			that.reader = new FileReader();
			that.reader.readAsDataURL(file);
			that.reader.addEventListener('loadend', () => {
				appendImageContainer(that.reader, type);

				// const post_submit_btn = document.querySelector('.post_submit_btn');
				// post_submit_btn.style.bottom = '-16px';
				// post_submit_btn.style.top = "auto";
			});
		}
		function checkFileSizeLimit(params) {
			var kbs = 0;
			if ($('#post_files')[0].files && $('#post_files')[0].files[0]) {
				kbs = Math.ceil($('#post_files')[0].files[0].size / 1024);
				// Limit 10 MB
				if (kbs > 10000) {
					alert('Please upload file less than 10mb.');
					return false;
				}
			}
			return true;
		}
		function mount() {
			$("#post_files").change(function (e) {
				let file = e.target.files[0];

				if (file) {
					var r = new FileReader();
					r.readAsDataURL(file);
					if (!checkFileSizeLimit()) {
						// Clear File
						$('#post_files').val('');
						return;
					}
					initFileReader(file, file.type)
					if (!that.extensionAllowed) {
						return false;
					}
				}

			});
			$(that.refs.visibility_button).foundation();
			$(that.refs.visibility_dropdown).foundation();
			that.update();
			// Drag Image Container for Post

			const dropzone = document.querySelector('post-form .form-container');

			dropzone.addEventListener('drop', event => {
				event.preventDefault();

				if (document.querySelector('.img_selected_container #post_img_drag_container')) {
					document.querySelector('.img_selected_container #post_img_drag_container').remove();
				}

				const file = event.dataTransfer.files[0];
				var type = event.dataTransfer.items[0].type.trim();
				const dT = new DataTransfer();
				dT.items.add(file);
				$('#post_files')[0].files = dT.files;
				if (!checkFileSizeLimit()) {
					// Clear File
					$('#post_files').val('');
					return;
				}
				initFileReader(file, type);
				if (!that.extensionAllowed) {
					$('#post_files').val('');
					return;
				}
				that.form['files'] = [];
				that.form['files'].push(file);


			});
		}
		function removeFileContainer() {
			if ($('#post_img_drag_container')[0]) {
				$('#post_img_drag_container')[0].remove();
			}
			that.form['files'] = [];
		}
		function appendImageContainer(reader, type) {
			const close_container = document.createElement('div');
			close_container.innerText = "X";
			close_container.classList.add('home-post-fileclose-btn');
			close_container.onclick = function () { removeFileContainer(); };
			// close_container.setAttribute("onclick", "removeFileContainer()");
			const parent_container = document.createElement('div');
			parent_container.setAttribute(
				'ondragstart',
				"return false"
			);
			parent_container.setAttribute(
				'id',
				'post_img_drag_container',
			);
			const img = document.createElement('img');
			console.log(type);
			switch (type) {
				case 'image/jpeg':
				case 'image/png':
					img.src = reader.result;
					break;
				case 'text/csv':
				case 'application/msword':
				case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
					img.src = "/images/post-form-excel.png";
					break;
				case 'video/mp4':
					img.src = "/images/video-timeline-default.jpg";
					break;
				default:
					alert('Only jpeg, png, csv, xlsx, mp4 allowed');
					that.extensionAllowed = false;
					return;
					// img.src = '/images/post-form-document.jpg';
					break;
			}
			parent_container.append(img);
			parent_container.append(close_container);

			const img_append_container = document.querySelector('.img_selected_container');
			img_append_container.append(parent_container);
		}

		// shouldUpdate(data, nextOpts) {
		// 	console.log(data);
		// 	console.log(nextOpts);
		// 	// do not update
		// 	return true
		// }
		function clearForm() {
			that.form = {
				"photo_ids": [],
				"video_ids": [],
				"post_visibility_user_ids": []
			};
			that.post_id = null;
			that.isRestricted = false;
			that.error = false;
			that.setProgress(0);
			that.update();
		}

		loadPost(post) {
			that.post_id = post.post_id;
			that.refs.text_content.value = post.description;
			that.setVisibility(that.refs['visibility_checbox_' + post.visibility]);
			for (let i = 0; i < post.subposts.length; i++) {
				if (post.subposts[i].type == 'IMAGE') {
					that.form.photo_ids.push(post.subposts[i].post_id);
				}
				if (post.subposts[i].type == 'VIDEO') {
					that.form.video_ids.push(post.subposts[i].post_id);
				}
			}
			for (let i = 0; i < post.visibilities.length; i++) {
				that.form.post_visibility_user_ids.push(post.visibilities[i].user_id);
			}
			that.update();
		}

		loadExisting(post_id) {
			that.setProgress(0);
			that.disable();
			that.update();

			window.axios.get(baseapipath + '/post/' + post_id,)
				.then(function (response) {
					that.setProgress(0);
					that.enable();
					that.error = true;
					that.loadPost(post);
				}).catch(function (error) {
					that.setProgress(0);
					that.error = true;
					that.update();
				});
		}

		visibilityClicked(e) {
			that.setVisibility(e.target);
			// console.log(that);
			if (e.target.value === "RESTRICTED") {
				that.openFriendSelector();
			} else {
				that.form.post_visibility_user_ids = [];
			}
		}

		clearVisibility() {
			that.setVisibility(that.refs.form.querySelector('input[name="visibility"]'));
		}

		setVisibility(checkbox) {
			let jCheckbox = $(checkbox);
			// console.log(jCheckbox.siblings()[0]);
			if (jCheckbox.siblings()[0].innerText != 'RESTRICTED') {
				$('[name="post_visibility_selected"]').text(jCheckbox.siblings()[0].innerText);
			}
			jCheckbox.prop('checked', true);
			that.refs.visibility_icon.className = that.icons[checkbox.value];
			$(that.refs.visibility_dropdown).foundation('close');
			that.isRestricted = checkbox.value == 'RESTRICTED';
		}

		restrictedClicked(e) {
			if (that.isRestricted) {
				e.preventDefault();
				e.stopImmediatePropagation();
				that.openFriendSelector();
			}
		}

		function onFriendSelected(friends) {
			that.form.post_visibility_user_ids = [];
			for (let i = 0; i < friends.length; i++) {
				that.form.post_visibility_user_ids.push(friends[i].user_id);
			}
		}

		function onFriendNotSelected() {
			that.clearVisibility();
		}

		function friendGet(tag) {
			tag.isLoading();
			window.axios.get(opts.baseApiPath + '/network/users')
				.then(function (response) {
					let constructItems = response.data.data;
					// console.log(constructItems);
					// if( opts.postId && that.post_visibility_user_ids.length === 0 ) {
					// 	window.axios.get( opts.baseApiPath + '/post/' + opts.postId + '/access' )
					// 		.then( function( response ) {
					// 			tag.notLoading();
					// 			console.log( reponse );
					// 		} ).catch( function( error ) {
					// 		tag.notLoading();
					// 		console.log( error );
					// 	} );
					// } else {
					// 	tag.notLoading();
					// 	for( let i = 0; i < constructItems.users.length; i++ ) {
					// 		if( that.form.post_visibility_user_ids.indexOf( constructItems[ i ].user2_id ) > -1 ) {
					// 			constructItems[ i ].selected = true;
					// 		}
					// 	}
					// }
					tag.setItems(constructItems);
				}).catch(function (error) {
					tag.notLoading();
					console.log(error);
				});
		}

		function onFriendSelected(friends) {
			that.form.post_visibility_user_ids = [];
			for (let i = 0; i < friends.length; i++) {
				that.form.post_visibility_user_ids.push(friends[i].user_id);
			}
		}

		function onFriendNotSelected() {
			that.clearVisibility();
		}

		openFriendSelector() {
			$('body').append($('<tag-selector></tag-selector>'));
			window.riot.mount('tag-selector', {
				onSelected: onFriendSelected,
				onCancelled: onFriendNotSelected,
				component: 'friend-renderer',
				itemGetInitier: friendGet,
				fullWidth: true
			});
		}

		function imagesGet(tag) {
			tag.isLoading();
			window.axios.get(opts.baseApiPath + '/images')
				.then(function (response) {
					tag.notLoading();
					let items = response.data;
					for (let i = 0; i < items.length; i++) {
						if (that.form.photo_ids.indexOf(items[i].post_id) > -1) {
							items[i].selected = true;
						}
					}
					tag.setItems(items);
				}).catch(function (error) {
					tag.notLoading();
					console.log(error);
				});
		}

		function onImageSelected(images) {
			that.refs.photo_button.disabled = false;
			that.form.photo_ids = [];
			for (let i = 0; i < images.length; i++) {
				that.form.photo_ids.push(images[i].post_id);
			}
		}

		function onImageCancelled() {
			that.refs.photo_button.disabled = false;
		}

		function onImageSubmit(image, tag) {
			tag.disable();
			image.append('_method', 'PUT');
			window.axios.post(
				opts.baseApiPath + '/image',
				image,
				{
					headers: { 'content-type': 'multipart/form-data' },
					onUploadProgress: function (progressEvent) {
						tag.setProgress(Math.round((progressEvent.loaded * 100) / progressEvent.total));
					}
				}
			).then(function (response) {
				selectorTag.onAdd();
				tag.unmount();
			}).catch(function (error) {
				tag.enable();
				tag.setProgress(0);
				console.log(error);
			});
		}
		function openImage(selectorTag) {

			$('body').append($('<image-form></image-form>'));
			window.riot.mount('image-form', { onSelected: onImageSubmit });
		}

		openImageSelector() {
			that.refs.photo_button.disabled = true;
			$('body').append($('<tag-selector></tag-selector>'));
			window.riot.mount('tag-selector', {
				onSelected: onImageSelected,
				onCancelled: onImageCancelled,
				component: 'image-renderer',
				itemGetInitier: imagesGet,
				hasAdd: true,
				add: openImage
			});
		}

		function videosGet(tag) {
			tag.isLoading();
			window.axios.get(opts.baseApiPath + '/videos')
				.then(function (response) {
					tag.notLoading();
					let items = response.data;
					for (let i = 0; i < items.length; i++) {
						if (that.form.video_ids.indexOf(items[i].post_id) > -1) {
							items[i].selected = true;
						}
					}
					tag.setItems(items);
				}).catch(function (error) {
					tag.notLoading();
					console.log(error);
				});
		}

		function onVideoSelected(videos) {
			that.refs.video_button.disabled = false;
			that.form.video_ids = [];
			for (let i = 0; i < videos.length; i++) {
				that.form.video_ids.push(videos[i].post_id);
			}
		}

		function onVideoCancelled() {
			that.refs.video_button.disabled = false;
		}

		function openVideo(selectorTag) {
			function onVideoSubmit(video, tag) {
				tag.disable();
				video.append('_method', 'PUT');
				window.axios.post(
					opts.baseApiPath + '/video',
					video,
					{
						headers: { 'content-type': 'multipart/form-data' },
						onUploadProgress: function (progressEvent) {
							tag.setProgress(Math.round((progressEvent.loaded * 100) / progressEvent.total));
						}
					}
				).then(function (response) {
					selectorTag.onAdd();
					tag.unmount();
				}).catch(function (error) {
					tag.enable();
					tag.setProgress(0);
					console.log(error);
				});
			}

			$('body').append($('<video-form></video-form>'));
			window.riot.mount('video-form', { onSelected: onVideoSubmit });
		}

		openVideoSelector() {
			that.refs.video_button.disabled = true;
			$('body').append($('<tag-selector></tag-selector>'));
			window.riot.mount('tag-selector', {
				onSelected: onVideoSelected,
				onCancelled: onVideoCancelled,
				component: 'video-renderer',
				itemGetInitier: videosGet,
				hasAdd: true,
				add: openVideo
			});
		}
		this.on('mount', function () {
			mount();
		});


		function getForm() {

			let formData = new FormData();
			formData.append('description', document.querySelector('span[name="description"]').innerText);
			// that.form[ "post_id" ] = that.post_id;
			// that.form[ "description" ] = that.refs.text_content.value;

			let visibilityRadios = that.refs.form.querySelectorAll('input[name="visibility"]');
			// console.log(visibilityRadios);
			for (let i = 0; i < visibilityRadios.length; i++) {
				if (visibilityRadios[i].checked) {
					// that.form[ "visibility" ] = visibilityRadios[ i ].value;
					console.log(visibilityRadios[i].value);
					formData.append('visibility', visibilityRadios[i].value)
				}
			}
			if ($('#post_files')[0].files[0]) {
				formData.append('files', $('#post_files')[0].files[0], $('#post_files')[0].files[0].name)
			}

			formData.append('post_visibility_user_ids', that.form.post_visibility_user_ids);
			// formData.append( '_method', 'PUT' );
			// const img_append_container = document.querySelectorAll('.img_selected_container img');
			// for (let index = 0; index < img_append_container.length; index++) {
			// 	that.form["files"].push(img_append_container[index]);
			// }
			// for (var key of formData.entries()) {
			// 	console.log(key[0] + ', ' + key[1]);
			// }
			return formData;
		}

		setProgress(progress) {
			that.refs.progressBar.style.width = progress + '%';
			if (progress > 0) {
				$(that.refs.progressBar).show();
			} else {
				$(that.refs.progressBar).hide();
			}
		}

		clear() {
			// that.clearForm();
			that.clearVisibility();
			document.querySelector('span[name="description"]').innerText = "";
			// that.refs.text_content.value = "";
		}

		disable() {
			let elems = that.refs.form.querySelectorAll('button');
			for (let i = 0; i < elems.length; i++) {
				elems[i].disabled = true;
			}
			that.refs.text_content.disabled = true;
		}

		enable() {
			let elems = that.refs.form.querySelectorAll('button');
			for (let i = 0; i < elems.length; i++) {
				elems[i].disabled = false;
			}
			that.refs.text_content.disabled = false;
		}

		submit(e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			that.disable();
			if (document.querySelector('.img_selected_container #post_img_drag_container')) {
				document.querySelector('.img_selected_container #post_img_drag_container').remove();
			}

			opts.onSubmitted(getForm(), that);
		}
		// console.log( opts.addsopts)
	</script>
</post-form>