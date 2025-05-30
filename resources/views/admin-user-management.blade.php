{{-- Unified SaaS CRM theme applied --}}
@extends('layouts.app')
@section('content')
<div class="container-fluid py-4">
    <h2 class="mb-4 fw-bold" style="color:#2a5298;">User Management</h2>
    <div class="card border-0 shadow-sm rounded-lg mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->roles->pluck('name')->first() }}</td>
                                <td>
                                    @if($user->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($user->status == 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->status == 'pending')
                                        <form method="POST" action="{{ route('admin.users.approve', $user->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.reject', $user->id) }}" class="d-inline ms-2">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                        </form>
                                    @endif
                                    <button type="button" class="btn btn-outline-primary btn-sm ms-2" onclick="openEditUserModal('{{ $user->id }}')"><i class="bi bi-pencil-square"></i></button>
                                    <form method="POST" action="{{ route('admin.users.delete', $user->id) }}" class="d-inline ms-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this user?')"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No users found.</td>
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
<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editUserForm">
        <div class="modal-body">
          <input type="hidden" name="id" id="editUserId">
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name" id="editUserName" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="editUserEmail" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Role</label>
            <select class="form-select" name="role" id="editUserRole" required>
              <option value="Agent">Agent</option>
              <option value="Admin">Admin</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status" id="editUserStatus" required>
              <option value="Pending">Pending</option>
              <option value="Active">Active</option>
              <option value="Revoked">Revoked</option>
            </select>
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
window.openEditUserModal = function(userId) {
    fetch(`/admin/users/${userId}/edit`)
        .then(res => res.json())
        .then(function(data) {
            if (data.success) {
                var user = data.user;
                document.getElementById('editUserId').value = user.id;
                document.getElementById('editUserName').value = user.name;
                document.getElementById('editUserEmail').value = user.email;
                document.getElementById('editUserRole').value = capitalizeFirst(user.role);
                document.getElementById('editUserStatus').value = capitalizeFirst(user.status);
                var modal = new bootstrap.Modal(document.getElementById('editUserModal'));
                modal.show();
            } else {
                alert('Failed to load user data.');
            }
        })
        .catch(function(err) { alert('Failed to load user data.'); });
}

function capitalizeFirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var userId = document.getElementById('editUserId').value;
        var formData = new FormData(this);
        fetch(`/admin/users/${userId}/edit`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
            body: formData
        })
        .then(res => res.json())
        .then(function(data) {
            if (data.success) {
                location.reload(); // Or update the row inline if desired
            } else {
                alert('Failed to update user.');
            }
        })
        .catch(function(err) { alert('Failed to update user.'); });
    });
});
</script>
@endpush
