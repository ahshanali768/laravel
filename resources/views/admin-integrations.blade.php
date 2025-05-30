@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4" style="color:#2267E3;">Integrations</h2>
    <div class="row g-4">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-envelope-at display-4 mb-3 text-primary"></i>
                    <h5 class="card-title mb-2">Email (Gmail/Outlook)</h5>
                    <p class="text-muted text-center mb-3">Send and receive emails via SMTP/IMAP.</p>
                    <a href="#" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#emailIntegrationModal">Configure</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-chat-dots display-4 mb-3 text-success"></i>
                    <h5 class="card-title mb-2">Live Chat (Tawk.to/Crisp)</h5>
                    <p class="text-muted text-center mb-3">Add a free live chat widget to your CRM.</p>
                    <a href="#" class="btn btn-outline-success w-100 disabled">Configure (Coming Soon)</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-cloud-arrow-up display-4 mb-3 text-info"></i>
                    <h5 class="card-title mb-2">File Storage (Google Drive/Dropbox)</h5>
                    <p class="text-muted text-center mb-3">Attach and manage files from cloud storage.</p>
                    <a href="#" class="btn btn-outline-info w-100 disabled">Configure (Coming Soon)</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-facebook display-4 mb-3 text-primary"></i>
                    <h5 class="card-title mb-2">Social Media (FB/Twitter)</h5>
                    <p class="text-muted text-center mb-3">Connect Facebook and Twitter for leads and messaging.</p>
                    <a href="#" class="btn btn-outline-primary w-100 disabled">Configure (Coming Soon)</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-bar-chart-line display-4 mb-3 text-warning"></i>
                    <h5 class="card-title mb-2">Analytics & Reporting</h5>
                    <p class="text-muted text-center mb-3">Google Analytics, Data Studio, Matomo, and more.</p>
                    <a href="#" class="btn btn-outline-warning w-100 disabled">Configure (Coming Soon)</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <i class="bi bi-slack display-4 mb-3 text-secondary"></i>
                    <h5 class="card-title mb-2">Team Chat & Alerts</h5>
                    <p class="text-muted text-center mb-3">Slack, Telegram, Rocket.Chat, and more.</p>
                    <a href="#" class="btn btn-outline-secondary w-100 disabled">Configure (Coming Soon)</a>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-info mt-4">
        <strong>Note:</strong> Integration setup is coming soon. Contact your developer to enable or customize integrations.
    </div>
</div>

{{-- Email Integration Modal --}}
<div class="modal fade" id="emailIntegrationModal" tabindex="-1" aria-labelledby="emailIntegrationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="emailIntegrationModalLabel">Configure Email Integration</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="{{ route('admin.integrations.email.save') }}">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="smtp_host" class="form-label">SMTP Host</label>
            <input type="text" class="form-control" id="smtp_host" name="smtp_host" required>
          </div>
          <div class="mb-3">
            <label for="smtp_port" class="form-label">SMTP Port</label>
            <input type="number" class="form-control" id="smtp_port" name="smtp_port" required>
          </div>
          <div class="mb-3">
            <label for="smtp_user" class="form-label">SMTP Username</label>
            <input type="text" class="form-control" id="smtp_user" name="smtp_user" required>
          </div>
          <div class="mb-3">
            <label for="smtp_pass" class="form-label">SMTP Password</label>
            <input type="password" class="form-control" id="smtp_pass" name="smtp_pass" required>
          </div>
          <div class="mb-3">
            <label for="smtp_encryption" class="form-label">Encryption</label>
            <select class="form-select" id="smtp_encryption" name="smtp_encryption">
              <option value="tls">TLS</option>
              <option value="ssl">SSL</option>
              <option value="none">None</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Settings</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
