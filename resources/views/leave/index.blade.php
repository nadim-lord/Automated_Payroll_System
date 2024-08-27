@extends('admin.layouts.default')

@section('main_content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Leave Requests</h1>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">All Leave Requests</h5>
                        @if($user->position != 'Admin')
                        <form action="{{ route('leave.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="end_date">End Date</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                            <div class="my-4 text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                        @endif
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>User Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    @if($user->position == 'Admin')
                                    <th>Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaves as $request)
                                    <tr>
                                        <td>{{ $request->user_id }}</td>
                                        <td>{{ $request->user->name }}</td>
                                        <td>{{ $request->start_date }}</td>
                                        <td>{{ $request->end_date }}</td>
                                        <td>{{ ucfirst($request->status) }}</td>
                                        @if($user->position == 'Admin')
                                        <td>
                                            <!-- Update Button -->
                                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateLeaveModal{{ $request->id }}">Update</button>
                                            <!-- Delete Form -->
                                            <form action="{{ route('leave.destroy', $request->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                        @endif
                                    </tr>

                                    <!-- Update Leave Modal -->
                                    @if($user->position == 'Admin')
                                    <div class="modal fade" id="updateLeaveModal{{ $request->id }}" tabindex="-1" aria-labelledby="updateLeaveModalLabel{{ $request->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="updateLeaveModalLabel{{ $request->id }}">Update Leave Request</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('leave.update', $request->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="mb-3">
                                                            <label for="start_date{{ $request->id }}" class="form-label">Start Date</label>
                                                            <input type="date" name="start_date" id="start_date{{ $request->id }}" class="form-control" value="{{ $request->start_date }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="end_date{{ $request->id }}" class="form-label">End Date</label>
                                                            <input type="date" name="end_date" id="end_date{{ $request->id }}" class="form-control" value="{{ $request->end_date }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="status{{ $request->id }}" class="form-label">Status</label>
                                                            <select name="status" id="status{{ $request->id }}" class="form-control" required>
                                                                <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                                <option value="approved" {{ $request->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                                <option value="rejected" {{ $request->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                            </select>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@section('scripts')
<script src="{{ asset('admin/assets/js/tables.js') }}"></script>
@endsection
