@extends('layouts.app')

@section('title', 'Contact Us - AliarLIE Clothing')

@section('wrapper-class', '')

@section('content')
  @php
    $bannerImage = $banner?->image
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($banner->image)
        : asset('assets/img/photos/bg-page3.jpg');
  @endphp

  <!--== Start Page Title Area ==-->
  <section class="page-title-area bg-img" data-bg-img="{{ $bannerImage }}">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="page-title-content">
            <h2 class="title">Contact Us</h2>
            <div class="bread-crumbs">
              <a href="{{ route('home') }}">Home<span class="breadcrumb-sep">></span></a>
              <span class="active">Contact Us</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--== End Page Title Area ==-->

  <!--== Start Contact Area ==-->
  <section class="contact-area">
    <div class="container">
      @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      @if ($errors->has('website'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ $errors->first('website') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      <div class="row">
        <div class="col-lg-7">
          <div class="contact-form">
            <form class="contact-form-wrapper form-style" action="{{ route('contact.store') }}" method="post">
              @csrf
              {{-- Honeypot: leave empty; bots that fill it will be rejected --}}
              <div class="contact-honeypot" aria-hidden="true">
                <label for="website">Leave this blank</label>
                <input type="text" name="website" id="website" value="" tabindex="-1" autocomplete="off">
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="section-title">
                    <h2 class="title">Contact us for any questions</h2>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" placeholder="Name*" value="{{ old('name') }}" required>
                    @error('name')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" placeholder="Email*" value="{{ old('email') }}" required>
                    @error('email')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <input class="form-control @error('phone') is-invalid @enderror" type="text" name="phone" placeholder="Phone Number" value="{{ old('phone') }}">
                    @error('phone')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group mb-0">
                    <textarea class="form-control textarea @error('message') is-invalid @enderror" name="message" placeholder="How can we help?" rows="5" required>{{ old('message') }}</textarea>
                    @error('message')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group mb-0">
                    <button class="btn btn-theme btn-black" type="submit">Send Message</button>
                    <p class="form-text text-muted small mt-2 mb-0">You can send up to 3 messages per minute.</p>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="contact-info-wrapper">
            <div class="section-title">
              <h2 class="title">Get info</h2>
            </div>
            <div class="contact-info-content">
              <div class="align-top">
                @if ($contactInfo?->address_1_title || $contactInfo?->address_1_text)
                  <div class="contact-info-item">
                    <div class="icon">
                      <i class="lastudioicon lastudioicon-pin-3-2"></i>
                    </div>
                    <div class="content">
                      @if ($contactInfo?->address_1_title)<h4>{{ $contactInfo->address_1_title }}</h4>@endif
                      @if ($contactInfo?->address_1_text)<p>{{ $contactInfo->address_1_text }}</p>@endif
                    </div>
                  </div>
                @endif
                @if ($contactInfo?->address_2_title || $contactInfo?->address_2_text)
                  <div class="contact-info-item">
                    <div class="icon">
                      <span><i class="lastudioicon lastudioicon-pin-3-2"></i></span>
                    </div>
                    <div class="content">
                      @if ($contactInfo?->address_2_title)<h4>{{ $contactInfo->address_2_title }}</h4>@endif
                      @if ($contactInfo?->address_2_text)<p>{{ $contactInfo->address_2_text }}</p>@endif
                    </div>
                  </div>
                @endif
              </div>
              <div class="align-bottom">
                @if ($contactInfo?->email)
                  <div class="contact-info-item info-item2">
                    <div class="icon">
                      <span><i class="lastudioicon lastudioicon-mail"></i></span>
                    </div>
                    <div class="content">
                      <p><a href="mailto:{{ $contactInfo->email }}">{{ $contactInfo->email }}</a></p>
                    </div>
                  </div>
                @endif
                @if ($contactInfo?->phone)
                  <div class="contact-info-item">
                    <div class="icon">
                      <span><i class="lastudioicon lastudioicon-phone-call-2"></i></span>
                    </div>
                    <div class="content">
                      <p><a href="tel:{{ preg_replace('/\s+/', '', $contactInfo->phone) }}">{{ $contactInfo->phone }}</a></p>
                    </div>
                  </div>
                @endif
                @if ($contactInfo && ($contactInfo->social_facebook || $contactInfo->social_pinterest || $contactInfo->social_twitter || $contactInfo->social_instagram))
                  <div class="contact-info-item social-icons-item mb-0 pb-0">
                    <div class="content">
                      <div class="social-widget">
                        @if ($contactInfo->social_facebook)<a href="{{ $contactInfo->social_facebook }}" target="_blank" rel="noopener noreferrer"><i class="lastudioicon lastudioicon-b-facebook"></i></a>@endif
                        @if ($contactInfo->social_pinterest)<a href="{{ $contactInfo->social_pinterest }}" target="_blank" rel="noopener noreferrer"><i class="lastudioicon lastudioicon-b-pinterest"></i></a>@endif
                        @if ($contactInfo->social_twitter)<a href="{{ $contactInfo->social_twitter }}" target="_blank" rel="noopener noreferrer"><i class="lastudioicon lastudioicon-b-twitter"></i></a>@endif
                        @if ($contactInfo->social_instagram)<a href="{{ $contactInfo->social_instagram }}" target="_blank" rel="noopener noreferrer"><i class="lastudioicon lastudioicon-b-instagram"></i></a>@endif
                      </div>
                    </div>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--== End Contact Area ==-->

  <!--== Start Map Area ==-->
  {{-- <div class="contact-map-area">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.8402891185374!2d144.95373631590425!3d-37.81720974201477!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad65d4c2b349649%3A0xb6899234e561db11!2sEnvato!5e0!3m2!1sen!2ssg!4v1607294780661!5m2!1sen!2ssg" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Map"></iframe>
  </div> --}}
  <!--== End Map Area ==-->
@endsection

@push('styles')
  <style>
    .contact-honeypot {
      position: absolute;
      left: -9999px;
      width: 1px;
      height: 1px;
      overflow: hidden;
    }
  </style>
@endpush
