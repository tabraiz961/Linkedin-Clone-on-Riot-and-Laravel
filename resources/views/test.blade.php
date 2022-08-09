@extends('layouts.app')

@section('content')

    <input type="text" name="searchAddress" onkeyup="filterSelect(this.value)">
    <select name="address_selector_us" id="address_selector_us">
    </select>

@endsection

@section('scripts')
	@parent
    <script>
        var launchOnce = true;
        filterSelect();
        function filterSelect(params = "") {
            if(params.length > 3 || this.launchOnce){
                this.launchOnce = false;
                window.axios.get('<?php echo route("search.getAddress" ) ?>'+'/'+params)
                .then( function( response ) {
                    console.log(response);
                    if(response.data){
                        $("#address_selector_us").empty();
                        for (let index = 0; index < response.data.length; index++) {
                            var x = document.getElementById("address_selector_us");
                            var option = document.createElement("option");
                            option.text = response.data[index]['address_text'];
                            option.value = response.data[index]['id'];
                            x.add(option);
                        }
                	}
                })
                .catch( function( error ) {
                	console.log( error );
                } );
            }
        }
    </script>

@endsection