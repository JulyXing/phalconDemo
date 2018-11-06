<?php

namespace app\config;

use Phalcon\Config;

return new Config(
    array(
        'application' => array(
            'controllersDir'     => 'app/controllers/',
            'modelsDir'          => 'app/models/',
            'viewsDir'           => 'app/views/',
            'pluginsDir'         => 'app/plugins/',
            'servicesDir'        => 'app/IService/',
            'baseuri'           => '/'
        ),
        'debug' => true,
        'log' => array(
            'type'      => 'file',
            'filepath'  => 'app/logs/'
        ),
        'xhprof' => array(
            'debug'     => false,
            'root'      => '',
            'filepath'  => 'app/xhprof'
        )
    )
);
