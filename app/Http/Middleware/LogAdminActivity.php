<?php

namespace App\Http\Middleware;

use App\Support\AdminActivityLogger;
use Closure;

class LogAdminActivity
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($this->shouldLog($request)) {
            AdminActivityLogger::log('admin.request', null, [], [], [
                'module' => 'admin',
                'description' => 'درخواست مدیریتی ثبت شد',
                'request_payload' => $request->except(['password', 'password_confirmation', '_token']),
                'response_status' => method_exists($response, 'getStatusCode') ? $response->getStatusCode() : null,
            ]);
        }

        return $response;
    }

    protected function shouldLog($request)
    {
        if (!auth()->check()) {
            return false;
        }

        if (!in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return false;
        }

        if (strpos($request->path(), 'admin') !== 0) {
            return false;
        }

        if (strpos($request->path(), 'admin/activity') === 0) {
            return false;
        }

        return true;
    }
}
