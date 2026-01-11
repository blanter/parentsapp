@extends('layouts.admin')

@section('title', 'Manage Users')
@section('header_title', 'Admin Panel')
@section('header_subtitle', 'User Management')

@section('styles')
<style>
    /* Responsive Table Refined */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        border-radius: 16px 16px 0 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        text-align: left;
        table-layout: auto;
    }

    th {
        background: #f8fafc;
        padding: 14px 16px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--muted);
        border-bottom: 1px solid var(--box-border);
        letter-spacing: 0.05em;
        white-space: nowrap;
    }

    td {
        padding: 16px;
        font-size: 13px;
        border-bottom: 1px solid var(--box-border);
        vertical-align: middle;
        color: #374151;
    }

    .card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        border: 1px solid var(--box-border);
        overflow: hidden;
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 600;
    }

    .status-approved {
        background: #ecfdf5;
        color: #059669;
    }

    .status-blocked {
        background: #ffe4e6;
        color: #e11d48;
    }
</style>
@endsection

@section('header_title', 'Admin Panel')
@section('header_subtitle', 'User Management')

@section('content')
<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Children</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td style="font-weight: 500;">{{ $user->name }}</td>
                        <td style="color: var(--muted);">{{ $user->email }}</td>
                        <td style="min-width: 180px;">
                            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                @foreach($user->students as $student)
                                    <span
                                        style="font-size: 11px; background: rgba(0, 74, 173, 0.05); color: #004AAD; padding: 2px 8px; border-radius: 4px; font-weight: 600;">{{ $student->name }}</span>
                                @endforeach
                                @if($user->students->isEmpty())
                                    <span style="font-size: 11px; color: var(--muted); font-style: italic;">No children linked</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($user->is_approved)
                                <span class="status-badge status-approved">Active</span>
                            @else
                                <span class="status-badge status-blocked">Blocked</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" style="white-space: nowrap;">
                                <button onclick="openEditModal({{ json_encode($user->load('students')) }})" class="btn btn-edit">
                                    <i data-lucide="edit-2" style="width: 14px; height: 14px;"></i> Edit
                                </button>

                                <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST"
                                    onsubmit="return confirm('Change user status?')" style="margin: 0;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn {{ $user->is_approved ? 'btn-block' : 'btn-approve' }}">
                                        @if($user->is_approved)
                                            <i data-lucide="slash" style="width: 14px; height: 14px;"></i> Block
                                        @else
                                            <i data-lucide="check" style="width: 14px; height: 14px;"></i> Unblock
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($users->isEmpty())
        <div style="text-align: center; color: var(--muted); padding: 32px;">No users found.</div>
    @endif

    <div style="padding: 20px;">
        {{ $users->links('vendor.pagination.custom') }}
    </div>
</div>

<!-- Edit User Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit User Account</h2>
            <div class="close" onclick="closeEditModal()">
                <i data-lucide="x"></i>
            </div>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" id="edit_name" class="form-control" placeholder="Enter full name" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" id="edit_email" class="form-control" placeholder="name@example.com" required>
            </div>
            <div class="form-group">
                <label class="form-label">Linked Children</label>
                <select name="student_ids[]" id="edit_students" class="form-control select2" multiple="multiple">
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Security</label>
                <input type="password" name="password" class="form-control" placeholder="New password (optional)" autocomplete="new-password">
                <small style="font-size: 10px; color: #9ca3af; display: block; mt: 4px;">Leave empty to keep current password</small>
            </div>
            <div class="modal-actions" style="margin-top: 30px; display: flex; gap: 12px;">
                <button type="submit" class="auth-btn-primary" style="flex: 2; padding: 14px; margin: 0;">
                    <i data-lucide="save"></i> Update User
                </button>
                <button type="button" onclick="closeEditModal()" class="btn btn-edit" style="flex: 1; padding: 14px; justify-content: center;">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: "Select children...",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#editModal')
        });
    });

    function openEditModal(user) {
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_email').value = user.email;

        // Set selected students
        let studentIds = user.students.map(s => s.id.toString());
        $('#edit_students').val(studentIds).trigger('change');

        document.getElementById('editForm').action = "/manage-users/" + user.id;
        document.getElementById('editModal').style.display = "block";
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = "none";
    }

    window.onclick = function (event) {
        let modal = document.getElementById('editModal');
        if (event.target == modal) {
            closeEditModal();
        }
    }
</script>
@endsection