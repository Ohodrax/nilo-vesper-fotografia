<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/control/c_getSiteInfo.php"; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta name="csrf-token" content="<?php echo h(csrf_token()); ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap 5 reference - CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    
    <!-- Bootstrap 5 reference - JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>

    <!-- AOS Animated - CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- AOS Animated - JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="./assets/favicon.ico?v=lens-1">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./css/style.css">

    <title>Nilo Vesper Fotografia</title>

</head>
<body>
    <div class="header">
        <header>
            <div class="offcanvas-header-nav fixed-top offcanvas-header-nav-scrolled">
                <nav class="container navbar align-items-center px-3" style="padding: 35px 0;transition: all 0.3s;">
                    <div data-aos="fade-down">
                        <a href="/" style="text-decoration: none;">
                            <h1 style="color: var(--light-gray);font-weight: 400;transition: all 0.3s;margin: 0;">NILO<span style="font-weight: 700;">VESPER</span>FOTOGRAFIA</h1>
                        </a>
                    </div>
    
                    <div class="navbar-toggler" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation" data-aos="fade-down">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="var(--light-gray)" stroke="var(--light-gray)" width="32" height="32" stroke-width="1px" class="bi bi-list" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                        </svg>
                    </div>
                </nav>
            </div>
        </header>
    </div>

    <!-- Formulario de contato e links -->
    <?php include 'ajax/a_renderContact_form.php'; ?>
    
    <!-- Offcanvas menu -->
    <?php include 'components/_menu-offcanvas.html'; ?>
    
    <script src="./scripts/_main.js"></script>
</body>
</html>