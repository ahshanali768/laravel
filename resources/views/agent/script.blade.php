@extends('layouts.app')
@section('content')
<div class="container-fluid mt-4 px-4">
  <div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white fw-bold fs-5">Call Script</div>
    <div class="card-body bg-light rounded-bottom">
      <div class="fw-bold mb-2">Current Script</div>
      <div class="bg-white p-3 border rounded" id="agentScriptView">{!! $script !!}</div>
    </div>
  </div>
</div>
@endsection
