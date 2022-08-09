<header-search>
    <form method="GET" action="/search/user/all"  accept-charset="UTF-8">
        <input type="search" id="menu-search-bar" placeholder={ opts.placeHolderText } onkeydown={ menuSearchBarChanged } autocomplete="off" class="animated-search-form" name="q">
        <div if={ !trendingTags && defaultTags } class="search_overlay_items" style="display: none">
            <ul>
                <li onclick={ searchItems } each={ tag in defaultTags }><div >{ tag.hashtag }</div> <i class="fas fa-chart-line"></i></li>
            </ul>
        </div>
        <div if={ trendingTags && !defaultTags } ref="changed_search" class="search_overlay_items dirtySearch" style="display: none">
            <ul>
                <virtual each={ ttag in trendingTags }>
                    <li if={ ttag.type == 'user' }>
                        <a href="/profile/{ttag.username}">
                            <span>
                                <div class="header_profile_title_container">
                                    <p class="title">{ ttag.details.curated_name }</p> 
                                    <div class="conn_badge">
                                        <virtual if={ ttag.isFriend }>
                                            <i class="fas fa-user-friends"></i>
                                        </virtual>
                                    </div>
                                </div>
                                <p class="descrip">{ ttag.details.title_curated }</p>
                            </span>  
                            <img src={ ttag.details.photo_id } alt=""> 
                        </a> 
                    </li>
                    <li if={ ttag.type == 'institutions' }>
                        <a href="/institution/{ttag.slug}">
                            <span>
                                <div class="header_profile_title_container">
                                    <p class="title">{ ttag.institution_name }</p> 
                                    <div class="conn_badge">
                                        <virtual if={ ttag.isFriend }>
                                            <i class="fas fa-user-friends"></i>
                                        </virtual>
                                    </div>
                                </div>
                                <p class="descrip">{  }</p>
                            </span>  
                            <img src={ ttag.institution_photo } alt=""> 
                        </a> 
                    </li>
                </virtual> 
                
                <li onclick={ handlesubmit } class="showall_header">
                    <button type="submit">View More</button>
                </li>
            </ul>
        </div>
    </form>
    <style>
        .search_overlay_items {
            position: absolute;
        }

        .search_overlay_items ul {
            list-style: none;
            margin: 0px 2px;
            width: 18rem;
            border-radius: 10px;
        }

        .search_overlay_items ul li {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }
        .search_overlay_items ul li:hover {
            cursor: pointer;
        }

        .search_overlay_items ul li,
        .top-bar-left ul.menu li {
            margin: 0px 0px !important;
            padding: 7px 20px;
            display: flex;
            align-items: baseline;
            justify-content: space-between;
        }

        .search_overlay_items ul li:first-child:hover {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .search_overlay_items ul li:last-child:hover {
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .search_overlay_items ul li:hover {
            background: #daebff;
        }

        input#menu-search-bar:focus {
            -webkit-box-shadow: inset 0px 0px 0px 1px #000;
            -moz-box-shadow: inset 0px 0px 0px 1px #f00;
            box-shadow: inset 0px 0px 0px 2px #000;
        }

        .search_overlay_items ul li a {
            margin: 0px;
            color: black;
        }

        .search_overlay_items ul li:hover a {
            color: black;
            font-weight: 700;
        }
        
        .search_overlay_items.dirtySearch ul li {
            padding: 7px 11px;
        }
        .search_overlay_items.dirtySearch ul li a{
            display: flex !important;
            justify-content: space-between;
            padding: 5px;
            width: -webkit-fill-available;
            align-items: center;
        }
        .search_overlay_items.dirtySearch ul li a span{
            flex-basis: 70%;
        }
        
        .search_overlay_items.dirtySearch ul li a span p.title, .search_overlay_items.dirtySearch ul li a span p.descrip{
            margin-bottom: 0px;
        }
        .search_overlay_items.dirtySearch ul li a span p.descrip{
            font-size: 10px;
        }
        .search_overlay_items.dirtySearch ul li a img {
            flex-basis: 30%;
        }
        .search_overlay_items.dirtySearch ul li a img {
            max-width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        .search_overlay_items.dirtySearch ul li a img.squared {
            border-radius: 15%;
        }
        .header_profile_title_container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .conn_badge {
            color: #385072;
            font-weight: 700;
        }
        li.showall_header {
            display: flex !important;
            align-items: center!important;
            justify-content: center!important;
        }
    </style>
    <script>
        this.data = [];
        this.trendingTags = null;
        this.defaultTags = null;
        let that = this;

        $(document).ready(function () {
            // $(document).on('click', '.overlay_search_active', function () {
            //     performSearchFocusOut();
            // })
            $('#menu-search-bar.animated-search-form[type=search]').click(function (e) {
                if (!$('#overlay_search_container').hasClass('overlay_search_active')) {
                    $('#overlay_search_container').toggleClass("overlay_search_active");
                    $('.search_overlay_items').css('display', 'block');
                    jQuery('.overlay_search_active').css('top', $(document).scrollTop());
                    // stop scroll
                    $('.overlay_search_active').on('scroll touchmove mousewheel', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    })
                }
            })
            .focusout(function () {
                setTimeout(function() {
                    performSearchFocusOut();
                }, 250);
            });
            function performSearchFocusOut() {
                if ($('#overlay_search_container').hasClass('overlay_search_active')) {
                    $('#overlay_search_container').toggleClass("overlay_search_active");
                    $('.search_overlay_items').css('display', 'none');


                    // Enable  scroll
                    $('#element').on('scroll touchmove mousewheel', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    })
                }
            }
        });
        searchItems(e){
            window.location.href= "/search/user/all?q="+e.item.tag.hashtag;
        }
        handlesubmit() {
            window.location.href= "/search/user/all?q="+$('#menu-search-bar').val();
        }

        menuSearchBarChanged(bar){
            if (bar.target.value.length > 2) {
                window.axios.post(opts.searchActionurl, { input: bar.target.value })
                    .then(function (response) {
                        console.log(response.data);
                        if (response.data && response.data.sucess) {
                            that.trendingTags = response.data.data;
                            that.defaultTags = null;

                            that.update();
                            $(that.refs.changed_search).css('display', 'block');
                        }
                    })
                    .catch(function (error) { console.log(error); })
            } else {
                that.trendingTags = null;
            }
        }
        init() {
            opts.defaultTrendLoad(that);
        }
        this.on('mount', function () {
            that.init();
        });
    </script>
</header-search>