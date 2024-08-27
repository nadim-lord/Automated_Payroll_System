@extends('admin.layouts.default')

@section('main_content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Time Entries</h1>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">All Time Entries</h5>
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        @if($user->position != 'Admin')
                        <div class="mb-2">
                            <form action="{{ route('time.clock-in') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">Clock In</button>
                            </form>
                        </div>
                        <div class="mb-3">
                            <form action="{{ route('time.clock-out') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-secondary">Clock Out</button>
                            </form>
                        </div>
                        @endif
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">User ID</th>
                                    <th scope="col">User Name</th>
                                    <th scope="col">Clock In</th>
                                    <th scope="col">Clock Out</th>
                                    <th scope="col">Total Time</th>
                                    @if($user->position == 'Admin')
                                    <th scope="col">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($timeEntries as $entry)
                                    <tr>
                                        <td>{{ $entry->user_id }}</td>
                                        <td>{{ $entry->user->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($entry->clock_in)->timezone('Asia/Dhaka')->format('Y-m-d h:i A') }}</td>
                                        <td>{{ $entry->clock_out ? \Carbon\Carbon::parse($entry->clock_out)->timezone('Asia/Dhaka')->format('Y-m-d h:i A') : 'Still Working' }}</td>
                                        <td>
                                            @if($entry->clock_out)
                                                @php
                                                    $clockIn = \Carbon\Carbon::parse($entry->clock_in)->timezone('Asia/Dhaka');
                                                    $clockOut = \Carbon\Carbon::parse($entry->clock_out)->timezone('UTC');
                                                    $diffInMinutes = $clockOut->diffInMinutes($clockIn);
                                                    $hours = floor($diffInMinutes / 60);
                                                    $minutes = $diffInMinutes % 60;
                                                @endphp
                                                {{ $hours }} hours {{ $minutes }} minutes
                                            @else
                                                Still Working
                                            @endif
                                        </td>
                                        @if($user->position == 'Admin')
                                        <td>
                                            <!-- Update Button -->
                                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateTimeEntryModal{{ $entry->id }}">Update</button>
                                            <!-- Delete Form -->
                                            <form action="{{ route('time.destroy', $entry->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </td>
                                        @endif
                                    </tr>

                                    <!-- Update Time Entry Modal -->
                                    @if($user->position == 'Admin')
                                    <div class="modal fade" id="updateTimeEntryModal{{ $entry->id }}" tabindex="-1" aria-labelledby="updateTimeEntryModalLabel{{ $entry->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="updateTimeEntryModalLabel{{ $entry->id }}">Update Time Entry</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('time.update', $entry->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="mb-3">
                                                            <label for="clock_in{{ $entry->id }}" class="form-label">Clock In</label>
                                                            <input type="datetime-local" name="clock_in" id="clock_in{{ $entry->id }}" class="form-control" value="{{ \Carbon\Carbon::parse($entry->clock_in)->timezone('Asia/Dhaka')->format('Y-m-d\TH:i') }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="clock_out{{ $entry->id }}" class="form-label">Clock Out</label>
                                                            <input type="datetime-local" name="clock_out" id="clock_out{{ $entry->id }}" class="form-control" value="{{ $entry->clock_out ? \Carbon\Carbon::parse($entry->clock_out)->timezone('UTC')->format('Y-m-d\TH:i') : '' }}">
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
