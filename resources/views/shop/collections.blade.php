@extends('layouts.app')

@section('title', 'Collections - AliarLIE Clothing')

@section('wrapper-class', 'page-collections-wrapper')

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
          <h2 class="title">{{ $banner->title ?? 'Collections' }}</h2>
          <div class="bread-crumbs">
            <a href="{{ route('home') }}">Home<span class="breadcrumb-sep">></span></a>
            <span class="active">Collections</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!--== End Page Title Area ==-->

<!--== Start Collections Area Wrapper ==-->
<section class="product-area">
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-lg-6 m-auto">
        <div class="section-title text-center" data-aos="fade-up" data-aos-duration="1000">
          <h2 class="title">Our Collections</h2>
          <h5 class="subtitle">Discover curated collections</h5>
        </div>
      </div>
    </div>
    <div class="row row-gutter-60" data-aos="fade-up" data-aos-duration="1000">
      @forelse ($collections as $collection)
        @php
          $image = $collection->image;
          $productCount = $collection->products_count;
        @endphp
        <div class="col-sm-6 col-lg-4">
          <!-- Start Collection Item -->
          <div class="product-item">
            <div class="product-thumb">
              <a href="{{ route('shop.collection', $collection->slug) }}">
                <img src="{{ $image ? \Illuminate\Support\Facades\Storage::disk('public')->url($image) : asset('assets/img/shop/category/1.jpg') }}" alt="{{ $collection->name }}">
                <span class="thumb-overlay"></span>
              </a>
            </div>
            <div class="product-info">
              <div class="content-inner">
                <h4 class="title"><a href="{{ route('shop.collection', $collection->slug) }}">{{ $collection->name }}</a></h4>
                @if($collection->description)
                  <p class="description">{{ \Illuminate\Support\Str::limit($collection->description, 100) }}</p>
                @endif
                <div class="prices">
                  <span class="price">{{ $productCount }} {{ \Illuminate\Support\Str::plural('Product', $productCount) }}</span>
                </div>
              </div>
              <div class="product-info-action">
                <a class="btn btn-theme" href="{{ route('shop.collection', $collection->slug) }}" style="padding: 8px 20px; font-size: 12px; line-height: 1.3; display: inline-block; text-align: center; min-width: auto; width: auto;">View Collection</a>
              </div>
            </div>
          </div>
          <!-- End Collection Item -->
        </div>
      @empty
        <div class="col-12">
          <p class="text-center">No collections available yet.</p>
        </div>
      @endforelse
    </div>
    @if($collections->hasPages())
      <div class="row">
        <div class="col-12">
          <div class="pagination-area text-center mt-5">
            {{ $collections->links() }}
          </div>
        </div>
      </div>
    @endif
  </div>
</section>
<!--== End Collections Area Wrapper ==-->
@endsection
