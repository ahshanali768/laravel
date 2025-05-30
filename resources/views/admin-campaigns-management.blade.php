@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Manage DIDs</h2>
        <button class="btn btn-primary" onclick="openAddDidModal()"><i class="bi bi-plus-circle me-1"></i>Add DID</button>
    </div>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 fw-bold" style="color:#2a5298; display:none;">DIDs</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>DID</th>
                            <th>Payout ($)</th>
                            <th>Campaign</th>
                            <th>Commission (₹)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php $dids = \App\Models\Did::all(); @endphp
                    @foreach($dids as $did)
                        <tr>
                            <td>{{ $did->did_number }}</td>
                            <td>${{ $did->payout_amount }}</td>
                            <td>{{ $did->owner_campaign }}</td>
                            <td>@if($did->campaign_payout) ₹{{ $did->campaign_payout }} @endif</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="openEditDidModal('{{ $did->id }}')"><i class="bi bi-pencil-square me-1"></i>Edit</a></li>
                                        <li>
                                            <form method="POST" action="{{ route('admin.dids.delete', $did->id) }}" onsubmit="return confirm('Delete this DID?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"><i class="bi bi-trash me-1"></i>Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('modals')
<!-- Add/Edit DID Modal -->
<div class="modal fade" id="didModal" tabindex="-1" aria-labelledby="didModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="didModalLabel">Add/Edit DID</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="didForm">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">DID</label>
            <input type="text" class="form-control" name="did_number" id="modalDidNumber" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Payout ($)</label>
            <input type="number" class="form-control" name="payout_amount" id="modalPayoutAmount" step="0.01" min="0" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Campaign</label>
            <input type="text" class="form-control" name="owner_campaign" id="modalOwnerCampaign">
          </div>
          <div class="mb-3">
            <label class="form-label">Commission (₹)</label>
            <input type="number" class="form-control" name="campaign_payout" id="modalCampaignPayout" step="0.01" min="0" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endpush

@push('scripts')
<script>
function openAddDidModal() {
    document.getElementById('didModalLabel').textContent = 'Add DID';
    document.getElementById('didForm').reset();
    document.getElementById('modalDidNumber').readOnly = false;
    var modal = new bootstrap.Modal(document.getElementById('didModal'));
    modal.show();
}
function openEditDidModal(id) {
    fetch(`/admin/dids/${id}/edit`)
        .then(res => res.json())
        .then(function(data) {
            if (data.success) {
                document.getElementById('didModalLabel').textContent = 'Edit DID';
                document.getElementById('modalDidNumber').value = data.did.did_number;
                document.getElementById('modalDidNumber').readOnly = true;
                document.getElementById('modalPayoutAmount').value = data.did.payout_amount;
                document.getElementById('modalOwnerCampaign').value = data.did.owner_campaign || '';
                document.getElementById('modalCampaignPayout').value = data.did.campaign_payout || 0;
                var modal = new bootstrap.Modal(document.getElementById('didModal'));
                modal.show();
            } else {
                alert('Failed to load DID data.');
            }
        });
}
document.getElementById('didForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    var didNumber = document.getElementById('modalDidNumber').value;
    var url = `/admin/dids/${didNumber}/edit`;
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
        },
        body: formData
    })
    .then(res => res.json())
    .then(function(data) {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to save DID.');
        }
    });
});
</script>
@endpush
