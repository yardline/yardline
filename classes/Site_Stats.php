<?php
namespace Yardline;

use function Yardline\dev_log;
use Yardline\Abstracts\Base_Object;
use Yardline\DB\Site_Stats as Site_Stats_DB;

class Site_Stats {
	public $db;
	
	public function __construct() {
		$this->db = new Site_Stats_DB();
	}

    /**
	 * Return the DB instance that is associated with items of this type.
	 *
	 * @return Site_Stats
	 */
	protected function get_db() {
		return Plugin::$instance->dbs->get_db( 'site_stats' );
    }
    
    protected function get_object_type() {
        return 'site_stat';
	}
	
	public function register_hit($pv, $uv) {
		//dev_log('register hit');
		//$this->get_db()->add();
	}

	public function get_for_date_range( $start_date, $end_date ) {
		return $this->db->get_for_date_range( $start_date, $end_date );
	}

}