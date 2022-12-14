<friend-renderer>
    <div class="grid-x grid-padding-x">
        <div class="cell shrink">
            <img src="{ __getProfileUrl( opts.item.photo_id ) }" />
        </div>
        <div class="cell auto">
            <p>{ opts.item.name } { opts.item.surname }</p>
        </div>
    </div>

    <style>
        :scope {
            padding: 1em;
        }

        img {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            border: 1px solid hsla(0,0%,4%,.25);
        }

        p {
            padding-top: calc( 32px - 1em );
            text-align: left;
        }
    </style>
</friend-renderer>