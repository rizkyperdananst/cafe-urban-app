 <div id="sidebar" class="p-3">
     @if (Auth::user()->role == 'Super Admin' || Auth::user()->role == 'Admin')
         <h5 class="text-center mb-4">Menu Admin</h5>
         <ul class="nav flex-column gap-1">
             <li>
                 <a href="#" class="nav-link active">
                     <i class="bi bi-speedometer2 me-2"></i> Dashboard
                 </a>
             </li>
             <li>
                 <a href="{{ route('admin.categories.index') }}" class="nav-link">
                     <i class="bi bi-tags me-2"></i> Kategori Menu
                 </a>
             </li>
             <li>
                 <a href="{{ route('admin.menus.index') }}" class="nav-link">
                     <i class="bi bi-list-ul me-2"></i> Data Menu
                 </a>
             </li>
             <li>
                 <a href="{{ route('admin.customers.index') }}" class="nav-link">
                     <i class="bi bi-person-lines-fill me-2"></i> Data Pelanggan
                 </a>
             </li>
             <li>
                 <a href="{{ route('admin.users.index') }}" class="nav-link">
                     <i class="bi bi-person-gear me-2"></i> Data Admin
                 </a>
             </li>
             <li>
                 <a href="{{ route('admin.orders.index') }}" class="nav-link">
                     <i class="bi bi-truck me-2"></i> Status Pesanan
                 </a>
             </li>
             <li>
                 <a href="{{ route('admin.reports.index') }}" class="nav-link">
                     <i class="bi bi-graph-up-arrow me-2"></i> Laporan Penjualan
                 </a>
             </li>
             <li>
                 <form action="{{ route('logout') }}" method="POST" class="nav-link">
                     @csrf
                     <button class="btn btn-outline-danger w-100">
                         <i class="bi bi-box-arrow-right me-2"></i> Logout
                     </button>
                 </form>
             </li>
         </ul>

         {{-- <li>
                 <a class="nav-link dropdown-toggle" data-bs-toggle="collapse" href="#menuLaporan">
                     <i class="bi bi-file-earmark-text me-2"></i> Laporan
                 </a>
                 <div class="collapse ps-3" id="menuLaporan">
                     <a href="#" class="nav-link"><i class="bi bi-chevron-right me-1"></i> Penjualan</a>
                     <a href="#" class="nav-link"><i class="bi bi-chevron-right me-1"></i> Stok Barang</a>
                 </div>
             </li> --}}
     @endif


     @if (Auth::user()->role == 'Customer')
         {{-- menu pelanggan --}}
         <h5 class="text-center mb-4">Menu Pelanggan</h5>
         <ul class="nav flex-column gap-1">
             <li>
                 <a href="{{ route('customer.dashboard') }}"
                     class="nav-link {{ Request::is('customer/dashboard*') ? 'active' : '' }}">
                     <i class="bi bi-house-door me-2"></i> Beranda
                 </a>
             </li>
             <li>
                 <a href="{{ route('customer.menu.index') }}"
                     class="nav-link {{ Request::is('customer/menu*') ? 'active' : '' }}">
                     <i class="bi bi-card-list me-2"></i> Daftar Menu
                 </a>
             </li>
             <li>
                 <a href="{{ route('customer.cart.index') }}"
                     class="nav-link {{ Request::is('customer/cart*') ? 'active' : '' }}">
                     <i class="bi bi-cart3 me-2"></i> Keranjang Pesanan
                 </a>
             </li>
             <li>
                 <a href="{{ route('customer.orders.history') }}"
                     class="nav-link {{ Request::is('customer/orders*') ? 'active' : '' }}">
                     <i class="bi bi-clock-history me-2"></i> Riwayat Pesanan
                 </a>
             </li>
             <li>
                 <form action="{{ route('logout') }}" method="POST" class="nav-link">
                     @csrf
                     <button class="btn btn-outline-danger w-100">
                         <i class="bi bi-box-arrow-right me-2"></i> Logout
                     </button>
                 </form>
             </li>
         </ul>
     @endif
 </div>
