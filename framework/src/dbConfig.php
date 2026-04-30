<?php 

if (isLocal()) {
    $dbConfig = [
        'host' => $_ENV['HOST_DEV'],
        'dbname' => $_ENV['DB_NAME_DEV'],
        'user' => $_ENV['DB_USER_DEV'],
        'password' => $_ENV['DB_PASS_DEV'],
    ];
} else {
    // Production/live server database config
    $dbConfig = [
        'host' => $_ENV['HOST'],
        'dbname' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASS'],
    ];
}

// dd($dbConfig);

return [
    'db' => $dbConfig,
];
