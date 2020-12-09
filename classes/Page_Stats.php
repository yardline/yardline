<?php
namespace Yardline;

use Yardline\DB\Page_Stats as Page_Stats_DB;
use function Yardline\dev_log;
class Page_Stats { 

    public $db;

    public function __construct() {
        $this->db = new Page_Stats_DB();
    }

    public function add_stats( $views ) {
        foreach( $views as $view ) {
            $this->db->add_on_duplicate( $view );
        }
    }
}