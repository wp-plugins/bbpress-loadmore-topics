jQuery(document).ready(function($){

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
            forum_id    : $(btn).attr('data-forumid'),
            nonce       : BBLMT_.nonce
        };

        $(this).addClass('loading');
        BBLMT_.doingajax = true;
        
        $.ajax({
            type: "POST",
            url: BBLMT_.ajaxurl,
            data: data,
            success: function (response) {
                $(btn).removeClass('loading');
                BBLMT_.doingajax = false; //reset it so that next ajax request can process

                if( typeof( response )!=='undefined' && response!=null && response!='0' && response!='' ){
                    $('ul.bbp-topics > .bbp-body' ).append( response );
                    jQuery(".bbp-body ul.fade_effect").hide();
                    jQuery(".bbp-body ul.fade_effect").show('slow');
                    jQuery(".bbp-body ul.fade_effect").removeClass('fade_effect');
                    var nextpaged = ( $(btn).attr( 'data-nextpage' ) ) * 1 + 1;//lol :)
                    $(btn).attr( 'data-nextpage', nextpaged );
                }
                else{
                    BBLMT_.nomoretopics = true;
                    $(btn).text(BBLMT_.text_nomoretopics);
                }
            }
        });

        return false;
    });
    
});