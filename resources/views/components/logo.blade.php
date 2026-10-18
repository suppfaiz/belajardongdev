@props(['size' => 'default', 'white' => false])

@php
    $iconBox = match($size) {
        'sm' => 'w-7 h-7 text-xs',
        'lg' => 'w-11 h-11 text-base',
        default => 'w-9 h-9 text-sm',
    };
    $textSize = match($size) {
        'sm' => 'text-base',
        'lg' => 'text-2xl',
        default => 'text-xl',
    };
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5 font-semibold select-none']) }}>
    {{-- Isometric 3-Layer Book / Learning Block Icon --}}
    <div class="{{ $iconBox }} flex items-center justify-center shrink-0">
        <svg class="w-full h-full" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Top Book (Light Sky Blue) -->
            <path d="M18 4L28.5 9.5L18 15L7.5 9.5L18 4Z" fill="#38BDF8"/>
            <path d="M7.5 9.5L18 15V17L7.5 11.5V9.5Z" fill="#0284C7"/>
            <path d="M28.5 9.5L18 15V17L28.5 11.5V9.5Z" fill="#0369A1"/>

            <!-- Middle Book (Royal Blue) -->
            <path d="M18 13.5L28.5 19L18 24.5L7.5 19L18 13.5Z" fill="#2563EB"/>
            <path d="M7.5 19L18 24.5V26.5L7.5 21V19Z" fill="#1D4ED8"/>
            <path d="M28.5 19L18 24.5V26.5L28.5 21V19Z" fill="#1E40AF"/>

            <!-- Bottom Book (Deep Navy) -->
            <path d="M18 23L28.5 28.5L18 34L7.5 28.5L18 23Z" fill="#080F63"/>
            <path d="M7.5 28.5L18 34V35.5L7.5 30V28.5Z" fill="#050A4F"/>
            <path d="M28.5 28.5L18 34V35.5L28.5 30V28.5Z" fill="#030733"/>
        </svg>
    </div>
    <span class="{{ $textSize }} font-extrabold tracking-tight {{ $white ? 'text-white' : 'text-[#111827]' }}">
        Belajar<span class="{{ $white ? 'text-blue-200' : 'text-[#2563EB]' }}">Yuk</span>
    </span>
</div>
