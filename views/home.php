<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/control/c_getSiteInfo.php"; ?>

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


    <!-- Imagem Principal Home -->
    <?php    
        if (count($arrayCarrosselImages) == 0) {
            // se tiver nenhuma imagem inserida como destaque da home
            
            echo '<section class="mb-5">';
            echo    '<img src="./assets/default-image.jpg" alt="Imagem Default" class="imagem-destaque-retrato">';
            echo '</section>';

        } elseif (count($arrayCarrosselImages) == 1) {
            // se tiver apenas 1 imagem inserida como destaque da home
       
            echo '<section class="mb-5">';
            echo    '<img src="./assets/'.$reference.'/'.$arrayCarrosselImages[0].'" alt="Imagem Default" class="imagem-destaque-retrato">';
            echo '</section>';

        } elseif (count($arrayCarrosselImages) > 1) {
            // se tiver mais de 1 imagem inserida como destaque da home

            // <!-- Carressel inicial -->
            echo '<div id="carrossel-home" class="carousel slide header-image mb-5" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="5000">';
            echo    '<div class="carousel-inner">';

            for ($i=0; $i < count($arrayCarrosselImages); $i++) {

                // indicar carrossel ativo
                if ($i == 0) {

                    echo '<div class="carousel-item active">';
                    echo    '<img src="./assets/'.$reference.'/'.$arrayCarrosselImages[$i].'" class="w-100 carrossel-image" alt="Carrossel Image '.$i.'">';
                    echo '</div>';

                } else {

                    echo '<div class="carousel-item">';
                    echo     '<img src="./assets/'.$reference.'/'.$arrayCarrosselImages[$i].'" class="w-100 carrossel-image" alt="Carrossel Image '.$i.'">';
                    echo '</div>';

                }

            }
            echo    '</div>';
            echo '</div>';
        }    
    ?>

    <br>
    <br>

    <!-- Conteúdo com titulo -->
    <main class="container my-5">
        <div class="row justify-content-center gap-5" style="flex-direction: row;">

            <div class="col-12 col-md-6 col-lg-6" data-aos="fade-up-right">
                <h1 class="texto-destaque mb-4">VERDADE<br><span>&</span> PERFECCIONISMO</h1>
                <br>
                <h2 class="texto-subdestaque"><?php echo $textarea_capa; ?></h2>
            </div>

            <div class="col-12 col-md-6 col-lg-4 text-end p-0 image-div-box" data-aos="fade-up" data-aos-delay="300" style="cursor: default;">
                <?php
                    if ($capaPrincipal == 'default-image.jpg') {
                        echo '<img src="./assets/default-image.jpg" alt="Capa Principal" class="image-row">';
                    } else {
                        echo '<img src="./assets/'.$reference.'/'.$capaPrincipal.'" alt="Capa Principal" class="image-row">';
                    }
                ?>
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
                    <?php
                        if (count($arrayCarrosselRetratos) == 0) {
                            // se tiver nenhuma imagem inserida como destaque da home
                            
                            echo '<section>';
                            echo    '<img src="./assets/default-image.jpg" alt="Imagem Default" class="w-100 image-row">';
                            echo '</section>';

                        } elseif (count($arrayCarrosselRetratos) == 1) {
                            // se tiver apenas 1 imagem inserida como destaque da home
                    
                            echo '<section>';
                            echo    '<img src="./assets/pictures/'.$arrayCarrosselRetratos[0].'" alt="Imagem Default" class="w-100 image-row">';
                            echo '</section>';

                        } elseif (count($arrayCarrosselRetratos) > 1) {
                            // se tiver mais de 1 imagem inserida como destaque da home

                            // <!-- Carressel inicial -->
                            echo '<div id="carrossel-retratos" class="carousel slide carousel-fade w-100" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="4000" style="height: 450px;">';
                            echo    '<div class="carousel-inner">';

                            for ($i=0; $i < count($arrayCarrosselRetratos); $i++) {

                                // indicar carrossel ativo
                                if ($i == 0) {

                                    echo '<div class="carousel-item active">';
                                    echo    '<img src="./assets/pictures/'.$arrayCarrosselRetratos[$i].'" class="w-100 image-row" alt="Carrossel Retrato '.$i.'">';
                                    echo '</div>';

                                } else {

                                    echo '<div class="carousel-item">';
                                    echo     '<img src="./assets/pictures/'.$arrayCarrosselRetratos[$i].'" class="w-100 image-row" alt="Carrossel Retrato '.$i.'">';
                                    echo '</div>';

                                }

                            }
                            echo    '</div>';
                            echo '</div>';
                        }    
                    ?>
                    <!-- <div id="carrossel-retratos" class="carousel slide carousel-fade w-100" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="4000" style="height: 450px;">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="https://static.wixstatic.com/media/e139a4_6caebbaf7f734d1682b9ac9edfdb9e15~mv2_d_2048_1365_s_2.jpg" class="w-100 image-row" alt="Carrossel Retrato Image 1">
                            </div>
                            <div class="carousel-item">
                                <img src="https://static.wixstatic.com/media/e139a4_ef0ac8c1f1514326a3e34480abc5b9c9~mv2.jpg" class="w-100 image-row" alt="Carrossel Retrato Image 2">
                            </div>
                        </div>
                    </div> -->
                    <span class="image-hover-text">Retratos</span>
                </a>
            </div>

            <!-- DESTAQUE PERSONALIDADES -->
            <div class="col-12 col-md-6 col-lg-6 mb-3" data-aos="fade-up">
                <a class="link-hover-carrossel image-div-box" style="height: 450px;" href="/personalidades">
                    <?php
                        if (count($arrayCarrosselPersonalidades) == 0) {
                            // se tiver nenhuma imagem inserida como destaque da home
                            
                            echo '<section>';
                            echo    '<img src="./assets/default-image.jpg" alt="Imagem Default" class="w-100 image-row">';
                            echo '</section>';

                        } elseif (count($arrayCarrosselPersonalidades) == 1) {
                            // se tiver apenas 1 imagem inserida como destaque da home
                    
                            echo '<section>';
                            echo    '<img src="./assets/personalities/'.$arrayCarrosselPersonalidades[0].'" alt="Imagem Default" class="w-100 image-row">';
                            echo '</section>';

                        } elseif (count($arrayCarrosselPersonalidades) > 1) {
                            // se tiver mais de 1 imagem inserida como destaque da home

                            // <!-- Carressel inicial -->
                            echo '<div id="carrossel-personalidades" class="carousel slide carousel-fade w-100" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="4000" style="height: 450px;">';
                            echo    '<div class="carousel-inner">';

                            for ($i=0; $i < count($arrayCarrosselPersonalidades); $i++) {

                                // indicar carrossel ativo
                                if ($i == 0) {

                                    echo '<div class="carousel-item active" data-bs-interval="3500">';
                                    echo    '<img src="./assets/personalities/'.$arrayCarrosselPersonalidades[$i].'" class="w-100 image-row" alt="Carrossel Personalidade '.$i.'">';
                                    echo '</div>';

                                } else {

                                    echo '<div class="carousel-item" data-bs-interval="5000">';
                                    echo     '<img src="./assets/personalities/'.$arrayCarrosselPersonalidades[$i].'" class="w-100 image-row" alt="Carrossel Personalidade '.$i.'">';
                                    echo '</div>';

                                }

                            }
                            echo    '</div>';
                            echo '</div>';
                        }    
                    ?>
                    <span class="image-hover-text">Personalidades</span>
                </a>
            </div>

        </div>
    </section>

    <br>
    <br>

    <!-- parallax girassois -->
    <div class="content-image-1 my-5" style="background-image: url('../assets/home/<?php echo $banner1; ?>');"></div>

    <br>
    <br>

    <!-- Corporativo -->
    <section class="container my-5">
        <div class="row gap-5 justify-content-center" style="flex-direction: row;">

            <!-- DESTAQUE CORPORATIVO -->
            <div class="col-12 col-md-6 col-lg-6 mb-3" data-aos="fade-up-right">
                <a class="link-hover-carrossel image-div-box" style="height: 450px;" href="/corporativo">
                    <?php
                        if (count($arrayCarrosselCorporativo) == 0) {
                            // se tiver nenhuma imagem inserida como destaque da home
                            
                            echo '<section>';
                            echo    '<img src="./assets/default-image.jpg" alt="Imagem Default" class="w-100 image-row">';
                            echo '</section>';

                        } elseif (count($arrayCarrosselCorporativo) == 1) {
                            // se tiver apenas 1 imagem inserida como destaque da home
                    
                            echo '<section>';
                            echo    '<img src="./assets/corporate/'.$arrayCarrosselCorporativo[0].'" alt="Imagem Default" class="w-100 image-row">';
                            echo '</section>';

                        } elseif (count($arrayCarrosselCorporativo) > 1) {
                            // se tiver mais de 1 imagem inserida como destaque da home

                            // <!-- Carressel inicial -->
                            echo '<div id="carrossel-corporativo" class="carousel slide carousel-fade w-100" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="4000" style="height: 450px;">';
                            echo    '<div class="carousel-inner">';

                            for ($i=0; $i < count($arrayCarrosselCorporativo); $i++) {

                                // indicar carrossel ativo
                                if ($i == 0) {

                                    echo '<div class="carousel-item active" data-bs-interval="3500">';
                                    echo    '<img src="./assets/corporate/'.$arrayCarrosselCorporativo[$i].'" class="w-100 image-row" alt="Carrossel Corporativo '.$i.'">';
                                    echo '</div>';

                                } else {

                                    echo '<div class="carousel-item" data-bs-interval="5000">';
                                    echo     '<img src="./assets/corporate/'.$arrayCarrosselCorporativo[$i].'" class="w-100 image-row" alt="Carrossel Corporativo '.$i.'">';
                                    echo '</div>';

                                }

                            }
                            echo    '</div>';
                            echo '</div>';
                        }    
                    ?>
                    <span class="image-hover-text">Corporativo</span>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-4"></div>

        </div>
    </section>

    <br>
    <br>

    <!-- parallax modelo -->
    <div class="content-image-2 my-5" style="background-image: url('../assets/home/<?php echo $banner2; ?>');"></div>
    
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
                    <?php
                        if (count($arrayCarrosselModa) == 0) {
                            // se tiver nenhuma imagem inserida como destaque da home
                            
                            echo '<section>';
                            echo    '<img src="./assets/default-image.jpg" style="min-height: 700px;" alt="Imagem Default" class="w-100 image-row">';
                            echo '</section>';

                        } elseif (count($arrayCarrosselModa) == 1) {
                            // se tiver apenas 1 imagem inserida como destaque da home
                    
                            echo '<section>';
                            echo    '<img src="./assets/fashion/'.$arrayCarrosselModa[0].'" style="min-height: 700px;" alt="Imagem Default" class="w-100 image-row">';
                            echo '</section>';

                        } elseif (count($arrayCarrosselModa) > 1) {
                            // se tiver mais de 1 imagem inserida como destaque da home

                            // <!-- Carressel inicial -->
                            echo '<div id="carrossel-moda" class="carousel slide carousel-fade w-100" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="4000" style="height: 700px;">';
                            echo    '<div class="carousel-inner">';

                            for ($i=0; $i < count($arrayCarrosselModa); $i++) {

                                // indicar carrossel ativo
                                if ($i == 0) {

                                    echo '<div class="carousel-item active" data-bs-interval="3500">';
                                    echo    '<img src="./assets/fashion/'.$arrayCarrosselModa[$i].'" style="min-height: 700px;" class="w-100 image-row" alt="Carrossel Moda '.$i.'">';
                                    echo '</div>';

                                } else {

                                    echo '<div class="carousel-item" data-bs-interval="5000">';
                                    echo     '<img src="./assets/fashion/'.$arrayCarrosselModa[$i].'" style="min-height: 700px;" class="w-100 image-row" alt="Carrossel Moda '.$i.'">';
                                    echo '</div>';

                                }

                            }
                            echo    '</div>';
                            echo '</div>';
                        }    
                    ?>
                    <span class="image-hover-text">Moda</span>
                </a>
            </div>

        </div>
    </section>

    <br>
    <br>

    <!-- Formulario de contato e links -->
    <?php require_once 'ajax/a_renderContact_form.php'; ?>

    <br>
    
    <!-- Offcanvas menu -->
    <?php require_once 'components/_menu-offcanvas.html'; ?>
    
    <script src="./scripts/_main.js"></script>

</body>
</html>