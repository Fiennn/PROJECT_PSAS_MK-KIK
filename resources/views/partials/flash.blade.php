@if (session('success'))
    <x-alert type="success" class="mb-6">{{ session('success') }}</x-alert>
@endif

@if (session('error'))
    <x-alert type="error" class="mb-6">{{ session('error') }}</x-alert>
@endif

@if (session('info'))
    <x-alert type="info" class="mb-6">{{ session('info') }}</x-alert>
@endif
