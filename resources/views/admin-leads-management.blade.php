{{-- Unified SaaS CRM theme applied --}}
@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4 fw-bold" style="color:#2a5298;">Lead Management</h2>
    <div class="card border-0 shadow-sm rounded-lg mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Date</th>
                            <th>Full Name</th>
                            <th>Phone</th>
                            <th>DID</th>
                            <th>Campaign</th>
                            <th>Agent</th>
                            <th>Verifier</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leads as $lead)
                            <tr id="lead-row-{{ $lead->id }}">
                                <td>{{ $lead->id }}</td>
                                <td>{{ $lead->created_at->format('Y-m-d') }}</td>
                                <td>{{ $lead->first_name }} {{ $lead->last_name }}</td>
                                <td>{{ $lead->phone }}</td>
                                <td>{{ $lead->did_number }}</td>
                                <td>{{ $lead->campaign_name }}</td>
                                <td>{{ $lead->agent_name }}</td>
                                <td>{{ $lead->verifier_name ?? '-' }}</td>
                                <td class="status-cell">{{ ucfirst($lead->status) }}</td>
                                <td id="lead-row-{{ $lead->id }}">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" id="leadActionsDropdown{{ $lead->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="leadActionsDropdown{{ $lead->id }}">
                                            <li>
                                                {{-- Replace the Edit link with a button for AJAX modal --}}
                                                <button type="button" class="dropdown-item" onclick="openEditLeadModal('{{ $lead->id }}')"><i class="bi bi-pencil-square me-2"></i>Edit</button>
                                            </li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.leads.delete', $lead->id) }}" onsubmit="return confirm('Delete this lead?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Delete</button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li class="dropdown dropend position-static">
                                                <a class="dropdown-item dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false" onclick="event.stopPropagation();">
                                                    Change Status
                                                </a>
                                                <ul class="dropdown-menu position-absolute" style="z-index:1050;">
                                                    <li><button type="button" class="dropdown-item" onclick="changeLeadStatus('{{ $lead->id }}', 'Verified', event)">Verified</button></li>
                                                    <li><button type="button" class="dropdown-item" onclick="changeLeadStatus('{{ $lead->id }}', 'Approved', event)">Approved</button></li>
                                                    <li><button type="button" class="dropdown-item" onclick="changeLeadStatus('{{ $lead->id }}', 'Rejected', event)">Rejected</button></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No leads found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('modals')
<!-- Edit Lead Modal -->
<div class="modal fade" id="editLeadModal" tabindex="-1" aria-labelledby="editLeadModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editLeadModalLabel">Edit Lead</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editLeadForm">
        <div class="modal-body">
          <input type="hidden" name="id" id="editLeadId">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">First Name *</label>
              <input type="text" class="form-control" name="first_name" id="editFirstName" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Last Name *</label>
              <input type="text" class="form-control" name="last_name" id="editLastName" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Phone *</label>
              <input type="text" class="form-control" name="phone" id="editPhone" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">DID Number *</label>
              <select class="form-select" name="did_number" id="editDidNumber" required></select>
            </div>
            <div class="col-md-6">
              <label class="form-label">DID Payout ($)</label>
              <input type="text" class="form-control" name="did_payout" id="editDidPayout" readonly>
            </div>
            <div class="col-md-12">
              <label class="form-label">Address</label>
              <input type="text" class="form-control" name="address" id="editAddress">
            </div>
            <div class="col-md-4">
              <label class="form-label">City</label>
              <input type="text" class="form-control" name="city" id="editCity">
            </div>
            <div class="col-md-4">
              <label class="form-label">State</label>
              <input type="text" class="form-control" name="state" id="editState">
            </div>
            <div class="col-md-4">
              <label class="form-label">Zip</label>
              <input type="text" class="form-control" name="zip" id="editZip">
            </div>
            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" id="editEmail">
            </div>
            <div class="col-md-6">
              <label class="form-label">Campaign</label>
              <input type="text" class="form-control" name="campaign_name" id="editCampaignName">
            </div>
            <div class="col-md-4">
              <label class="form-label">Agent</label>
              <input type="text" class="form-control" name="agent_name" id="editAgentName">
            </div>
            <div class="col-md-4">
              <label class="form-label">Verifier</label>
              <input type="text" class="form-control" name="verifier_name" id="editVerifierName">
            </div>
            <div class="col-md-4">
              <label class="form-label">Status *</label>
              <select class="form-select" name="status" id="editStatus" required>
                <option value="Pending">Pending</option>
                <option value="Verified">Verified</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
                <option value="Completed">Completed</option>
              </select>
            </div>
            <div class="col-md-12">
              <label class="form-label">Notes</label>
              <textarea class="form-control" name="notes" id="editNotes" rows="2"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endpush

@push('scripts')
<script>
window.openEditLeadModal = function(leadId) {
    // Fetch DIDs dynamically before showing modal
    fetch('/admin/dids/list')
        .then(res => res.json())
        .then(function(didData) {
            var didSelect = document.getElementById('editDidNumber');
            var didPayout = document.getElementById('editDidPayout');
            didSelect.innerHTML = '';
            didData.dids.forEach(function(did) {
                var opt = document.createElement('option');
                opt.value = did.did_number;
                opt.textContent = did.did_number;
                opt.setAttribute('data-payout', did.payout_amount || 0);
                didSelect.appendChild(opt);
            });
            // Set payout on change
            didSelect.onchange = function() {
                var selected = didSelect.options[didSelect.selectedIndex];
                didPayout.value = selected.getAttribute('data-payout') || '';
            };
            // Now fetch lead data and show modal
            fetch(`/admin/leads/${leadId}/edit`)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data.success) {
                        var lead = data.lead;
                        document.getElementById('editLeadId').value = lead.id;
                        document.getElementById('editFirstName').value = lead.first_name;
                        document.getElementById('editLastName').value = lead.last_name;
                        document.getElementById('editPhone').value = lead.phone;
                        didSelect.value = lead.did_number;
                        // Set payout for selected DID
                        var selected = didSelect.querySelector('option[value="' + lead.did_number + '"]');
                        didPayout.value = selected ? selected.getAttribute('data-payout') : '';
                        document.getElementById('editCampaignName').value = lead.campaign_name || '';
                        document.getElementById('editAgentName').value = lead.agent_name;
                        document.getElementById('editVerifierName').value = lead.verifier_name || '';
                        document.getElementById('editStatus').value = capitalizeFirst(lead.status);
                        document.getElementById('editAddress').value = lead.address || '';
                        document.getElementById('editCity').value = lead.city || '';
                        document.getElementById('editState').value = lead.state || '';
                        document.getElementById('editZip').value = lead.zip || '';
                        document.getElementById('editEmail').value = lead.email || '';
                        document.getElementById('editNotes').value = lead.notes || '';
                        var modal = new bootstrap.Modal(document.getElementById('editLeadModal'));
                        modal.show();
                    } else {
                        alert('Failed to load lead data.');
                        console.error(data);
                    }
                })
                .catch(function(err) { alert('Failed to load lead data.'); console.error(err); });
        });
}

