<?php

namespace App\Core;

use ErrorException;
use Throwable;

class ErrorHandler
{
    /**
     * Register the error and exception handlers.
     */
    public static function register()
    {
        // Set a global exception handler
        set_exception_handler([self::class, 'handleException']);

        // Convert PHP errors (warnings, notices, etc.) to exceptions
        set_error_handler([self::class, 'handleError']);

        // Handle fatal errors that can't be caught by the other handlers
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    /**
     * Convert PHP errors to ErrorException.
     * This allows us to catch warnings and notices as exceptions.
     *
     * @throws ErrorException
     */
    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): bool
    {
        // Don't throw exception for errors that are suppressed with @
        if (!(error_reporting() & $errno)) {
            return false;
        }
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    /**
     * Handle uncaught exceptions.
     * Logs the error and displays a generic error page.
     */
    public static function handleException(Throwable $e): void
    {
        self::renderErrorPage($e);
    }

    /**
     * Handle fatal errors on shutdown.
     * This is our last resort for catching errors that stop script execution.
     */
    public static function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            // Create an exception object to pass to our handler
            $exception = new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
            self::renderErrorPage($exception, true);
        }
    }

    /**
     * Render the error page.
     *
     * @param Throwable $e The exception/throwable object.
     * @param bool $isFatal Indicates if the error was fatal.
     */
    private static function renderErrorPage(Throwable $e, bool $isFatal = false): void
    {
        // In case of a fatal error, the output buffer might still be active.
        // We clear it to ensure our error page is the only thing displayed.
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        http_response_code(500);

        $code = 500;
        $title = 'خطای سرور';
        $message = 'یک خطای غیرمنتظره در سیستم رخ داده است. لطفاً بعداً دوباره امتحان کنید.';

        $debug_info = null;
        if (defined('DEBUG_MODE') && DEBUG_MODE === true) {
             $debug_info = [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ];
        }

        // Include the error view directly.
        // Using a function like `view()` might not be safe if the error originated from it.
        require_once PROJECT_ROOT . '/views/error.php';

        // Stop execution, especially after a fatal error.
        if ($isFatal) {
            exit();
        }
    }

    /**
     * Render a specific HTTP error page (e.g., 404, 403).
     */
    public static function renderHttpError(int $code, string $title, string $message): void
    {
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        http_response_code($code);

        // We don't pass debug info for HTTP errors as they are not exceptions.
        $debug_info = null;

        require_once PROJECT_ROOT . '/views/error.php';
        exit();
    }
}
