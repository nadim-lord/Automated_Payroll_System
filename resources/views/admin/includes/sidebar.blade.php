 <!-- ======= Sidebar ======= -->
 <aside id="sidebar" class="sidebar bg-primary">

<ul class="sidebar-nav bg-info" id="sidebar-nav">
  <li class="nav-item">
    <a class="nav-link" href="{{ url('dashboard') }}">
      <i class="bi bi-grid"></i>
      <span>Home</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="{{ url('time') }}">
      <i class="bi bi-clock"></i>
      <span>Time</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="{{ url('leave') }}">
      <i class="bi bi-circle"></i>
      <span>Leave</span>
    </a>
  </li>
  
  <li class="nav-item">
    <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
      <i class="bi bi-people"></i><span>Users</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
      <li>
        <a href="{{ url('users') }}">
          <i class="bi bi-people"></i><span>All Users</span>
        </a>
      </li>
    </ul>
  </li>

  <li class="nav-heading text-white">Features</li>

  <li class="nav-item">
    <a class="nav-link collapsed" href="{{ url('login') }}">
      <i class="bi bi-box-arrow-in-right"></i>
      <span>Logout</span>
    </a>
  </li><!-- End Login Page Nav -->

</ul>

</aside><!-- End Sidebar-->