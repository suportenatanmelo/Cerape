<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CaptureAuditContext
{
    public function handle(Request $request, Closure $next)
    {
        // Capture the necessary context for auditing
        $request->attributes->set('ip_address', $request->ip());
        $request->attributes->set('user_agent', $request->userAgent());
        $request->attributes->set('browser', $this->getBrowser($request));
        $request->attributes->set('platform', $this->getPlatform($request));
        $request->attributes->set('device', $this->getDevice($request));
        $request->attributes->set('method', $request->method());
        $request->attributes->set('url', $request->fullUrl());
        $request->attributes->set('route', $request->route()->getName());
        $request->attributes->set('session_id', session()->getId());

        return $next($request);
    }

    private function getBrowser(Request $request)
    {
        // Logic to determine the browser from the user agent
        // This can be enhanced with a library if needed
        return 'Unknown Browser';
    }

    private function getPlatform(Request $request)
    {
        // Logic to determine the platform from the user agent
        return 'Unknown Platform';
    }

    private function getDevice(Request $request)
    {
        // Logic to determine the device type from the user agent
        return 'Unknown Device';
    }
}