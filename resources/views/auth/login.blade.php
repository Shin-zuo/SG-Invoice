<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | SG-Invoice</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* --- Theme Variables --- */
        :root {
            --bg-dark: #0e1217;       
            --card-dark: #161b22;
            --text-main: #f0f6fc;
            --text-muted: #8b949e;
            --accent-primary: #2f81f7; 
            --accent-glow-1: #7d56f4;  /* Tertiary Purple */
            --accent-glow-2: #238636;  /* Quaternary Green */
            --border-color: #30363d;
            --input-bg: #0d1117;
            --radius-md: 12px;
        }

        /* Force Reset */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            width: 100%;
            background-color: var(--bg-dark);
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            overflow: hidden; /* Stops scrollbars */
        }

        /* --- Layout Wrapper (Fixes Centering) --- */
        .auth-wrapper {
            width: 100%;
            height: 100vh; /* Use 100dvh for mobile support if preferred */
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* --- Ambient Glow --- */
        .ambient-glow {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, var(--accent-glow-1) 0%, rgba(0,0,0,0) 70%);
            top: -20%;
            left: -10%;
            opacity: 0.15;
            filter: blur(80px);
            z-index: 0; /* Behind card */
            pointer-events: none;
        }

        .ambient-glow-2 {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, var(--accent-primary) 0%, rgba(0,0,0,0) 70%);
            bottom: -10%;
            right: -10%;
            opacity: 0.12;
            filter: blur(80px);
            z-index: 0;
            pointer-events: none;
        }

        /* --- Card Styling --- */
        .login-card {
            background-color: var(--card-dark);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            position: relative;
            z-index: 10; /* Above glow */
            overflow: hidden;
            width: 100%;
        }

        .card-accent-strip {
            height: 4px;
            width: 100%;
            background: linear-gradient(90deg, var(--accent-glow-1), var(--accent-primary));
            position: absolute;
            top: 0;
            left: 0;
        }

        /* --- Form Elements --- */
        .form-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .custom-input-group {
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 4px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }

        .custom-input-group:focus-within {
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(47, 129, 247, 0.15);
        }

        .custom-input-group input {
            background: transparent;
            border: none;
            color: var(--text-main);
            padding: 10px 12px;
            font-size: 0.95rem;
            width: 100%;
        }
        
        .custom-input-group input:focus {
            outline: none;
            background: transparent;
        }

        .input-icon {
            color: var(--text-muted);
            padding-left: 12px;
            display: flex;
            align-items: center;
        }

        /* --- Buttons --- */
        .btn-primary-custom {
            background-color: var(--accent-primary);
            color: #fff;
            font-weight: 600;
            border: none;
            padding: 12px;
            border-radius: 6px;
            transition: 0.2s;
        }
        
        .btn-primary-custom:hover {
            background-color: #388bfd;
        }

        a { color: var(--accent-primary); text-decoration: none; font-size: 0.85rem; }
        a:hover { text-decoration: underline; }

        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 30px var(--input-bg) inset !important;
            -webkit-text-fill-color: white !important;
        }
    </style>
</head>

<body>
    
    <div class="auth-wrapper">
        
        <div class="ambient-glow"></div>
        <div class="ambient-glow-2"></div>

        <main class="w-100 p-3" style="max-width: 420px;">
            
            <div class="login-card p-4 p-md-5">
                <div class="card-accent-strip"></div>

                <div class="text-center mb-4 mt-2">
                    <h1 class="h4 fw-bold mb-1">Welcome back</h1>
                    <p class="text-secondary small">Enter your credentials to access Invoice.</p>
                </div>

                <form action="{{ route('login.authenticate') }}" method="POST" x-data="{ showPassword: false }">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <div class="custom-input-group">
                            <div class="input-icon">
                                <i data-lucide="mail" style="width: 18px; height: 18px;"></i>
                            </div>
                            <input type="email" name="email" required placeholder="name@company.com">
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label mb-0">Password</label>
                            <a href="#">Forgot password?</a>
                        </div>
                        <div class="custom-input-group">
                            <div class="input-icon">
                                <i data-lucide="lock" style="width: 18px; height: 18px;"></i>
                            </div>
                            <input :type="showPassword ? 'text' : 'password'" name="password" required placeholder="••••••••">
                            <button type="button" @click="showPassword = !showPassword" class="btn border-0 text-secondary pe-3" style="background: transparent;">
                                <i x-show="!showPassword" data-lucide="eye" style="width: 18px; height: 18px;"></i>
                                <i x-show="showPassword" data-lucide="eye-off" style="width: 18px; height: 18px; display: none;"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary-custom w-100 d-flex align-items-center justify-content-center gap-2">
                        Sign in
                    </button>
                </form>
            </div>

            <div class="text-center mt-4">
                <p class="text-secondary small opacity-75">&copy; 2026 SG - Invoice Portal</p>
            </div>

        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>