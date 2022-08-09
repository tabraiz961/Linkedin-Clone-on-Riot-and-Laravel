<know-from-experince-network>
    <virtual each={user in e_n_users}>
        <div data-userid-card={ user.user_id } class="callout card">
            <div class="network-profile-cover">
                <img src="" alt="">
            </div>
            <div class="network-profile-details">
                <div class="network-profile-image">
                    <a onclick="{ redirectNetwork }" data-username={ user.username }>
                        <img src="{ user.photo_id }" alt="">
                    </a>
                </div>
                <div class="network-profile-name">
                    <a onclick="{ redirectNetwork }" data-username={ user.username }>
                        <p>{ user.curated_name }</p>
                    </a>
                </div>
                
                <div class="network-profile-specialization">
                    <div class=""><p>BS Psychology</p></div><div><p>MS Science</p></div>
                </div>
                <div class="network-profile-mutual-details" if={user.friends}>
                    <p>{ user.friends.mutual_friends_count } mututal connections</p>
                </div>
                <div class="network-profile-mutual-details" if={!user.friends}>
                    <p>{ address }</p>
                </div>
                
                <!-- @if (isset($user['friends']))
                    <div class="network-profile-mutual-details">
                        <p>{{$user['friends']['mutual_friends_count'] }} mututal connections</p>
                    </div>
                @else
                    <div class="network-profile-mutual-details">
                        <p>{{ $address }}</p>
                    </div>
                @endif -->
                
                <div class="network-profile-connect-btn">
                    <button class="button" data-userid={ user.user_id } onclick={ connectNetwork }>Connect</button>
                </div>
            </div>
        </div>
    </virtual>
    
    <style>
        know-from-experince-network {
            display: flex;
            justify-content: space-around;
        }
    </style>

    <script>
        this.e_n_users = [];
        // this.address = '';
        this.e_n_isLoading = false;
        
        let that = this;
        setENUsers( users) {
            that.e_n_users = users;
            // that.address = address;
            that.update();
        }
        addItems(items) {
            that.setENUsers(items)
        }
        this.on( 'mount', function() {
            opts.load(that);
            if( opts.onMounted ) {
                opts.onMounted(that);
            }
        });
        connectNetwork(e) {
            let data = {
                '_method': 'PUT'
            };
            window.axios.post(
                opts.addsopts.basepath + "/friend/"+e.item.user.username+"/ask",
                data
            ).then( function( response ) {
                // that.items = [];
                // that.users
                var filtered = that.e_n_users.filter(function(_obj, index, arr){ 
                    return _obj.username != e.item.user.username;
                });
                console.log(filtered);
                that.setENUsers(filtered)
                console.log(response);
            } ).catch( function( error ) {
                console.log( error );
            });
        }
    </script>
</know-from-experince-network>