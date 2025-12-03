@extends('layouts.app')

@section('content')
<div class="users-page">
    <div class="users-container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-user-cog me-2"></i>Users</h4>
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add User
                </a>
            </div>
            <div class="card-body">
                @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role(s)</th>
                                    <th>Registered</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td><strong>{{ $user->name }}</strong></td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->roles->pluck('name')->join(', ') ?: 'User' }}</td>
                                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-user-cog fa-3x text-muted mb-3"></i>
                        <h5>No users found</h5>
                        <p class="text-muted">Get started by adding your first user</p>
                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add your first user
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
