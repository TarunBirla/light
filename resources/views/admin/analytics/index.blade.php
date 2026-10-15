@extends('layouts.admin')

@section('page-title', 'Visitor Analytics & Traffic Insights')
@section('breadcrumb', 'Admin / Analytics & Visitors')

@section('content')

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .kpi-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #E8E6DF;
        padding: 22px 20px;
        transition: transform .2s, box-shadow .2s;
        position: relative;
        overflow: hidden;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
    }
    .kpi-card .kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .kpi-value {
        font-size: 28px;
        font-weight: 800;
        color: #111;
        line-height: 1.2;
    }
    .kpi-label {
        font-size: 13px;
        font-weight: 600;
        color: #777;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .chart-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #E8E6DF;
        padding: 22px;
        margin-bottom: 24px;
    }
    .chart-title {
        font-size: 16px;
        font-weight: 700;
        color: #111;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .flag-icon {
        width: 20px;
        height: 14px;
        object-fit: cover;
        border-radius: 2px;
        margin-right: 6px;
        display: inline-block;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .device-badge {
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: 600;
    }
</style>

<!-- Date Filter Bar -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-chart-line me-2" style="color:#FFC700"></i>Web Analytics & Visitors</h3>
        <p class="text-muted small mb-0">Track real-time unique visitors, IP geolocation, devices, and user page journey.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="?range=today" class="btn btn-sm {{ $range == 'today' ? 'btn-warning fw-bold' : 'btn-light border' }}">Today</a>
        <a href="?range=yesterday" class="btn btn-sm {{ $range == 'yesterday' ? 'btn-warning fw-bold' : 'btn-light border' }}">Yesterday</a>
        <a href="?range=7" class="btn btn-sm {{ $range == '7' ? 'btn-warning fw-bold' : 'btn-light border' }}">Last 7 Days</a>
        <a href="?range=30" class="btn btn-sm {{ $range == '30' || !$range ? 'btn-warning fw-bold' : 'btn-light border' }}">Last 30 Days</a>
        <a href="?range=this_month" class="btn btn-sm {{ $range == 'this_month' ? 'btn-warning fw-bold' : 'btn-light border' }}">This Month</a>
    </div>
</div>

<!-- 1. Top KPI Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="kpi-card d-flex align-items-center justify-content-between">
            <div>
                <div class="kpi-label">Total Unique Visitors</div>
                <div class="kpi-value mt-1">{{ number_format($totalUniqueVisitors) }}</div>
                <small class="text-muted"><i class="fa-solid fa-fingerprint me-1 text-warning"></i>1 IP = 1 Unique Count</small>
            </div>
            <div class="kpi-icon bg-warning bg-opacity-25 text-dark">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="kpi-card d-flex align-items-center justify-content-between">
            <div>
                <div class="kpi-label">Today's Visitors</div>
                <div class="kpi-value mt-1 text-success">{{ number_format($todayVisitors) }}</div>
                <small class="text-muted"><i class="fa-solid fa-circle-dot text-success me-1"></i>Active Today</small>
            </div>
            <div class="kpi-icon bg-success bg-opacity-10 text-success">
                <i class="fa-solid fa-user-clock"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="kpi-card d-flex align-items-center justify-content-between">
            <div>
                <div class="kpi-label">Total Page Views</div>
                <div class="kpi-value mt-1 text-primary">{{ number_format($totalPageViews) }}</div>
                <small class="text-muted"><i class="fa-solid fa-arrow-trend-up text-primary me-1"></i>In selected period</small>
            </div>
            <div class="kpi-icon bg-primary bg-opacity-10 text-primary">
                <i class="fa-solid fa-eye"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="kpi-card d-flex align-items-center justify-content-between">
            <div>
                <div class="kpi-label">Most Visited Page</div>
                <div class="kpi-value mt-1 fs-5 text-truncate" style="max-width:180px;" title="{{ $topPage->page_name ?? 'N/A' }}">
                    {{ $topPage->page_name ?? 'Home' }}
                </div>
                <small class="text-muted">{{ $topPage ? number_format($topPage->total) . ' views' : '0 views' }}</small>
            </div>
            <div class="kpi-icon bg-info bg-opacity-10 text-info">
                <i class="fa-solid fa-file-lines"></i>
            </div>
        </div>
    </div>
</div>

<!-- 2. Charts Section -->
<div class="row g-3 mb-4">
    <!-- Main Traffic Trend Chart -->
    <div class="col-lg-8">
        <div class="chart-card h-100">
            <div class="chart-title">
                <span><i class="fa-solid fa-chart-area me-2 text-warning"></i>Traffic Trend (Visitors vs Page Views)</span>
                <span class="badge bg-light text-dark border">{{ $range == 'today' ? 'Hourly' : 'Daily' }}</span>
            </div>
            <div style="height: 320px;">
                <canvas id="trafficChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Device & Browser Distribution -->
    <div class="col-lg-4">
        <div class="chart-card mb-3" style="min-height: 180px;">
            <div class="chart-title">
                <span><i class="fa-solid fa-mobile-screen-button me-2 text-warning"></i>Devices</span>
            </div>
            <div style="height: 140px;">
                <canvas id="deviceChart"></canvas>
            </div>
        </div>

        <div class="chart-card mb-0" style="min-height: 195px;">
            <div class="chart-title">
                <span><i class="fa-solid fa-globe me-2 text-warning"></i>Top Browsers</span>
            </div>
            <div class="d-flex flex-column gap-2 mt-2">
                @forelse($browserStats->take(4) as $b)
                    @php
                        $pct = $totalUniqueVisitors > 0 ? round(($b->total / $totalUniqueVisitors) * 100) : 0;
                    @endphp
                    <div>
                        <div class="d-flex justify-content-between small fw-bold mb-1">
                            <span>{{ $b->browser }}</span>
                            <span>{{ $b->total }} ({{ $pct }}%)</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small">No browser data yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- 3. Top Countries & Top Pages Grid -->
<div class="row g-3 mb-4">
    <!-- Top Countries -->
    <div class="col-lg-5">
        <div class="chart-card h-100">
            <div class="chart-title">
                <span><i class="fa-solid fa-earth-americas me-2 text-warning"></i>Top Countries & Locations</span>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead>
                        <tr class="text-muted small">
                            <th>Country</th>
                            <th class="text-end">Visitors</th>
                            <th class="text-end">Share</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($countryStats as $c)
                            @php
                                $share = $filteredUniqueVisitors > 0 ? round(($c->total / $filteredUniqueVisitors) * 100, 1) : 0;
                                $flagUrl = $c->country_code ? "https://flagcdn.com/24x18/" . strtolower($c->country_code) . ".png" : null;
                            @endphp
                            <tr>
                                <td>
                                    @if($flagUrl)
                                        <img src="{{ $flagUrl }}" class="flag-icon" alt="{{ $c->country }}">
                                    @endif
                                    <strong>{{ $c->country }}</strong>
                                </td>
                                <td class="text-end fw-bold">{{ number_format($c->total) }}</td>
                                <td class="text-end text-muted small">{{ $share }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted">No location data captured yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Visited Pages -->
    <div class="col-lg-7">
        <div class="chart-card h-100">
            <div class="chart-title">
                <span><i class="fa-solid fa-file-circle-check me-2 text-warning"></i>Top Viewed Pages</span>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead>
                        <tr class="text-muted small">
                            <th>Page Name</th>
                            <th>URL</th>
                            <th class="text-end">Views</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topPagesList as $p)
                            <tr>
                                <td><strong class="text-dark">{{ $p->page_name }}</strong></td>
                                <td class="text-muted small" style="max-width:250px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                    <a href="{{ $p->url }}" target="_blank" class="text-decoration-none text-muted">
                                        {{ parse_url($p->url, PHP_URL_PATH) ?: '/' }}
                                    </a>
                                </td>
                                <td class="text-end"><span class="badge bg-warning text-dark fw-bold px-2 py-1">{{ number_format($p->total) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted">No page views recorded yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- 4. Detailed Visitors Log Table -->
<div class="table-card p-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h5 class="fw-bold mb-0"><i class="fa-solid fa-list me-2" style="color:#FFC700"></i>Unique Visitors Log</h5>
            <small class="text-muted">Showing individual unique IP visitors with location, device, and page journey.</small>
        </div>
        <form method="GET" action="{{ route('admin.analytics.index') }}" class="d-flex gap-2">
            <input type="hidden" name="range" value="{{ $range }}">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search IP, City, Browser..." class="form-control form-control-sm" style="min-width:220px;">
            <button type="submit" class="btn btn-sm btn-dark"><i class="fa-solid fa-search"></i></button>
            @if(request('search'))
                <a href="{{ route('admin.analytics.index', ['range' => $range]) }}" class="btn btn-sm btn-outline-secondary">Clear</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>IP Address & Location</th>
                    <th>Device & Browser</th>
                    <th>Landing (Entry) Page</th>
                    <th>Last Visited Page</th>
                    <th>Visits</th>
                    <th>Last Active</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visitors as $v)
                    @php
                        $flagUrl = $v->country_code ? "https://flagcdn.com/24x18/" . strtolower($v->country_code) . ".png" : null;
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($flagUrl)
                                    <img src="{{ $flagUrl }}" class="flag-icon" alt="{{ $v->country }}">
                                @endif
                                <div>
                                    <strong class="d-block text-dark">{{ $v->ip_address }}</strong>
                                    <small class="text-muted">
                                        {{ $v->city ?? 'Unknown' }}, {{ $v->country ?? 'Unknown' }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <span class="badge {{ $v->device == 'Mobile' ? 'bg-primary' : 'bg-secondary' }} device-badge me-1">
                                    <i class="fa-solid {{ $v->device == 'Mobile' ? 'fa-mobile-screen' : 'fa-laptop' }} me-1"></i>{{ $v->device }}
                                </span>
                                <small class="text-dark fw-semibold">{{ $v->browser }}</small>
                            </div>
                            <small class="text-muted">{{ $v->os }}</small>
                        </td>
                        <td style="max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                            <a href="{{ $v->landing_page }}" target="_blank" class="text-decoration-none text-muted small" title="{{ $v->landing_page }}">
                                {{ parse_url($v->landing_page, PHP_URL_PATH) ?: '/' }}
                            </a>
                        </td>
                        <td style="max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                            <a href="{{ $v->last_page }}" target="_blank" class="text-decoration-none text-dark small fw-semibold" title="{{ $v->last_page }}">
                                {{ parse_url($v->last_page, PHP_URL_PATH) ?: '/' }}
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border fw-bold">{{ $v->total_visits ?? 1 }}</span>
                        </td>
                        <td>
                            <small class="text-muted">{{ $v->last_visit_at ? $v->last_visit_at->diffForHumans() : '-' }}</small>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-dark" onclick="viewJourney({{ $v->id }}, '{{ $v->ip_address }}')">
                                <i class="fa-solid fa-route me-1"></i> Journey
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No visitor records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $visitors->links() }}
    </div>
</div>

<!-- Modal: Visitor Page Journey Modal -->
<div class="modal fade" id="journeyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="journeyModalTitle">
                    <i class="fa-solid fa-route text-warning me-2"></i>Visitor Page Journey
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="journeyLoader" class="text-center py-4">
                    <div class="spinner-border text-warning" role="status"></div>
                    <p class="text-muted mt-2 small">Loading visited pages...</p>
                </div>

                <div id="journeyContent" style="display:none;">
                    <div class="p-3 bg-light rounded-3 mb-3 d-flex flex-wrap justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small">IP Address:</span> <strong id="modalIp"></strong><br>
                            <span class="text-muted small">Location:</span> <span id="modalLocation"></span>
                        </div>
                        <div>
                            <span class="text-muted small">Device:</span> <span id="modalDevice"></span><br>
                            <span class="text-muted small">Total Pages Viewed:</span> <span class="badge bg-dark" id="modalCount">0</span>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3"><i class="fa-solid fa-clock-rotate-left me-2 text-warning"></i>Page Journey History (Latest First)</h6>
                    <div class="table-responsive" style="max-height: 350px; overflow-y:auto;">
                        <table class="table table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Page Name</th>
                                    <th>URL Path</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody id="journeyTableBody">
                                <!-- Loaded via Ajax -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Scripts -->
<script>
    // 1. Traffic Line Chart
    const ctx = document.getElementById('trafficChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dates) !!},
            datasets: [
                {
                    label: 'Unique Visitors',
                    data: {!! json_encode($visitorsData) !!},
                    borderColor: '#FFC700',
                    backgroundColor: 'rgba(255, 199, 0, 0.15)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 3,
                    pointBackgroundColor: '#FFC700',
                    pointRadius: 4,
                },
                {
                    label: 'Page Views',
                    data: {!! json_encode($pageViewsData) !!},
                    borderColor: '#111111',
                    backgroundColor: 'transparent',
                    borderDash: [5, 5],
                    tension: 0.35,
                    borderWidth: 2,
                    pointRadius: 3,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });

    // 2. Devices Donut Chart
    const devCtx = document.getElementById('deviceChart').getContext('2d');
    @php
        $deviceLabels = $deviceStats->pluck('device')->toArray();
        $deviceCounts = $deviceStats->pluck('total')->toArray();
    @endphp
    new Chart(devCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($deviceLabels) !!},
            datasets: [{
                data: {!! json_encode($deviceCounts) !!},
                backgroundColor: ['#111111', '#FFC700', '#6c757d', '#17a2b8'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right' }
            }
        }
    });

    // Modal Ajax Function
    function viewJourney(visitorId, ip) {
        const modal = new bootstrap.Modal(document.getElementById('journeyModal'));
        document.getElementById('journeyLoader').style.display = 'block';
        document.getElementById('journeyContent').style.display = 'none';
        modal.show();

        fetch(`/admin/analytics/visitor-journey/${visitorId}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('journeyLoader').style.display = 'none';
                document.getElementById('journeyContent').style.display = 'block';

                const v = data.visitor;
                document.getElementById('modalIp').innerText = v.ip_address;
                document.getElementById('modalLocation').innerText = `${v.city || 'Unknown'}, ${v.country || 'Unknown'}`;
                document.getElementById('modalDevice').innerText = `${v.device} - ${v.browser} (${v.os})`;
                document.getElementById('modalCount').innerText = data.pages.length;

                const tbody = document.getElementById('journeyTableBody');
                tbody.innerHTML = '';

                if (data.pages.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center py-3 text-muted">No page logs recorded.</td></tr>';
                    return;
                }

                data.pages.forEach((p, idx) => {
                    const row = `
                        <tr>
                            <td><span class="badge bg-light text-dark border">${idx + 1}</span></td>
                            <td><strong class="text-dark">${p.page_name || 'Home'}</strong></td>
                            <td class="text-muted small">${p.url}</td>
                            <td class="text-muted small">${new Date(p.viewed_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
            })
            .catch(err => {
                document.getElementById('journeyLoader').innerHTML = '<p class="text-danger">Failed to load journey data.</p>';
            });
    }
</script>

@endsection
