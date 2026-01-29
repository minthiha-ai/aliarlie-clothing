@extends('layouts.app')

@section('title', 'My Returns - AliarLIE Clothing')

@section('wrapper-class', '')

@section('content')
  @php
    $bannerImage = $banner?->image
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($banner->image)
        : asset('assets/img/photos/bg-page2.jpg');
  @endphp

  <!--== Start Page Title Area ==-->
  <section class="page-title-area bg-overlay-black2-6 bg-img" data-bg-img="{{ $bannerImage }}">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="page-title-content">
            <h2 class="title">My Account</h2>
            <div class="bread-crumbs">
              <a href="{{ route('home') }}">Home<span class="breadcrumb-sep">></span></a>
              <span class="active">My Returns</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--== End Page Title Area ==-->

  <section class="account-dashboard-area mb-5 mt-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 mb-4 mb-lg-0">
          @include('shop.partials.account-sidebar')
        </div>
        <div class="col-lg-9">
          <h1 class="account-page-title mb-4">My Returns</h1>
          <div class="account-card">
            <div class="account-card-header">
              <h5 class="account-card-title">Return Requests</h5>
            </div>
            <div class="account-card-body">
              <div class="text-center py-5">
                <p class="text-muted mb-3">No return requests yet.</p>
                <p class="text-muted small">Return requests will appear here when you initiate a return.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

@push('styles')
  @include('shop.account.styles')
@endpush
@endsection
