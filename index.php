<?php 
/* 
Plugin Name: bbPress load more topics
Plugin URI: http://webdeveloperswall.com/wordpress/bbpress-loading-more-topics-with-ajax
Description: load more topics with ajax
Version: 1.1
Revision Date: 02 08, 2015
Requires at least: 3.5
Tested up to: 4.1
Author: ckchaudhary
Author URI: http://webdeveloperswall.com/wordpress/bbpress-loading-more-topics-with-ajax
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'bbpresslmt_init_plugin' );
function bbpresslmt_init_plugin(){
    $instance = BBPressMoreTopics::get_instance();
}

class BBPressMoreTopics{
    private $data;
    private static $instance;
    private $next_page;

    private function __construct(){
        $this->add_actions();
    }

    public static function get_instance(){
        if(!isset(self::$instance)){
            self::$instance=new self();
        }
        return self::$instance;
    }

    function load_more_button(){
        $html ="";
        $next_page = bbp_get_paged() + 1;
        $forum_id = bbp_get_forum_id();

        $args = array( "paged" => $next_page );
        if( $forum_id ){
            $args['post_parent'] = $forum_id;
        }


        if( $have_topics = emusic_set_bbp_query( 'hasmoretopics', $args ) ){
            if( $have_topics->max_num_pages > bbp_get_paged() ){
                $html = "<ul class='activity-list topics-list-load-more'><li class='load-more'>";
                $html .= "<a href='#more' class='bbtopics_loadmore js_bbtopics_loadmore' data-nextpage='$next_page' ";

                if( $forum_id ){
                    $html .= "data-forumid='$forum_id' ";
                }
                /*Use filter to change Button text.*/
                $button_text = apply_filters( "bblmt_button_text", "Load More" );
                $html .= "> $button_text </a></li></ul>";    
            }
        }

        emusic_reset_bbp_query();

        return $html;
    }

    function add_actions(){
        add_action( 'wp_enqueue_scripts', array( $this, 'addjs' ) );
        add_action( 'wp_ajax_bbpress_loadmore_topics', array( $this, 'ajax_load_more' ) );
        add_action( 'wp_ajax_nopriv_bbpress_loadmore_topics', array( $this, 'ajax_load_more' ) );
    }

    function addjs(){
        wp_enqueue_script(
            "bbpresslmt",
            path_join( WP_PLUGIN_URL, basename( dirname( __FILE__ ) )."/bbpress-loadmore-topics.js" ),
            array( 'jquery' )
        );
        wp_localize_script(
            'bbpresslmt',
			'BBLMT_',
            array(
                'ajaxurl' 			=> admin_url( 'admin-ajax.php' ),
                'nonce'				=> wp_create_nonce('bbplmt'),
				'nomoretopics'		=> false,
				'text_nomoretopics' => apply_filters( 'bbpress_lmt_text_nomoretopics', 'No more posts..' ),
            )
        );

    }

    function ajax_load_more(){
        check_ajax_referer('bbplmt','nonce');
        $next       = ( $_POST['next_page'] ? $_POST['next_page']   : 2 );
        $forum_id   = ( $_POST['forum_id']  ? $_POST['forum_id']    : 0 );
        die( $this->load_more( $next, $forum_id ) );
    }

    /*
     * load more topics; for the next page
     *
     * @param int $next_page : pagination parameter
     * @param int $forum_id : parent forum id : default false - load topics from all forums
     *
     * @return mixed : string if topics found: false otherwise
    */
    private function load_more( $next_page, $forum_id=false ){
        $html = false;
        
        $args = array( "paged" => $next_page );

        if( $forum_id )
            $args["post_parent"] = $forum_id;
        
        if( emusic_set_bbp_query( 'nextposts',  $args ) ):

            //make sure the css class 'topic' is added, else it screws the design
            add_filter( 'bbp_get_topic_class', array( $this, 'add_topic_class' ) );
            
            ob_start();
            
            while( bbp_topics() ): bbp_the_topic();
                bbp_get_template_part( 'loop', 'single-topic' );
            endwhile;
            
            $html = ob_get_contents();
            ob_end_clean();

            remove_filter( 'bbp_get_topic_class', array( $this, 'add_topic_class' ) );

        endif;
        emusic_reset_bbp_query();

        return $html;
    }

    function add_topic_class( $classes ){
        $added = false;
        if( $classes && !empty( $classes ) && in_array('topic', $classes) ){
            $added = true;
        }
        if( !$added )
            $classes[] = 'topic';
            $classes[] = 'fade_effect';

        return $classes;
    }
}

/*
 * Everything starts from here,
 * Once you have the plugin activated, call this function( make sure to use function_exists ) in bbpress template file called loop-topics.php,
 * append it inside <li class="bbp-body"> or <li class="bbp-footer">.
 * And you are done!
*/
function bbpresslmt_loadmore_button(){
    $instance = BBPressMoreTopics::get_instance();
    echo $instance->load_more_button();
}

add_action( "bbp_template_after_topics_loop", "bbpresslmt_loadmore_button" );

/* #############################################################################
support functions 

reset custom bbpress loop and go back to main bbpress loop. 
http://scotty-t.com/2012/06/29/a-few-notes-on-bbpress/
############################################################################ */
if( !function_exists( 'emusic_set_bbp_query' ) ):
global $emusic_the_bbp_query;
$emusic_the_bbp_query = array();
function emusic_set_bbp_query( $name = 'main', $params = array() ) {
    global $emusic_the_bbp_query;
    $bbp = bbpress();

    bbp_set_query_name( $name );

    if ( !empty( $bbp->topic_query ) && empty( $emusic_the_bbp_query ) )
        $emusic_the_bbp_query['main'] = $bbp->topic_query;    

    if ( !empty( $name ) && isset( $emusic_the_bbp_query[$name] ) ) {
        $bbp->topic_query = $emusic_the_bbp_query[$name];
        return $bbp->topic_query;
    }

    if ( !empty( $params ) ) {
        bbp_has_topics( $params );
        $emusic_the_bbp_query[$name] = $bbp->topic_query;
        return $bbp->topic_query;
    }
}

function emusic_reset_bbp_query() {
    global $emusic_the_bbp_query;
    $bbp = bbpress();
    $bbp->topic_query = $emusic_the_bbp_query['main'];
    bbp_set_query_name();
}

endif;