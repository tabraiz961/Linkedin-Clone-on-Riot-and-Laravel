<course-list>
    
    
    <div class="grid-x grid-margin-x">
        <div class="cell medium-9 large-9 hide-for-small-only">
            <div>
                <h4><strong>Courses</strong></h4>
            </div>
            <div class="grid-x grid-margin-x">
                <div each={ course in data } class="cell callout medium-3 large-3 p-0 border-rad-10" >
                    <div class="course-list-container">
                        <div class="poster-container"> 
                            <a href="{'/course/'+ course.slug }">
                                <img class="border-top-rad-10" src={ '/storage/courses/'+course.id+'/course-poster/'+course.course_image } alt="">
                            </a>
                        </div>
                        <div class="course-info p-10">
                            <div class="course-info-title">
                                <a href="{'/course/'+ course.slug }"><strong>{course.course_title}</strong></a>
                            </div>
                            <div class="course-ratings flex-container text-center">
                                <div class="ceiled">{course.ratings_average}</div>
                                <div class="container_stars">
                                    <select style="display: none;" id="course_{course.id}">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div class="rating_times">
                                    ({ course.ratings.length })
                                </div>
                            </div>
                            <div class="stats space-between">
                                <div class="flex-container flex-dir-column ">
                                    <span class="views">{ course.views.length } Views</span>
                                    <span class="intrested">{ course.interested.length } Interested</span>
                                </div>
                                <div class="interested_icon" >
                                    <i class="fas fa-heart" onclick={ toggleInterested } style="color: { course.interested_color }" ></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <div class="cell medium-3 large-3 hide-for-small-only">
            <a onclick={redirectNewCourse}><div class=" create_course cell callout mt-20 border-rad-10 text-center">Create a new Course</div></a>
        </div> 
    </div>
    

    <div  class="reveal tiny border-rad-10"   id="show-login-on-action-rate-own-course" data-reveal >
		<div class="login_reveal_container flex-container flex-dir-column text-center" >
			<div><span>You cannot rate your own course <i class="fas fa-ban" style="font-size: 25px; color:#C6C5C2"></i></span></div>
            <div class="mt-10"><a href="/" class="button border-rad-10">Login</a></div>
		</div>

		<button class="close-button" data-close aria-label="Close reveal" type="button">
			<span  aria-hidden="true">&times;</span>
		</button>
	</div>

    <div  class="reveal tiny border-rad-10"   id="show-login-on-action" data-reveal >
		<div class="login_reveal_container flex-container flex-dir-column text-center" >
			<div><span>You need to Login to show interests <i class="fas fa-heart" style="font-size: 25px; color:#C6C5C2"></i></span></div>
            <div class="mt-10"><a href="/" class="button border-rad-10">Login</a></div>
		</div>

		<button class="close-button" data-close aria-label="Close reveal" type="button">
			<span  aria-hidden="true">&times;</span>
		</button>
	</div>
    
    <div  class="reveal tiny border-rad-10"   id="show-login-on-action-ratings" data-reveal >
		<div class="login_reveal_container flex-container flex-dir-column text-center" >
			<div><span>You need to Login to place ratings <i class="fas fa-star" style="font-size: 25px; color:#eab823"></i></span></div>
            <div class="mt-10"><a href="/" class="button border-rad-10">Login</a></div>
		</div>

		<button class="close-button" data-close aria-label="Close reveal" type="button">
			<span  aria-hidden="true">&times;</span>
		</button>
	</div>

    <div  class="reveal tiny border-rad-10"   id="show-login-on-action-course" data-reveal >
		<div class="login_reveal_container flex-container flex-dir-column text-center" >
			<div><span>You need to Login to create a new course <i class="fas fa-university" style="font-size: 25px; color:#C6C5C2"></i></span></div>
            <div class="mt-10"><a href="/" class="button border-rad-10">Login</a></div>
		</div>

		<button class="close-button" data-close aria-label="Close reveal" type="button">
			<span  aria-hidden="true">&times;</span>
		</button>
	</div>
    <style>
        .course-info-title {
            font-size: 13px;
            line-height: 15px;
            min-height: 40px;
        }
        .course-info-title a{
            color: black;
        }
        .poster-container {
            box-shadow: 2px 7px 10px -5px rgb(0 0 0 / 63%);
            -webkit-box-shadow: 2px 7px 10px -5px rgb(0 0 0 / 63%);
            -moz-box-shadow: 2px 7px 10px -5px rgb(0 0 0 / 63%);
            border-bottom: 2px solid #dbdbdb;
        }
        .course-ratings {
            margin-top: 10px;
        }

        .ceiled {
            flex-grow: 2;
            font-size: 18px;
            color: gold;
            font-weight: 800;
        }

        .rating-stars {
            display: flex;
            flex-grow: 3;
            justify-content: flex-start;
            align-items: center;
        }
        .rating_times {
            flex-grow: 2;
            color: gray;
        }
        .interested_icon {
            display: flex;
            flex-direction: column-reverse;
            font-size: 25px;
            margin-bottom: 5px;
            color: #C6C5C2;
        }

        .interested_icon i:hover {
            color: red;
            cursor:pointer;
        }
        .poster-container a img {
            max-height: 118px;
        }
        .create_course {
            font-size: 22px;
        }
    </style>
    
    <script>
        
        this.data = [];
		let that = this;
        redirectNewCourse() {
            if (opts.current_user_id) {
                window.location.href = opts.course_create_url;
            }else{
                $('#show-login-on-action-course').foundation();
                $('#show-login-on-action-course').foundation('open');         
            }
        }
		toggleInterested(course) {
            if(opts.current_user_id){
                window.axios.get(opts.interest_toggle_url+'/'+course.item.course.id).then(function (response) {
                    if(response.data.success){
                        that.init();
					    $.notify("Interests Updated", {position:'bottom-right', className: 'success'});
                    }
                }).catch(function (error) {
                    console.log(error);
                })
            }else{
                $('#show-login-on-action').foundation('open');
            }
        }
        init(){
            window.axios.get(opts.all_course_url).then(function (response) {
                if(response.data && response.data.success){
                    that.data = response.data.data;
                    for (let index = 0; index < that.data.length; index++) {
                        that.data[index].ratings_sum =0;
                        that.data[index].ratings_average = 0; 
                        that.data[index].ratings_average_ceiled = 0;
                        that.data[index].interested_color = '';
                        if(that.data[index].ratings.length){
                            that.data[index].ratings_sum = that.data[index].ratings.reduce((a, b) => a + b.points, 0);
                            that.data[index].ratings_average = (that.data[index].ratings_sum/that.data[index].ratings.length).toFixed(1);
                            that.data[index].ratings_average_ceiled = Math.ceil(that.data[index].ratings_average);
                        }
                        if(that.data[index].interested.length){
                            that.data[index].interested_color = opts.current_user_id && that.data[index].interested.find(x => x.user_id === parseInt(opts.current_user_id) ) ? 'red' : '';
                        }
                        
                    }
			        $('#show-login-on-action').foundation();
                    setTimeout(function (params) {
                        for (let index = 0; index < that.data.length; index++) {
                            $('#course_'+that.data[index].id).barrating('show',{
                                theme: 'br-theme-css-stars',
                                initialRating: that.data[index].ratings_average_ceiled,
                                onSelect: function(value, text, event) {
                                    if(opts.current_user_id){
                                        if(event.target){
                                            let courseId = event.target.parentElement.previousElementSibling.id.split('_')[1];
                                            if(that.data.find(x=>x.id == courseId).get_owner.user_id == opts.current_user_id){
                                                // if rating your own course
                                                $('#show-login-on-action-rate-own-course').foundation();
                                                $('#show-login-on-action-rate-own-course').foundation('open');
                                                return;
                                            }
                                            var body = {
                                                'courseId': courseId,
                                                'value': value
                                            }
                                            window.axios.post(opts.rating_update_url, { 'data': body }).then(function (response) {
                                                if(response.data && response.data.success) {
                                                    that.init();
                                                }
                                            }).catch(function (error) {
                                                console.log(error);
                                            })
                                        }
                                    }else{
                                        $('#show-login-on-action-ratings').foundation();
                                        $('#show-login-on-action-ratings').foundation('open');
                                    }
                                }
                            })
                        }
                    }, 250);
                    that.update();
                }
            }).catch(function (error) {
                console.log(error);    
            })

        }
        this.on( 'mount', function() {
			that.init();

		} );
    </script>
</course-list>