<?php
namespace Yardline;

use Yardline\DB\Site_Stats;
use Yardline\Page_Paths;

class Hit_Collector {


    //make sure to add cron to deactivation hook!!!!
    public function init() {
        add_action( 'yardline_hit_collector', [ $this, 'collect'] );
        add_filter( 'cron_schedules', [ $this, 'add_interval' ] );
        add_action( 'init', [ $this, 'schedule' ] );
    }
    public function add_interval( $intervals ) {
		$intervals['yardline_hits_interval'] = array(
			'interval' => 1 * 60, // 1 minute
			'display'  => esc_html__( 'Every minute', 'yardline' ),
		);
		return $intervals;
	}

    public function schedule() {
        if ( ! wp_next_scheduled( 'yardline_hit_collector' ) && ! wp_installing() ) {
            wp_schedule_event( time() + 60, 'yardline_hits_interval', 'yardline_hit_collector' );
        }
    }
    
    public function collect() {
        global $wpdb;
        $filename = Hit_Tracker::get_buffer_filename();
		if ( ! file_exists( $filename ) ) {
			return;
        }
        
        // rename file to temporary location so nothing new is written to it while we process it
        $tmp_filename = dirname( $filename ) . '/yardlinehits-busy.php';
		$renamed = rename( $filename, $tmp_filename );
		if ( $renamed !== true ) {
			if ( WP_DEBUG ) {
				throw new Exception( 'Error renaming Yardline hits file.' );
			}
			return;
		}

		// open file for reading
		$file_handle = fopen( $tmp_filename, 'rb' );
		if ( ! is_resource( $file_handle ) ) {
			if ( WP_DEBUG ) {
				throw new Exception( 'Error opening Yardline hits file for reading.' );
			}
			return;
        }
        
        // read and ignore first line (the PHP header that prevents direct file access)
		fgets( $file_handle, 1024 );

		// combine stats for each table
		$site_stats     = array(
			'visitors'  => 0,
			'pageviews' => 0,
		);
		$post_stats     = array();
		$referrer_stats = array();

		while ( ( $line = fgets( $file_handle, 1024 ) ) !== false ) {
			$line               = rtrim( $line );
			$p                  = explode( ',', $line );
			$url                = trim( $p[0] );
			$new_visitor        = (int) $p[1];
			$unique_pageview    = (int) $p[2];
			$referrer_url       = trim( $p[3] );
			
			$site_stats['pageviews'] += 1;
			if ( $new_visitor ) {
				$site_stats['visitors'] += 1;
			}

			// update page stats (if received)
			if ( $url ) {

				$path = str_replace( get_site_url(), '', $url );
				if ( ! isset( $post_stats[ $path ] ) ) {
					$post_stats[ $path ] = array(
						'visitors'  => 0,
						'pageviews' => 0,
					);
				}

				$post_stats[ $path ]['pageviews'] += 1;

				if ( $unique_pageview) {
					$post_stats[ $path ]['visitors'] += 1;
				}
			}
			
          
			// increment referrals
			if ( $referrer_url !== '' && ! $this->ignore_referrer_url( $referrer_url ) ) {
				$referrer_url = $this->clean_url( $referrer_url );
				$referrer_url = $this->normalize_url( $referrer_url );

				if ( ! isset( $referrer_stats[ $referrer_url ] ) ) {
					$referrer_stats[ $referrer_url ] = array(
						'pageviews' => 0,
						'visitors'  => 0,
					);
				}

				$referrer_stats[ $referrer_url ]['pageviews'] += 1;
				if ( $new_visitor ) {
					$referrer_stats[ $referrer_url ]['visitors'] += 1;
				}
			}
			//var_dump($referrer_stats);
			//var_dump('Referrer URL: ' . $referrer_url);
		}

		// close file & remove it from filesystem
		fclose( $file_handle );
		unlink( $tmp_filename );

		if ( $site_stats['pageviews'] === 0 ) {
			return;
		}
      
		// store as local date using the timezone specified in WP settings
		$date = gmdate( 'Y-m-d', time() + get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
        $site_stat = new Site_Stats();
		$site_stat->add_on_duplicate( $site_stats );
		

		// insert post stats
		if ( count( $post_stats ) > 0 ) {

            //select urls from page paths table
			$page_paths = new Page_Paths();
			
			$exisiting_paths = $page_paths->db->get_by_urls( array_keys( $post_stats ) );
			  
          
            if ( $exisiting_paths ) {
                //add path id to $post_stats;
                foreach( $exisiting_paths as $exisiting_path) {
                   $post_stats[ $exisiting_path->path ]['path_id'] = $exisiting_path->id;
                } 
            }
            $new_paths = [];
            foreach ($post_stats as $url => $post_stat) {
                if ( ! isset( $post_stat['path_id'] ) ) {
					$new_paths[] = $url;
				}
            }

            if ( count( $new_paths ) > 0 ) {
 
				$page_paths->add_urls( $new_paths );
				$values       = $new_paths;
				$last_insert_id = $wpdb->insert_id;
				foreach ( array_reverse( $values ) as $url ) {
					$post_stats[ $url ]['path_id'] = $last_insert_id--;
				}
			}
			
			$page_stats = new Page_Stats();
			$page_stats->add_stats( $post_stats );
		}
		
		if ( count( $referrer_stats ) > 0 ) {
			 //select urls from page paths table
			 $referrers = new Referrers();
			//create a method in referrers that will get existing urls from the referrer URL table
			
			 $exisiting_urls = $referrers->get_by_urls( array_keys( $referrer_stats ) );
			
		   
			 if ( $exisiting_urls ) {
				 //add path id to $post_stats;
				 foreach( $exisiting_urls as $exisiting_url) {
					$referrer_stats[ $exisiting_url->url ]['url_id'] = $exisiting_url->id;
				 } 
			 }
			 $new_urls = [];
			
			 foreach ($referrer_stats as $url => $referrer_stat) {
				 if ( ! isset( $referrer_stat['url_id'] ) ) {
					 $new_urls[] = $url;
				 }
			 }
			
			 if ( count( $new_urls ) > 0 ) {
				
				 $referrers->add_urls( $new_urls );
				 $values       = $new_urls;
				 $last_insert_id = $wpdb->insert_id;
				 foreach ( array_reverse( $values ) as $url ) {
					 $referrer_stats[ $url ]['url_id'] = $last_insert_id--;
				 }
			 }
			 
			 $referrers->add_stats( $referrer_stats );
			
		}

		//$this->update_realtime_pageview_count( $site_stats['pageviews'] );
    }
    
    private function ignore_referrer_url( $url ) {
		// read blocklist into array
		static $blocklist = null;
		if ( $blocklist === null ) {
			$blocklist = file( YARDLINE_PATH . '/vendor/matomo/referrer-spam-blacklist/spammers.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );

			// add result of filter hook to blocklist so user can provide custom domains to block through simple array
			$custom_blocklist = apply_filters( 'yardline_referrer_blocklist', array() );
            $blocklist = array_merge( $blocklist, $custom_blocklist );
		}

		foreach ( $blocklist as $blocklisted_domain ) {
			if ( false !== stripos( $url, $blocklisted_domain ) ) {
				return true;
			}
		}
		/*yardline_ignore_referrer_url filter
		* Use this if to add site specific urls to block
		*/
		return apply_filters( 'yardline_ignore_referrer_url', false, $url );
	}

	public function clean_url( $url ) {
		// remove # amd after from URL
		$pos = strpos( $url, '#' );
		if ( $pos !== false ) {
			$url = substr( $url, 0, $pos );
		}

		// if URL contains query string, parse it and only keep certain parameters
		$pos = strpos( $url, '?' );
		if ( $pos !== false ) {
			$query_str = substr( $url, $pos + 1 );

			$params = array();
			parse_str( $query_str, $params );

			// strip all non-allowed params from url 
			$allowed_params = array( 'page_id', 'p', 'cat', 'product' );
			$new_params    = array_intersect_key( $params, array_flip( $allowed_params ) );
			$new_query_str = http_build_query( $new_params );
			$new_url       = substr( $url, 0, $pos + 1 ) . $new_query_str;

			// trim trailing question mark & replace url with new sanitized url
			$url = rtrim( $new_url, '?' );
		}

		// trim trailing slash
		$url = rtrim( $url, '/' );

		return $url;
	}

	public function normalize_url( $url ) {
		// if URL has no protocol, assume HTTP
		// we change this to HTTPS for sites that are known to support it (hopefully, all)
		if ( strpos( $url, '://' ) === false ) {
			$url = 'http://' . $url;
		}

		$aggregations = array(
			'/^android-app:\/\/com\.(www\.)?google\.android\.googlequicksearchbox(\/.+)?$/' => 'https://www.google.com',
			'/^android-app:\/\/com\.www\.google\.android\.gm$/' => 'https://www.google.com',
			'/^https?:\/\/(?:www\.)?(google|bing|ecosia)\.([a-z]{2,3}(?:\.[a-z]{2,3})?)(?:\/search|\/url)?/' => 'https://www.$1.$2',
			'/^android-app:\/\/com\.facebook\.(.+)/' => 'https://facebook.com',
			'/^https?:\/\/(?:[a-z-]+)?\.?l?facebook\.com(?:\/l\.php)?/' => 'https://facebook.com',
			'/^https?:\/\/(?:[a-z-]+)?\.?l?instagram\.com(?:\/l\.php)?/' => 'https://www.instagram.com',
			'/^https?:\/\/(?:www\.)?linkedin\.com\/feed.*/' => 'https://www.linkedin.com',
			'/(?:www|m)\.baidu\.com.*/' => 'www.baidu.com',
			'/yandex\.ru\/clck.*/' => 'yandex.ru',
		);

		return preg_replace( array_keys( $aggregations ), array_values( $aggregations ), $url, 1 );
	}

   
}