<job-listings>
	
	<section class="job-listings">
		<div class="grid-container">
			<div class="grid-x grid-margin-x">
				<div class="cell medium-12 large-12">
					<div class="grid-x grid-margin-x flex-vertical-direction">
						<virtual if={ !opts.loading } each={ item, index in jobs } if={ !hasError }>
							<job-listing-item editLink={opts.editUrl} deleteLink={opts.delLink} delAsked={delAsked} removeme={ removeMe }  job={ item } indexer={index} clicked={ _clicked } ></job-listing-item>
						</virtual>
						<div if={ opts.loading }>
							<job-item-loader></job-item-loader>
							<job-item-loader></job-item-loader>
							<job-item-loader></job-item-loader>
							<job-item-loader></job-item-loader>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<style>
	</style>

	<script>
		
		this.jobs = [];
		this.hasError = false;
		// this.selectedJob = null
		this._clicked = null;
		this.delSelected = null;
		let that = this;
		
		function mount() {
			that.loadInitial(); 
			// $( that.refs.visibility_dropdown ).foundation();
			that.update();
		}
		removeMe() {
			that.jobs = that.jobs.filter(x=> x.job_id !== that.delSelected.job_id);
			that.delSelected = null;
			that.update();
			// console.log(that.delSelected);
		}
		delAsked(e){
			that.delSelected = e;
			that.update();
		}
		addItems(jobs){
			if(jobs.data.length> 0){
				let temp = jobs.data;
				
				// Find Selected Job
				const urlSearchParams = new URLSearchParams(window.location.search);
				const params = Object.fromEntries(urlSearchParams.entries());
				if(params && params.jobkey){
					let index = temp.findIndex( x => x.job_id === parseInt(params.jobkey) );
					if(index>-1){
						temp.unshift(temp.splice(index, 1)[0]);
					}
				}
				that.jobs = temp;
				console.log(that.jobs);
				opts.loading = false;
				that.update();
				$('#0_jobpost').click()
			}

		}
		loadInitial() {
			that.hasError = false;
			that.jobs = [];
			that.update();
			opts.load( null, that );
			
		}
		jobToggle(job){
			that._clicked = job;
			opts.selectedJobProp(job);
			that.update();
		}
		window.riot.mount( 'job-listing-item', { 
			addsopts: {
				jobselected: that.jobToggle,
				
			},
			
		});
		
		this.on( 'mount', function() {
			mount();
		} );

	</script>
</job-listings>