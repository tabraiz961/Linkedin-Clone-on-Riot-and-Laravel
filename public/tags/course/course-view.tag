<course-view>

	<div if={course} class="cell callout border-rad-10 p-0">
		<div>
			<div class="topinfo space-between">
				<div class="flex-dir-column p-10">
					<div class="title">
						<h4>{ course.course_title }</h4>
					</div>
					<div class="course_brief">
						<p>{ course.course_brief }</p>
					</div>
					<div class="ratings flex-container">
						<div class="flex-container" style="margin-right: 50px;">
							<span class="gold">{ ratings.value ? ratings.value : 0.0 }</span>
							<div class="icon">
								<i each={ item in courseStars } style="color: gold;" class="fas fa-star"></i>
								<i each={ item in greyStars } style="color: gray;" class="fas fa-star"></i>
							</div>
							<span class="review_count"> ({ ratings.total_ratings ? ratings.total_ratings : 0  }) </span>
						</div>
						<div class="flex-container interested">
							<div class="icon"><i style="color: gray; margin-right: 5px;" class="fas fa-eye"></i></div>
							<span>{ ratings.interested ? ratings.interested : 0}</span>
						</div>
					</div>
					<div  class="author mt-20">
						<span if={ courseOwner }>Created by <a href={'/profile/'+courseOwner.username}>{ courseOwner.curated_name }</a></span>
					</div>
				</div>
				<div class="course-poster">
					<img src={ opts.courseStoragePath + '/' + course.id + '/course-poster/' + course.course_image}/>
				</div>
			</div>
			<div class="points mt-10 p-10" >
				<h5>What you’ll learn</h5>
				<div class="flex-container flex-wrap">
					<p if={JSON.parse(course.course_points).length && JSON.parse(course.course_points)[0] != ''} each={point in JSON.parse(course.course_points)}><i class="fas fa-check"></i> {point}</p>			
				</div>
			</div>
			<div class="blocks  p-10">
				<h5>Course Content</h5>
				<div class="border-sides">
					<div class="course_block" each={block in course.get_blocks}>
						<div class="space-between clickableCat "  onclick={ toggleBlock }>
							<div class="arrows title">{block.block_name}</div>
							<div>{ block.course_files.length} Items</div>
						</div>
						
						<ul id="toggle_{block.id}"  class="m-0" style="display: none;">
							<li class="space-between" each={ files in block.course_files}>
								<div><i class="fas fa-{files.info}"></i> { files.defined_detail}</div> 
								<div if={files.info == 'file' || files.info == 'question'}> 
									
									<a if={opts.authenticated} href={opts.courseStoragePath + '/' + course.id + '/' + block.id + '/' + files.file_name } download>download</a>
									<a if={!opts.authenticated} onclick={showLogin}>download</a>
								</div>
								<div if={opts.authenticated && (files.info == 'image' || files.info == 'video')}  onclick={previewFile}> 
									<a>preview</a>
								</div>
								
								<div if={!opts.authenticated && (files.info == 'image' || files.info == 'video')}  onclick={showLogin}> 
									<a>preview</a>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="requirement p-10">
				<h5>Requirements</h5>
				<div>
					<ul id="requirements_list" if={JSON.parse(course.course_requirements).length && JSON.parse(course.course_requirements)[0] != ''} >
						<li  each={requirement in JSON.parse(course.course_requirements)}>
							<span>{requirement}</span>
						</li>
					</ul>
				</div>
			</div>
			<div class="description p-10">
				<h5>Description</h5>
				<div class="append_description" if={ course } >
					<raw html={course.course_description}></raw>
				</div>
			</div>
		</div>
	</div>




	<div  class="reveal tiny border-rad-10"  style="background: black;" id="course-block-preview-video" data-reveal data-close-on-esc="false" data-close-on-click="false">
		<div class="course_player" >
			<div class="course_info" style="color: white;font-weight: 600;">
				<span>Course</span>
				<h4>{ course ? course.course_title : '' }</h4>
			</div>
			<!-- width="532px" min-height="270px" -->
			<div style="display:flex; justify-content: center;" class="course_item" >
				<div if={ previewItem && previewItem.info == 'video' }>
					<video id="preview-video" class="video-js" controls preload="auto"  data-setup="{}" >
						<source  src="{opts.courseStoragePath + '/' + course.id + '/' + previewItem.block_id + '/' + previewItem.file_name}#t=1" type="video/mp4" />
					</video>
				</div>
				<div if={ previewItem && previewItem.info == 'image' }>
					<img src={opts.courseStoragePath + '/' + course.id + '/' + previewItem.block_id + '/' + previewItem.file_name} alt="">
				</div>
			</div>
		</div>
		<div></div>
		<button class="close-button" data-close aria-label="Close reveal" type="button">
			<span style="color: white;" aria-hidden="true">&times;</span>
		</button>
	</div>


	
    <div  class="reveal tiny border-rad-10"   id="show-login-on-action-course-file" data-reveal >
		<div class="login_reveal_container flex-container flex-dir-column text-center" >
			<div><span>You need to Login to create a new course <i class="fas fa-university" style="font-size: 25px; color:#C6C5C2"></i></span></div>
            <div class="mt-10"><a href="/" class="button border-rad-10">Login</a></div>
		</div>

		<button class="close-button" data-close aria-label="Close reveal" type="button">
			<span style="color: white;" aria-hidden="true">&times;</span>
		</button>
	</div>
	
	<style>
		.course-poster {
			min-width: 350px;
		}
		.course-poster img {
			border-top-right-radius: 10px;
			object-fit: cover;
			height: auto;
			width: 100%;
		}
		.course_brief {
			min-height: 78px;
		}
		.course_brief p {
			overflow: hidden;
			text-overflow: ellipsis;
			display: -webkit-box;
			-webkit-line-clamp: 3; /* number of lines to show */
					line-clamp: 3; 
			-webkit-box-orient: vertical;
		}
		.ratings {
			align-items: baseline;
		}
		
		.ratings span{
			font-size: 18px;
			font-weight: 600;
			margin: 0px 6px;
		}
		.ratings span.gold {
			color: #df9e27;
    		font-size: 22px;
		}
		.ratings .icon {
			margin-top: 7px;
		}
		.title h4 {
			font-weight: 600;
		}
		.interested {
			align-items: baseline;
		}
		.flex-container.interested span {
			margin: 0px 0px;
		}
		.author {
			min-height: 24px;
		}
		span.review_count {
			margin-top: 7px !important;
			font-size: 15px !important;
		}
		.flex-wrap{
			flex-wrap: wrap;
		}
		.points h5, .blocks h5, .requirement h5, .description h5{
			font-weight: 600;
		}
		.points > div > p {
			flex: 50%;
			font-size: 18px;
		}
		
		.points > div > p i  {
			margin-right: 10px;
		}

		.course_block .arrows:before {
			margin-right: 10px;
			content: '\02C4';
		}
		.course_block .arrows.down:before {
			content: '\02C5';
		}
		.course_block .clickableCat {
			padding: 8px 15px;
			cursor: pointer;
			font-size: 20px;
			border-bottom: 2px solid #c2c2c2;
			background: #f1f0ef;
			font-weight: 600;
			flex: 10 1 auto;
		}
		.course_block ul li {
			border-bottom: 2px solid #ddd;
			margin-top: -1px;
			background-color: #f6f6f6;
			padding: 12px;
		}
		.course_block ul li i.fas {
			color: #385072;
		}
		.video-js {
			width:532px !important;
			height:270px !important;
		}
		ul#requirements_list { 
			line-height: 1.3;
		}
		ul#requirements_list li {
			font-size: 20px;
		}

		ul#requirements_list li span {
			font-size: 16px;
		}
		.requirement ul#requirements_list {
			margin-left: 30px;
		}
		.requirement ul#requirements_list span {
			margin-left: 10px;
		} 
		/* .course_player .course_item {
			display: flex;
			justify-content: center;
		} */
		/* #course-block-preview-video {
			background: black;
		} */
		/* .course_info span, .course_info h4 {
			color: white;
			font-weight: 600;
		} */
	</style>

	<script>
	// 	min-LENGTH
		this.course = null;
		this.ratings = null;
		this.courseOwner = null;
		this.is_owner = false;
		this.courseStars = [];
		this.greyStars = [0,1,2,3,4];
		this.loading = true;
		this.previewItem = null;
		let that = this;
		showLogin() {
			$('#show-login-on-action-course-file').foundation();
			$('#show-login-on-action-course-file').foundation('open');
		}

		init(){
			window.axios.get(opts.getCourseUrl).then(function (response) {
				if(response.data && response.data.success) {
					that.course = response.data.data.course;
					that.ratings = response.data.data.ratings;
					if(response.data.data.owner && response.data.data.owner[0]){
						that.courseOwner = response.data.data.owner[0];
						console.log(that.courseOwner);
					}
					that.is_owner = response.data.data.is_owner;
					let count = parseInt(Math.floor(that.ratings.value));
					for (let index = 0; index < count; index++) {
						that.courseStars.push(index);
						that.greyStars.splice(0,1);
					}

					
					// <raw html="<strong>I am strong</strong>"></raw>
				
					riot.tag('raw', '', function(opts) {
						this.root.innerHTML = opts.html;
					});
					// console.log(that.course.course_description);
					// that.course.course_description = that.course.course_description.substring(1,that.course.course_description.length-2);
					// console.log(that.course.course_description); 
					// $('.append_description')[0].innerHTML = that.course.course_description;
					that.update(); 
				}
			}).catch(function (error) {
				
			})
		}
		previewFile(e){
			// e.stopPropagation();
			that.previewItem = e.item.files;
			that.update();
			console.log(e.item.files.info);
			console.log(opts.courseStoragePath + '/' + that.course.id + '/' + that.previewItem.block_id + '/' + that.previewItem.file_name);
			$('#course-block-preview-video').foundation();
			$('#course-block-preview-video').foundation('open');
			// console.log(e);
		}
		toggleBlock(block){
			if(block.target){
				let index = block.target.classList.contains('down');
				if(block.target.classList.contains('down'))
				{
					block.target.classList.remove('down');
				}else{
					block.target.classList.add('down');
				}
				$('#toggle_'+block.item.block.id).slideToggle();
			}
		}
		this.on( 'mount', function() {
			that.init();
			// window.riot.mount('raw', {
			// 	html: '<strong>I am strong</strong>'
			// });
			that.update();
		} );
		
		

	</script>
</course-view>