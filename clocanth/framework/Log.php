<?php

        namespace Clocanth;

        use Katzgrau\KLogger\Logger;
        use Psr\Log\LogLevel;

        use Clocanth\Config as Conf;

        class Log extends Logger
        {
            public function __construct($log_directory = null, $log_level_threshold = LogLevel::DEBUG)
            {
                if (is_null($log_directory))
                    $log_directory = __DIR__ . '/../../app/' . Conf::logging('directory');

                parent::__construct($log_directory, $log_level_threshold);
            }
        }
