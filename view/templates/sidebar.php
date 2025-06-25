      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="./index.html" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="dist/assets/img/AdminLTELogo.png"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">nortwind</span>
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
              role="menu"
              data-accordion="false"
            >
           
                 <!-- Dashboard -->
        <li class="nav-item">
        <a href="index.php?page=dashboard" class="nav-link <?php echo ($active_menu ?? '') == 'dashboard' ? 'active' : ''; ?>">
            <i class="nav-icon bi bi-speedometer2"></i>
            <p>Dashboard</p>
        </a>
        </li>

      <!-- Customers -->
      <li class="nav-item">
        <a href="index.php?page=customers" class="nav-link  <?php echo ($active_menu ?? '') == 'customers' ? 'active' : ''; ?>">
          <i class="nav-icon bi bi-people"></i>
          <p>Customers</p>
        </a>
      </li>

      <!-- Categories -->
      <li class="nav-item">
        <a href="index.php?page=categories" class="nav-link <?php echo ($active_menu ?? '') == 'categories' ? 'active' : ''; ?>">
          <i class="nav-icon bi bi-tags"></i>
          <p>Categories</p>
        </a>
      </li>

    <!-- Employees -->
    <li class="nav-item">
      <a href="index.php?page=employees" class="nav-link <?php echo ($active_menu ?? '') == 'employees' ? 'active' : ''; ?> ">
        <i class="nav-icon bi bi-person-badge"></i>
        <p>Employees</p>
      </a>
    </li>

    <!-- Orders -->
    <li class="nav-item">
      <a href="index.php?page=orders" class="nav-link <?php echo ($active_menu ?? '') == 'orders' ? 'active' : ''; ?>">
        <i class="nav-icon bi bi-bag-check"></i>
        <p>Orders</p>
      </a>
    </li>

    <!-- Products -->
    <li class="nav-item">
      <a href="index.php?page=products" class="nav-link <?php echo ($active_menu ?? '') == 'products' ? 'active' : ''; ?>">
        <i class="nav-icon bi bi-box-seam"></i>
        <p>Products</p>
      </a>
    </li>

    <!-- Shippers -->
    <li class="nav-item">
      <a href="index.php?page=shippers" class="nav-link <?php echo ($active_menu ?? '') == 'shippers' ? 'active' : ''; ?>">
        <i class="nav-icon bi bi-truck"></i>
        <p>Shippers</p>
      </a>
    </li>

    <!-- Suppliers -->
    <li class="nav-item">
      <a href="index.php?page=suppliers" class="nav-link <?php echo ($active_menu ?? '') == 'suppliers' ? 'active' : ''; ?>">
        <i class="nav-icon bi bi-boxes"></i>
        <p>Suppliers</p>
      </a>
    </li>
    </ul>
    <!--end::Sidebar Menu-->
  </nav>
</div>
<!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->