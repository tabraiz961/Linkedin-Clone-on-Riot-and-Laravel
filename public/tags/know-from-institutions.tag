<know-from-institutions>
    <virtual each={institution in institutions}>
        <div data-userid-card={ institution.institution_id  } class="callout card">
            <div class="network-profile-cover">
                <img src="" alt="">
            </div>
            <div class="network-profile-details">
                <div class="network-profile-image">
                    <a onclick="{ redirectNetwork }" data-username={ institution.slug }>
                        <img src="{ institution.institution_photo }" alt="">
                    </a>
                </div>
                <div class="network-profile-name">
                    <a onclick="{ redirectNetwork }" data-username={ institution.slug }>
                        <p>{ institution.institution_name }</p>
                    </a>
                </div>
                
                <div class="network-profile-aluminis"> 
                    <a><p>{ institution.aluminis }</p></a> 
                </div>
                <div class="network-profile-institute-connections"> 
                    <a><p>{ institution.currently_working }</p></a> 
                </div>
                <!-- <div class="network-profile-mutual-details" if={institution.friends}>
                    <p>{ institution.friends.mutual_friends_count } mututal connections</p>
                </div>
                <div class="network-profile-mutual-details" if={!institution.friends}>
                    <p>{ address }</p>
                </div> -->
                <div class="network-profile-connect-btn">
                    <button class="button" data-slug={ institution.slug  } onclick={ followNetwork }>Connect</button>
                </div>
            </div>
        </div>
    </virtual>
    
    <style>
        know-from-institutions {
            display: flex;
            justify-content: space-around;
        }
    </style>

    <script>
        this.institutions = [];
        // this.address = '';
        this.e_n_isLoading = false;
        
        let that = this;
        setInstitutions( users) {
            that.institutions = users;
            // that.address = address;
            that.update();
        }
        addItems(items) {
            that.setInstitutions(items)
        }
        this.on( 'mount', function() {
            opts.load(that);
            if( opts.onMounted ) {
                opts.onMounted(that);
            }
        });
        var clicked_slug = '';
        followNetwork(e) {
            let data = {
                '_method': 'PUT'
            };
            that.clicked_slug = e.item.institution.slug;
            window.axios.post(
                opts.addsopts.baseapipath + "/network/institutions/ask/"+that.clicked_slug,
                data
            ).then( function( response ) {
                if(response.data && response.data.success){
                    var filtered = that.institutions.filter(function(_obj, index, arr){ 
                        return _obj.slug != that.clicked_slug;
                    });
                    that.setInstitutions(filtered)
                    
                }
            } ).catch( function( error ) {
                console.log( error );
            });
        }
    </script>
</know-from-institutions>