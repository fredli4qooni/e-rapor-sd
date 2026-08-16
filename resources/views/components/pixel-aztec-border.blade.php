@props([
    'bandHeight' => 40,
    'colors' => [],
    'topOnly' => false,
    'bottomOnly' => false,
])

@php
    $unit = 8;
    $tileCols = 10;
    $tileRows = 6;
    $tileW = $unit * $tileCols; // 80
    $tileH = $unit * $tileRows; // 48

    $defaultColors = [
        'yellow' => '#FFCD2D',
        'red'    => '#C41E18',
        'black'  => '#171412',
    ];
    $c = array_merge($defaultColors, $colors);

    $rect = fn ($x, $y, $w, $h, $fill) =>
        "<rect x='{$x}' y='{$y}' width='{$w}' height='{$h}' fill='{$fill}'/>";

    $dots = '';
    foreach ([0, 2, 4, 6, 8] as $col) {
        $dots .= $rect($col * $unit, 0, $unit, $unit, $c['black']);
    }

    $svg = "<svg xmlns='http://www.w3.org/2000/svg' width='{$tileW}' height='{$tileH}'>"
        . $rect(0, 0, $tileW, $unit * 5, $c['yellow'])
        . $rect(0, $unit * 5, $tileW, $unit, $c['black'])
        . $dots
        . $rect(4 * $unit, $unit * 1, 2 * $unit, $unit, $c['red'])
        . $rect(3 * $unit, $unit * 2, 4 * $unit, $unit, $c['red'])
        . $rect(2 * $unit, $unit * 3, 6 * $unit, $unit, $c['red'])
        . $rect(1 * $unit, $unit * 4, 8 * $unit, $unit, $c['black'])
        . "</svg>";

    $dataUri = 'data:image/svg+xml,' . rawurlencode($svg);
    $tileWidthOnScreen = ($tileW / $tileH) * $bandHeight;

    $stripStyle = "width:100%;height:{$bandHeight}px;"
        . "background-image:url('{$dataUri}');"
        . "background-repeat:repeat-x;"
        . "background-size:{$tileWidthOnScreen}px {$bandHeight}px;"
        . "flex-shrink:0;";
@endphp

<div {{ $attributes->merge(['class' => 'pixel-aztec-border']) }}
     style="display:flex;flex-direction:column;background:#fff;">

    @if(!$bottomOnly)
        {{-- top band --}}
        <div style="{{ $stripStyle }}"></div>
    @endif

    {{-- your content --}}
    @if(trim($slot) !== '')
        <div style="flex:1;">
            {{ $slot }}
        </div>
    @endif

    @if(!$topOnly && trim($slot) !== '')
        {{-- bottom band: same tile, flipped vertically --}}
        <div style="{{ $stripStyle }}transform:scaleY(-1);"></div>
    @endif
</div>
