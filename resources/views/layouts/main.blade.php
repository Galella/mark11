<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>@yield('title', 'AdminLTE Laravel')</title>

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <!--begin::Primary Meta Tags-->
    <meta name="title" content="@yield('meta_title', 'AdminLTE Laravel')" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="@yield('meta_description', 'AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance.')"
    />
    <meta
      name="keywords"
      content="@yield('meta_keywords', 'bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant')"
    />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="{{ asset('adminlte/css/adminlte.css') }}" as="style" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media='all'"
    />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.css') }}" />
    <!--end::Required Plugin(AdminLTE)-->

    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />

    <!-- jsvectormap -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
      integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
      crossorigin="anonymous"
    />
    
    @yield('styles')
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            @yield('navbar-start')
          </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
            @yield('navbar-end')
            
            <!--begin::User Menu Dropdown-->
            @auth
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img
                  src="{{ asset('adminlte/assets/img/user2-160x160.jpg') }}"
                  class="user-image rounded-circle shadow"
                  alt="User Image"
                />
                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <!--begin::User Image-->
                <li class="user-header text-bg-primary">
                  <img
                    src="{{ asset('adminlte/assets/img/user2-160x160.jpg') }}"
                    class="rounded-circle shadow"
                    alt="User Image"
                  />
                  <p>
                    {{ Auth::user()->name }} - System User
                    <small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
                  </p>
                </li>
                <!--end::User Image-->
                <!--begin::Menu Body-->
                <li class="user-body">
                  <!--begin::Row-->
                  <div class="row">
                    <div class="col-4 text-center">
                      <a href="{{ route('dashboard') }}">Dashboard</a>
                    </div>
                    <div class="col-4 text-center">
                      <a href="{{ route('containers.index') }}">Containers</a>
                    </div>
                    <div class="col-4 text-center">
                      <a href="{{ route('inventory.index') }}">Inventory</a>
                    </div>
                  </div>
                  <!--end::Row-->
                </li>
                <!--end::Menu Body-->
                <!--begin::Menu Footer-->
                <li class="user-footer">
                  <a href="{{ route('dashboard') }}" class="btn btn-default btn-flat">Profile</a>
                  <a href="{{ route('logout') }}" class="btn btn-default btn-flat float-end"
                     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                     Sign out
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                      @csrf
                  </form>
                </li>
                <!--end::Menu Footer-->
              </ul>
            </li>
            @endauth
            @guest
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Login</span>
              </a>
            </li>
            @endguest
            <!--end::User Menu Dropdown-->
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->
      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="{{ route('dashboard') }}" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="{{ asset('adminlte/assets/img/AdminLTELogo.png') }}"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">@yield('brand-text', 'AdminLTE Laravel')</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              aria-label="Main navigation"
              data-accordion="false"
              id="navigation"
            >
              @yield('sidebar-menu')

              <!-- General Menu -->
              <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>Dashboard</p>
                </a>
              </li>

              <!-- Terminal Management -->
              <li class="nav-item">
                <a href="{{ route('terminals.index') }}" class="nav-link {{ request()->routeIs('terminals.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-building"></i>
                  <p>Terminals</p>
                </a>
              </li>

              <!-- Container Management -->
              <li class="nav-item">
                <a href="{{ route('containers.index') }}" class="nav-link {{ request()->routeIs('containers.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-box"></i>
                  <p>Containers</p>
                </a>
              </li>

              <!-- Inventory Management -->
              <li class="nav-item">
                <a href="{{ route('inventory.index') }}" class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-list"></i>
                  <p>Active Inventory</p>
                </a>
              </li>

              <!-- Gate Operations -->
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-truck"></i>
                  <p>
                    Gate Operations
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('gate.in.form') }}" class="nav-link {{ request()->routeIs('gate.in.form') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-arrow-down-circle"></i>
                      <p>Gate In</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('gate.out.form') }}" class="nav-link {{ request()->routeIs('gate.out.form') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-arrow-up-circle"></i>
                      <p>Gate Out</p>
                    </a>
                  </li>
                </ul>
              </li>

              <!-- Rail Operations -->
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon bi bi-train-freight-front"></i>
                  <p>
                    Rail Operations
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('rail.in.form') }}" class="nav-link {{ request()->routeIs('rail.in.form') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-arrow-down-circle"></i>
                      <p>Rail In (LOR)</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('rail.out.form') }}" class="nav-link {{ request()->routeIs('rail.out.form') ? 'active' : '' }}">
                      <i class="nav-icon bi bi-arrow-up-circle"></i>
                      <p>Rail Out (UFR)</p>
                    </a>
                  </li>
                </ul>
              </li>

              <!-- Master Data -->
              <li class="nav-header">MASTER DATA</li>

              <li class="nav-item">
                <a href="{{ route('trains.index') }}" class="nav-link {{ request()->routeIs('trains.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-train-lightrail-front"></i>
                  <p>Trains</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('wagons.index') }}" class="nav-link {{ request()->routeIs('wagons.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-train"></i>
                  <p>Wagons</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('rail-schedules.index') }}" class="nav-link {{ request()->routeIs('rail-schedules.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-calendar"></i>
                  <p>Rail Schedules</p>
                </a>
              </li>

              <!-- Reports -->
              <li class="nav-header">REPORTS</li>

              <li class="nav-item">
                <a href="{{ route('reports.inventory') }}" class="nav-link">
                  <i class="nav-icon bi bi-file-earmark-bar-graph"></i>
                  <p>Inventory Report</p>
                </a>
              </li>
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
            @yield('content-header')
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            @yield('content')
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
      <!--begin::Footer-->
      <footer class="app-footer">
        <!--begin::To the end-->
        <div class="float-end d-none d-sm-inline">@yield('footer-end', 'Anything you want')</div>
        <!--end::To the end-->
        <!--begin::Copyright-->
        <strong>
          Copyright &copy; 2014-2025&nbsp;
          <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.
        </strong>
        @yield('footer-copyright', 'All rights reserved.')
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="{{ asset('adminlte/js/adminlte.js') }}"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

        // Disable OverlayScrollbars on mobile devices to prevent touch interference
        const isMobile = window.innerWidth <= 992;

        if (
          sidebarWrapper &&
          OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
          !isMobile
        ) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->
    
    @yield('scripts')
  </body>
  <!--end::Body-->
</html>