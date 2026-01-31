@extends('layouts.app')

@section('title', 'Address Book - AliarLIE Clothing')

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
              <span class="active">Address Book</span>
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
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4 mb-lg-0">
          @include('shop.partials.account-sidebar')
        </div>

        <!-- Main Content Area -->
        <div class="col-lg-9">
          <h1 class="account-page-title mb-4">Address Book</h1>

          <div class="account-card">
            <div class="account-card-header">
              <h5 class="account-card-title">Saved Addresses</h5>
            </div>
            <div class="account-card-body">
              @if ($addresses->isEmpty())
                <div class="text-center py-5">
                  <p class="text-muted mb-3">No saved addresses yet.</p>
                  <button type="button" class="btn btn-theme btn-black" data-bs-toggle="modal" data-bs-target="#addAddressModal">Add Your First Address</button>
                </div>
              @else
                <div class="row g-4 mb-4">
                  @foreach ($addresses as $address)
                    <div class="col-md-6">
                      <div class="account-address-card">
                        <h6 class="account-address-card-title">{{ $address->receiver_name }}</h6>
                        <div class="account-address-card-body">
                          <p class="mb-1">{{ $address->phone }}</p>
                          <p class="mb-1">{{ $address->address }}</p>
                          <p class="mb-0">{{ $address->township }}, {{ $address->city }}</p>
                        </div>
                        <div class="account-address-card-footer">
                          <button class="btn btn-outline-secondary btn-sm" type="button" disabled>Edit</button>
                          <button class="btn btn-outline-danger btn-sm" type="button" disabled>Delete</button>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              @endif

              <div class="mt-4">
                <button type="button" class="btn btn-theme btn-black" data-bs-toggle="modal" data-bs-target="#addAddressModal">Add New Address</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Add Address Modal -->
  <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addAddressModalLabel">Add New Address</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="{{ route('shop.account.addresses.store') }}">
          @csrf
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-12">
                <div class="form-group">
                  <label for="receiver_name" class="form-label">Full name *</label>
                  <input type="text" class="form-control @error('receiver_name') is-invalid @enderror" id="receiver_name" name="receiver_name" value="{{ old('receiver_name') }}" required>
                  @error('receiver_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label for="phone" class="form-label">Phone *</label>
                  <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                  @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label for="address" class="form-label">Address *</label>
                  <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                  @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="state_region_id" class="form-label">State / Region *</label>
                  <select class="form-select @error('state_region_id') is-invalid @enderror" id="state_region_id" name="state_region_id" required>
                    <option value="">Select State / Region</option>
                    @foreach ($stateRegions as $region)
                      <option value="{{ $region->id }}" @selected(old('state_region_id') == $region->id)>{{ $region->name }}</option>
                    @endforeach
                  </select>
                  @error('state_region_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="township_id" class="form-label">Township *</label>
                  <select class="form-select @error('township_id') is-invalid @enderror" id="township_id" name="township_id" required disabled>
                    <option value="">Select Township</option>
                  </select>
                  @error('township_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-theme btn-black">Save Address</button>
          </div>
        </form>
      </div>
    </div>
  </div>

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const $ = window.jQuery;
      if (!$) return;

      const stateRegionSelect = document.getElementById('state_region_id');
      const townshipSelect = document.getElementById('township_id');
      const townshipsUrl = '{{ route("shop.account.townships") }}';
      const oldTownshipId = '{{ old("township_id") }}';

      const select2Opts = { width: '100%', placeholder: 'Select State / Region', allowClear: true, theme: 'bootstrap-5' };
      $(stateRegionSelect).select2(select2Opts);

      function initTownshipSelect2() {
        const opts = { width: '100%', placeholder: 'Select Township', allowClear: true, theme: 'bootstrap-5' };
        if ($(townshipSelect).hasClass('select2-hidden-accessible')) {
          $(townshipSelect).select2('destroy');
        }
        $(townshipSelect).select2(opts);
      }

      function loadTownships(stateRegionId) {
        $(townshipSelect).empty().append('<option value="">Select Township</option>');
        townshipSelect.disabled = true;
        if ($(townshipSelect).hasClass('select2-hidden-accessible')) {
          $(townshipSelect).select2('destroy');
        }
        if (!stateRegionId) return;

        fetch(townshipsUrl + '?state_region_id=' + encodeURIComponent(stateRegionId), {
          headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
          .then(res => res.json())
          .then(townships => {
            townships.forEach(t => {
              const opt = document.createElement('option');
              opt.value = t.id;
              opt.textContent = t.name;
              if (oldTownshipId && String(t.id) === oldTownshipId) opt.selected = true;
              townshipSelect.appendChild(opt);
            });
            townshipSelect.disabled = false;
            initTownshipSelect2();
          })
          .catch(() => { townshipSelect.disabled = false; });
      }

      $(stateRegionSelect).on('change', function () {
        loadTownships(this.value);
      });

      const addModal = document.getElementById('addAddressModal');
      if (addModal) {
        addModal.addEventListener('show.bs.modal', function () {
          const stateId = stateRegionSelect.value;
          if (stateId) loadTownships(stateId);
          else {
            $(townshipSelect).empty().append('<option value="">Select Township</option>');
            townshipSelect.disabled = true;
            if ($(townshipSelect).hasClass('select2-hidden-accessible')) $(townshipSelect).select2('destroy');
          }
        });
      }

      if (stateRegionSelect.value) {
        loadTownships(stateRegionSelect.value);
      }
    });
  </script>
@endpush

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
  <style>
    .account-dashboard-area .btn-theme.btn-black {
      padding: 0.5rem 1.25rem;
      font-size: 0.9rem;
    }

    #addAddressModal .modal-footer .btn {
      padding: 0.45rem 1rem;
      font-size: 0.9rem;
      min-height: auto;
    }

    #addAddressModal .modal-footer .btn-theme.btn-black {
      padding: 0.45rem 1rem;
      font-size: 0.9rem;
    }

    .account-dashboard-area {
      background-color: #f8f9fa;
      padding: 40px 0;
    }

    .account-sidebar {
      background: #fff;
      border-radius: 8px;
      padding: 24px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .account-greeting h5 {
      font-size: 18px;
      font-weight: 600;
      color: #333;
      margin-bottom: 0;
    }

    .account-nav {
      margin-top: 24px;
    }

    .nav-section {
      margin-bottom: 32px;
    }

    .nav-section:last-child {
      margin-bottom: 0;
    }

    .nav-section-title {
      font-size: 14px;
      font-weight: 700;
      color: #333;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 12px;
      padding-bottom: 8px;
      border-bottom: 1px solid #e8e8e8;
    }

    .nav-section-title.active {
      color: #007bff;
    }

    .nav-section-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .nav-section-list li {
      margin-bottom: 8px;
    }

    .nav-link {
      display: block;
      padding: 8px 0;
      color: #666;
      text-decoration: none;
      font-size: 14px;
      transition: color 0.2s ease;
    }

    .nav-link:hover,
    .nav-link.active {
      color: #007bff;
      font-weight: 600;
    }

    .account-page-title {
      font-size: 28px;
      font-weight: 600;
      color: #333;
      margin-bottom: 24px;
    }

    .account-card {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      margin-bottom: 24px;
    }

    .account-card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 24px;
      border-bottom: 1px solid #e8e8e8;
    }

    .account-card-title {
      font-size: 18px;
      font-weight: 600;
      color: #333;
      margin: 0;
    }

    .account-card-body {
      padding: 24px;
    }

    .account-address-card {
      background: #f8f9fa;
      border: 1px solid #e8e8e8;
      border-radius: 8px;
      padding: 20px;
      height: 100%;
      display: flex;
      flex-direction: column;
    }

    .account-address-card-title {
      font-size: 16px;
      font-weight: 600;
      color: #333;
      margin-bottom: 12px;
    }

    .account-address-card-body {
      flex: 1;
      color: #666;
      font-size: 14px;
      line-height: 1.6;
    }

    .account-address-card-footer {
      margin-top: 16px;
      display: flex;
      gap: 8px;
    }
  </style>
@endpush
@endsection
