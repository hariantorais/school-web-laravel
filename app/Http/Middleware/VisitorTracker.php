<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class VisitorTracker
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Skip untuk admin dan ajax
        if ($request->is('admin*') || $request->ajax() || $request->is('livewire*')) {
            return $response;
        }

        $ip = $request->ip();
        $userAgent = $request->userAgent();

        // Cek apakah unique visitor (berdasarkan IP + User Agent)
        $cacheKey = 'visitor_' . md5($ip . $userAgent . today()->toDateString());
        $isUnique = !Cache::has($cacheKey);

        if ($isUnique) {
            Cache::put($cacheKey, true, now()->endOfDay());
        }

        // Detect device
        $device = $this->detectDevice($userAgent);
        $browser = $this->detectBrowser($userAgent);
        $os = $this->detectOS($userAgent);

        // Get location from IP (optional)
        $location = $this->getLocation($ip);

        Visitor::create([
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'url' => $request->fullUrl(),
            'referrer' => $request->header('referer'),
            'device' => $device,
            'browser' => $browser,
            'os' => $os,
            'country' => $location['country'] ?? null,
            'city' => $location['city'] ?? null,
            'is_unique' => $isUnique,
        ]);

        return $response;
    }

    private function detectDevice($userAgent): string
    {
        if (str_contains($userAgent, 'Mobile')) {
            return 'mobile';
        }
        if (str_contains($userAgent, 'Tablet')) {
            return 'tablet';
        }
        return 'desktop';
    }

    private function detectBrowser($userAgent): string
    {
        if (str_contains($userAgent, 'Chrome')) return 'Chrome';
        if (str_contains($userAgent, 'Firefox')) return 'Firefox';
        if (str_contains($userAgent, 'Safari')) return 'Safari';
        if (str_contains($userAgent, 'Edge')) return 'Edge';
        if (str_contains($userAgent, 'Opera')) return 'Opera';
        return 'Other';
    }

    private function detectOS($userAgent): string
    {
        if (str_contains($userAgent, 'Windows')) return 'Windows';
        if (str_contains($userAgent, 'Mac')) return 'MacOS';
        if (str_contains($userAgent, 'Linux')) return 'Linux';
        if (str_contains($userAgent, 'Android')) return 'Android';
        if (str_contains($userAgent, 'iOS')) return 'iOS';
        return 'Other';
    }

    private function getLocation($ip): array
    {
        try {
            $response = Http::timeout(2)->get("http://ip-api.com/json/{$ip}");
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'country' => $data['country'] ?? null,
                    'city' => $data['city'] ?? null,
                ];
            }
        } catch (\Exception $e) {
            // Silent fail
        }
        return ['country' => null, 'city' => null];
    }
}
