@extends('layouts.app')

@section('title', 'Stories - AliarLIE Clothing')

@section('wrapper-class', 'page-stories-wrapper')

@section('content')
<!--== Start Page Title Area ==-->
@php
  $bannerImage = $banner?->image
      ? \Illuminate\Support\Facades\Storage::disk('public')->url($banner->image)
      : asset('assets/img/photos/bg-page9.jpg');
@endphp
<section class="page-title-area bg-img" data-bg-img="{{ $bannerImage }}">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="page-title-content">
          <h2 class="title">{{ $banner->title ?? 'Stories' }}</h2>
          <div class="bread-crumbs">
            <a href="{{ route('home') }}">Home<span class="breadcrumb-sep">></span></a>
            <span class="active">Stories</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--== End Page Title Area ==-->

<!--== Start Stories Area (design from template, alternating image left/right) ==-->
@push('styles')
  <style>
    .stories-area.collection-slider-area {
      width: 100vw;
      max-width: 100vw;
      margin-left: calc(50% - 50vw);
      margin-right: calc(50% - 50vw);
      margin-top: 2rem;
      padding: 0 0 4rem;
    }
    .stories-area .collection-slider-content {
      display: flex;
      flex-direction: column;
      gap: 2rem;
    }
    .stories-area .story-item {
      display: flex;
      align-items: center;
      height: 800px;
      position: relative;
    }
    .stories-area .story-item.story-item--reversed {
      flex-direction: row-reverse;
    }
    @media (max-width: 1199px) {
      .stories-area .story-item {
        height: 570px;
      }
    }
    @media (max-width: 767px) {
      .stories-area.collection-slider-area {
        padding: 0 0 3rem;
      }
      .stories-area .collection-slider-content {
        gap: 3rem;
      }
      .stories-area .story-item {
        height: 470px;
        flex-direction: column !important;
      }
      .stories-area .story-item.story-item--reversed {
        flex-direction: column !important;
      }
    }
    .stories-area .story-item .story-thumb {
      height: 100%;
      overflow: hidden;
      position: absolute;
      width: 100%;
      left: 0;
      right: 0;
      top: 0;
      z-index: 0;
    }
    .stories-area .story-item.story-item--reversed .story-thumb {
      left: 0;
      right: 0;
    }
    @media (max-width: 767px) {
      .stories-area .story-item .story-thumb {
        position: relative;
        width: 100%;
        height: 50%;
      }
    }
    .stories-area .story-item .story-thumb .bg-thumb,
    .stories-area .story-item .story-thumb .bg-img {
      background-position: center;
      background-size: cover;
      height: 100%;
      width: 100%;
    }
    .stories-area .story-content-area {
      background: transparent !important;
      box-shadow: none !important;
      position: absolute;
      right: 0;
      top: 0;
      bottom: 0;
      margin: 0;
      max-width: 520px;
      width: 100%;
      padding: 3rem 2.5rem 3rem 3rem;
      z-index: 1;
      display: flex;
      align-items: center;
    }
    .stories-area .story-item.story-item--reversed .story-content-area {
      right: auto;
      left: 0;
      padding: 3rem 3rem 3rem 2.5rem;
    }
    @media (max-width: 1199px) {
      .stories-area .story-content-area {
        max-width: 48%;
        padding: 2rem 1.75rem;
      }
      .stories-area .story-item.story-item--reversed .story-content-area {
        padding: 2rem 1.75rem;
      }
    }
    @media (max-width: 575px) {
      .stories-area .story-content-area {
        max-width: 90%;
        padding: 1.5rem 1.25rem;
      }
      .stories-area .story-item.story-item--reversed .story-content-area {
        padding: 1.5rem 1.25rem;
      }
    }
    @media (max-width: 767px) {
      .stories-area .story-content-area {
        position: relative;
        max-width: 100%;
        padding: 2rem 1.25rem;
      }
      .stories-area .story-item.story-item--reversed .story-content-area {
        left: auto;
        padding: 2rem 1.25rem;
      }
    }
    .stories-area .story-content-area .content {
      background: rgba(0, 0, 0, 0.82);
      color: #fff;
      padding: 2.25rem 2rem;
      border-radius: 12px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.35);
      max-width: 100%;
    }
    .stories-area .story-content-area .content .inner-content {
      position: relative;
    }
    .stories-area .story-content-area .content .inner-content h2 {
      color: #fff;
      font-size: 1.75rem;
      font-weight: 600;
      letter-spacing: -0.02em;
      line-height: 1.25;
      margin-bottom: 0;
      padding-bottom: 0.75rem;
      border-bottom: 3px solid #e53935;
      display: inline-block;
    }
    .stories-area .story-content-area .content .inner-content p {
      color: rgba(255, 255, 255, 0.92);
      font-size: 0.95rem;
      line-height: 1.65;
      margin-top: 1rem;
      margin-bottom: 0;
    }
    @media (max-width: 575px) {
      .stories-area .story-content-area .content .inner-content h2 {
        font-size: 1.4rem;
      }
      .stories-area .story-content-area .content .inner-content p {
        font-size: 0.9rem;
      }
    }
    .stories-area .story-content-area .content .inner-content span {
      color: #e53935;
      font-weight: 600;
      letter-spacing: 0.08em;
      font-size: 0.75rem;
      text-transform: uppercase;
      display: block;
      margin-bottom: 0.5rem;
    }
    .stories-area .story-content-area .content .story-details-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      margin-top: 1.25rem;
      padding: 0.65rem 1.35rem;
      font-size: 0.875rem;
      font-weight: 600;
      letter-spacing: 0.04em;
      color: #fff;
      background: #e53935;
      border: 2px solid #e53935;
      border-radius: 6px;
      text-decoration: none;
      transition: color 0.25s ease, background 0.25s ease, border-color 0.25s ease, transform 0.2s ease;
    }
    .stories-area .story-content-area .content .story-details-btn:hover {
      color: #fff;
      background: #c62828;
      border-color: #c62828;
      transform: translateY(-1px);
    }
  </style>
@endpush

<section class="collection-slider-area stories-area">
  <div class="collection-slider-content">
    @forelse ($stories as $story)
      <div class="slider-item story-item {{ $loop->even ? 'story-item--reversed' : '' }}">
        <div class="thumb story-thumb">
          <div class="bg-thumb bg-overlay bg-img" data-bg-img="{{ $story->image ? \Illuminate\Support\Facades\Storage::disk('public')->url($story->image) : asset('assets/img/slider/cs1-s1.jpg') }}"></div>
        </div>
        <div class="slider-content-area story-content-area">
          <div class="content">
            <div class="inner-content">
              <h2>{{ $story->title }}</h2>
              @if ($story->description)
                <p>{{ \Illuminate\Support\Str::limit($story->description, 120) }}</p>
              @endif
              <a href="{{ route('shop.stories.show', $story) }}" class="story-details-btn">View details</a>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="slider-item story-item">
        <div class="thumb story-thumb">
          <div class="bg-thumb bg-overlay bg-img" data-bg-img="{{ asset('assets/img/slider/cs1-s1.jpg') }}"></div>
        </div>
        <div class="slider-content-area story-content-area">
          <div class="content">
            <div class="inner-content">
              <h2>Our Stories</h2>
              <p>Add stories from Admin → Stories to display them here.</p>
            </div>
          </div>
        </div>
      </div>
    @endforelse
  </div>
</section>
<!--== End Stories Area ==-->
@endsection
