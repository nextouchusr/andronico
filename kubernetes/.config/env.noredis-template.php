<?php
return array(
    'backend' =>
        array(
            'frontName' => '##b_frontName##',
        ),
    'crypt' =>
        array(
            'key' => '##key##',
        ),
    'session' =>
        array(
            'save' => '##session##',
            'redis' =>
                array(
                    'host' => '##session_redis_host##',
                    'port' => '##session_redis_port##',
                    'password' => '##session_redis_password##',
                    'timeout' => '##session_redis_timeout##',
                    'persistent_identifier' => '##session_redis_persistent_identifier##',
                    'database' => '##session_redis_database##',
                    'compression_threshold' => '##session_redis_compression_threshold##',
                    'compression_library' => '##session_redis_compression_library##',
                    'log_level' => '##session_redis_log_level##',
                    'max_concurrency' => '##session_redis_max_concurrency##',
                    'break_after_frontend' => '##session_redis_break_after_frontend##',
                    'break_after_adminhtml' => '##session_redis_break_after_adminhtml##',
                    'first_lifetime' => '##session_redis_first_lifetime##',
                    'bot_first_lifetime' => '##session_redis_bot_first_lifetime##',
                    'bot_lifetime' => '##session_redis_bot_lifetime##',
                    'disable_locking' => '##session_redis_disable_locking##',
                    'min_lifetime' => '##session_redis_min_lifetime##',
                    'max_lifetime' => '##session_redis_max_lifetime##',
                    'sentinel_master' => '##sentinel_master##',
                    'sentinel_servers' => '##sentinel_servers##',
                    'sentinel_connect_retries' => '##sentinel_connect_retries##',
                    'sentinel_verify_master' => '##sentinel_verify_master##'
                ),
        ),
    'db' =>
        array(
            'table_prefix' => '',
            'connection' =>
                array(
                    'default' =>
                        array(
                            'host' => '##host##',
                            'dbname' => '##dbname##',
                            'username' => '##username##',
                            'password' => '##password##',
                            'model' => '##model##',
                            'engine' => '##engine##',
                            'initStatements' => '##initStatements##',
                            'active' => '##active##',
                        ),
                ),
        ),
    'resource' =>
        array(
            'default_setup' =>
                array(
                    'connection' => 'default',
                ),
        ),
    'x-frame-options' => 'SAMEORIGIN',
    'MAGE_MODE' => '##MAGE_MODE##',
    'http_cache_hosts' =>
        array(
            0 =>
                array(
                    'host' => '##cache_host##',
                    'port' => '##cache_port##',
                ),
        ),
    'cache_types' =>
        array(
            'config' => 1,
            'layout' => 1,
            'block_html' => 1,
            'collections' => 1,
            'reflection' => 1,
            'db_ddl' => 1,
            'compiled_config' => 1,
            'eav' => 1,
            'customer_notification' => 1,
            'config_integration' => 1,
            'config_integration_api' => 1,
            'full_page' => '##full_page##',
            'config_webservice' => 1,
            'target_rule' => 1,
            'translate' => 1,
            'vertex' => 1,
            'google_product' => 1,
            'amasty_shopby' => 1
        ),
);
