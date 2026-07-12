@extends('admin.layout')

@section('title', 'Pengguna - BuildNest Admin')

@section('page_title', 'Pengguna')

@section('content')
<div class="card-dashboard">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span>Daftar Pengguna</span>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.user.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Tambah Pengguna
            </a>
        </div>
    </div>
    <div class="card-body">
        {{-- Filter & Search --}}
        <form method="GET" class="p-3 border-bottom bg-light d-flex flex-wrap gap-2">
            <input type="text" name="search" class="form-control form-control-sm" style="width:200px;" placeholder="Cari nama/email/telepon..." value="{{ request('search') }}">
            <select name="role" class="form-select form-select-sm" style="width:150px;">
                <option value="">Semua Role</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                <option value="kontraktor" {{ request('role') == 'kontraktor' ? 'selected' : '' }}>Kontraktor</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-search"></i> Filter</button>
            @if(request()->anyFilled(['search','role']))
                <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-outline-danger"><i class="bi bi-x-circle"></i> Reset</a>
            @endif
        </form>

        @if($users->count())
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Role</th>
                        <th>Bergabung</th>
                        <th style="width:150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                    <tr>
                        <td><strong>{{ $u->nama }}</strong></td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->telepon ?? '—' }}</td>
                        <td>
                            @if($u->role === 'admin')
                                <span class="badge bg-danger">{{ $u->role }}</span>
                            @elseif($u->role === 'kontraktor')
                                <span class="badge bg-warning text-dark">{{ $u->role }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $u->role }}</span>
                            @endif
                        </td>
                        <td>{{ $u->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.user.edit', $u->id) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.user.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengguna {{ $u->nama }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $users->links() }}</div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-people" style="font-size:2.5rem;"></i>
            <p class="mt-2">Tidak ada pengguna.</p>
        </div>
        @endif
    </div>
</div>
@endsection
