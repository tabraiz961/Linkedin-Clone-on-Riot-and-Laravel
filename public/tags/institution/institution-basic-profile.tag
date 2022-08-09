<institution-basic-profile>
    <div id="myprofile-card" class="border-rad-10 myprofile-header-card" >
        <div class="cover" style="background-image: url('{ opts.basepath + institution.institution_cover }'); border-radius: 10px 10px 0px 0px;" > </div>
        <div class="institution-extender-container">
            <div class="institution-top-detail-container flex-container" >
                <div class="profile-img">
                    <img src={ institution.institution_photo } alt="">
                </div>
                <div class="institution-top-basic-detail">
                    <div class="uni_name_profile">
                        <p class="h4">
                            { institution.institution_name }
                        </p>
                    </div>
                    <div class="short_bio">
                        <p >
                            { institution.public_bio }
                        </p>
                    </div>
                    <div class="flex-container" style="justify-content: space-between;"> <p if={ institution.address_details && institution.address_details.curated_address}> {institution.category + " ~ "+institution.address_details.curated_address} </p> <p> {institution.followers_count} </p></div>
                    <div class="flex-container hinted" style="justify-content: space-between;"> <p> <a>{institution.conn_working}</a> </p> <p> <a>{institution.emp_count}</a> </p></div>
                    <div class="follow-row" if={ institution.owner_id != user.user_id }>
                        <button class="button follow_btn">Follow</button>
                    </div>
                </div>
                <div class="actions" style="width: 50px;">
                    <i data-open="institutionEditModal" if={ institution.owner_id == user.user_id } id="editInstitutiontrigger" class="fas fa-pen-square"></i>
                </div>
            </div>
            <div class="overview-section"> 
                <div class="h5 overview-heading" style="font-weight: 600;">Overview</div> 
                <div class="long_description_institution">{ institution.long_overview_description}</div>
               
                <div class="overview-subdetails-container">
                    <div class="overview-subdetails-subcontainer">
                        <div if={institution.website_link} class="website-overview-container">
                            <div class="fw-6">Website</div>
                            <div><a>{ institution.website_link }</a></div>
                        </div>
                        
                        <div if={ institution.address_details && institution.address_details.curated_address } class="website-overview-container">
                            <div class="fw-6">Headquarters</div>
                            <div><p>{ institution.address_details.curated_address }</p></div>
                        </div>
                        <div if={institution.founded_date} class="website-overview-container">
                            <div class="fw-6">Founded Year</div>
                            <div><p>{ institution.founded_year }</p></div>
                        </div>
                    </div>
                    <div class="overview-subdetails-subcontainer">
                        <div class="create-job-container">
                            <a href={opts.institution_job_create_link}>
                                <button class="border-rad-10 button">Create A job</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
    
        </div>
        <div class="border-bot-rad-10 institute-show-bar">
            <div onclick={extend} class="profile-details-show-toggle fw-6">
                Show More <i class="fa fa-arrow-up"></i>
            </div>
        </div>
    </div>
    <style>
        p {
            margin-bottom: 0rem !important;
        }
        .institution-top-detail-container {
            margin: 22px;
            margin-bottom: 4px;
            align-items: flex-start;
        }
        .profile-img {
            width: 120px;
            border: 1px solid #c1c1c1;
            box-shadow: 7px 10px 21px -7px rgba(0,0,0,0.43);
            -webkit-box-shadow: 7px 10px 21px -7px rgba(0,0,0,0.43);
            -moz-box-shadow: 7px 10px 21px -7px rgba(0,0,0,0.43);
            border-radius:5px;
        }
        .profile-img img {
            border-radius:5px;
        }
        .short_bio {
            font-weight: 600;
        }
        .institution-top-basic-detail {
            margin-left: 30px;
            width: -webkit-fill-available;
        }
        .institution-top-basic-detail .uni_name_profile p.h4 {
            line-height: 0.9;
            font-weight: 600;
        }
        .hinted a {
            color: #C4C4C4;
        }
        .hinted p:hover {
            cursor: pointer;
            text-decoration: underline;
        }
        .institution-top-detail-container i.fas.fa-pen-square {
            float: right;
            font-size: 28px;
            color: black;
            background: transparent;
        }
        .actions i.fas.fa-pen-square:hover {
            cursor: pointer;
        }
        .overview-section {
            margin: 0px 23px;
            display: flex;
            flex-direction: column;
        }
        .website-overview-container {
            display: flex;
            flex-direction: column;
            margin-top: 1rem;
        }
        .institute-show-bar {
            width: -webkit-fill-available;
            background: #D9D9D9;
            display: flex;
            justify-content: center;
        }
        .institute-show-bar i.fa.fa-arrow-up,.institute-show-bar i.fa.fa-arrow-down {
            margin-top: 3px;
            margin-left: 5px;
        }
        .profile-details-show-toggle:hover {
            cursor: pointer;
        }
        .institution-extender-container {
            max-height: 300px;
            overflow: hidden;
        }
        .overview-subdetails-container {
            display: flex;
            justify-content: space-between;
        }
    </style>
    <script>
        this.institution = [];
        this.user = [];
        this.stepTwo = false;
        let that = this;
        extend (e) {
            if(e.srcElement.firstElementChild){
                if(e.srcElement.firstElementChild.classList.contains('fa-arrow-up')){
                    e.srcElement.firstElementChild.classList.remove('fa-arrow-up');
                    e.srcElement.firstElementChild.classList.add('fa-arrow-down');
                    // e.srcElement.innerText = "Show More ";
                    $('.institution-extender-container')[0].style.maxHeight = "300px";
                }else{
                    e.srcElement.firstElementChild.classList.add('fa-arrow-up');
                    e.srcElement.firstElementChild.classList.remove('fa-arrow-down');
                    // e.srcElement.innerText = "Show Less ";
                    $('.institution-extender-container')[0].style.maxHeight = "none";
                }
            }
        }
        this.on('update', function() {
            if(that.stepTwo){
                
            }
        })
        this.on( 'mount', function() {
			if( opts.institution != null ) {
				that.institution = opts.institution;
				that.user = opts.user;
                // console.log(opts);
				that.stepTwo = opts.stepTwo;
                that.update();
			}
		});
    </script>
</institution-basic-profile>