<!-- ======= Sidebar ======= -->
<style>
    .sidebar-nav .nav-link.collapsed {
        color: #012970;
        background: #fff;
    }

    .sidebar-nav .nav-link.collapsed i {
        color: #012970;
    }

    .sidebar-nav .nav-link.collapsed:hover {
        color: #fff;
        background: #F16667;
    }

    .sidebar-nav .nav-link.collapsed:hover i {
        color: #fff;
    }
</style>
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('dashboard') || request()->is('admin-profile') ? '' : 'collapsed' }}" href="{{route('admin.dashboard')}}">
            <i class="bi bi-grid"></i>
            <span>Dashboard</span>
            </a>
        </li>
        <!-- End Dashboard Nav -->
        <!-- <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-menu-button-wide"></i><span>Components</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
              <li>
                <a href="components-alerts.html">
                  <i class="bi bi-circle"></i><span>Alerts</span>
                </a>
              </li>
              <li>
                <a href="components-accordion.html">
                  <i class="bi bi-circle"></i><span>Accordion</span>
                </a>
              </li>
              <li>
                <a href="components-badges.html">
                  <i class="bi bi-circle"></i><span>Badges</span>
                </a>
              </li>
              <li>
                <a href="components-breadcrumbs.html">
                  <i class="bi bi-circle"></i><span>Breadcrumbs</span>
                </a>
              </li>
              <li>
                <a href="components-buttons.html">
                  <i class="bi bi-circle"></i><span>Buttons</span>
                </a>
              </li>
              <li>
                <a href="components-cards.html">
                  <i class="bi bi-circle"></i><span>Cards</span>
                </a>
              </li>
              <li>
                <a href="components-carousel.html">
                  <i class="bi bi-circle"></i><span>Carousel</span>
                </a>
              </li>
              <li>
                <a href="components-list-group.html">
                  <i class="bi bi-circle"></i><span>List group</span>
                </a>
              </li>
              <li>
                <a href="components-modal.html">
                  <i class="bi bi-circle"></i><span>Modal</span>
                </a>
              </li>
              <li>
                <a href="components-tabs.html">
                  <i class="bi bi-circle"></i><span>Tabs</span>
                </a>
              </li>
              <li>
                <a href="components-pagination.html">
                  <i class="bi bi-circle"></i><span>Pagination</span>
                </a>
              </li>
              <li>
                <a href="components-progress.html">
                  <i class="bi bi-circle"></i><span>Progress</span>
                </a>
              </li>
              <li>
                <a href="components-spinners.html">
                  <i class="bi bi-circle"></i><span>Spinners</span>
                </a>
              </li>
              <li>
                <a href="components-tooltips.html">
                  <i class="bi bi-circle"></i><span>Tooltips</span>
                </a>
              </li>
            </ul>
            </li>End Components Nav -->
        <li class="nav-item">
            <a class="nav-link {{ request()->is('profile') || request()->is('profiles') || request()->is('profile/edit/*') ? '' : 'collapsed' }} " data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-journal-text"></i><span>Profile</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav" class="nav-content collapse {{ request()->is('profile/edit*') || request()->is('profiles') || request()->is('profile') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('profile.profile') }}" class="{{ request()->is('profile') ? 'active' : ''}}">
                    <i class="bi bi-circle"></i><span>Add Profile</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.profiles') }}" class="{{ request()->is('profile/edit*') || request()->is('profiles') ? 'active' : '' }}">
                    <i class="bi bi-circle"></i><span>Profile list</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.identityindex') }}" class="{{ request()->is('identity*') || request()->is('identity') ? 'active' : '' }}">
                    <i class="bi bi-circle"></i><span>Identity Verification</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- End Forms Nav -->
        <li class="nav-item">
            <a class="nav-link {{ request()->is('userreport') ? '' : 'collapsed' }}" href="{{ route('userreport.user-report') }}">
            <i class="bi bi-grid"></i>
            <span>Report</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('plans') ? '' : 'collapsed' }}" href="{{ route('admin.plans') }}">
            <i class="bi bi-grid"></i>
            <span>Plans</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('subscriptions') || request()->is('view/subscription/*') ? '' : 'collapsed' }}" href="{{ route('admin.subscriptions') }}">
            <i class="bi bi-grid"></i>
            <span>Subscriptions</span>
            </a>
        </li>
        <!-- <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-layout-text-window-reverse"></i><span>Tables</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
              <li>
                <a href="tables-general.html">
                  <i class="bi bi-circle"></i><span>General Tables</span>
                </a>
              </li>
              <li>
                <a href="tables-data.html">
                  <i class="bi bi-circle"></i><span>Data Tables</span>
                </a>
              </li>
            </ul>
            </li>End Tables Nav -->
        <!-- <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-bar-chart"></i><span>Charts</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
              <li>
                <a href="charts-chartjs.html">
                  <i class="bi bi-circle"></i><span>Chart.js</span>
                </a>
              </li>
              <li>
                <a href="charts-apexcharts.html">
                  <i class="bi bi-circle"></i><span>ApexCharts</span>
                </a>
              </li>
              <li>
                <a href="charts-echarts.html">
                  <i class="bi bi-circle"></i><span>ECharts</span>
                </a>
              </li>
            </ul>
            </li>End Charts Nav -->
        <!-- <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
              <i class="bi bi-gem"></i><span>Icons</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="icons-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
              <li>
                <a href="icons-bootstrap.html">
                  <i class="bi bi-circle"></i><span>Bootstrap Icons</span>
                </a>
              </li>
              <li>
                <a href="icons-remix.html">
                  <i class="bi bi-circle"></i><span>Remix Icons</span>
                </a>
              </li>
              <li>
                <a href="icons-boxicons.html">
                  <i class="bi bi-circle"></i><span>Boxicons</span>
                </a>
              </li>
            </ul>
            </li>End Icons Nav -->
    </ul>
</aside>
<!-- End Sidebar-->