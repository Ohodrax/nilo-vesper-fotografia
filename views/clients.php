<!DOCTYPE html>
<html lang="en">
<head>
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

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./css/style.css">

    <title>Paulo Bareta Fotografia</title>

</head>
<body>
    <div class="header">
        <header>
            <div class="offcanvas-header-nav fixed-top offcanvas-header-nav-scrolled">
                <nav class="container navbar align-items-center px-3" style="padding: 35px 0;transition: all 0.3s;">
                    <div data-aos="fade-down">
                        <a href="/" style="text-decoration: none;">
                            <h1 style="color: var(--light-gray);font-weight: 400;transition: all 0.3s;margin: 0;">PAULO<span style="font-weight: 700;">BARETA</span>FOTOGRAFIA</h1>
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

    <main class="container mb-5" style="margin-top: 120px">
        <div class="row justify-content-center align-items-top gap-5 flex-row">
            <div class="col-12 col-md-6 col-lg-5">
                <h1 class="texto-destaque mb-4" data-aos="fade-up-right" data-aos-delay="200">PARCEIROS<br><span>DE HISTÓRIA</span></h1>
                
                <br>

                <p class="text-end fs-4" data-aos="fade-up-right" data-aos-delay="300">
                    proident qui ut Excepteur aliqua ullamco in deserunt ad qui veniam veniam minim id est proident. proident tempor id in proident do sed ipsum voluptate sint in eiusmod est reprehenderit aute esse occaecat ut velit. cupidatat ut ipsum exercitation enim amet incididunt amet laborum veniam proident incididunt ullamco reprehenderit commodo amet.
                    <br><br>
                    mollit eu culpa lorem eu adipiscing in magna elit aliquip non veniam veniam aliquip sed ex id. eu nisi adipiscing reprehenderit labore quis amet esse consectetur do cupidatat labore reprehenderit proident officia ipsum laborum. enim ut pariatur enim deserunt nulla sed in quis ut irure minim id officia occaecat aliquip sint velit ex.
                    <br><br>
                    ut est consequat occaecat enim qui voluptate pariatur officia aliqua fugiat do cupidatat nisi culpa in ut elit commodo. ex laboris aute veniam officia commodo exercitation dolore non fugiat dolor officia officia deserunt ad quis do.
                </p>

                <br>

                <div class="d-flex flex-row justify-content-center w-100 mt-3" data-aos="fade-up-right" data-aos-delay="400">
                    <img src="./assets/parceiros.png" alt="Image Parceiros" class="img-fluid">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-5 text-end p-0 image-div-box image-content-parceiros" data-aos="fade-up" data-aos-delay="300">
                <img src="https://static.wixstatic.com/media/e139a4_3b61b5690c78465c86494dfb5e52e24c~mv2.jpg" alt="Modelo na Praia" class="image-row mt-2">
            </div>
        </div>
    </main>

    <br>
    <br>

    <section>
        <div class="row align-items-center mx-0 mt-5 gap-5 flex-row w-100">
            <div class="col-12 col-md-8 col-lg-7 p-0" data-aos="fade-right" data-aos-delay="200">
                <img src="https://static.wixstatic.com/media/e139a4_7fb5c08e3fb147dc974853abd82f0c57~mv2_d_5213_3475_s_4_2.jpg" alt="Banner parceiros" class="content-client-image-1">
            </div>
        </div>
    </section>

    <br>
    <br>

    <!-- Formulario de contato e links -->
    <?php include 'ajax/a_renderContact_form.php'; ?>

    <br>
    
    <!-- Offcanvas menu -->
    <?php include 'components/_menu-offcanvas.html'; ?>
    
    <script src="./scripts/_main.js"></script>
</body>
</html>