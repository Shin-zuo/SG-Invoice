<aside id="sidebar" class="sidebar d-flex flex-column">
    <div class="p-4 d-flex align-items-center justify-content-between">
        
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('images/favicon.png') }}" alt="Project Logo" style="height: 32px;">
            <h5 class="mb-0 fw-bold sidebar-logo text-theme-bright">
                SG-Invoice
            </h5>
        </div>

        <button class="btn btn-sm text-secondary d-md-none m-0 p-0" onclick="toggleSidebar()">
            <i class="fa-solid fa-xmark fs-5"></i>
        </button>
        
    </div>
    <div class="px-3 flex-grow-1 overflow-auto mt-2">
        <ul class="nav nav-pills flex-column mb-auto gap-1">
            <li class="nav-item">
                <a href="{{ route ('dashboard') }}" 
                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house me-2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('invoice')}}" 
                class="nav-link {{ request()->routeIs('invoice') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-invoice me-2"></i> Invoices
                </a>
            </li>
            <li>
                <a href="{{ route('analytics')}}" 
                class="nav-link {{ request()->routeIs('analytics') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-invoice me-2"></i> Analytics
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-gear me-2"></i> Settings
                </a>
            </li>
        </ul>
    </div>

    <div class="p-3 border-top" style="border-color: var(--border-subtle) !important;">
        <div class="d-flex align-items-center p-2 rounded hover-bg" style="cursor: pointer;">
            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px; font-size: 0.8rem;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <h6 class="mb-0 fw-bold text-theme-bright" style="font-size: 0.9rem;">{{auth()->user()->name}}</h6>
                <small class="text-theme-muted" style="font-size: 0.75rem;">{{auth()->user()->email}}</small>
            </div>
        </div>
    </div>
</aside>