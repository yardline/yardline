<?php

namespace Yardline\Api;
use Yardline\Api\V1\API_V1;


class Api_Loader
{

    /**
     * @var API_V1
     */
    public $v1;

    /**
     * WPGH_API_LOADER constructor.
     */
    public function __construct()
    {
        add_action( 'rest_api_init', [ $this, 'load_api' ] );
    }

    public function load_api()
    {
        $this->v1 = new API_V1();
    }

}