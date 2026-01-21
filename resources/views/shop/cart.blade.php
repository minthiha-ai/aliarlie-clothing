@extends('layouts.app')

@section('title', 'Cart - AliarLIE Clothing')

@section('wrapper-class', 'page-blog-wrapper')

@section('content')
  @php
    $bannerImage = $banner?->image
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($banner->image)
        : asset('assets/img/photos/bg-page5.jpg');
  @endphp

  <!--== Start Page Title Area ==-->
  <section class="page-title-area bg-img" data-bg-img="{{ $bannerImage }}">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="page-title-content">
            <h2 class="title">Cart</h2>
            <div class="bread-crumbs">
              <a href="{{ route('home') }}">Home<span class="breadcrumb-sep">></span></a>
              <span class="active">Cart</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--== End Page Title Area ==-->

  <!--== Start Cart Area Wrapper ==-->
  <section class="product-area cart-page-area">
    <div class="container">
      <div class="js-cart-content">
        @include('partials.cart-content', ['cartItems' => $cartItems, 'subtotal' => $subtotal])
      </div>
    </div>
  </section>
  <!--== End Cart Area Wrapper ==-->
@endsection
