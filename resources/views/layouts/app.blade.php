<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hello World')</title>
    
    <!-- Lien vers le CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optionnel : Ajoute ton propre CSS personnalisé ici -->
    @yield('styles')
</head>
<body>
    <!-- Toasts Bootstrap -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
        @if (session('success'))
            <div id="successToast" class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
                </div>
            </div>
        @endif
    
        @if (session('error'))
            <div id="errorToast" class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fermer"></button>
                </div>
            </div>
        @endif
    </div>

    <div class="container">
        @yield('content')  <!-- Contenu spécifique à chaque page -->
    </div>

    <script>
        setTimeout(() => {
            const successToast = document.getElementById('successToast');
            const errorToast = document.getElementById('errorToast');
    
            if (successToast) {
                const toast = bootstrap.Toast.getOrCreateInstance(successToast);
                toast.hide();
            }
    
            if (errorToast) {
                const toast = bootstrap.Toast.getOrCreateInstance(errorToast);
                toast.hide();
            }
        }, 3000); // 3 secondes
    </script>
    

    <!-- Lien vers le JS de Bootstrap (avant </body>) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