window.changeLeadStatus = function(leadId, status, event) {
    const statusValue = capitalizeFirst(status);
    fetch(`/admin/leads/${leadId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
        },
        body: JSON.stringify({ status: statusValue })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Find the row by id, not by event (since dropdown nesting can break event.target.closest)
            const row = document.getElementById('lead-row-' + leadId);
            if(row) {
                const statusCell = row.querySelector('.status-cell');
                if(statusCell) statusCell.textContent = statusValue;
            }
        } else {
            alert('Status update failed.');
            console.error(data);
        }
    })
    .catch((err) => { alert('Status update failed.'); console.error(err); });
}

function capitalizeFirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('editLeadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var leadId = document.getElementById('editLeadId').value;
        var formData = new FormData(this);
        fetch(`/admin/leads/${leadId}/edit`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
            body: formData
        })
        .then(res => res.json())
        .then(function(data) {
            if (data.success) {
                // Update the table row with new data
                const row = document.getElementById('lead-row-' + leadId);
                if (row) {
                    row.children[2].textContent = data.lead.first_name + ' ' + data.lead.last_name;
                    row.children[3].textContent = data.lead.phone;
                    row.children[4].textContent = data.lead.did_number;
                    row.children[5].textContent = data.lead.campaign_name || '';
                    row.children[6].textContent = data.lead.agent_name;
                    row.children[7].textContent = data.lead.verifier_name || '-';
                    row.querySelector('.status-cell').textContent = data.lead.status;
                }
                bootstrap.Modal.getInstance(document.getElementById('editLeadModal')).hide();
            } else {
                alert('Failed to update lead.');
                console.error(data);
            }
        })
        .catch(function(err) { alert('Failed to update lead.'); console.error(err); });
    });
});
</script>
@endpush
