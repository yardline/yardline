<?php
namespace Yardline;

use Yardline\DB\Referrer_URLs as Referrer_URLs_DB;
use Yardline\DB\Referrer_Stats as Referrer_Stats_DB;

class Referrers {

    public $url_db;

    public $stat_db;

    public function __construct() {
        $this->url_db = new Referrer_URLs_DB();
        $this->stat_db = new Referrer_Stats_DB();
    }

    public function get_by_urls( $urls ) {
       // dev_log('urls');
       // dev_log($this->url_db->get_by_urls( $urls ));
        return $this->url_db->get_by_urls( $urls );
    }

    public function add_stats( $views ) {
       // dev_log( 'views');
        //dev_log($views );
        foreach( $views as $view ) {
            $this->stat_db->add_on_duplicate( $view );
        }
    }

    public function add_urls( $urls ) {
        dev_log('add urls referrers');
        return $this->url_db->add_urls( $urls );
    }

    public function get_for_date_range( $start_date, $end_date ) {
        
        global $wpdb;
		
		$offset = isset( $params['offset'] ) ? absint( $params['offset'] ) : 0;
		$limit = isset( $params['limit'] ) ? absint( $params['limit'] ) : 10;
      
        $sql = $wpdb->prepare( "SELECT stats.id, urls.url, SUM(visitors) AS visitors, SUM(pageviews) AS pageviews FROM {$wpdb->prefix}yl_referrer_stats stats INNER JOIN {$wpdb->prefix}yl_referrer_urls urls ON urls.id = stats.id WHERE stats.date >= %s AND stats.date <= %s GROUP BY stats.id ORDER BY visitors DESC, urls.id ASC LIMIT %d, %d", array( $start_date, $end_date, $offset, $limit ) );
      
        $results = $wpdb->get_results( $sql );
        
		return $results;
       
    }
}