@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 fw-bold" style="color:#2a5298;">Add New Lead</div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                            <a href="{{ route('agent.leads.create') }}" class="btn btn-sm btn-primary mt-2">Submit Another</a>
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(!empty($note))
                        <div class="alert alert-info">
                            <pre style="white-space: pre-wrap;">{{ $note }}</pre>
                        </div>
                    @endif
                    @if(!session('success'))
                    <form method="POST" action="{{ route('agent.leads.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>First Name *</label>
                                <input type="text" name="first_name" class="form-control" required value="{{ old('first_name') }}">
                            </div>
                            <div class="col-md-6">
                                <label>Last Name *</label>
                                <input type="text" name="last_name" class="form-control" required value="{{ old('last_name') }}">
                            </div>
                            <div class="col-md-6">
                                <label>Phone *</label>
                                <input type="text" name="phone" class="form-control" required placeholder="Enter 10-digit Phone Number" value="{{ old('phone') }}">
                            </div>
                            <div class="col-md-6">
                                <label>DID Number *</label>
                                <input type="text" name="did_number" class="form-control" required placeholder="Enter 10-digit DID" value="{{ old('did_number') }}">
                            </div>
                            <div class="col-12">
                                <label>Address *</label>
                                <textarea name="address" class="form-control" rows="2" required>{{ old('address') }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label>City *</label>
                                <input type="text" name="city" class="form-control" required value="{{ old('city') }}">
                            </div>
                            <div class="col-md-4">
                                <label>State *</label>
                                <input type="text" name="state" class="form-control" required value="{{ old('state') }}">
                            </div>
                            <div class="col-md-4">
                                <label>Zip Code *</label>
                                <input type="text" name="zip" class="form-control" required value="{{ old('zip') }}">
                            </div>
                            <div class="col-md-6">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            </div>
                            <div class="col-md-6">
                                <label>Campaign</label>
                                <select name="campaign_name" class="form-select">
                                    <option value="">-- Optional --</option>
                                    @foreach($campaigns as $c)
                                        <option value="{{ $c->name }}" @if(old('campaign_name')==$c->name) selected @endif>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Agent Name *</label>
                                <input type="text" name="agent_name" id="agent_name" class="form-control" value="{{ old('agent_name', $agent_name) }}" required autocomplete="off">
                            </div>
                            <div class="col-12">
                                <label>Notes</label>
                                <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                            </div>
                            <div class="col-12 mt-3 text-end">
                                <button type="submit" class="btn btn-success px-4">Submit Lead</button>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
