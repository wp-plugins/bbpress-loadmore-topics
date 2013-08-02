jQuery(document).ready(function($){
    var BBLMT_ = {
        debug: true
    };

    $(".js_bbtopics_loadmore").click(function(){
        //dont proceed if already loading..
        if( BBLMT_.doingajax )
            return false;

        if( BBLMT_.nomoretopics )
            return false;

        var btn = $(this);    

        var data = {
            action      : 'bbpress_loadmore_topics',
            next_page   : $(btn).attr('data-nextpage'),
            forum_id    : $(btn).attr('data-forumid')
        };

        $(this).addClass('loading');
        BBLMT_.doingajax = true;
        if( BBLMT_.debug )
            console.log("started ajax request");

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: data,
            success: function (response) {
                $(btn).removeClass('loading');
                //bfln_after_get_new_notification(response);
                BBLMT_.doingajax = false; //reset it so that next ajax request can process
                if( BBLMT_.debug )
                    console.log( "ajax request completed, aborting" );

                if( typeof( response )!=='undefined' && response!=null && response!='0' && response!='' ){
                    $('ul.bbp-topics > .bbp-body' ).append( response );
                    var nextpaged = ( $(btn).attr( 'data-nextpage' ) ) * 1 + 1;//damn!
                    $(btn).attr( 'data-nextpage', nextpaged );
                }
                else{
                    if( BBLMT_.debug )
                        console.log( "empty response" );
                    BBLMT_.nomoretopics = true;
                    $(btn).text('No more posts..');
                }
            }
        });

        return false;
    });
    
});