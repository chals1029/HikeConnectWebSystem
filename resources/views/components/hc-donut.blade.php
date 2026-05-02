@props([
    'slices' => [],          // array of ['label' => 'Pending', 'value' => 4, 'color' => '#f59e0b']
    'centerValue' => null,   // big text in middle
    'centerLabel' => 'TOTAL',
    'size' => 170,
])

@php
    $total = max(1, collect($slices)->sum('value'));
    $center = $centerValue ?? collect($slices)->sum('value');

    $r = 60;
    $cx = 80;
    $cy = 80;
    $circumference = 2 * pi() * $r;

    $offset = 0;
    $arcs = [];
    foreach ($slices as $s) {
        $value = (int) $s['value'];
        $share = $total > 0 ? $value / $total : 0;
        $length = $share * $circumference;
        $arcs[] = [
            'label' => (string) $s['label'],
            'value' => $value,
            'color' => $s['color'] ?? '#10b981',
            'pct'   => round($share * 100),
            'dasharray'  => round($length, 3) . ' ' . round($circumference - $length, 3),
            'dashoffset' => round(-$offset, 3),
        ];
        $offset += $length;
    }
@endphp

<div class="hc-donut-wrap">
    <div class="hc-donut-svg-wrap" style="width:{{ $size }}px;height:{{ $size }}px;">
        <svg class="hc-donut-svg" viewBox="0 0 160 160" style="width:{{ $size }}px;height:{{ $size }}px;">
            <circle class="hc-donut-track" cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}"/>
            @foreach($arcs as $arc)
                <circle class="hc-donut-arc"
                        cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}"
                        stroke="{{ $arc['color'] }}"
                        stroke-dasharray="{{ $arc['dasharray'] }}"
                        stroke-dashoffset="{{ $arc['dashoffset'] }}">
                    <title>{{ $arc['label'] }}: {{ $arc['value'] }} ({{ $arc['pct'] }}%)</title>
                </circle>
            @endforeach
        </svg>
        <div class="hc-donut-center">
            <div>
                <div class="v">{{ is_numeric($center) ? number_format($center) : $center }}</div>
                <div class="l">{{ $centerLabel }}</div>
            </div>
        </div>
    </div>

    <div class="hc-legend">
        @foreach($arcs as $arc)
            <div class="hc-legend-row">
                <span class="swatch" style="background:{{ $arc['color'] }};"></span>
                <span class="label">{{ $arc['label'] }}</span>
                <span class="count">{{ number_format($arc['value']) }}</span>
                <span class="pct">{{ $arc['pct'] }}%</span>
            </div>
        @endforeach
    </div>
</div>
