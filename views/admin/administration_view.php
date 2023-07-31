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
    <link rel="stylesheet" href="../../css/admin-style.css">

    <title>Admin Page</title>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg nav-content bg-body-tertiary">
            <div class="container-fluid content ">

                <a class="navbar-brand" href="#" data-aos="fade-down" data-aos-delay="100"><h1>Painel<span>Administrativo</span></h1></a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdm" aria-controls="navbarAdm" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-end" id="navbarAdm">
                    <ul class="navbar-nav my-3 mt-lg-0 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link hover-underline-animation" aria-current="page" href="#" data-aos="fade-down" data-aos-delay="150">Início</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link hover-underline-animation" href="#" data-aos="fade-down" data-aos-delay="200">Retratos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link hover-underline-animation" href="#" data-aos="fade-down" data-aos-delay="250">Personalidades</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link hover-underline-animation" href="#" data-aos="fade-down" data-aos-delay="300">Moda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link hover-underline-animation" href="#" data-aos="fade-down" data-aos-delay="350">Corporativo</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link hover-underline-animation" href="#" data-aos="fade-down" data-aos-delay="400">Clientes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link hover-underline-animation" href="#" data-aos="fade-down" data-aos-delay="450">Sobre</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link hover-underline-animation" href="#" data-aos="fade-down" data-aos-delay="500">Contato</a>
                        </li>
                    </ul>
                </div>

            </div>
        </nav>
    </header>

    <section class="content" id="root">
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderAdmin_main.php"; ?>
    </section>

    <section class="content">
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
    </section>

    <div class="toast align-items-center text-bg-primary border-0" role="alert" id="toastNotification">
  <div class="d-flex">
    <div class="toast-body">
      Hello, world! This is a toast message.
    </div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
</div>

    <!-- Custom JS -->
    <script src="../../scripts/_admin.js"></script>
</body>
</html>