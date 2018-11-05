<?php

namespace app\config;

use Phalcon\Config;

return new Config(
    array(
        'application' => array(

        ),
        'debug' => false,
        'log' => array(
            'type' => 'file',
            'filepath' => 'app/logs'
        ),
        'xhprof' => array(
            'debug' => false,
            'root' => '',
            'filepath' => ''
        )
    )
);
