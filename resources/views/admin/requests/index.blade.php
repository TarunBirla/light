@extends('layouts.admin')

@section('page-title', 'Requests')
@section('breadcrumb', 'Admin / Requests')

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

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #FFC700;
            color: #111;
            font-size: 14px;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: background .2s, transform .15s;
            font-family: 'Akshar', sans-serif;
        }

        .btn-add:hover {
            background: #E6B200;
            color: #111;
            transform: translateY(-1px);
        }

        .table-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #E8E6DF;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-card::-webkit-scrollbar {
            height: 8px;
        }
        .table-card::-webkit-scrollbar-track {
            background: #F7F6F1;
            border-radius: 0 0 14px 14px;
        }
        .table-card::-webkit-scrollbar-thumb {
            background: #FFC700;
            border-radius: 4px;
        }
        .table-card::-webkit-scrollbar-thumb:hover {
            background: #E6B200;
        }

        .table-card table {
            width: 100%;
            min-width: 950px;
            border-collapse: collapse;
        }

        .table-card thead tr {
            background: #FAFAF8;
            border-bottom: 1px solid #F0EEE8;
        }

        .table-card thead th {
            padding: 13px 18px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: #888;
            text-align: left;
        }

        .table-card tbody tr {
            border-bottom: 1px solid #F7F6F1;
            transition: background .15s;
        }

        .table-card tbody tr:last-child {
            border-bottom: none;
        }

        .table-card tbody tr:hover {
            background: #FAFAF8;
        }

        .table-card tbody td {
            padding: 14px 18px;
            font-size: 14px;
            color: #111;
            vertical-align: middle;
        }

        .thumb-img {
            width: 80px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #E8E6DF;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-active {
            background: #EDFAF0;
            color: #1a7a3a;
        }

        .badge-inactive {
            background: #F5F5F5;
            color: #888;
        }

        .action-group {
            display: flex;
            gap: 8px;
        }

        .btn-edit {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            background: #FFF3B0;
            color: #B38A00;
            border: none;
            cursor: pointer;
            font-family: 'Akshar', sans-serif;
            transition: background .2s;
        }

        .btn-edit:hover {
            background: #FFC700;
            color: #111;
        }

        .btn-del {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 13px;
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

        .id-badge {
            display: inline-block;
            background: #F7F6F1;
            color: #888;
            border-radius: 6px;
            padding: 3px 9px;
            font-size: 12px;
            font-weight: 600;
        }

        .empty-row td {
            text-align: center;
            padding: 60px 0;
            color: #bbb;
            font-size: 14px;
        }
        .custom-pagination {
    display: flex;
    justify-content: center;
    margin: 25px 0;
}

.custom-pagination nav {
    display: flex;
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

.custom-pagination .page-item .page-link:hover {
    background: #FFC700;
    border-color: #FFC700;
    color: #111;
}

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

.custom-pagination svg {
    width: 16px;
    height: 16px;
}
    </style>



    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Product Type</th>
                    <th>Title</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Message</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $request)

                    <tr>
                        <td>
                            @if(in_array(strtolower($request->product_type ?? 'rental'), ['sell', 'selling', 'selling request']))
                                <span class="badge-status" style="background:#EDFAF0; color:#1a7a3a; border: 1px solid #86efac; font-size:12px; padding:5px 12px;">
                                     Sell
                                </span>
                            @else
                                <span class="badge-status" style="background:#EBF5FF; color:#1d4ed8; border: 1px solid #93c5fd; font-size:12px; padding:5px 12px;">
                                     Rental
                                </span>
                            @endif
                        </td>
                        <td>{{ $request->item_name }}</td>
                        <td>{{ $request->name }}</td>
                        <td>{{ $request->email }}</td>
                        <td>{{ $request->phone }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($request->message, 40) }}</td>
                        <td>
                            <div class="action-group">
                                <button
                                    type="button"
                                    class="btn-edit"
                                    onclick="viewRequestDetails(
                                        '{{ $request->id }}',
                                        '{{ addslashes($request->item_name) }}',
                                        '{{ addslashes($request->name) }}',
                                        '{{ addslashes($request->email) }}',
                                        '{{ addslashes($request->phone) }}',
                                        '{{ addslashes($request->message ?? '') }}',
                                        '{{ $request->product_type }}'
                                    )"
                                >
                                    <i class="fa-solid fa-eye"></i>
                                    Details
                                </button>

                                <form
                                    action="{{ route('admin.requests.delete',$request->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Delete this request?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn-del"
                                    >
                                        <i class="fa-solid fa-trash"></i>
                                        Delete
                                    </button>

                                </form>
                            </div>
                        </td>
                    </tr>

                @empty

                    <tr class="empty-row">
                        <td colspan="7">
                            No Request Found
                        </td>
                    </tr>

                @endforelse
            </tbody>
        </table>
      <div class="custom-pagination">
    {{ $requests->onEachSide(1)->links() }}
</div>
    </div>

    <!-- Request Details Modal -->
    <div class="modal fade" id="requestDetailsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius:14px;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Request Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="text-muted small uppercase fw-bold d-block mb-1">Request Type</label>
                        <div id="modalProductTypeBadge"></div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small uppercase fw-bold d-block mb-1">Item Title</label>
                        <p class="fw-bold fs-6 mb-0 text-dark" id="modalItemTitle"></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small uppercase fw-bold d-block mb-1">Customer Name</label>
                        <p class="mb-0 text-dark" id="modalName"></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small uppercase fw-bold d-block mb-1">Email Address</label>
                        <p class="mb-0 text-dark" id="modalEmail"></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small uppercase fw-bold d-block mb-1">Phone Number</label>
                        <p class="mb-0 text-dark" id="modalPhone"></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small uppercase fw-bold d-block mb-1">Customer Message</label>
                        <p class="mb-0 text-break text-dark" id="modalMessage"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function viewRequestDetails(id, item_name, name, email, phone, message, product_type) {
        document.getElementById('modalItemTitle').innerText = item_name;
        document.getElementById('modalName').innerText = name;
        document.getElementById('modalEmail').innerText = email;
        document.getElementById('modalPhone').innerText = phone;
        document.getElementById('modalMessage').innerText = message || '—';

        let badgeContainer = document.getElementById('modalProductTypeBadge');
        let typeLower = (product_type || 'rental').toLowerCase();
        if (typeLower === 'sell' || typeLower === 'selling' || typeLower === 'selling request') {
            badgeContainer.innerHTML = '<span class="badge-status" style="background:#EDFAF0; color:#1a7a3a; border: 1px solid #86efac; font-size:14px; padding:6px 14px; border-radius:20px;"> Sell (Purchase Request)</span>';
        } else {
            badgeContainer.innerHTML = '<span class="badge-status" style="background:#EBF5FF; color:#1d4ed8; border: 1px solid #93c5fd; font-size:14px; padding:6px 14px; border-radius:20px;"> Rental (Rental Request)</span>';
        }

        new bootstrap.Modal(document.getElementById('requestDetailsModal')).show();
    }
    </script>

@endsection