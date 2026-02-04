@extends('layouts.app')

@section('title', $story->title . ' - AliarLIE Clothing')

@section('wrapper-class', 'page-stories-wrapper')

@section('content')
@push('styles')
  <style>
    .story-detail-hero {
      width: 100vw;
      max-width: 100vw;
      margin-left: calc(50% - 50vw);
      margin-right: calc(50% - 50vw);
      height: 58vh;
      min-height: 360px;
      position: relative;
      overflow: hidden;
      background-size: cover;
      background-position: center;
      background-color: #1a1a1a;
    }
    .story-detail-hero::after {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(to bottom, rgba(0,0,0,0.15) 0%, rgba(0,0,0,0.55) 70%, rgba(0,0,0,0.75) 100%);
      pointer-events: none;
    }
    @media (max-width: 767px) {
      .story-detail-hero {
        height: 42vh;
        min-height: 280px;
      }
    }
    .story-detail-content {
      max-width: 80vw;
      margin: 0 auto;
      padding: 4rem 1.5rem 5rem;
    }
    .story-detail-content .story-label {
      color: #e53935;
      font-size: 0.75rem;
      font-weight: 600;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      margin-bottom: 0.75rem;
      display: block;
    }
    .story-detail-content h1 {
      font-size: 2.25rem;
      font-weight: 600;
      letter-spacing: -0.02em;
      line-height: 1.25;
      margin-bottom: 1.75rem;
      padding-bottom: 1rem;
      border-bottom: 3px solid #e53935;
      display: inline-block;
      color: #1a1a1a;
    }
    .story-detail-content .story-body {
      font-size: 1.0625rem;
      line-height: 1.8;
      color: #444;
      margin-bottom: 2.5rem;
      white-space: pre-line;
    }
    .story-detail-content .story-body p {
      margin-bottom: 1.25rem;
    }
    .story-detail-content .story-body p:last-child {
      margin-bottom: 0;
    }
    .story-detail-content .btn-back {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.75rem 1.5rem;
      font-size: 0.9rem;
      font-weight: 600;
      letter-spacing: 0.03em;
      color: #fff;
      background: #e53935;
      border: 2px solid #e53935;
      border-radius: 6px;
      text-decoration: none;
      transition: color 0.25s ease, background 0.25s ease, border-color 0.25s ease, transform 0.2s ease;
    }
    .story-detail-content .btn-back:hover {
      color: #fff;
      background: #c62828;
      border-color: #c62828;
      transform: translateX(-2px);
    }
    @media (max-width: 575px) {
      .story-detail-content {
        padding: 3rem 1.25rem 4rem;
      }
      .story-detail-content h1 {
        font-size: 1.75rem;
      }
      .story-detail-content .story-body {
        font-size: 1rem;
      }
    }
  </style>
@endpush

<!-- Hero: full-width story image -->
<section
  class="story-detail-hero"
  style="background-image: url('{{ $story->image ? \Illuminate\Support\Facades\Storage::disk('public')->url($story->image) : asset('assets/img/slider/cs1-s1.jpg') }}');"
  role="img"
  aria-label="{{ $story->title }}"
></section>

<!-- Content -->
<section class="story-detail-content">
  <span class="story-label">Story</span>
  <h1>{{ $story->title }}</h1>
  @if ($story->description)
    <div class="story-body">
      {!! nl2br(e($story->description)) !!}
    </div>
  @endif
  <a href="{{ route('shop.stories') }}" class="btn-back">
    <span aria-hidden="true">←</span>
    Back to Stories
  </a>
</section>
@endsection
