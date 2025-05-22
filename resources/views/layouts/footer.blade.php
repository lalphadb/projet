<footer>
    <div class="footer-content">
        <!-- Liens utiles -->
        <div class="footer-links">
            <a href="{{ route('politique') }}" class="footer-link">
                <i class="fas fa-shield-alt me-1"></i>
                Politique de confidentialité
            </a>
            
            <a href="#" class="footer-link">
                <i class="fas fa-file-contract me-1"></i>
                Conditions d'utilisation
            </a>
            
            <a href="#" class="footer-link">
                <i class="fas fa-life-ring me-1"></i>
                Support
            </a>
            
            <a href="#" class="footer-link">
                <i class="fas fa-info-circle me-1"></i>
                À propos
            </a>
        </div>
        
        <!-- Copyright -->
        <div class="footer-copyright">
            <span>&copy; {{ date('Y') }} </span>
            <a href="https://4lb.ca" class="footer-brand" target="_blank">4lb.ca</a>
            <span> — </span>
            <strong>StudiosUnisDB</strong>
            <span> | Tous droits réservés</span>
        </div>
        
        <!-- Information version -->
        <div class="footer-copyright">
            <small>
                <i class="fas fa-code me-1"></i>
                Version 1.0.0 | 
                <i class="fas fa-clock me-1"></i>
                Dernière mise à jour: {{ date('d/m/Y') }}
            </small>
        </div>
    </div>
</footer>
