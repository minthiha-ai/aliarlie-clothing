@php
    $url = $record && $record->payment_proof_photo
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($record->payment_proof_photo)
        : null;
@endphp
@if ($url)
    <div class="fi-fo-field-wrp">
        <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3">
            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                Current payment proof
            </span>
        </label>
        <div class="mt-2 flex flex-wrap items-start gap-3">
            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="inline-block rounded-lg border border-gray-200 dark:border-white/10 overflow-hidden bg-gray-50 dark:bg-white/5 focus:outline-none focus:ring-2 focus:ring-primary-500">
                <img src="{{ $url }}" alt="Payment proof" class="max-h-48 w-auto object-contain" loading="lazy">
            </a>
            <div class="flex flex-col gap-1">
                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:underline">
                    View full size →
                </a>
            </div>
        </div>
    </div>
@endif
