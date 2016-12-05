<?php


namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LogConfigServiceProvider extends ServiceProvider
{
    public function register() {
        $this->app->configureMonologUsing(function ($monolog) {
            $appLogLevel = env('APP_DEBUG', false) ? Logger::DEBUG : Logger::INFO;
            $logName     = env('APP_NAME', 'lumen');
            $logLineFormat = "[%datetime%] %channel%.%level_name%: %message%\n";
            $logDateFormat = "Y-m-d H:i:s";
            $fileHandler = new StreamHandler(env("APP_LOG_DIR", storage_path("logs")) . "/{$logName}.log", $appLogLevel);
            $fileHandler->setFormatter(new LineFormatter($logLineFormat, $logDateFormat));
            $stdOutLogLineFormat = "[%datetime%] %channel%.%level_name%: %message%";
            $stdOutHandler       = new StreamHandler("php://stdout", $appLogLevel);
            $stdOutHandler->setFormatter(new LineFormatter($stdOutLogLineFormat . PHP_EOL, $logDateFormat));
            $stdErrHandler = new ErrorLogHandler(0, Logger::ERROR, false);
            $stdErrHandler->setFormatter(new LineFormatter($stdOutLogLineFormat, $logDateFormat));
            $monolog->pushHandler($stdOutHandler);
            $monolog->pushHandler($stdErrHandler);
            $monolog->pushHandler($fileHandler);
            return $monolog;
        });
    }
}
