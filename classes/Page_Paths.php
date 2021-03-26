<?php
namespace Yardline;

use Yardline\DB\Page_Paths as Page_Paths_DB;

class Page_Paths {

    public $db;

    public function __construct() {
        $this->db = new Page_Paths_DB();
    }

    public function add_urls( $paths ) {
        global $wpdb;
		foreach( $paths as $path ) {
            $url = get_site_url() . $path;
            $post_id = url_to_postid( $url );
            $type = $this->set_type( $path, $post_id );
			
			$this->db->add([
				'path'		=> $path,
				'type' 		=> $type,
                'post_id' 	=> $post_id,
                'tax_id'    => $tax_id
			]);
		}

    }
    
    public function set_type( $path, $post_id = 0 ) {
        if ($post_id > 0) {
            return get_post_type($post_id);
        }
       $url = get_site_url() . $path;
        if ( rtrim( $url, "/" ) == get_home_url() ) {
            return 'home';
        }

        $cat_base        = 'category';
		$category_option = get_option( 'category_base' );
		if ( ! empty( $category_option ) ) {
			$cat_base = $category_option;
        }

		$sanitize_category_url = str_ireplace( rtrim( get_home_url(), "/" ) . "/" . ltrim( $cat_base, "/" ), '', $url );
		$cat                   = get_category_by_path( $sanitize_category_url );
		if ( is_object( $cat ) and $cat != false ) {
			return 'category';
		}
            
        return 'other';
    }
}