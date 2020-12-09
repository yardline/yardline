<?php
namespace Yardline;

/**
 * Hit Tracker.
 *
 * This class adds Hits to a buffer file.  We can then add these hits on cron instead of on 
 * every hit
 *
 * @since 1.0
 */
class Hit_Tracker {

   
    public static function get_buffer_filename() {
        if ( defined( 'YARDLINE_HIT_FILE' ) ) {
            return YARDLINE_HIT_FILE;
        }
    
        $uploads = wp_upload_dir( null, false );
        return rtrim( $uploads['basedir'], '/' ) . '/yardlinehits.php';
    }
    
    public function track_hit( $stats ) {
        $path = $stats['url'] ?: '';
        $is_new_visitor = $stats['visitors'];
        $is_unique_pageview = $stats['pageviews'];
        $referrer = $stats['referer'] ?: '';
        $filename = $this->get_buffer_filename();
    
        if ( ! file_exists( $filename ) ) {
            $content = '<?php exit; ?>' . PHP_EOL;
        } else {
            $content = '';
        }
    
        $line = join( ' ,', array( $path, $is_new_visitor, $is_unique_pageview, $referrer ) );
        $content .= $line . PHP_EOL;
        return file_put_contents( $filename, $content, FILE_APPEND );
    }
}