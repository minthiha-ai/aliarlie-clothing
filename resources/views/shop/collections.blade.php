@extends('layouts.app')

@section('title', 'Collections - AliarLIE Clothing')

@section('wrapper-class', 'products-coming-soon-wrapper')

@section('content')
  <!--== Start Coming Soon Area ==-->
  <section class="coming-soon-area bg-img">
    <div class="container-fluid">
      <div class="row row-gutter-0">
        <div class="col-12">
          <div class="coming-soon-wrap">
            <div class="column-left" data-bg-img="{{ asset('assets/img/photos/bg-page11.jpg') }}"></div>
            <div class="column-right" data-bg-img="{{ asset('assets/img/photos/bg-page10.jpg') }}">
              <div class="coming-soon-content">
                <h2 class="title">Collections Coming Soon</h2>
                <div class="countdown-content">
                  <ul class="countdown-timer">
                    <li><span class="days">00</span><p class="days_text">Days</p></li>
                    <li><span class="hours">00</span><p class="hours_text">Hour</p></li>
                    <li><span class="minutes">00</span><p class="minutes_text">Mint</p></li>
                    <li><span class="seconds">00</span><p class="seconds_text">Sec</p></li>
                  </ul>
                </div>
                <a class="btn btn-theme" href="{{ route('shop.index') }}">Get notification</a>
                <div class="coming-social-icons">
                  <a href="#/"><i class="lastudioicon-b-facebook"></i></a>
                  <a href="#/"><i class="lastudioicon-b-pinterest"></i></a>
                  <a href="#/"><i class="lastudioicon-b-twitter"></i></a>
                  <a href="#/"><i class="lastudioicon-b-instagram"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--== End Coming Soon Area ==-->
@endsection
