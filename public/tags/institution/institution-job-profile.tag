<institution-job-profile>
    <div if={ institution } id="myprofile-card" class="border-rad-10 institution-job-card" >
       <div class="margin-holder">
            <div class="card-heading">
                <span>
                    Recent Job Openings
                </span>
            </div>
            <div class="flex-container mt-20 job-section-body">
                <virtual if={ institution.jobs_placed && institution.jobs_placed.length > 0 } each={item in (institution.jobs_placed.length == 1 ? institution.jobs_placed.slice(0, 1): institution.jobs_placed.slice(0, 2))}>
                    <a href={ opts.job_listing_link+"?jobkey="+item.job_id } class="anchor_container">
                        <div class="flex-container clickable p-10 border-rad-10 job_container">
                            <div class="job-poster">
                                <img if={ item.job_poster } width="100px" height="100px" src={ "/storage/"+ item.job_poster } alt="">
                                <div if={ !item.job_poster } class="default-job-img">
                                    <span>{ institution.institution_name_blockedName }</span>
                                </div>  
                            </div>
                            <div>
                                <div class="details">
                                    <div class="job-position">{ item.position_curated } </div>
                                    <div class="job-specs">{ item.job_location_curated+ " ~ "+ item.job_type_curated }</div>
                                </div>
                            </div>
                        </div>
                    </a>
                </virtual> 
                <div class="job_container" if={ institution.jobs_placed && institution.jobs_placed.length < 1 }>
                    <h5>No jobs availble for now, try searching through <a href={ opts.job_listing_link }>Jobs section</a></h5>
                </div>
            </div>
       </div>

    </div>
 

    <style>
        p {
            margin-bottom: 0rem !important;
        }
        .institution-job-card .margin-holder{
            margin: 22px;
        }
        .card-heading {
            font-size: 18px;
            font-weight: 600;
        }
        .job-position {
            font-weight: 600;
            font-size: 18px;
            line-height: 17px;
        }
        .job-poster {
            width: 100px;
            height: 100px;
        }
        .job-poster img {
            box-shadow: 0px 0px 5px 4px rgb(0 0 0 / 75%);
            -webkit-box-shadow: 0px 0px 5px 4px rgb(0 0 0 / 75%);
            -moz-box-shadow: 0px 0px 5px 4px rgba(0,0,0,0.75);
            object-fit: cover;
            width: 100px;
            height: 100px;
            border-radius: 5px;
        }
        .details {
            margin-left: 17px;
        }
        .job-section-body {
            justify-content: space-around;
        }
        .clickable:hover{
            background: #d9d9d9;
            color: #0a0a0a;
        }
        .anchor_container {
            flex: 0 0 50%;
        }
        .job-specs {
            margin-top: 10px;
        }
        .default-job-img {
            background-color: #d9d9d9;
            height: -webkit-fill-available;
            width: -webkit-fill-available;
            display: flex;
            align-items: center;
            border-radius: 10px;
            justify-content: center;
            letter-spacing: 4px;
            font-weight: 600;
            font-size: 20px;
        }
    </style>
    
    
    <script>
        this.institution = null;
        this.user = [];
        this.stepTwo = false;
        let that = this;
        // this.on('update', function() {
        //     console.log(that.institution);
        // })
        this.on( 'mount', function() {
			if( opts.institution != null ) {
				that.institution = opts.institution;
                // console.log(that.institution);
				that.user = opts.user;
                // console.log(opts);
				that.stepTwo = opts.stepTwo;
                that.update();
			}
		});
    </script>
</institution-job-profile>