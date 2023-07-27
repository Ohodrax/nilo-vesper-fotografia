<!DOCTYPE html>
<html lang="pt-BR">
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
    <!-- menu header -->
    <?php include "./views/components/_header.html"; ?>

    <!-- Carressel inicial -->
    <div id="carrossel-home" class="carousel slide header-image mb-5" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="5000">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="./assets/carrossel-home-1.jpg" class="w-100 carrossel-image" alt="Carrossel Image 1">
            </div>
            <div class="carousel-item">
                <img src="./assets/carrossel-home-2.jpg" class="w-100 carrossel-image" alt="Carrossel Image 2">
            </div>
            <div class="carousel-item">
                <img src="./assets/carrossel-home-3.jpg" class="w-100 carrossel-image" alt="Carrossel Image 3">
            </div>
            <div class="carousel-item">
                <img src="./assets/carrossel-home-4.jpg" class="w-100 carrossel-image" alt="Carrossel Image 4">
            </div>
        </div>
    </div>

    <br>
    <br>

    <!-- Conteúdo com titulo -->
    <main class="container my-5">
        <div class="row justify-content-center gap-5" style="flex-direction: row;">

            <div class="col-12 col-md-6 col-lg-6" data-aos="fade-up-right">
                <h1 class="texto-destaque mb-4">VERDADE<br><span>&</span> PERFECCIONISMO</h1>
                <br>
                <h2 class="texto-subdestaque">30 ANOS DE CARREIRA<br>DEDICADOS A ARTE</h2>
            </div>

            <div class="col-12 col-md-6 col-lg-4 text-end p-0 image-div-box" data-aos="fade-up" data-aos-delay="300" style="cursor: default;">
                <img src="https://static.stealthelook.com.br/wp-content/uploads/2017/06/gal-gadot-maquiagem-batom-vermelho-bronzer-170620-042611.jpg" alt="woman-image" class="image-row">
            </div>

        </div>
    </main>

    <br>
    <br>

    <!-- Retratos e personalidades -->
    <section class="container my-5">
        <div class="row justify-content-center gap-5" style="flex-direction: row;">
            
            <!-- DESTAQUE RETRATOS -->
            <div class="col-12 col-md-6 col-lg-4 mb-3" data-aos="fade-up-right">
                <a class="link-hover-carrossel image-div-box" style="height: 450px;" href="/retratos">
                    <div id="carrossel-retratos" class="carousel slide carousel-fade w-100" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="4000" style="height: 450px;">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="https://static.wixstatic.com/media/e139a4_6caebbaf7f734d1682b9ac9edfdb9e15~mv2_d_2048_1365_s_2.jpg" class="w-100 image-row" alt="Carrossel Retrato Image 1">
                            </div>
                            <div class="carousel-item">
                                <img src="https://static.wixstatic.com/media/e139a4_ef0ac8c1f1514326a3e34480abc5b9c9~mv2.jpg" class="w-100 image-row" alt="Carrossel Retrato Image 2">
                            </div>
                        </div>
                    </div>
                    <span class="image-hover-text">Retratos</span>
                </a>
            </div>

            <!-- DESTAQUE PERSONALIDADES -->
            <div class="col-12 col-md-6 col-lg-6 mb-3" data-aos="fade-up">
                <a class="link-hover-carrossel image-div-box" style="height: 450px;" href="/personalidades">
                    <div id="carrossel-personalidades" class="carousel slide carousel-fade w-100" data-bs-ride="carousel" data-bs-pause="false" style="height: 450px;">
                        <div class="carousel-inner">
                            <div class="carousel-item active" data-bs-interval="3500">
                                <img src="https://static.wixstatic.com/media/e139a4_fec6f5c4de024900a41c24ae81a5eace~mv2.jpg" class="w-100 image-row" alt="Carrossel Personalidade Image 1">
                            </div>
                            <div class="carousel-item" data-bs-interval="5000">
                                <img src="https://static.wixstatic.com/media/e139a4_cb4589d48c4e4a4bafc9d01cb569dc39~mv2.jpg" class="w-100 image-row" alt="Carrossel Personalidade Image 2">
                            </div>
                        </div>
                    </div>
                    <span class="image-hover-text">Personalidades</span>
                </a>
            </div>

        </div>
    </section>

    <br>
    <br>

    <!-- parallax girassois -->
    <div class="content-image-1 my-5"></div>

    <br>
    <br>

    <!-- Corporativo -->
    <section class="container my-5">
        <div class="row gap-5 justify-content-center" style="flex-direction: row;">

            <!-- DESTAQUE CORPORATIVO -->
            <div class="col-12 col-md-6 col-lg-6 mb-3" data-aos="fade-up-right">
                <a class="link-hover-carrossel image-div-box" style="height: 450px;" href="/corporativo">
                    <div id="carrossel-corporativo" class="carousel slide carousel-fade w-100" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="5000" style="height: 450px;">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="https://static.wixstatic.com/media/e139a4_b84d0081a8344e0fab9a17233b6a3aa3~mv2.jpg" class="w-100 image-row" alt="Carrossel Corporativo Image 1">
                            </div>
                            <div class="carousel-item">
                                <img src="https://static.wixstatic.com/media/e139a4_cc0f5cbe77754c05a1a63d6e3eac9ae3~mv2.jpg" class="w-100 image-row" alt="Carrossel Corporativo Image 2">
                            </div>
                        </div>
                    </div>
                    <span class="image-hover-text">Corporativo</span>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-4"></div>

        </div>
    </section>

    <br>
    <br>

    <!-- parallax modelo -->
    <div class="content-image-2 my-5"></div>
    
    <br>
    <br>

    <!-- Clientes e moda -->
    <section class="container my-5">
        <div class="row gap-5 justify-content-center">

            <div class="col-12 col-md-7 col-lg-6" data-aos="fade-up-right">
                <a class="link-hover-carrossel image-div-box" style="height: 350px;" href="/clientes">
                    <div id="carrossel-corporativo" class="carousel slide carousel-fade w-100" data-bs-ride="carousel" data-bs-pause="false">
                        <div class="carousel-inner">
                            <div class="carousel-item active" data-bs-interval="2500">
                                <img src="https://static.wixstatic.com/media/e139a4_67021da088a647c88a658ac2ed8e9474~mv2_d_2048_1365_s_2.jpg" class="w-100 image-row" alt="Carrossel Clientes Image 1">
                            </div>
                            <div class="carousel-item" data-bs-interval="5000">
                                <img src="https://static.wixstatic.com/media/e139a4_8fe5954414ee4281bad13f9cf9799bfb~mv2.jpg" class="w-100 image-row" alt="Carrossel Clientes Image 2">
                            </div>
                            <div class="carousel-item" data-bs-interval="2500">
                                <img src="https://static.wixstatic.com/media/e139a4_d38b9ab9f56341f889953bc6e6fc0c83~mv2.jpg" class="w-100 image-row" alt="Carrossel Clientes Image 3">
                            </div>
                        </div>
                    </div>
                    <span class="image-hover-text">Clientes</span>
                </a>
            </div>

            <div class="col-12 col-md-5 col-lg-4" data-aos="fade-up">
                <a class="link-hover-carrossel image-div-box" style="height: 700px;" href="/moda">
                    <div id="carrossel-corporativo" class="carousel slide carousel-fade w-100" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="5000" style="height: 700px;">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="https://static.wixstatic.com/media/e139a4_d1d126b24011496e9a6d1800965f75a7~mv2.jpg" style="min-height: 700px;" class="w-100 image-row" alt="Carrossel Moda Image 1">
                            </div>
                            <div class="carousel-item">
                                <img src="https://static.wixstatic.com/media/e139a4_3dd8a1740905465ebf09a327dd72414f~mv2.jpg" style="min-height: 700px;" class="w-100 image-row" alt="Carrossel Moda Image 2">
                            </div>
                            <div class="carousel-item">
                                <img src="https://static.wixstatic.com/media/e139a4_23c5c64b9b8747a7a156de2c9540d616~mv2.jpg" style="min-height: 700px;" class="w-100 image-row" alt="Carrossel Moda Image 3">
                            </div>
                        </div>
                    </div>
                    <span class="image-hover-text">Moda</span>
                </a>
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