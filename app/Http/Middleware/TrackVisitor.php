<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;
use App\Models\PageView;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Sirf GET requests aur frontend web pages ko track karenge (admin, api, static assets ko skip karenge)
        if ($request->isMethod('GET') && !$this->shouldSkip($request)) {
            try {
                $this->track($request);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('TrackVisitor Error: ' . $e->getMessage());
            }
        }

        return $response;
    }

    /**
     * Skip unwanted routes like admin, debugbar, assets, ajax
     */
    protected function shouldSkip(Request $request): bool
    {
        $path = $request->path();

        if (
            str_starts_with($path, 'admin') ||
            str_starts_with($path, 'api') ||
            str_starts_with($path, '_') ||
            str_starts_with($path, 'run-') ||
            $request->ajax() ||
            $request->wantsJson()
        ) {
            return true;
        }

        // Static file extensions check
        $extensions = ['js', 'css', 'png', 'jpg', 'jpeg', 'webp', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'map', 'txt', 'xml'];
        foreach ($extensions as $ext) {
            if (str_ends_with($path, '.' . $ext)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Record visitor and page view
     */
    protected function track(Request $request)
    {
        // Safe check agar tables create na hui ho
        if (!Schema::hasTable('visitors') || !Schema::hasTable('page_views')) {
            return;
        }

        $ip = $this->getClientIp($request);
        $userAgent = $request->header('User-Agent', '');
        $url = $request->fullUrl();
        $path = $request->path();
        $referrer = $request->header('referer') ?: null;

        $deviceInfo = $this->parseUserAgent($userAgent);

        // 1 IP Address ko Ek Hi Record me maintain karenge (Unique Visitor Tracking)
        $visitor = Visitor::where('ip_address', $ip)->first();

        if (!$visitor) {
            // New unique visitor - fetch Geolocation via Free IP API
            $geo = $this->getIpLocation($ip);

            $visitor = Visitor::create([
                'ip_address'      => $ip,
                'country'         => $geo['country'] ?? 'Unknown',
                'country_code'    => $geo['country_code'] ?? null,
                'city'            => $geo['city'] ?? 'Unknown',
                'region'          => $geo['region'] ?? 'Unknown',
                'latitude'        => $geo['latitude'] ?? null,
                'longitude'       => $geo['longitude'] ?? null,
                'isp'             => $geo['isp'] ?? null,
                'browser'         => $deviceInfo['browser'],
                'browser_version' => $deviceInfo['version'],
                'device'          => $deviceInfo['device'],
                'os'              => $deviceInfo['os'],
                'landing_page'    => $url,
                'last_page'       => $url,
                'referrer'        => $referrer,
                'total_visits'    => 1,
                'last_visit_at'   => now(),
            ]);
        } else {
            // Existing visitor: Update last seen and last page
            $updateData = [
                'last_page'     => $url,
                'last_visit_at' => now(),
            ];

            // Agar 30 minutes se zyada ho gaya hai to session/visit count badha do
            if (!$visitor->last_visit_at || $visitor->last_visit_at->diffInMinutes(now()) >= 30) {
                $updateData['total_visits'] = ($visitor->total_visits ?? 1) + 1;
            }

            // Agar pehle location miss hui ho to dobara try karo
            if (!$visitor->country || $visitor->country === 'Unknown') {
                $geo = $this->getIpLocation($ip);
                if (!empty($geo['country']) && $geo['country'] !== 'Unknown') {
                    $updateData['country'] = $geo['country'];
                    $updateData['country_code'] = $geo['country_code'];
                    $updateData['city'] = $geo['city'];
                    $updateData['region'] = $geo['region'];
                    $updateData['latitude'] = $geo['latitude'];
                    $updateData['longitude'] = $geo['longitude'];
                    $updateData['isp'] = $geo['isp'];
                }
            }

            $visitor->update($updateData);
        }

        // Record the page journey log in page_views table
        PageView::create([
            'visitor_id' => $visitor->id,
            'ip_address' => $ip,
            'url'        => $url,
            'page_name'  => $path === '/' || $path === '' ? 'Home' : ucwords(str_replace(['/', '-', '_'], ' ', $path)),
            'referrer'   => $referrer,
            'method'     => $request->method(),
            'viewed_at'  => now(),
        ]);
    }

    /**
     * Get real client IP address even behind Cloudflare / reverse proxy
     */
    protected function getClientIp(Request $request): string
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ];

        foreach ($headers as $header) {
            $ip = $request->server($header);
            if ($ip) {
                // X-Forwarded-For can contain comma-separated IPs
                $parts = explode(',', $ip);
                $cleanIp = trim($parts[0]);
                if (filter_var($cleanIp, FILTER_VALIDATE_IP)) {
                    return $cleanIp;
                }
            }
        }

        return $request->ip() ?: '127.0.0.1';
    }

    /**
     * Fetch Geolocation details from Free IP API (Cached for 24 hours per IP)
     */
    protected function getIpLocation(string $ip): array
    {
        // Localhost/Private IP fallback
        if (in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return [
                'country'      => 'Local Host',
                'country_code' => 'GB',
                'city'         => 'London',
                'region'       => 'England',
                'latitude'     => 51.5074,
                'longitude'    => -0.1278,
                'isp'          => 'Development Local Network',
            ];
        }

        return Cache::remember('ip_geo_' . $ip, 86400, function () use ($ip) {
            try {
                // Using ip-api.com free API (No key required, 45 req/min, highly accurate)
                $response = Http::timeout(2)->get("http://ip-api.com/json/{$ip}?fields=status,message,country,countryCode,regionName,city,lat,lon,isp");

                if ($response->successful()) {
                    $data = $response->json();
                    if (($data['status'] ?? '') === 'success') {
                        return [
                            'country'      => $data['country'] ?? 'Unknown',
                            'country_code' => $data['countryCode'] ?? null,
                            'city'         => $data['city'] ?? 'Unknown',
                            'region'       => $data['regionName'] ?? 'Unknown',
                            'latitude'     => $data['lat'] ?? null,
                            'longitude'    => $data['lon'] ?? null,
                            'isp'          => $data['isp'] ?? null,
                        ];
                    }
                }
            } catch (\Exception $e) {
                // Fallback attempt with ipapi.co if needed
                try {
                    $alt = Http::timeout(2)->get("https://ipapi.co/{$ip}/json/");
                    if ($alt->successful()) {
                        $a = $alt->json();
                        return [
                            'country'      => $a['country_name'] ?? 'Unknown',
                            'country_code' => $a['country_code'] ?? null,
                            'city'         => $a['city'] ?? 'Unknown',
                            'region'       => $a['region'] ?? 'Unknown',
                            'latitude'     => $a['latitude'] ?? null,
                            'longitude'    => $a['longitude'] ?? null,
                            'isp'          => $a['org'] ?? null,
                        ];
                    }
                } catch (\Exception $ex) {
                    // Ignore
                }
            }

            return [
                'country'      => 'Unknown',
                'country_code' => null,
                'city'         => 'Unknown',
                'region'       => 'Unknown',
                'latitude'     => null,
                'longitude'    => null,
                'isp'          => null,
            ];
        });
    }

    /**
     * Parse browser, device, OS from User-Agent string
     */
    protected function parseUserAgent(string $ua): array
    {
        $browser = 'Other';
        $version = '';
        $device  = 'Desktop';
        $os      = 'Other';

        // 1. Device detection
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $ua)) {
            $device = 'Tablet';
        } elseif (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', $ua)) {
            $device = 'Mobile';
        }

        // 2. OS detection
        if (preg_match('/windows nt 10/i', $ua)) {
            $os = 'Windows 10/11';
        } elseif (preg_match('/windows nt 6.3/i', $ua)) {
            $os = 'Windows 8.1';
        } elseif (preg_match('/windows/i', $ua)) {
            $os = 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $ua)) {
            $os = 'macOS';
        } elseif (preg_match('/iphone/i', $ua)) {
            $os = 'iOS (iPhone)';
        } elseif (preg_match('/ipad/i', $ua)) {
            $os = 'iOS (iPad)';
        } elseif (preg_match('/android/i', $ua)) {
            $os = 'Android';
        } elseif (preg_match('/linux/i', $ua)) {
            $os = 'Linux';
        }

        // 3. Browser detection
        if (preg_match('/edg/i', $ua)) {
            $browser = 'Microsoft Edge';
        } elseif (preg_match('/chrome|crios/i', $ua)) {
            $browser = 'Google Chrome';
        } elseif (preg_match('/firefox|fxios/i', $ua)) {
            $browser = 'Mozilla Firefox';
        } elseif (preg_match('/safari/i', $ua) && !preg_match('/chrome/i', $ua)) {
            $browser = 'Apple Safari';
        } elseif (preg_match('/opera|opr/i', $ua)) {
            $browser = 'Opera';
        } elseif (preg_match('/msie|trident/i', $ua)) {
            $browser = 'Internet Explorer';
        }

        return [
            'browser' => $browser,
            'version' => $version,
            'device'  => $device,
            'os'      => $os,
        ];
    }
}
