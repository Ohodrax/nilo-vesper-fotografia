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
    <link rel="icon" type="image/x-icon" href="./assets/favicon.ico">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./css/style.css">

    <title>Paulo Bareta Fotografia</title>

</head>
<body>
    <!-- menu header -->
    <?php include "./views/components/_header.html"; ?>

    <section class="mb-5">
        <?php
            if ($banner1 == 'default-image.jpg') {
                echo '<img src="./assets/default-image.jpg" alt="Banner Destaque" class="banner-destaque">';
            } else {
                echo '<img src="./assets/'.$reference.'/'.h($banner1).'" alt="Banner Destaque" class="banner-destaque">';
            }
        ?>
    </section>

    <br>
    <br>

    <section class="container my-5">
        <div class="row gap-5 justify-content-center align-items-center flex-row">

            <div class="col-12 col-md-6 col-lg-6 p-0" data-aos="fade-up-right" data-aos-delay="300">
                <h1 class="texto-destaque mb-4">RETRATOS</h1>
            </div>

            <div class="col-12 col-md-6 col-lg-4 text-end image-retrato-box" style="cursor: default;height: 450px;" data-aos="fade-up" data-aos-delay="400">
                <?php
                    if ($capaPrincipal == 'default-image.jpg') {
                        echo '<img src="./assets/default-image.jpg" alt="Capa Principal" class="image-retrato">';
                    } else {
                        echo '<img src="./assets/'.$reference.'/'.h($capaPrincipal).'" alt="Capa Principal" class="image-retrato">';
                    }
                ?>
            </div>

        </div>
    </section>

    <br>
    <br>

    <div id="gallery-box">
        <?php echo "<input type='hidden' id='referenceRef' value='".$reference."'>"; include "./ajax/a_renderSite_galleryImages.php"; ?>
    </div>

    <!-- Formulario de contato e links -->
    <?php include 'ajax/a_renderContact_form.php'; ?>

    <br>
    
    <!-- Offcanvas menu -->
    <?php include 'components/_menu-offcanvas.html'; ?>
    
    <script src="./scripts/_main.js"></script>
</body>
</html>