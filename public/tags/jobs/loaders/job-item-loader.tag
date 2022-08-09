<job-item-loader>
    <div class="loading-container" >
        <ul class="loading-list-item-container">
            <li style={opts.height ? opts.height+'px':'264.97px' } class="border-rad-10"></li>
        </ul>
    </div>
	<style>
		.loading-container{
			margin-bottom: 15px;
		}
		.loading-container .loading-list-item-container {
			margin: 0px;
			width: auto; 
			background: #fff;
			border-radius: 5px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
			padding: 0px;
		}
		.loading-container .loading-list-item-container li {
			list-style-type: none;
			height: 264.97px;
			border-bottom: 1px solid #f5f7f8;
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
</job-item-loader>
