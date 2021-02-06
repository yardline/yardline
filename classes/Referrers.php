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

    public function get_by_urls( array $urls ) {
        return $this->url_db->get_by_urls( $urls );
    }
}