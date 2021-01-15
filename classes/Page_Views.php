<?php
namespace Yardline;

class Page_Views {
    public function get_for_date_range( $start_date, $end_date ) {
        //get data from page stats table and merge with page title table
        //koko might have the awnser
        global $wpdb;
		
		$offset = isset( $params['offset'] ) ? absint( $params['offset'] ) : 0;
		$limit = isset( $params['limit'] ) ? absint( $params['limit'] ) : 10;
      
        $sql = $wpdb->prepare( "SELECT stats.path_id, paths.path, SUM(visitors) AS visitors, SUM(pageviews) AS pageviews FROM {$wpdb->prefix}yl_page_stats stats INNER JOIN {$wpdb->prefix}yl_page_paths paths ON paths.id = stats.path_id WHERE stats.date >= %s AND stats.date <= %s GROUP BY stats.path_id ORDER BY pageviews DESC, paths.id ASC LIMIT %d, %d", array( $start_date, $end_date, $offset, $limit ) );
       // $sql = $wpdb->prepare( "SELECT stats.path_id, paths.path, SUM(stats.visitors), SUM(stats.pageviews) FROM {$wpdb->prefix}yl_page_stats stats INNER JOIN {$wpdb->prefix}yl_page_paths paths ON paths.id = stats.path_id WHERE stats.date >= %s AND stats.date <= %s GROUP BY stats.path_id ORDER BY pageviews DESC, paths.id ASC LIMIT %d, %d", array( $start_date, $end_date, $offset, $limit ) );
       dev_log($sql);
        $results    = $wpdb->get_results( $sql );
        dev_log($results);
		return $results;
        //return ['date_range'];
    }
}