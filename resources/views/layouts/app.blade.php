<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'StudiosDB') - {{ config('app.name', 'StudiosDB') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Base Glassmorphique StudiosDB -->
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #48bb78, #38b2ac);
            --secondary-gradient: linear-gradient(135deg, #4299e1, #3182ce);
            --danger-gradient: linear-gradient(135deg, #f56565, #e53e3e);
            --glass-bg: rgba(26, 54, 93, 0.8);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text-primary: #e2e8f0;
            --text-secondary: #a0aec0;
            --text-muted: #6b7280;
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, 
                rgba(13, 27, 42, 0.95) 0%, 
                rgba(26, 54, 93, 0.95) 50%,
                rgba(45, 55, 72, 0.95) 100%);
            background-attachment: fixed;
            min-height: 100vh;
            font-family: 'Figtree', sans-serif;
            color: var(--text-primary);
            margin: 0;
            padding: 0;
        }
        
        /* Boutons personnalisés */
        .btn-success-gradient {
            background: var(--primary-gradient);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            border-radius: 8px;
            padding: 10px 20px;
        }
        
        .btn-success-gradient:hover {
            background: linear-gradient(135deg, #38a169, #319795);
            box-shadow: 0 8px 25px rgba(72, 187, 120, 0.4);
            transform: translateY(-2px);
            color: white;
        }
        
        .btn-secondary-glass {
            background: rgba(74, 85, 104, 0.8);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border-radius: 8px;
            padding: 10px 20px;
        }
        
        .btn-secondary-glass:hover {
            background: rgba(74, 85, 104, 1);
            color: white;
            transform: translateY(-1px);
            text-decoration: none;
        }
        
        .btn-outline-primary {
            border-color: rgba(66, 153, 225, 0.5);
            color: #4299e1;
            background: transparent;
        }
        
        .btn-outline-primary:hover {
            background: rgba(66, 153, 225, 0.2);
            color: white;
            border-color: #4299e1;
        }
        
        .btn-outline-success {
            border-color: rgba(72, 187, 120, 0.5);
            color: #48bb78;
            background: transparent;
        }
        
        .btn-outline-success:hover {
            background: rgba(72, 187, 120, 0.2);
            color: white;
            border-color: #48bb78;
        }
        
        .btn-outline-info {
            border-color: rgba(56, 178, 172, 0.5);
            color: #38b2ac;
            background: transparent;
        }
        
        .btn-outline-info:hover {
            background: rgba(56, 178, 172, 0.2);
            color: white;
            border-color: #38b2ac;
        }
        
        .btn-outline-secondary {
            border-color: var(--glass-border);
            color: var(--text-secondary);
            background: transparent;
        }
        
        .btn-outline-secondary:hover {
            background: rgba(107, 114, 128, 0.2);
            color: white;
            border-color: #6b7280;
        }
        
        .btn-outline-danger {
            border-color: rgba(245, 101, 101, 0.5);
            color: #f56565;
            background: transparent;
        }
        
        .btn-outline-danger:hover {
            background: rgba(245, 101, 101, 0.2);
            color: white;
            border-color: #f56565;
        }
        
        /* Formulaires */
        .form-control {
            background: rgba(45, 55, 72, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--text-primary);
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            background: rgba(45, 55, 72, 0.95);
            border-color: rgba(72, 187, 120, 0.6);
            box-shadow: 0 0 0 3px rgba(72, 187, 120, 0.1);
            color: white;
            outline: none;
        }
        
        .form-control::placeholder {
            color: var(--text-secondary);
        }
        
        .form-label {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .form-select {
            background: rgba(45, 55, 72, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--text-primary);
        }
        
        .form-select:focus {
            background: rgba(45, 55, 72, 0.95);
            border-color: rgba(72, 187, 120, 0.6);
            box-shadow: 0 0 0 3px rgba(72, 187, 120, 0.1);
            color: white;
        }
        
        .form-check-input:checked {
            background-color: #48bb78;
            border-color: #48bb78;
        }
        
        .form-check-label {
            color: var(--text-secondary);
        }
        
        /* Cartes */
        .card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .card-header {
            background: rgba(45, 55, 72, 0.6);
            border-bottom: 1px solid var(--glass-border);
            border-radius: 15px 15px 0 0;
            color: var(--text-primary);
        }
        
        .card-body {
            color: var(--text-primary);
        }
        
        .card-footer {
            background: rgba(45, 55, 72, 0.3);
            border-top: 1px solid var(--glass-border);
            border-radius: 0 0 15px 15px;
        }
        
        /* Tables */
        .table {
            color: var(--text-primary);
        }
        
        .table-hover tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .table th {
            border-color: var(--glass-border);
            color: var(--text-primary);
        }
        
        .table td {
            border-color: var(--glass-border);
            color: var(--text-primary);
        }
        
        /* Badges */
        .badge {
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 6px;
        }
        
        .bg-success {
            background: var(--primary-gradient) !important;
        }
        
        .bg-danger {
            background: var(--danger-gradient) !important;
        }
        
        .bg-warning {
            background: linear-gradient(135deg, #ed8936, #dd6b20) !important;
        }
        
        .bg-info {
            background: var(--secondary-gradient) !important;
        }
        
        .bg-secondary {
            background: linear-gradient(135deg, #a0aec0, #718096) !important;
        }
        
        .bg-primary {
            background: var(--secondary-gradient) !important;
        }
        
        /* Alerts glassmorphiques */
        .alert {
            background: rgba(26, 54, 93, 0.9);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
            color: var(--text-primary);
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            border-left: 4px solid #48bb78;
            background: rgba(72, 187, 120, 0.1);
        }
        
        .alert-danger {
            border-left: 4px solid #f56565;
            background: rgba(245, 101, 101, 0.1);
        }
        
        .alert-warning {
            border-left: 4px solid #ed8936;
            background: rgba(237, 137, 54, 0.1);
        }
        
        .alert-info {
            border-left: 4px solid #4299e1;
            background: rgba(66, 153, 225, 0.1);
        }
        
        .alert-dismissible .btn-close {
            color: var(--text-primary);
            opacity: 0.8;
        }
        
        /* Dropdown */
        .dropdown-menu {
            background: rgba(45, 55, 72, 0.95);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(15px);
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        .dropdown-item {
            color: var(--text-secondary);
            transition: all 0.2s ease;
        }
        
        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .dropdown-divider {
            border-color: var(--glass-border);
            margin: 8px 0;
        }
        
        /* Modal */
        .modal-content {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(15px);
            border-radius: 15px;
        }
        
        .modal-header {
            border-bottom: 1px solid var(--glass-border);
            background: rgba(45, 55, 72, 0.6);
            border-radius: 15px 15px 0 0;
        }
        
        .modal-title {
            color: var(--text-primary);
        }
        
        .modal-footer {
            border-top: 1px solid var(--glass-border);
            background: rgba(45, 55, 72, 0.3);
            border-radius: 0 0 15px 15px;
        }
        
        .btn-close {
            color: var(--text-primary);
            opacity: 0.8;
        }
        
        /* Pagination */
        .pagination .page-link {
            background: rgba(45, 55, 72, 0.8);
            border: 1px solid var(--glass-border);
            color: var(--text-primary);
            margin: 0 2px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        
        .pagination .page-item.active .page-link {
            background: var(--primary-gradient);
            border-color: #48bb78;
            color: white;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
        }
        
        .pagination .page-link:hover {
            background: rgba(45, 55, 72, 1);
            color: white;
            transform: translateY(-1px);
        }
        
        .pagination .page-item.disabled .page-link {
            background: rgba(45, 55, 72, 0.4);
            color: var(--text-muted);
            border-color: var(--glass-border);
        }
        
        /* Text utilities */
        .text-muted {
            color: var(--text-muted) !important;
        }
        
        .text-success {
            color: #48bb78 !important;
        }
        
        .text-danger {
            color: #f56565 !important;
        }
        
        .text-warning {
            color: #ed8936 !important;
        }
        
        .text-info {
            color: #4299e1 !important;
        }
        
        /* Links */
        a {
            color: #4299e1;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        
        a:hover {
            color: #63b3ed;
            text-decoration: none;
        }
        
        /* Progress bars */
        .progress {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            overflow: hidden;
        }
        
        .progress-bar {
            transition: width 0.6s ease;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(45, 55, 72, 0.3);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(72, 187, 120, 0.5);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(72, 187, 120, 0.7);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            .card {
                border-radius: 10px;
            }
            
            .btn {
                font-size: 0.9rem;
                padding: 8px 16px;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        
        /* Loading states */
        .loading {
            opacity: 0.7;
            pointer-events: none;
            position: relative;
        }
        
        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid rgba(72, 187, 120, 0.3);
            border-top: 2px solid #48bb78;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    
    <!-- Custom Styles -->
    @stack('styles')
</head>
<body>
    <div class="min-h-screen">
        @include('layouts.navbar')
        
        <!-- Messages Flash -->
        @if(session('success'))
            <div class="container-fluid pt-3">
                <div class="alert alert-success alert-dismissible fade show fade-in" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="container-fluid pt-3">
                <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        
        @if(session('warning'))
            <div class="container-fluid pt-3">
                <div class="alert alert-warning alert-dismissible fade show fade-in" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        
        @if(session('info'))
            <div class="container-fluid pt-3">
                <div class="alert alert-info alert-dismissible fade show fade-in" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        
        @if($errors->any())
            <div class="container-fluid pt-3">
                <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Erreurs de validation :</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        
        <!-- Page Content -->
        <main class="fade-in">
            @yield('content')
        </main>
        
        @if(View::exists('layouts.footer'))
            @include('layouts.footer')
        @endif
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    @stack('scripts')
    
    <!-- Auto-dismiss alerts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss alerts après 5 secondes
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert.alert-dismissible');
                alerts.forEach(alert => {
                    try {
                        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                        bsAlert.close();
                    } catch(e) {
                        console.log('Alert auto-dismiss error:', e);
                    }
                });
            }, 5000);
            
            // Smooth scrolling pour liens internes
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
