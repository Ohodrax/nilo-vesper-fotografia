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

    <!-- Bootstrap 5 reference - Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- AOS Animated - CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- AOS Animated - JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../css/admin-style.css">

    <title>Admin Page</title>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg nav-content bg-body-tertiary">
            <div class="container-fluid content">

                <a class="navbar-brand" data-aos="fade-down" data-aos-delay="100" onclick="renderPage(this)"><h1>Painel<span>Administrativo</span></h1></a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdm" aria-controls="navbarAdm" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-end" id="navbarAdm">
                    <ul class="navbar-nav my-3 mt-lg-0 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link mx-2 hover-underline-animation" data-aos="fade-down" data-aos-delay="150" onclick="renderPage(this)">Início</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-2 hover-underline-animation" data-aos="fade-down" data-aos-delay="200" onclick="renderPage(this)">Retratos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-2 hover-underline-animation" data-aos="fade-down" data-aos-delay="250" onclick="renderPage(this)">Personalidades</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-2 hover-underline-animation" data-aos="fade-down" data-aos-delay="300" onclick="renderPage(this)">Moda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-2 hover-underline-animation" data-aos="fade-down" data-aos-delay="350" onclick="renderPage(this)">Corporativo</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-2 hover-underline-animation" data-aos="fade-down" data-aos-delay="400" onclick="renderPage(this)">Clientes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-2 hover-underline-animation" data-aos="fade-down" data-aos-delay="450" onclick="renderPage(this)">Sobre</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-2 hover-underline-animation" data-aos="fade-down" data-aos-delay="500" onclick="renderPage(this)">Contato</a>
                        </li>
                    </ul>
                </div>

            </div>
        </nav>
    </header>

    <br>

    <section>
        <div class="container-fluid content my-5" id="root">
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_main.php"; ?>
        </div>
    </section>

    <!-- <section>
        <div class="container-fluid content">
            <h3>Início</h3>
            <ul>
                <li>Banner Carrossel</li>
                <li><ol>
                    <li>Modo de imagem</li>
                </ol></li>
                <li>Textos</li>
                <li>Banner Parallax 1 e 2</li>
            </ul>
            <h3>Retratos | Personalidades | Moda | Corporativo</h3>
            <ul>
                <li>Galeria</li>
                <li>Imagem destaque</li>
                <li>Selecionar 5 destaques no máximo</li>
            </ul>
            <h3>Clientes | Sobre</h3>
            <ul>
                <li>Texto início</li>
                <li>Imagem destaque 1</li>
                <li>Imagem destaque 2</li>
            </ul>
            <h3>Contato</h3>
            <ul>
                <li>E-mail de destaque do contato</li>
                <li>E-mail para receber contato do formulário</li>
                <li>Número de telefone de destaque do contato</li>
                <li>Número de telefone de contato do whatsapp</li>
            </ul>
            <h3>Quem pode acessar minha página Admin?</h3>
            <ul>
                <li>CRUD usuários</li>
            </ul>
        </div>
    </section> -->

    <div id="toastBox"></div>

    <!-- <div class="image-render-box"></div> -->

    <!-- Custom JS -->
    <script src="../../scripts/_admin.js"></script>
</body>
</html>