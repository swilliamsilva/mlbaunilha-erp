<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
        </main> <!-- Fecha main-content -->
    </div> <!-- Fecha d-flex -->

    <footer class="bg-dark text-white py-3 mt-auto">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-start">
                    <span class="text-muted">Versão 1.0.0</span>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">
                        © <?= date('Y') ?> MLBaunilha - Todos os direitos reservados
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts essenciais -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Ativar tooltips e popovers
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        })
    </script>
</body>
</html>
