<header class="glass-header sticky-top py-3 px-4 d-flex align-items-center justify-content-between z-1">
    <div class="d-flex align-items-center">
        <button class="btn btn-light d-md-none me-3 p-2 shadow-sm" onclick="toggleSidebar()">
            <i class="fa-solid fa-bars fs-6"></i>
        </button>

        <h4 class="mb-0 fw-bold header-title fs-5">
            {{ $title ?? 'Dashboard' }}
        </h4>
    </div>

    <div class="d-flex align-items-center gap-2">
        
        <button onclick="toggleTheme()" 
                class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm"
                style="width: 38px; height: 38px;"
                title="Toggle Theme">
            <i id="theme-icon" class="fa-solid fa-moon fs-6"></i>
        </button>
        
        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit"
                class="btn btn-light rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm text-danger"
                style="width: 38px; height: 38px;"
                title="Logout">
                <i class="fa-solid fa-right-from-bracket fs-6"></i>
            </button>
        </form>
    </div>
</header>