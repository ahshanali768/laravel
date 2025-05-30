@extends('layouts.app')
@section('content')
<div class="container-fluid px-0" style="max-width:100vw;">
  <div class="bg-primary text-white fw-bold fs-5 px-4 py-3 mb-0" style="border-radius:0;">Call Script</div>
  <div class="bg-light px-0" style="min-height:calc(100vh - 70px);">
    @hasrole('Admin')
    <form method="POST" action="{{ route('admin.script.save') }}" class="h-100 d-flex flex-column">
      @csrf
      <div class="d-flex align-items-center px-4 pt-4 pb-2">
        <span class="fw-semibold me-2">Script Content</span>
        <span class="text-muted small"><i class="bi bi-info-circle me-1"></i> This script will be shown to all agents during calls.</span>
      </div>
      <div class="flex-grow-1 d-flex flex-column px-4 pb-0">
        <textarea id="adminScriptEditor" name="content" style="width:100%;height:60vh;min-height:400px;max-height:70vh;">{{ $script }}</textarea>
      </div>
      <div class="text-end px-4 py-3 bg-light border-top mt-auto">
        <button type="submit" class="btn btn-primary px-4">Save Script</button>
      </div>
    </form>
    @endhasrole
    @hasrole('agent')
      <div class="fw-bold mb-2 px-4 pt-4">Current Script</div>
      <div class="bg-white p-3 border rounded mx-4 mb-4" id="agentScriptView">{!! $script !!}</div>
    @endhasrole
  </div>
</div>
@endsection
@push('scripts')
@hasrole('Admin')
<!-- TinyMCE (Editable) -->
<script src="/assets/js/tinymce/tinymce.min.js"></script>
<script>
  tinymce.init({
    selector: '#adminScriptEditor',
    menubar: false,
    toolbar: 'undo redo | bold italic underline | bullist numlist | link',
    plugins: 'lists link',
    height: '60vh',
    width: '100%',
    resize: false,
    content_style: "body { font-family:Arial; font-size:15px; padding:15px }"
  });
</script>
@endhasrole
@endpush
