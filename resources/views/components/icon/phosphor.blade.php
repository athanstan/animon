{{--
    Phosphor Icon component for easy icon usage.
    Usage: <x-icon.phosphor name="heart" weight="bold" class="text-kawaii-coral" />
--}}
@props([
    'name',
    'weight' => 'regular', // regular, thin, light, bold, fill, duotone
    'class' => '',
])

@php
    $weightClass = $weight === 'regular' ? 'ph' : "ph-{$weight}";
    $classes = "{$weightClass} ph-{$name} {$class}";
@endphp

<i {{ $attributes->merge(['class' => $classes]) }} aria-hidden="true"></i>
