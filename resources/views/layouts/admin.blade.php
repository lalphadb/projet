<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Studios Unis - Administration')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Styles de base critiques (intégrés pour un chargement immédiat) -->
    <style>
        /* Styles minimaux pour une transition fluide */
        html { 
            background-color: #343a40; 
        }
        body {
            opacity: 0;
            transition: opacity 0.4s ease-in;
            background-color: #343a40;
        }
        body.ready {
            opacity: 1;
        }
        .content-wrapper {
            background-color: #343a40;
        }
        /* Animation subtile de transition */
        .fade-in {
            animation: fadeIn 0.4s ease-in forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Préchargement des CSS critiques -->
    <link rel="preload" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" as="style">
    <link rel="preload" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}" as="style">
    <link rel="preload" href="{{ asset('css/style.css') }}" as="style">
    
    <!-- CSS Vendor (AdminLTE) -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    
    <!-- Styles personnalisés - Ajout d'un timestamp côté serveur -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/navbar-footer.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/member-profile.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/member-forms.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/members-list.css') }}?v={{ time() }}">
    

    <!-- Styles supplémentaires de la page -->
    @stack('styles')
    
    <!-- Script de chargement progressif -->
    <script>
        // Marquer le corps comme prêt quand tout est chargé
        window.addEventListener('load', function() {
            document.body.classList.add('ready');
            
            // Animation subtile des cartes statistiques
            const statCards = document.querySelectorAll('.stat-card-modern');
            if (statCards.length > 0) {
                statCards.forEach(function(card, index) {
                    setTimeout(function() {
                        card.classList.add('fade-in');
                    }, index * 100); // Animation séquentielle avec délai
                });
            }
        });
    </script>
</head>
<body class="hold-transition sidebar-mini layout-fixed dark-mode">
    <div class="wrapper">
        <!-- Navbar -->
        @include('layouts.navbar')
        
        <!-- Sidebar -->
        @include('layouts.sidebar')
        
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Messages flash -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-3 mt-3">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Erreurs de validation :</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
            @endif
            
           <!-- Page Content -->
		@yield('content')
        </div>
        
        <!-- Footer -->
        @include('layouts.footer')
        
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
    </div>

    <!-- Scripts Vendor -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script src="{{ asset('js/navbar-footer.js') }}"></script>
    
    <!-- Scripts personnalisés -->
    <script>
    $(document).ready(function() {
        // Auto-hide des alertes après 5 secondes
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
        
        // Initialisation des tooltips
        $('[data-toggle="tooltip"]').tooltip();
        
        // Initialisation des popovers
        $('[data-toggle="popover"]').popover();
        
        // Animation pour les boutons
        $('.btn').on('click', function() {
            $(this).addClass('btn-loading');
            setTimeout(() => {
                $(this).removeClass('btn-loading');
            }, 1000);
        });
        
        // Confirmation pour les suppressions
        $('form[data-confirm]').on('submit', function(e) {
            const message = $(this).data('confirm') || 'Êtes-vous sûr de vouloir effectuer cette action ?';
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
        });
        
        // Amélioration UX pour les formulaires
        $('.form-control').on('focus', function() {
            $(this).parent().addClass('focused');
        }).on('blur', function() {
            $(this).parent().removeClass('focused');
        });
        
        // Correction automatique du footer
        function adjustFooterPosition() {
            const contentWrapper = $('.content-wrapper');
            const footer = $('.main-footer');
            const windowHeight = $(window).height();
            const contentHeight = contentWrapper.outerHeight();
            const footerHeight = footer.outerHeight();
            
            // S'assurer que le contenu a assez d'espace
            const minPadding = footerHeight + 50;
            if (contentWrapper.css('padding-bottom').replace('px', '') < minPadding) {
                contentWrapper.css('padding-bottom', minPadding + 'px');
            }
        }
        
        // Ajuster au chargement et redimensionnement
        adjustFooterPosition();
        $(window).on('resize', adjustFooterPosition);
    });

    // Fonction utilitaire pour les notifications
    function showNotification(message, type = 'success') {
        const alertClass = `alert-${type}`;
        const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const alert = $(`
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <i class="fas ${iconClass} mr-2"></i>
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
        `);
        
        $('body').append(alert);
        
        setTimeout(() => {
            alert.fadeOut('slow', function() {
                $(this).remove();
            });
        }, 4000);
    }
    </script>

    <!-- Scripts supplémentaires de la page -->
    @stack('scripts')
    
</body>
</html>
