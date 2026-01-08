{{--
    Animon mascot SVG icon - cute anime-style character.
--}}
@props(['class' => 'w-7 h-7'])

<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
    aria-hidden="true">
    <circle cx="12" cy="10" r="8" class="fill-border-brutal" />
    <circle cx="9" cy="9" r="2" fill="white" />
    <circle cx="15" cy="9" r="2" fill="white" />
    <circle cx="9.5" cy="9.5" r="0.8" class="fill-border-brutal" />
    <circle cx="15.5" cy="9.5" r="0.8" class="fill-border-brutal" />
    <path d="M10 13 Q12 15 14 13" class="stroke-kawaii-coral" stroke-width="1.5" stroke-linecap="round"
        fill="none" />
    <path d="M5 5 L7 2" class="stroke-border-brutal" stroke-width="2" stroke-linecap="round" />
    <path d="M19 5 L17 2" class="stroke-border-brutal" stroke-width="2" stroke-linecap="round" />
</svg>
