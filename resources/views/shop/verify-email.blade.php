@extends('layouts.app')

@section('title', 'Verify Email - AliarLIE Clothing')

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
            <h2 class="title">Verify your email</h2>
            <div class="bread-crumbs">
              <a href="{{ route('home') }}">Home<span class="breadcrumb-sep">></span></a>
              <span class="active">Verify Email</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--== End Page Title Area ==-->

  <section class="account-login-area">
    <div class="container">
      <div class="row">
        <div class="col-lg-7 m-auto">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5 text-center">
              <h4 class="mb-3">Check your inbox</h4>
              <p class="text-muted mb-4">
                We sent a verification link to your email address.
                Please verify your account to continue.
              </p>
              <form method="POST" action="{{ route('shop.verification.send') }}">
                @csrf
                <button class="btn btn-theme btn-black w-100" type="submit">Resend verification email</button>
              </form>
              <div class="mt-3">
                <a href="{{ route('shop.account') }}" class="btn btn-link">Back to account</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
