@props([
    'src' => null,
    'alt' => 'Image',
    'class' => '',
    'width' => null,
    'height' => null,
    'placeholder' => null
])

@php
    $imageSrc = getImageWithFallback($src, $placeholder);
    $noImageUrl = asset('front_assets/images/no-image.svg');
@endphp

<img 
    src="{{ $imageSrc }}" 
    alt="{{ $alt }}"
    class="{{ $class }}"
    @if($width) width="{{ $width }}" @endif
    @if($height) height="{{ $height }}" @endif
    onerror="this.src='{{ $noImageUrl }}'; this.classList.add('error');"
    onload="this.classList.remove('error');"
    {{ $attributes }}
> 