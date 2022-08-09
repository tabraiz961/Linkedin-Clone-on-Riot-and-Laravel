<card-loader>
    <div class="loading-container" >
        <ul class="loading-list-item-container">
            <li></li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
	<style>
		.loading-container .loading-list-item-container {
			margin: 0px;
			width: 268px; 
			background: #fff;
			border-radius: 5px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
			padding: 0px;
		}
		.loading-container .loading-list-item-container li {
			list-style-type: none;
			height: 67.97px;
			border-bottom-left-radius: 0;
			border-bottom: 1px solid #f5f7f8;
			border-bottom-right-radius: 0;			
			background: #c2c2c2;
			background: linear-gradient(110deg, #c2c2c2 8%, #d7d4d4 18%, #c2c2c2 33%);
			background-size: 200% 100%;
			animation: 1s shine linear infinite; 
		}
		
		@keyframes shine {
			to {
			background-position-x: -200%;
			}
		}
	</style>
</card-loader>

<timeline-card-loader>
    <div class="timeline-post-loader-container">
        <div class="loading-items-container">
            <div class="loading-image"></div>
            <h2></h2>
            <p></p>
        </div>
    </div>
	<style>
		.timeline-post-loader-container h2, .timeline-post-loader-container p,.timeline-post-loader-container .loading-image  {
			/* background: #eee;
			background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%); */
			background: #c2c2c2;
			background: linear-gradient(110deg, #c2c2c2 8%, #d7d4d4 18%, #c2c2c2 33%);
			border-radius: 5px;
			background-size: 200% 100%;
			animation: 1.5s shine linear infinite;
		}
		.timeline-post-loader-container p{
			height: 161px;
		}
		.timeline-post-loader-container .loading-image {
			border-radius: 50%;
    		margin-bottom: 10px;
		}
		.loading-items-container {
			background: #fff;
			padding: 10px;
			border-radius: 15px;
			border: 1px solid #c2c2c2;
			margin-bottom: 18px;
		}
		h2 {
			height: 30px;
		}
		p {
			height: 70px;
		}
		.loading-image {
			height: 40px;
			width: 40px;
			border-radius: 50%;
		}
		@keyframes shine {
		to {
			background-position-x: -200%;
		}
		}
	</style>
</timeline-card-loader>