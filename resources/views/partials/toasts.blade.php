@if (session('success') || session('error') || session('warning') || $errors->any())
  <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055; max-width: 420px;">
    <div class="d-flex flex-column gap-2">
      @if (session('success'))
        <div class="js-toast alert alert-success alert-dismissible fade show mb-0" role="alert" data-auto-dismiss="4500">
          {{ session('success') }}
          <button type="button" class="btn-close js-toast-close" aria-label="Close"></button>
        </div>
      @endif

      @if (session('warning'))
        <div class="js-toast alert alert-info alert-dismissible fade show mb-0" role="alert" data-auto-dismiss="12000">
          {{ session('warning') }}
          <button type="button" class="btn-close js-toast-close" aria-label="Close"></button>
        </div>
      @endif

      @if (session('error'))
        <div class="js-toast alert alert-danger alert-dismissible fade show mb-0" role="alert" data-auto-dismiss="6000">
          {{ session('error') }}
          <button type="button" class="btn-close js-toast-close" aria-label="Close"></button>
        </div>
      @endif

      @if ($errors->any())
        @foreach ($errors->all() as $error)
          <div class="js-toast alert alert-danger alert-dismissible fade show mb-0" role="alert" data-auto-dismiss="8000">
            {{ $error }}
            <button type="button" class="btn-close js-toast-close" aria-label="Close"></button>
          </div>
        @endforeach
      @endif
    </div>
  </div>
@endif
