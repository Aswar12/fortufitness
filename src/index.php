<?php

require __DIR__ . '/../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Create a log channel
$log = new Logger('name');
$log->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::WARNING));

// Add records to the log
$log->warning('This is a warning message');
$log->error('This is an error message');

echo "Logs have been written to the logs/app.log file.";
