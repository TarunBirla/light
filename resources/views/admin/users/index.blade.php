@extends('layouts.admin')

@section('page-title', 'Registered Users')
@section('breadcrumb', 'Admin / All Users')

@section('content')

    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }

        .page-header h3 {
            font-size: 22px;
            font-weight: 700;
            color: #111;
        }

        .table-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #E8E6DF;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-card table {
            width: 100%;
            min-width: 1000px;
            border-collapse: collapse;
        }

        .table-card thead tr {
            background: #FAFAF8;
            border-bottom: 1px solid #F0EEE8;
        }

        .table-card thead th {
            padding: 13px 14px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: #888;
            text-align: left;
            white-space: nowrap;
        }

        .table-card tbody tr {
            border-bottom: 1px solid #F7F6F1;
            transition: background .15s;
        }

        .table-card tbody tr:hover {
            background: #FAFAF8;
        }

        .table-card tbody td {
            padding: 13px 14px;
            font-size: 14px;
            color: #111;
            vertical-align: middle;
        }

        .id-badge {
            display: inline-block;
            background: #F7F6F1;
            color: #888;
            border-radius: 6px;
            padding: 3px 9px;
            font-size: 12px;
            font-weight: 600;
        }

        .btn-del {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            background: #FEF0F0;
            color: #c0392b;
            border: none;
            cursor: pointer;
            font-family: 'Akshar', sans-serif;
            transition: background .2s;
        }

        .btn-del:hover {
            background: #c0392b;
            color: #fff;
        }

        .search-input {
            width: 280px;
            height: 40px;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 0 15px;
            font-size: 14px;
            outline: none;
        }

        .search-input:focus {
            border-color: #FFC700;
        }

        .status-select {
            padding: 5px 10px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
        }

        .status-active { background: #D4EDDA; color: #155724; border-color: #c3e6cb; }
        .status-inactive { background: #FFF3B0; color: #856404; border-color: #ffebaa; }
        .status-suspended { background: #F8D7DA; color: #721C24; border-color: #f5c6cb; }

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #FFC700;
            color: #111;
            font-size: 14px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 10px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .custom-pagination {
            display: flex;
            justify-content: center;
            margin: 25px 0;
        }

        .custom-pagination .pagination {
            display: flex;
            gap: 8px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .custom-pagination .page-item .page-link {
            min-width: 42px;
            height: 42px;
            border-radius: 10px;
            border: 1px solid #E8E6DF;
            background: #fff;
            color: #111;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all .2s ease;
        }

        .custom-pagination .page-item .page-link:hover,
        .custom-pagination .page-item.active .page-link {
            background: #FFC700;
            border-color: #FFC700;
            color: #111;
        }

        .custom-pagination .page-item.disabled .page-link {
            background: #f5f5f5;
            color: #aaa;
            cursor: not-allowed;
        }
    </style>

    <div class="page-header">
        <h3><i class="fa-solid fa-users me-2" style="color:#FFC700"></i>All Registered Users</h3>
    </div>

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; flex-wrap:wrap; gap:10px;">
        <form method="GET" action="{{ route('admin.all-users.index') }}" style="display:flex; gap:10px; flex-wrap:wrap;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, phone..." class="search-input">
            
            <select name="status" class="form-select" style="width:160px; height:40px; border-radius:10px;" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>

            <button type="submit" class="btn-add">
                <i class="fa-solid fa-search"></i> Filter
            </button>

            @if(request('search') || request('status'))
                <a href="{{ route('admin.all-users.index') }}" class="btn btn-outline-secondary" style="height:40px; border-radius:10px; display:inline-flex; align-items:center;">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>User Details</th>
                    <th>Email Address</th>
                    <th>Phone Number</th>
                    <th>Delivery Address</th>
                    <th>Registration Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $usr)
                    <tr>
                        <td><span class="id-badge">{{ $usr->id }}</span></td>
                        <td>
                            <strong style="color:#111; font-size:15px;">{{ $usr->name ?: trim($usr->first_name . ' ' . $usr->last_name) }}</strong>
                            @if($usr->role === 'admin')
                                <span class="badge bg-danger text-white ms-1" style="font-size:10px;">ADMIN</span>
                            @endif
                        </td>
                        <td>
                            <div><i class="fa-regular fa-envelope me-1 text-muted"></i>{{ $usr->email }}</div>
                        </td>
                        <td>
                            <div><i class="fa-solid fa-phone me-1 text-muted"></i>{{ $usr->phone ?: $usr->mobile ?: '—' }}</div>
                        </td>
                        <td>
                            <small class="text-secondary" style="max-width:240px; display:inline-block; word-break:break-word;">
                                {{ $usr->address ?: '—' }}
                            </small>
                        </td>
                        <td>
                            <small class="text-muted">{{ $usr->created_at ? $usr->created_at->format('d M Y, h:i A') : '—' }}</small>
                        </td>
                        <td>
                            <form action="{{ route('admin.all-users.status', $usr->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="status-select status-{{ $usr->status ?? 'active' }}" onchange="this.form.submit()">
                                    <option value="active" {{ ($usr->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ ($usr->status ?? 'active') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="suspended" {{ ($usr->status ?? 'active') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            @if($usr->role !== 'admin')
                                <form action="{{ route('admin.all-users.destroy', $usr->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-del" onclick="return confirm('Are you sure you want to delete user {{ addslashes($usr->name) }}?')">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </button>
                                </form>
                            @else
                                <span class="text-muted small">Protected</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-users-slash mb-2" style="font-size:32px;"></i>
                            <div>No registered users found.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="custom-pagination">
        {{ $users->appends(request()->query())->onEachSide(1)->links() }}
    </div>

@endsection