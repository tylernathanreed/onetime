<?php

return [

    'collectors' => [
        'phpinfo'         => true,  // Php version
        'messages'        => false,  // Messages
        'time'            => true,  // Time Datalogger
        'memory'          => true,  // Memory usage
        'exceptions'      => false,  // Exception displayer
        'log'             => false,  // Logs from Monolog (merged in messages if enabled)
        'db'              => false,  // Show database (PDO) queries and bindings
        'views'           => false,  // Views with their data
        'route'           => false,  // Current route information
        'auth'            => false, // Display Laravel authentication status
        'gate'            => false,  // Display Laravel Gate checks
        'session'         => false,  // Display session data
        'symfony_request' => false,  // Only one can be enabled..
        'mail'            => false,  // Catch mail messages
        'laravel'         => false, // Laravel version and environment
        'events'          => false, // All events fired
        'default_request' => false, // Regular or special Symfony request logger
        'logs'            => false, // Add the latest log messages
        'files'           => false, // Show the included files
        'config'          => false, // Display config settings
        'cache'           => false, // Display cache events
        'models'          => false,  // Display models
        'livewire'        => false,  // Display Livewire (when available)
    ]

];
