@extends('layouts.app')

@section('data_one')
    <div class="container mt-4">
        <h4>Assign Permissions to {{ $user->name }}</h4>
        <form action="{{ route('superadmin.users.permissions.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                @foreach ($permissions as $permission)
                    <div class="form-check">
                        <input class="form-check-input"
                               type="checkbox"
                               name="permissions[]"
                               value="{{ $permission->name }}"
                               id="perm{{ $permission->id }}"
                               {{ in_array($permission->name, $userPermissions) ? 'checked' : '' }}>
                        <label class="form-check-label" for="perm{{ $permission->id }}">
                            {{ $permission->name }}
                        </label>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary">Update Permissions</button>
            <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
@endsection
