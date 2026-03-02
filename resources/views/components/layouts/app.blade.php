<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Laravel App' }}</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="overflow-hidden">
    
    <div class="d-flex vh-100">
        
        <x-layouts.sidebar />

        <div id="sidebarOverlay" class="d-none position-fixed top-0 start-0 w-100 h-100 bg-dark opacity-50 z-3" onclick="toggleSidebar()"></div>

        <div class="main-content">
            
            <x-layouts.header :title="$title ?? 'Dashboard'" />

            <main class="p-4 flex-grow-1 overflow-auto">
                <div class="container-fluid p-0">
                    {{ $slot }}
                </div>
            </main>

            <x-layouts.footer />
            
        </div>
    </div>

    <script>
        // Sidebar Logic
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            const overlay = document.getElementById('sidebarOverlay');
            overlay.classList.toggle('d-none');
            overlay.classList.toggle('d-block');
        }

        // --- Theme Logic (NEW) ---
        // 1. Initialize on load
        const currentTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', currentTheme);
        updateThemeIcon(currentTheme);

        // 2. Toggle function
        function toggleTheme() {
            const html = document.documentElement;
            let theme = html.getAttribute('data-theme');
            let newTheme = theme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        }

        // 3. Icon update
        function updateThemeIcon(theme) {
            const icon = document.getElementById('theme-icon');
            if (!icon) return;
            if (theme === 'dark') {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
            } else {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }
        }
    </script>
</body>
</html>