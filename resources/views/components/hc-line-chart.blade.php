@props([
    'series' => [],
    'colorStroke' => '#065f46',
    'colorFill' => '#10b981',
    'accent' => '#34d399',
    'noun' => 'item',
    'height' => 220,
    'showAxis' => true,
    'showDots' => true,
])

@php
    $vals = collect($series)->map(fn ($d) => (float) ($d['count'] ?? 0))->all();
    $dates = collect($series)->map(fn ($d) => (string) ($d['date'] ?? ''))->all();
    $n = count($vals);
    $max = $n > 0 ? max($vals) : 0;

    $W  = 1000;
    $H  = 280;
    $padL = 36;
    $padR = 12;
    $padT = 12;
    $padB = 28;
    $innerW = $W - $padL - $padR;
    $innerH = $H - $padT - $padB;

    $yMax = max(1, (int) ceil($max));
    if ($yMax < 4) $yMax = 4;
    elseif ($yMax % 4 !== 0) $yMax = (int) (ceil($yMax / 4) * 4);

    $points = [];
    foreach ($vals as $i => $v) {
        $x = $n > 1 ? $padL + ($i / ($n - 1)) * $innerW : $padL + $innerW / 2;
        $y = $padT + ($innerH * (1 - ($v / $yMax)));
        $points[] = [
            'x' => round($x, 2),
            'y' => round($y, 2),
            'v' => (int) $v,
            'date' => $dates[$i] ?? '',
        ];
    }

    // Build a smooth Catmull-Rom-ish path using bezier curves through points.
    $line = '';
    $area = '';
    if ($n > 0) {
        $line = 'M ' . $points[0]['x'] . ' ' . $points[0]['y'];
        for ($i = 1; $i < $n; $i++) {
            $p0 = $points[max(0, $i - 2)];
            $p1 = $points[$i - 1];
            $p2 = $points[$i];
            $p3 = $points[min($n - 1, $i + 1)];
            $tension = 0.18;
            $cp1x = $p1['x'] + ($p2['x'] - $p0['x']) * $tension;
            $cp1y = $p1['y'] + ($p2['y'] - $p0['y']) * $tension;
            $cp2x = $p2['x'] - ($p3['x'] - $p1['x']) * $tension;
            $cp2y = $p2['y'] - ($p3['y'] - $p1['y']) * $tension;
            $line .= sprintf(' C %s %s %s %s %s %s', round($cp1x, 2), round($cp1y, 2), round($cp2x, 2), round($cp2y, 2), $p2['x'], $p2['y']);
        }
        $area = $line . ' L ' . end($points)['x'] . ' ' . ($padT + $innerH) . ' L ' . $points[0]['x'] . ' ' . ($padT + $innerH) . ' Z';
    }

    // Build 4 horizontal grid lines + y-axis labels.
    $gridLines = [];
    for ($i = 0; $i <= 4; $i++) {
        $y = $padT + ($innerH * (1 - $i / 4));
        $gridLines[] = ['y' => round($y, 2), 'value' => (int) round(($yMax * $i) / 4)];
    }

    // Choose ~6 x-axis tick indices spaced evenly through the series.
    $tickCount = $n > 6 ? 6 : max(1, $n - 1);
    $tickIndices = [];
    if ($n > 0) {
        for ($i = 0; $i <= $tickCount; $i++) {
            $idx = (int) round(($i / $tickCount) * ($n - 1));
            if (! in_array($idx, $tickIndices, true)) $tickIndices[] = $idx;
        }
    }

    $uid = 'hc-line-' . substr(md5($colorStroke . $colorFill . $noun . $n), 0, 8);
@endphp

<div class="hc-line-wrap" style="height:{{ $height }}px;">
    <svg class="hc-line-svg" viewBox="0 0 {{ $W }} {{ $H }}" preserveAspectRatio="none" role="img"
         aria-label="Trend chart for {{ Str::plural($noun) }}">
        <defs>
            <linearGradient id="{{ $uid }}-fill" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="{{ $colorFill }}" stop-opacity="0.55"/>
                <stop offset="100%" stop-color="{{ $colorFill }}" stop-opacity="0.02"/>
            </linearGradient>
        </defs>

        {{-- Horizontal grid lines + Y axis labels --}}
        <g class="hc-line-grid">
            @foreach($gridLines as $g)
                <line x1="{{ $padL }}" y1="{{ $g['y'] }}" x2="{{ $W - $padR }}" y2="{{ $g['y'] }}"/>
            @endforeach
        </g>
        @if($showAxis)
            <g class="hc-line-axis">
                @foreach($gridLines as $g)
                    <text x="{{ $padL - 6 }}" y="{{ $g['y'] + 3 }}" text-anchor="end">{{ $g['value'] }}</text>
                @endforeach
                @foreach($tickIndices as $i)
                    @php $p = $points[$i] ?? null; @endphp
                    @if($p)
                        <text x="{{ $p['x'] }}" y="{{ $H - 8 }}" text-anchor="middle">{{ \Carbon\Carbon::parse($p['date'])->format('M j') }}</text>
                    @endif
                @endforeach
            </g>
        @endif

        {{-- Area fill + line --}}
        @if($area)
            <path class="hc-line-area" d="{{ $area }}" fill="url(#{{ $uid }}-fill)"/>
            <path class="hc-line-stroke" d="{{ $line }}" stroke="{{ $colorStroke }}"/>
        @endif

        {{-- Hover cursor line --}}
        <line class="hc-line-cursor" x1="0" y1="{{ $padT }}" x2="0" y2="{{ $padT + $innerH }}" data-cursor></line>

        {{-- Data points --}}
        @if($showDots && $n > 0)
            @foreach($points as $i => $p)
                <circle class="hc-line-dot"
                        cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="{{ $n > 20 ? 0 : 3.5 }}"
                        stroke="{{ $colorStroke }}"
                        data-x="{{ $p['x'] }}" data-y="{{ $p['y'] }}"
                        data-tip="{{ \Carbon\Carbon::parse($p['date'])->format('M j') }} · {{ $p['v'] }} {{ Str::plural($noun, $p['v']) }}"/>
            @endforeach
        @endif

        {{-- Invisible hit-test rect that drives hover via JS --}}
        <rect class="hc-line-hover" data-hover
              x="{{ $padL }}" y="{{ $padT }}"
              width="{{ $innerW }}" height="{{ $innerH }}"
              data-points='@json($points)'
              data-stroke="{{ $colorStroke }}"
              data-accent="{{ $accent }}"
              data-noun="{{ $noun }}"/>
    </svg>
    <div class="hc-line-tip" data-tip-el></div>
</div>
