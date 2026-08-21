@extends('front.layouts.app')

@section('content')
<style>
    .eq-page-header {
        background: #111;
        color: #fff;
        padding: 35px 0;
        margin-bottom: 25px;
        border-bottom: 4px solid var(--brand);
    }

    .eq-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        margin-bottom: 25px;
        overflow: hidden;
    }

    .eq-card-header {
        background: #111;
        color: #fff;
        padding: 14px 20px;
        font-weight: 700;
        font-size: 1.1rem;
        letter-spacing: .5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .eq-card-header i {
        color: var(--brand);
    }

    .eq-card-body {
        padding: 22px;
    }

    .eq-form-label {
        font-weight: 600;
        font-size: 0.88rem;
        margin-bottom: 5px;
        color: #222;
    }

    .eq-form-control {
        border-radius: 8px;
        border: 1px solid #ccc;
        padding: 9px 13px;
        font-size: 0.92rem;
        transition: border-color .2s, box-shadow .2s;
    }

    .eq-form-control:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(255, 199, 0, 0.25);
        outline: none;
    }

    .eq-section-subtitle {
        font-size: 0.95rem;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--dark);
        border-bottom: 2px solid var(--brand);
        padding-bottom: 6px;
        margin-bottom: 16px;
        letter-spacing: 0.5px;
    }

    /* Styled Category Tabs Bar */
    .category-tabs-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 20px;
        background: #e9ecef;
        padding: 8px;
        border-radius: 10px;
        border: 1px solid #ced4da;
    }

    .cat-tab-btn {
        background: linear-gradient(180deg, #ffffff 0%, #e9ecef 100%);
        color: #1d4ed8;
        border: 1px solid #bde0fe;
        font-weight: 700;
        font-size: 0.92rem;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cat-tab-btn:hover {
        background: #dbeafe;
        color: #1e40af;
    }

    .cat-tab-btn.active {
        background: linear-gradient(180deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e3a8a;
        border-color: #3b82f6;
        font-weight: 800;
        box-shadow: 0 3px 8px rgba(59, 130, 246, 0.3);
    }

    .badge-qty-count {
        background: #d97706;
        color: #fff;
        font-size: 0.75rem;
        padding: 2px 7px;
        border-radius: 10px;
        font-weight: 800;
    }

    /* Sub-Header Banner */
    .red-sub-header {
        background: #FFC700;
        color: #000;
        padding: 8px 14px;
        font-weight: 800;
        font-size: 0.95rem;
        text-transform: uppercase;
        border-radius: 6px;
        margin-top: 18px;
        margin-bottom: 12px;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 4px rgba(204,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .category-scroll-container {
        max-height: 520px;
        overflow-y: auto;
        padding-right: 8px;
    }

    .category-scroll-container::-webkit-scrollbar {
        width: 7px;
    }

    .category-scroll-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .category-scroll-container::-webkit-scrollbar-thumb {
        background: #cc0000;
        border-radius: 4px;
    }

    .product-item-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 8px 12px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        height: 100%;
    }

    .product-item-card:hover {
        border-color: #999;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }

    .product-item-card.has-qty {
        border-color: #cc0000;
        background-color: #fff5f5;
        box-shadow: 0 0 0 1px #cc0000;
    }

    .product-title-text {
        font-weight: 600;
        font-size: 0.88rem;
        color: #111;
        word-break: break-word;
    }

    .qty-input-group {
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }

    .qty-btn {
        width: 28px;
        height: 28px;
        background: #eee;
        border: 1px solid #ccc;
        font-weight: bold;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        user-select: none;
        transition: background .15s;
    }

    .qty-btn:hover {
        background: #cc0000;
        color: #fff;
        border-color: #990000;
    }

    .qty-btn.btn-minus {
        border-radius: 5px 0 0 5px;
    }

    .qty-btn.btn-plus {
        border-radius: 0 5px 5px 0;
    }

    .qty-number-input {
        width: 44px;
        height: 28px;
        text-align: center;
        border-top: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
        border-left: none;
        border-right: none;
        font-weight: 700;
        font-size: 0.88rem;
    }

    .qty-number-input::-webkit-inner-spin-button,
    .qty-number-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Fixed Submit Form Card at Bottom */
    .submit-card {
        background: #111;
        color: #fff;
        border-radius: var(--radius);
        padding: 22px;
        box-shadow: var(--shadow);
        border-top: 4px solid var(--brand);
        margin-top: 25px;
    }

    .submit-summary-box {
        font-size: 1.1rem;
    }

    .submit-summary-box strong {
        color: var(--brand);
        font-size: 1.3rem;
    }
</style>

<!-- Header Banner -->
<div class="eq-page-header text-center">
    <div class="container">
        <h1 class="fw-bold mb-2" style="font-size: 2.2rem;">EQUIPMENT ORDER FORM (v3.1)</h1>
        <p class="text-muted mb-0" style="color: #ccc !important;">Select your required equipment quantities below and proceed to production details.</p>
    </div>
</div>

<div class="container pb-5">

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <h6 class="fw-bold mb-2"><i class="bi bi-x-circle-fill me-2"></i> Please fix the following errors:</h6>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('equipment-requestnew.store') }}" method="POST" id="equipmentRequestForm">
        @csrf

        <!-- SECTION 1: STATIC CATEGORY & PRODUCT QUANTITY SELECTION (GRID 3) -->
        <div class="eq-card">
            <div class="eq-card-header">
                <i class="bi bi-boxes"></i>
                <span>PRODUCT QUANTITY SELECTION (STATIC SPECIFICATION v3.1)</span>
            </div>
            <div class="eq-card-body">

                <!-- 5 Static Category Tabs -->
                @php
                    $staticCategories = [
                        'LED & Daylight',
                        'Tungsten, Control, Distro',
                        'Cables & Hardware',
                        'Cons, Vehicles & Power',
                        'Red Rack Recipes'
                    ];
                @endphp

                <div class="category-tabs-wrapper">
                    @foreach($staticCategories as $cIndex => $catName)
                        <button type="button" 
                                class="cat-tab-btn {{ $cIndex === 0 ? 'active' : '' }}" 
                                data-cat-slug="cat-{{ $cIndex }}"
                                onclick="switchStaticTab('cat-{{ $cIndex }}')">
                            <span>{{ $catName }}</span>
                            <span class="badge-qty-count" id="badge-cat-{{ $cIndex }}" style="display:none;">0</span>
                        </button>
                    @endforeach
                </div>

                <!-- CATEGORY PANELS CONTAINER -->
                <div id="staticCategoryPanels">

                    <!-- TAB 1: LED & Daylight -->
                    <div class="category-panel" id="cat-panel-cat-0" style="display: block;">
                        <div class="category-scroll-container">
                            
                            @php
                                $ledDaylightData = [
                                    'Daylight' => [
                                        'Arri M-Series: 18k M18', '12k M90', '9k M90', '6k M90', '4k M40', '2.5k M40', '1.8k M18', '800w M8',
                                        'Fresnel: 18k HMI', '12k MSR', '6k MSR', '4k MSR', '2.5k MSR', '1.2k MSR', '575w MSR',
                                        'Par: Alpha 18kw', 'Alpha 9kw', 'Alpha 4kw', '1.2kw Par', '575w Par',
                                        'Flood: 12kw Desisti Goya', '4kw Desisti Goya', '2.5kw Desisti Goya', '6kw Arri X-Light', '4kw Arri X-Light', '2.5kw Arri X-Light', '1.2kw Arri X-Light', '100kw Softsun',
                                        'Spotlight: 12kw Molebeam', '4kw Molebeam', '2.5kw Molebeam',
                                        'Daylight Kits: 200w Par', '200w Fresnel', '200w Flood', '400w Arri Pocket Par', '200w Arri Pocket Par', '125w Arri Pocket Par', '800w Joker', 'Bug A Beam (800)', '400w Joker', '200w Joker', 'Bug A Beam (400/200)',
                                        'Dedo: Panaura 7 (2 x 400w)', 'Panaura 5 (400wMSR)', 'Panaura 3 (200wMSR)', 'DLH400D 400w MSR', '400w MSR Lens', 'DLH200D 200w MSR', '200w MSR Lens', 'DLH200S 200w MSR',
                                        'Motorised Stirrup: 77', '100', '140', '180', '220', '330', '6kW', '12kW', '18kW'
                                    ],
                                    'LED ( Panels )' => [
                                        'Panalux Allegra 4:1 C', 'Panalux Allegra 4:2 C', 'Panalux Allegra 2:2 C', 'Panalux Allegra 2:1 C', 'Panalux Sonara 4:4', 'Panalux Sonara 3:2', 'Panalux Sonara 4:1', 'Panalux Tektile2 - Bi-Colour', 'Arri L10-C Fresnel (Hybrid)', 'Arri L7-C Fresnel (Hybrid)', 'Orbitor', 'S 360 SkyPanel (head only)', 'S 360 c/w SnapBag & Grid', 'S 60 SkyPanel (head only)', 'S 60 c/w SnapBag & Grid', 'S 30 SkyPanel (head only)', 'S 30 c/w SnapBag & Grid', 'SkyPanel Remote Controller', 'S 60 Spacelight Kit', 'S 60 Octodome Kit (5ft)', 'Aladdin Mosaic 4x4', 'Aladdin Bi Flex M7', 'Aladdin A-Lite', 'Creamsource Vortex 8 (bare head)', 'Vortex 8 c/w SnapBag & Grid', 'Vortex 4 (bare head)', 'Vortex 4 c/w SnapBag & Grid', 'Vortex 8 Octodome Kit (5ft)', 'Creamsource Micro Colour', 'KinoFlo Celeb 850', 'Celeb 401Q', 'Celeb 401', 'Celeb 201', 'Freestyle 41', 'Freestyle 31', 'Freestyle 21', 'Freestyle Mini', 'Freestyle T44 (4ft 4-Bank)', 'LiteGear Auroris V', 'Auroris X', "LiteTile 8'x8' Kit", 'LiteTile 8 (8\'x2\')', 'LiteTile 4\'x4\' (2x 4\'x2\')', 'LiteMat 8', 'LiteMat 4', 'LiteMat 3', 'LiteMat 2L', 'LiteMat 2', 'LiteMat 1', 'Spectrum 4', 'Spectrum 3', 'Spectrum 2L', 'Spectrum 2', 'Spectrum 1', 'LitePanels Gemini 2x1 Soft (bare)', 'Gemini 2x1 Soft (kit)', 'Gemini 1x1 Hard kit', 'Gemini 2x1 Hard Kit', '4-Way Gemini (4x2)', '2-Way Gemini (4x1)', '1x1 Bi-Colour'
                                    ],
                                    'LED ( Others & Kits )' => [
                                        'Panalux 100 Spring Ball', 'Panalux LED Spring Ball', 'MC Mini - 4 head kit', 'MC Mini - 9 head kit', 'Infinibar - 8 light kit', 'B7c kit - 8 light', 'Astera Kits AX 3 (x8 heads)', 'AX 5 (x8 heads)', 'AX 9 (x8 heads)', 'Helios Tube 2ft (x8)', 'Hyperion Tube 8ft (x4)', 'Titan Tube 4ft (x8)', 'Tube Charging Cable 15m', 'NYX Bulb kit BC (x8)', 'NYX Bulb kit ES (x8)', 'NYX Bulb kit Mixed (x8)', 'Astera Luna Kit (x8)', 'Pixel Brick kit (x8)', 'Hydra Panel kit (x4)', 'Dedo DLED4 Kit (3 x head)', 'DLED7 Kit (3 x head)', 'DLED9 Kit (1 x head)', 'ETC Source 4 LUSTR (15-30°)', 'Source 4 LUSTR (25-50°)', 'Fiilex Q5', 'Q8', 'Q10', 'Q6', 'G3', 'G6', 'Quasar Science Double Rainbow - 2ft', 'Double Rainbow - 4ft', 'Quasar Science RR2 - 8ft', 'RODLIGHT Rodlight 1.6m', 'Rodlight 2.5m', 'Rodlight 5m', 'Rodlight 10m', 'Rosco DMG Dash kit (x4 heads)', 'Dot for Dash (x1)', 'SGM P-5 RGBW (IP65)', 'P-5 TW Bi-Colour (IP65)', 'Xenon Torch', 'CLF Yara RGBW Par', 'Thomas Pixelpar 90L', 'TheLight Velvet KOSMOS 400', 'MilTec LEDHead Batten 2', 'Litemover kit (fits static fixtures)', 'Pheon Lux M48c'
                                    ],
                                    'LED ( COB Types )' => [
                                        'Aputure 60 X', '300 D', '600 C', '600 D', '600 X', '1200 D', '1200 X', 'Nanlux 5000 B', '2400 B', '1200 B', '900 C', 'Nanlite Forza 60 c', 'Forza 150 B', 'Forza 300 B', 'Forza 720 B', 'ProFoto L 1600 D', 'Reflector kit', 'Beauty Dish'
                                    ],
                                    'Dimmer Shutters & Moving Lights' => [
                                        'Dimmer Shutters DMX 700mm', '430mm', '350mm', '250mm', '200mm', 'Lamp Mounted Shutter', 'Stand Mounted Shutter', 'Ayrton Domino Profile LED', 'Clay Paky Sharpy', 'Mac Viper Performance', 'Mac Viper Profile', 'Mac Aura XB', 'Robe BMFL Washbeam'
                                    ]
                                ];
                            @endphp

                            @foreach($ledDaylightData as $subHeader => $items)
                                <div class="red-sub-header">
                                    <i class="bi bi-lightbulb-fill"></i> {{ $subHeader }}
                                </div>
                                <div class="row row-cols-1 row-cols-md-3 g-2 mb-3">
                                    @foreach($items as $itemTitle)
                                        @php $itemKey = md5('LED & Daylight' . $subHeader . $itemTitle); @endphp
                                        <div class="col">
                                            <div class="product-item-card" id="card-{{ $itemKey }}">
                                                <div class="product-title-text me-auto">{{ $itemTitle }}</div>
                                                <div class="qty-input-group">
                                                    <button type="button" class="qty-btn btn-minus" onclick="adjustStaticQty('{{ $itemKey }}', 'cat-0', -1)">-</button>
                                                    <input type="number" 
                                                           name="quantities[LED & Daylight][{{ $itemTitle }}]" 
                                                           id="input-{{ $itemKey }}" 
                                                           class="qty-number-input static-qty-field" 
                                                           data-cat-slug="cat-0"
                                                           data-category-name="LED & Daylight"
                                                           data-product-title="{{ $itemTitle }}"
                                                           data-key="{{ $itemKey }}"
                                                           min="0" value="0" 
                                                           oninput="onStaticQtyChange('{{ $itemKey }}', 'cat-0')">
                                                    <button type="button" class="qty-btn btn-plus" onclick="adjustStaticQty('{{ $itemKey }}', 'cat-0', 1)">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <!-- TAB 2: Tungsten, Control, Distro -->
                    <div class="category-panel" id="cat-panel-cat-1" style="display: none;">
                        <div class="category-scroll-container">
                            
                            @php
                                $tungstenData = [
                                    'Tungsten Fresnel & Softlight' => [
                                        'Fresnel 24kw', '20kw', '12kw (T12)', '10kw', '5kw', '2kw', '1kw', '650w', '500w Mizar', '300w', '150w',
                                        'Toplight', '5kw Arrisoft', '2.5kw Arrisoft', '2.5/5k Queen Beacon', '4kw Mole Softlight', '2.5kw Zap', '1.6kw Zap', '800w Zap', '1kw Rifa c/w eggcrate', '650w Rifa c/w eggcrate', '500w Rifa c/w eggcrate', '300w Rifa c/w eggcrate', '2kw Jemball (30")', '1kw Jemball (22")', '500w Jemball (19")'
                                    ],
                                    'PAR 64 & Par 36' => [
                                        '24-Light Dino', '12-Light Dino', '9-Light MaxiBrute', '6-Light MaxiBrute', '6-Light Dino Pod', '4-Light Dino Pod', '6 x Lamp Bar', 'Par64 Can Black spigot', 'Par64 Can Black hook', 'Par64 Can Silver spigot', 'Par64 Can Silver hook', 'Par 64 Floor Can Black', 'Par 64 Floor Can Silver', '750w Source 4 Par', 'Full Wendy', 'Half Wendy', 'Quarter Wendy', '8-Light MiniBrute', '6-Light MiniBrute', '4-Light MiniBrute', '2-Light MiniBrute', 'Par 36 Can (110V)'
                                    ],
                                    'Effects & Battens' => [
                                        'Atomic 3000 Strobe', 'Clay Paky Stormy CC (RGBW)', 'Clay Paky Stormy (White)', 'Hungaroflash 15kW Strobe', '250kW Lightning Strike', '70kW Lightning Strike', '40kW Lightning Strike', 'Thundervoltz Battery Pack', '99k Longstrike', '8k Paparazzi Flash', 'Paparazzi 1500 LED', 'Lumipix 16H Batten', '400w Altman UV Fresnel', 'UV Blackgun', 'Infra Red LED 125mm', 'DF50 Cracked Oil - DMX', 'Unique Hazer 2.1 - DMX', 'Bowens Jetstream', '40cm London Fan', '18" Wind Machine'
                                    ],
                                    'Control & Dimmers' => [
                                        'Grand MA ~ Link System', 'Grand MA 2 Light', 'Chamsys Magic Q MQ100', 'Chamsys Magic Q MQ60', 'LSC Maxim 60/120', 'LSC Maxim 48/96', 'LSC Maxim 24/48', 'LSC Minim 12/24', 'Cinelex Spectre TX8 8ch Wireless', 'Gaffers Control Mk2', '12 x 15k Avo ART2000', '24 x 6k Avo ART2000', '48 x 3k Avo ART2000', '30k Single Dimmer - DMX', '15k Single Dimmer inline DMX', '5k Variac', '2k Variac', '3 x 2k Shadowmaker', '5k Flicker Dimmer', '2.5k Flicker Dimmer'
                                    ],
                                    'Power Distribution & Protection' => [
                                        '400A ISU (BAC .2 IN)', '150A ISU (BAC .1 IN)', '6 x .1 HDD (BAC .2 IN)', '12 x 63A HDD (BAC .2 IN)', '6 x 63A 3ph HDD (.2)', '72 x 16A CCR (BAC.2)', '12 x 63A CCR', '12 x Soca CCR', '3 x .1 CC (.2)', '3 x .1 CCR', '6 x 63A CC', '4 x 16A K9', '4 x 13A Stagebox', '63A 3ph RCD 30mA', '32A 3ph RCD 30mA', '63A RCD 30mA', '32A RCD 30mA'
                                    ]
                                ];
                            @endphp

                            @foreach($tungstenData as $subHeader => $items)
                                <div class="red-sub-header">
                                    <i class="bi bi-sliders"></i> {{ $subHeader }}
                                </div>
                                <div class="row row-cols-1 row-cols-md-3 g-2 mb-3">
                                    @foreach($items as $itemTitle)
                                        @php $itemKey = md5('Tungsten' . $subHeader . $itemTitle); @endphp
                                        <div class="col">
                                            <div class="product-item-card" id="card-{{ $itemKey }}">
                                                <div class="product-title-text me-auto">{{ $itemTitle }}</div>
                                                <div class="qty-input-group">
                                                    <button type="button" class="qty-btn btn-minus" onclick="adjustStaticQty('{{ $itemKey }}', 'cat-1', -1)">-</button>
                                                    <input type="number" 
                                                           name="quantities[Tungsten, Control, Distro][{{ $itemTitle }}]" 
                                                           id="input-{{ $itemKey }}" 
                                                           class="qty-number-input static-qty-field" 
                                                           data-cat-slug="cat-1"
                                                           data-category-name="Tungsten, Control, Distro"
                                                           data-product-title="{{ $itemTitle }}"
                                                           data-key="{{ $itemKey }}"
                                                           min="0" value="0" 
                                                           oninput="onStaticQtyChange('{{ $itemKey }}', 'cat-1')">
                                                    <button type="button" class="qty-btn btn-plus" onclick="adjustStaticQty('{{ $itemKey }}', 'cat-1', 1)">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <!-- TAB 3: Cables & Hardware -->
                    <div class="category-panel" id="cat-panel-cat-2" style="display: none;">
                        <div class="category-scroll-container">
                            
                            @php
                                $cablesData = [
                                    'Cable' => [
                                        'Powerlock 20m (set of 5)', 'Powerlock 10m (set of 5)', '3m Links (set of 5)', '.2 BAC 30m (100\')', '.2 BAC 15m (50\')', '.2 BAC 4m (12\')', '.1 BAC 3-Core 15m', '.1 BAC 3-Core 4m', '.1 BAC Singles 30m', '63A Single Phase 30m', '15m', '8m', '4m', '32A Single Phase 30m', '15m', '8m', '4m', '16A Single Phase 30m', '15m', '8m', '4m', '2m', '125A 3-Phase 30m', '15m', '63A 3-Phase 30m', '15m', '32A 3-Phase 30m', '15m'
                                    ],
                                    'Mounting Equipment & Red Racks' => [
                                        'Red Rack 1 (20 x C Stands)', 'Red Rack 2 (Grip to accompany RR1)', 'Red Rack 3 (12 x C Stands & Grip)', 'Red Rack 4 (8 x C Stands & Grip)', 'A Crate - Flag Knuckles', 'B Crate - Barrell Clamps', 'C Crate - Stand Accessories', 'D Crate - Italian Clamps', 'E Crate - Turtles', 'F Crate - Flat Brackets', 'G Crate - G Clamps', 'H Crate - Magic Arms', 'J Crate - 2-4-6 Blocks', 'K Crate - Base Plates', 'S Crate - Sandbags'
                                    ],
                                    'Stands & Grip' => [
                                        'Long John Silver (5.7m / 120kg)', 'Long John Silver JNR (3.4m)', 'Strato 5-Section (6.1m)', 'Strato 4-Section (4.6m)', 'Gladiator (3.6m)', 'Safe-Crank (3.5m)', 'Super Wind-Up (3.6m)', 'Double Wind-Up (3.8m)', 'Single Wind-Up (2.5m)', '2k/5k Double Riser (3.2m)', 'Pup Stand (3.1m)', 'Lo Boy (1.9m)', 'Mighty Baby "Eiffel"', 'Hi-Roller (5.9m)', '16mm Spigot', '2k Swan Neck', 'Barrell Clamp', 'Big Ben', 'Cardellini Clamp 2"', '3"', '6"', 'Gaffer Grip', 'Italian Clamp', 'Magic Arm', 'Polecat 3ft', '5ft', '7ft', 'Safety Bond', 'Sandbag', 'Super Clamp'
                                    ],
                                    'Lighting Accessories & Misc' => [
                                        'Soft Bag Flag & Net Kit', '6\' x 2\' Floppy', '4\' x 4\' Floppy', '4\' x 2\' Floppy', '4\' x 4\' Ultrabounce Floppy', '4\' x 4\' Double Black Net', '4\' x 4\' Single Black Net', '4\' x 4\' Full Silk', 'Half Silk', 'Quarter Silk', '8\' x 4\' Lighttools Eggcrate', '4\' x 4\' Lighttools Eggcrate', '4\' x 4\' Snap Grid', 'Daylight Senior Plus Bank', 'Large Plus Bank', 'Medium Plus Bank', 'Small Plus Bank', '4\' x 4\' Lightweight Mirror', '3\' x 3\' Mirror', '2\' x 2\' Mirror', 'CRLS 100 System', 'CRLS Drive Kit', '4-Wheel Truck', '2-Wheel Sack Truck', 'Cable Ramp (1m)', 'Y-Frame Ladder', '10-Rung Steps', '8-Rung Steps', '6-Rung Steps', 'Aluminium Frames 8\'x4\'', '5\'x5\'', '4\'x4\'', '3\'x3\'', '2\'x2\''
                                    ]
                                ];
                            @endphp

                            @foreach($cablesData as $subHeader => $items)
                                <div class="red-sub-header">
                                    <i class="bi bi-tools"></i> {{ $subHeader }}
                                </div>
                                <div class="row row-cols-1 row-cols-md-3 g-2 mb-3">
                                    @foreach($items as $itemTitle)
                                        @php $itemKey = md5('Cables' . $subHeader . $itemTitle); @endphp
                                        <div class="col">
                                            <div class="product-item-card" id="card-{{ $itemKey }}">
                                                <div class="product-title-text me-auto">{{ $itemTitle }}</div>
                                                <div class="qty-input-group">
                                                    <button type="button" class="qty-btn btn-minus" onclick="adjustStaticQty('{{ $itemKey }}', 'cat-2', -1)">-</button>
                                                    <input type="number" 
                                                           name="quantities[Cables & Hardware][{{ $itemTitle }}]" 
                                                           id="input-{{ $itemKey }}" 
                                                           class="qty-number-input static-qty-field" 
                                                           data-cat-slug="cat-2"
                                                           data-category-name="Cables & Hardware"
                                                           data-product-title="{{ $itemTitle }}"
                                                           data-key="{{ $itemKey }}"
                                                           min="0" value="0" 
                                                           oninput="onStaticQtyChange('{{ $itemKey }}', 'cat-2')">
                                                    <button type="button" class="qty-btn btn-plus" onclick="adjustStaticQty('{{ $itemKey }}', 'cat-2', 1)">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <!-- TAB 4: Cons, Vehicles & Power -->
                    <div class="category-panel" id="cat-panel-cat-3" style="display: none;">
                        <div class="category-scroll-container">
                            
                            @php
                                $consData = [
                                    'Butterfly Frames & Textiles' => [
                                        'Frame - Square Section', 'Frame - Round Section', 'Blackout', 'Ultrabounce', 'Windbag', 'Bedsheet', 'Silk - Full', 'Silk - Half', 'Silk - Quarter', 'Silk - Eighth', 'Black Silk', 'Grid Cloth - Full', 'Grid Cloth - Half', 'Grid Cloth - Quarter', 'Silent Grid - Full', 'Silent Grid - Half', 'Black Net - Single', 'Black Net - Double', 'White Net - Single', 'Griffolyn - B/W', 'Griffolyn - Silver', 'Lame - Silver', 'Lame - Gold', 'Roscosoft', 'Rosco Hi-Lite', 'Muslin - Bleached', 'Muslin - Unbleached', 'Blue Screen', 'Green Screen'
                                    ],
                                    'Generators & Vehicles' => [
                                        '200kw Carrier Generator', '160kw Carrier', '110kw Carrier', '60kw Carrier', '80kw 4x4 Mitsubishi', '40kw 4x4 Land Rover', 'H40 Hybrid 40kw', '6k Honda (petrol)', '3k Honda (petrol)', '2k Honda (petrol)', '18t Truck (9.7m long)', '12t Truck (7.8m long)', '3.5t Van (5.7m long)'
                                    ],
                                    'Portable Power & Transport Plan' => [
                                        '12v Lithium Battery Kit (x3)', '14.8v V-Lok Battery Kit (x2)', '30v 23Ah Block Battery', 'Bebob 1200 Cube', 'Instagrid One Power Pack 2.1kWh', 'Panalux i2 Power Pack 5kWh', 'Panalux i4 Power Pack 10kWh', 'Anton Bauer Eden 2.5kWh', 'Customer Collect', 'Customer Return', 'Panalux Delivery', 'Panalux Collection', 'Vehicle Delivery', 'Vehicle Collection'
                                    ],
                                    'Consumables' => [
                                        'Full Consumables Rack', 'Practical Tape Kit', 'Practical Electrical Kit', 'Correction Filter Kit', 'Plus & Minus Green Filter Kit', 'Back-Up Kit', 'Effects Filter Kit', 'Reflection Kit', 'Rubber Matting (2.5m roll)', 'Black Bolton (per metre)', 'Ultrabounce (per metre)', 'Blackwrap roll', '1" Poly', '2" Poly', 'Foamcore (Shinyboard)', 'Croc Clips (pack of 100)', 'Cable Ties (pack of 100) black', 'white', '13A Plug', '16A Plug', 'LED Ribbon (5m rolls) RGBW', 'RGB', 'Bi-Colour', 'Tungsten', 'Daylight'
                                    ]
                                ];
                            @endphp

                            @foreach($consData as $subHeader => $items)
                                <div class="red-sub-header">
                                    <i class="bi bi-truck"></i> {{ $subHeader }}
                                </div>
                                <div class="row row-cols-1 row-cols-md-3 g-2 mb-3">
                                    @foreach($items as $itemTitle)
                                        @php $itemKey = md5('Cons' . $subHeader . $itemTitle); @endphp
                                        <div class="col">
                                            <div class="product-item-card" id="card-{{ $itemKey }}">
                                                <div class="product-title-text me-auto">{{ $itemTitle }}</div>
                                                <div class="qty-input-group">
                                                    <button type="button" class="qty-btn btn-minus" onclick="adjustStaticQty('{{ $itemKey }}', 'cat-3', -1)">-</button>
                                                    <input type="number" 
                                                           name="quantities[Cons, Vehicles & Power][{{ $itemTitle }}]" 
                                                           id="input-{{ $itemKey }}" 
                                                           class="qty-number-input static-qty-field" 
                                                           data-cat-slug="cat-3"
                                                           data-category-name="Cons, Vehicles & Power"
                                                           data-product-title="{{ $itemTitle }}"
                                                           data-key="{{ $itemKey }}"
                                                           min="0" value="0" 
                                                           oninput="onStaticQtyChange('{{ $itemKey }}', 'cat-3')">
                                                    <button type="button" class="qty-btn btn-plus" onclick="adjustStaticQty('{{ $itemKey }}', 'cat-3', 1)">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                        </div>
                    </div>

                    <!-- TAB 5: Red Rack Recipes -->
                    <div class="category-panel" id="cat-panel-cat-4" style="display: none;">
                        <div class="category-scroll-container">
                            
                            @php
                                $redRackData = [
                                    'Red Rack Recipes' => [
                                        '2 4 6 Block', 'Barrel clamp', 'Base plate (large)', 'Big ben', 'Flag arm (Long 40")', 'Flag kit (Half)', 'Flag & net kit (Full)', 'Flag stand (40")', 'Flat bracket / Set clamp', 'Frame holder', 'G Clamp 10"', 'G Clamp 8"', 'G Clamp 6"', 'Honka bonka set (3)', 'Italian clamp', 'Flag knuckle', 'Magic arm', 'Net kit (Half)', 'Offset arm (Baby)', 'Poly holder 1"', 'Poly spike / fork', 'Reducer (28mm - 15mm)', 'Rigging rope', 'Safety bond', 'Sandbag', 'Sleeve (28mm - 16mm)', 'Spigot (16mm)', 'Super clamp', 'Swan neck', 'Swan neck (junior)', 'Turtle', 'Turtle (baby)'
                                    ],
                                    'Autopicks' => [
                                        '13 - 16 jumper', '16 - 13 jumper', '16 Y cord', '32 - 16 Y cord', '4 x 2 floppy flag', '4 x 4 floppy flag', '4 x 4 frame', '8 x 4 frame', 'Finger & Dot Kit', 'Flag arm 20"', 'Flag stand 20"', 'Hi Lift 2 section', 'Lo Boy stand', 'Steps 6 rung', 'Steps 8 rung'
                                    ]
                                ];
                            @endphp

                            @foreach($redRackData as $subHeader => $items)
                                <div class="red-sub-header">
                                    <i class="bi bi-receipt"></i> {{ $subHeader }}
                                </div>
                                <div class="row row-cols-1 row-cols-md-3 g-2 mb-3">
                                    @foreach($items as $itemTitle)
                                        @php $itemKey = md5('RedRack' . $subHeader . $itemTitle); @endphp
                                        <div class="col">
                                            <div class="product-item-card" id="card-{{ $itemKey }}">
                                                <div class="product-title-text me-auto">{{ $itemTitle }}</div>
                                                <div class="qty-input-group">
                                                    <button type="button" class="qty-btn btn-minus" onclick="adjustStaticQty('{{ $itemKey }}', 'cat-4', -1)">-</button>
                                                    <input type="number" 
                                                           name="quantities[Red Rack Recipes][{{ $itemTitle }}]" 
                                                           id="input-{{ $itemKey }}" 
                                                           class="qty-number-input static-qty-field" 
                                                           data-cat-slug="cat-4"
                                                           data-category-name="Red Rack Recipes"
                                                           data-product-title="{{ $itemTitle }}"
                                                           data-key="{{ $itemKey }}"
                                                           min="0" value="0" 
                                                           oninput="onStaticQtyChange('{{ $itemKey }}', 'cat-4')">
                                                    <button type="button" class="qty-btn btn-plus" onclick="adjustStaticQty('{{ $itemKey }}', 'cat-4', 1)">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                        </div>
                    </div>

                </div>

            </div>
        </div>

        <!-- SECTION 2: BOTTOM BAR WITH PROCEED BUTTON -->
        <div class="submit-card">
            <div class="row align-items-center g-3">
                <div class="col-md-7 text-center text-md-start">
                    <div class="submit-summary-box">
                        <i class="bi bi-cart-check me-2 text-warning fs-4"></i>
                        <span>Total Products Selected: <strong id="totalSelectedQty">0</strong> Items</span>
                    </div>
                    <small class="text-muted d-block mt-1" style="color: #aaa !important;">
                        Select equipment quantities above, then click proceed to fill production information.
                    </small>
                </div>
                <div class="col-md-5 text-center text-md-end">
                    <button type="button" class="btn btn-brand btn-lg text-dark fw-bold px-5 py-3 rounded-3 w-100 w-md-auto" onclick="openProductionModal()">
                        PROCEED TO PRODUCTION DETAILS <i class="bi bi-arrow-right-circle-fill ms-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- SECTION 3: MODAL FOR PRODUCTION INFORMATION FORM -->
        <div class="modal fade" id="productionInfoModal" tabindex="-1" aria-labelledby="productionInfoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
                    <div class="modal-header bg-dark text-white px-4 py-3">
                        <h5 class="modal-title fw-bold text-white mb-0" id="productionInfoModalLabel">
                            <i class="bi bi-film text-warning me-2"></i> PRODUCTION INFORMATION FORM
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">

                        <div class="alert alert-warning py-2 px-3 mb-4 rounded-3 text-dark fw-bold display-flex align-items-center">
                            <i class="bi bi-cart-check-fill me-2 fs-5"></i> Selected Products Count: <span id="modalSelectedQtyBadge" class="badge bg-dark text-warning fs-6 ms-1">0</span> Items
                        </div>

                        <!-- Production Information -->
                        <div class="eq-section-subtitle">
                            <i class="bi bi-person-lines-fill me-1"></i> Production Details
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="eq-form-label">Gaffer</label>
                                <input type="text" name="gaffer" class="form-control eq-form-control" placeholder="e.g. Stephen Mathie" value="{{ old('gaffer') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="eq-form-label">Email</label>
                                <input type="email" name="email" class="form-control eq-form-control" placeholder="e.g. stephenmathie@me.com" value="{{ old('email') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="eq-form-label">Contact Phone</label>
                                <input type="text" name="contact" class="form-control eq-form-control" placeholder="e.g. 07973 427124" value="{{ old('contact') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="eq-form-label">Production Co.</label>
                                <input type="text" name="production_company" class="form-control eq-form-control" placeholder="e.g. 72" value="{{ old('production_company') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="eq-form-label">Production Title <span class="text-danger">*</span></label>
                                <input type="text" name="production_title" id="production_title_field" class="form-control eq-form-control" placeholder="e.g. Handcuffed" value="{{ old('production_title') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="eq-form-label">Production Contact</label>
                                <input type="text" name="production_contact" class="form-control eq-form-control" placeholder="Contact person name" value="{{ old('production_contact') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="eq-form-label">DoP (Director of Photography)</label>
                                <input type="text" name="dop" class="form-control eq-form-control" placeholder="e.g. Justin Frahms" value="{{ old('dop') }}">
                            </div>
                        </div>

                        <!-- Production Dates -->
                        <div class="eq-section-subtitle">
                            <i class="bi bi-calendar-range me-1"></i> Production Dates
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- Rig -->
                            <div class="col-md-3 col-6">
                                <label class="eq-form-label">Rig - From</label>
                                <input type="date" name="rig_from" class="form-control eq-form-control" value="{{ old('rig_from') }}">
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="eq-form-label">Rig - To</label>
                                <input type="date" name="rig_to" class="form-control eq-form-control" value="{{ old('rig_to') }}">
                            </div>

                            <!-- Prelight -->
                            <div class="col-md-3 col-6">
                                <label class="eq-form-label">Prelight - From</label>
                                <input type="date" name="prelight_from" class="form-control eq-form-control" value="{{ old('prelight_from') }}">
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="eq-form-label">Prelight - To</label>
                                <input type="date" name="prelight_to" class="form-control eq-form-control" value="{{ old('prelight_to') }}">
                            </div>

                            <!-- Shoot -->
                            <div class="col-md-3 col-6">
                                <label class="eq-form-label">Shoot - From</label>
                                <input type="date" name="shoot_from" class="form-control eq-form-control" value="{{ old('shoot_from') }}">
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="eq-form-label">Shoot - To</label>
                                <input type="date" name="shoot_to" class="form-control eq-form-control" value="{{ old('shoot_to') }}">
                            </div>

                            <!-- Derig -->
                            <div class="col-md-3 col-6">
                                <label class="eq-form-label">Derig - From</label>
                                <input type="date" name="derig_from" class="form-control eq-form-control" value="{{ old('derig_from') }}">
                            </div>
                            <div class="col-md-3 col-6">
                                <label class="eq-form-label">Derig - To</label>
                                <input type="date" name="derig_to" class="form-control eq-form-control" value="{{ old('derig_to') }}">
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="eq-section-subtitle">
                            <i class="bi bi-geo-alt-fill me-1"></i> Location Address
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="eq-form-label">Address Line 1</label>
                                <input type="text" name="address_line_1" class="form-control eq-form-control" placeholder="The Film Shed" value="{{ old('address_line_1') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="eq-form-label">Address Line 2</label>
                                <input type="text" name="address_line_2" class="form-control eq-form-control" placeholder="Millers Avenue" value="{{ old('address_line_2') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="eq-form-label">Address Line 3 / Postcode</label>
                                <input type="text" name="address_line_3_postcode" class="form-control eq-form-control" placeholder="Dalston E9" value="{{ old('address_line_3_postcode') }}">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer bg-light px-4 py-3">
                        <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Back to Products</button>
                        <button type="submit" class="btn btn-brand text-dark fw-bold px-5 py-2" id="submitBtn">
                            <i class="bi bi-send-fill me-2"></i> SUBMIT REQUEST
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

<script>
    // Tab switching logic for static tabs
    function switchStaticTab(catSlug) {
        const panels = document.querySelectorAll('.category-panel');
        panels.forEach(panel => panel.style.display = 'none');

        const targetPanel = document.getElementById('cat-panel-' + catSlug);
        if (targetPanel) {
            targetPanel.style.display = 'block';
        }

        const tabBtns = document.querySelectorAll('.cat-tab-btn');
        tabBtns.forEach(btn => {
            if (btn.getAttribute('data-cat-slug') === catSlug) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    }

    // Adjust static item quantity
    function adjustStaticQty(key, catSlug, delta) {
        const input = document.getElementById('input-' + key);
        if (!input) return;

        let val = parseInt(input.value) || 0;
        val += delta;
        if (val < 0) val = 0;
        input.value = val;

        onStaticQtyChange(key, catSlug);
    }

    // Handle static quantity input change
    function onStaticQtyChange(key, catSlug) {
        const input = document.getElementById('input-' + key);
        if (input) {
            if (parseInt(input.value) < 0 || isNaN(parseInt(input.value))) {
                input.value = 0;
            }
        }
        updateStaticTotals();
    }

    // Update overall totals & category badges & item highlighting
    function updateStaticTotals() {
        const qtyInputs = document.querySelectorAll('.static-qty-field');
        let totalCount = 0;
        const categoryCounts = {};

        qtyInputs.forEach(input => {
            const val = parseInt(input.value) || 0;
            const key = input.getAttribute('data-key');
            const card = document.getElementById('card-' + key);

            if (val > 0) {
                totalCount += val;
                const catSlug = input.getAttribute('data-cat-slug');
                categoryCounts[catSlug] = (categoryCounts[catSlug] || 0) + val;
                if (card) card.classList.add('has-qty');
            } else {
                if (card) card.classList.remove('has-qty');
            }
        });

        // Update overall total badge
        const totalElem = document.getElementById('totalSelectedQty');
        if (totalElem) {
            totalElem.innerText = totalCount;
        }

        // Update category tab badges
        const tabBtns = document.querySelectorAll('.cat-tab-btn');
        tabBtns.forEach(btn => {
            const catSlug = btn.getAttribute('data-cat-slug');
            const badge = document.getElementById('badge-' + catSlug);
            if (badge) {
                const count = categoryCounts[catSlug] || 0;
                if (count > 0) {
                    badge.innerText = count;
                    badge.style.display = 'inline-block';
                } else {
                    badge.style.display = 'none';
                }
            }
        });
    }

    // Open Production Information Modal (Checks if totalQty > 0)
    function openProductionModal() {
        let totalQty = 0;
        const qtyInputs = document.querySelectorAll('.static-qty-field');
        qtyInputs.forEach(input => {
            totalQty += (parseInt(input.value) || 0);
        });

        if (totalQty <= 0) {
            alert('Please select at least one product with a quantity greater than 0 before proceeding to production details.');
            return false;
        }

        const modalBadge = document.getElementById('modalSelectedQtyBadge');
        if (modalBadge) {
            modalBadge.innerText = totalQty;
        }

        const modalElem = document.getElementById('productionInfoModal');
        if (modalElem) {
            const modal = bootstrap.Modal.getOrCreateInstance(modalElem);
            modal.show();
        }
    }

    // Form Submit Validation & WhatsApp Trigger
    document.addEventListener('DOMContentLoaded', function () {
        updateStaticTotals();

        const form = document.getElementById('equipmentRequestForm');
        if (form) {
            form.addEventListener('submit', function (e) {
                let totalQty = 0;
                const qtyInputs = document.querySelectorAll('.static-qty-field');
                qtyInputs.forEach(input => {
                    totalQty += (parseInt(input.value) || 0);
                });

                if (totalQty <= 0) {
                    e.preventDefault();
                    alert('Please select at least one product with a valid quantity before submitting.');
                    return false;
                }

                const prodTitle = form.querySelector('[name="production_title"]')?.value || '';
                if (!prodTitle.trim()) {
                    e.preventDefault();
                    alert('Please fill out the Production Title in the form.');
                    const prodTitleInput = form.querySelector('[name="production_title"]');
                    if (prodTitleInput) prodTitleInput.focus();
                    return false;
                }

                // Construct WhatsApp Message
                const gaffer = (form.querySelector('[name="gaffer"]')?.value || '').trim();
                const email = (form.querySelector('[name="email"]')?.value || '').trim();
                const contact = (form.querySelector('[name="contact"]')?.value || '').trim();
                const prodCompany = (form.querySelector('[name="production_company"]')?.value || '').trim();
                const prodContact = (form.querySelector('[name="production_contact"]')?.value || '').trim();
                const dop = (form.querySelector('[name="dop"]')?.value || '').trim();

                let msg = "*NEW EQUIPMENT ORDER (v3.1)*\n\n";
                msg += "*Production Information:*\n";
                if (gaffer) msg += `• Gaffer: ${gaffer}\n`;
                if (email) msg += `• Email: ${email}\n`;
                if (contact) msg += `• Phone: ${contact}\n`;
                if (prodCompany) msg += `• Production Co.: ${prodCompany}\n`;
                if (prodTitle) msg += `• Production Title: ${prodTitle}\n`;
                if (prodContact) msg += `• Production Contact: ${prodContact}\n`;
                if (dop) msg += `• DoP: ${dop}\n`;

                const rigFrom = form.querySelector('[name="rig_from"]')?.value;
                const rigTo = form.querySelector('[name="rig_to"]')?.value;
                const prelightFrom = form.querySelector('[name="prelight_from"]')?.value;
                const prelightTo = form.querySelector('[name="prelight_to"]')?.value;
                const shootFrom = form.querySelector('[name="shoot_from"]')?.value;
                const shootTo = form.querySelector('[name="shoot_to"]')?.value;
                const derigFrom = form.querySelector('[name="derig_from"]')?.value;
                const derigTo = form.querySelector('[name="derig_to"]')?.value;

                let hasDates = false;
                let dateStr = "\n*Production Dates:*\n";
                if (rigFrom || rigTo) { dateStr += `• Rig: ${rigFrom} to ${rigTo}\n`; hasDates = true; }
                if (prelightFrom || prelightTo) { dateStr += `• Prelight: ${prelightFrom} to ${prelightTo}\n`; hasDates = true; }
                if (shootFrom || shootTo) { dateStr += `• Shoot: ${shootFrom} to ${shootTo}\n`; hasDates = true; }
                if (derigFrom || derigTo) { dateStr += `• Derig: ${derigFrom} to ${derigTo}\n`; hasDates = true; }
                if (hasDates) msg += dateStr;

                const addr1 = (form.querySelector('[name="address_line_1"]')?.value || '').trim();
                const addr2 = (form.querySelector('[name="address_line_2"]')?.value || '').trim();
                const addr3 = (form.querySelector('[name="address_line_3_postcode"]')?.value || '').trim();
                const fullAddr = [addr1, addr2, addr3].filter(Boolean).join(', ');
                if (fullAddr) {
                    msg += `\n*Location Address:*\n${fullAddr}\n`;
                }

                msg += `\n*Requested Equipment:*\n`;
                const selectedByCategory = {};

                qtyInputs.forEach(input => {
                    const qty = parseInt(input.value) || 0;
                    if (qty > 0) {
                        const catName = input.getAttribute('data-category-name') || 'Equipment';
                        const title = input.getAttribute('data-product-title') || 'Product';
                        if (!selectedByCategory[catName]) {
                            selectedByCategory[catName] = [];
                        }
                        selectedByCategory[catName].push({ title: title, qty: qty });
                    }
                });

                for (const catName in selectedByCategory) {
                    msg += `\n*${catName}*\n`;
                    selectedByCategory[catName].forEach(item => {
                        msg += `  - ${item.title} (Qty: ${item.qty})\n`;
                    });
                }

                // Open WhatsApp in new window
                window.open(
                    `https://wa.me/447879175585?text=${encodeURIComponent(msg)}`,
                    '_blank'
                );

                // Show spinner loading state
                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Submitting...';
                }
            });
        }
    });
</script>
@endsection
