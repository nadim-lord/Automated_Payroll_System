@extends('admin.layouts.default')
@section('main_content')
<main id="main" class="main">

    <div class="pagetitle">
      <h1>All Users</h1>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            @if(Session::has('msg'))
            <div class="alert alert-success">
              <strong>{{ Session::get('msg') }}</strong> 
            </div>
            @endif
            <div class="card-body">
              <h5 class="card-title">Show the Users Table</h5>

              <!-- Table with stripped rows -->
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Position</th>
                    @if(Session::has('userrole') && Session::get('userrole')!='Employee')
                    <th scope="col">Approved?</th>
                    @if(Session::has('userrole') && Session::get('userrole')!='Manager')
                    <th scope="col">Approve
                    @endif
                    </th>
                  </tr>
                  @endif
                </thead>
                <tbody>
                  @foreach($users as $u)
                  <tr>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->position }}</td>
                    @if(Session::has('userrole') && Session::get('userrole')!='Employee')
                    <td>{{ $u->is_approved }}</td>
                    @if(Session::has('userrole') && Session::get('userrole')!='Manager')
                    <td>
                      @if($u->is_approved==0)
                      <a href="{{ url('approve/'.$u->id) }}" class="btn btn-danger">Approve</a>
                      @endif
                    </td>
                    @endif
                    @endif
                  </tr>
                  @endforeach
                  
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->
@endsection
@section('scripts')
<script src="{{ asset('admin/assets/js/tables.js') }}"></script>
@endsection