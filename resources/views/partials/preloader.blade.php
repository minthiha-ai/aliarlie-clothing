{{--
  Preloader partial – customize by editing this file or yielding 'preloader' in your view.

  Options (set in layout or pass when including):
  - $preloaderLogo: image URL → show logo (e.g. asset('assets/img/logo-main.png'))
  - $preloaderVariant: 'spinner' | 'logo' → 'spinner' = default, 'logo' = logo + red "Loading" + three bounce dots
  - $preloaderClass: extra CSS class(es) for .preloader-wrap
  - $preloaderBgColor: background color for the overlay (e.g. '#fff' or '#f8f3f0')
--}}
@php
  $variant = $preloaderVariant ?? 'spinner';
  $useLogo = $variant === 'logo' && !empty($preloaderLogo);
@endphp
<div class="preloader-wrap {{ $preloaderClass ?? '' }} @if($useLogo) preloader-wrap--logo @endif"
     @if(!empty($preloaderBgColor)) style="background-color: {{ $preloaderBgColor }};" @endif>
  <div class="preloader">
    @if($useLogo)
      <div class="preloader-custom text-center">
        <img src="{{ $preloaderLogo }}" alt="" class="preloader-logo" style="max-width: 120px; height: auto; display: block; margin: 0 auto 1rem;" />
        <div class="preloader-loading preloader-wave mt-3">
          @foreach(str_split('Loading') as $i => $letter)
            <span class="preloader-wave-char" style="animation-delay: {{ $i * 0.08 }}s;">{{ $letter }}</span>
          @endforeach
          @foreach([0, 1, 2] as $i)
            <span class="preloader-wave-dot" style="animation-delay: {{ (7 + $i) * 0.08 }}s;"></span>
          @endforeach
        </div>
      </div>
    @else
      {{-- Default: theme spinner (rotating square + dots) --}}
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    @endif
  </div>
</div>
@if($useLogo)
<style>
  @keyframes preloader-bounce {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1); }
  }
  @keyframes preloader-wave {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
  }
  .preloader-wrap--logo .preloader {
    width: auto; height: auto;
    margin-left: 0; margin-top: 0;
    transform: translate(-50%, -50%);
  }
  .preloader-loading.preloader-wave {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
  }
  .preloader-wave-char {
    color: #ff1a1a;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.05em;
    display: inline-block;
    animation: preloader-wave 0.6s ease-in-out infinite both;
    -webkit-text-stroke: 1px #ff1a1a;
    text-stroke: 1px #ff1a1a;
    paint-order: stroke fill;
  }
  .preloader-wave-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #ff1a1a;
    flex-shrink: 0;
    display: inline-block;
    animation: preloader-wave 0.6s ease-in-out infinite both;
  }
</style>
@endif
