<?php

class MigrationCommand
{
    public static $command = 'migration';

    public static $description = 'Run database migrations';

    public static $arguments = [
        'action' => 'Migration action: run, create-migration, rollback, rollback-all, refresh, status',
        'name'   => 'Migration name for create-migration',
    ];

    public function handle($input = null, array $flags = [], $input2 = null)
    {
        // Load the LavaLust framework
        if (!function_exists('lava_instance')) {
            define('PREVENT_DIRECT_ACCESS', TRUE);

            $root_dir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR;
            $system_dir = $root_dir . 'scheme' . DIRECTORY_SEPARATOR;

            if (!defined('ROOT_DIR')) {
                define('ROOT_DIR', $root_dir);
            }

            if (!defined('SYSTEM_DIR')) {
                define('SYSTEM_DIR', $system_dir);
            }

            if (!defined('APP_DIR')) {
                define('APP_DIR', $root_dir . 'app' . DIRECTORY_SEPARATOR);
            }

            $original_argv = $GLOBALS['argv'] ?? [];
$GLOBALS['argv'] = [$original_argv[0] ?? 'lava'];

require_once SYSTEM_DIR . 'kernel/LavaLust.php';

$GLOBALS['argv'] = $original_argv;
        }

        $action = $input ?? 'run';

        switch ($action) {

            case 'run':
                $lava = lava_instance();
                $lava->call->library('migration');
                $lava->migration->migrate();
                break;

            case 'status':
                $lava = lava_instance();
                $lava->call->library('migration');
                $lava->migration->status();
                break;

            case 'rollback':
                $lava = lava_instance();
                $lava->call->library('migration');
                $lava->migration->rollback();
                break;

            case 'rollback-all':
                $lava = lava_instance();
                $lava->call->library('migration');
                $lava->migration->rollback_all();
                break;

            case 'refresh':
                $lava = lava_instance();
                $lava->call->library('migration');
                $lava->migration->refresh();
                break;

            case 'create-migration':
                if (empty($input2)) {
                    echo "Migration name is required." . PHP_EOL;
                    return;
                }

                $lava = lava_instance();
                $lava->call->library('migration');
                $lava->migration->create_migration($input2);
                break;

            default:
                echo "Invalid migration action." . PHP_EOL;
                echo "Available actions: run, create-migration, rollback, rollback-all, refresh, status" . PHP_EOL;
        }
    }
}