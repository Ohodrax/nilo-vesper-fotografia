<?php

    echo "<h1 class='my-5 texto-destaque text-center text-md-start'><span>".ucfirst($referencePage)."</span></h1>";

?>

<br>
<br>

<section class="content my-5">
    <div class="row">
        <div class="col-12">
            <h1 class="texto-destaque mb-4 text-center text-md-start"><span>BANNER</span> PARALLAX <span>1</span></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="set-parallax-image">
                <input type="file" oninput="uploadImage(this, '<?php echo $reference; ?>', 'banner_1')" accept="image/png, image/jpg, image/jpeg">

                <?php
                    echo "<img src='../../assets/".$banner1."' alt='Image'>";
                ?>

                <div class="new-banner-box">
                    <i class="bi bi-camera"></i>
                    <span>Nova imagem</span>
                </div>
            </div>
        </div>
    </div>
</section>

<br>
<br>

<section class="content my-5">
    <div class="row justify-content-between">
        <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center justify-content-md-end">
            <h1 class="texto-destaque text-center text-md-end"><span>CAPA</span> <?php echo strtoupper($referencePage); ?></h1>
        </div>
        <div class="col-12 col-lg-5">
            <div class="set-single-image">
                <input type="file" oninput="uploadImage(this, '<?php echo $reference; ?>', 'capa_principal')" accept="image/png, image/jpg, image/jpeg">
                
                <?php
                    echo "<img src='../../assets/".$capaImage."' alt='Image'>";
                ?>

                <div class="new-banner-box">
                    <i class="bi bi-camera"></i>
                    <span>Nova imagem</span>
                </div>
            </div>
        </div>
    </div>
</section>

<br>
<br>

<section>
    <div class="container-fluid content my-5">
        <h1 class="texto-destaque text-center text-md-start mb-3"><span>GALERIA DE</span> <?php echo strtoupper($referencePage); ?></h1>
        <br>
        <div class="row">
            <div class="col-12">
                <div class="add-image-gallery w-100 p-5">
                    <input type="file" oninput="uploadImage(this, '<?php echo $reference; ?>', 'gallery')" multiple accept="image/png, image/jpg, image/jpeg">

                    <div class="info-add-image-gallery">
                        <i class="bi bi-camera"></i>
                        <span>Insira novas imagens arrastando-as para este espaço ou selecione</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container-fluid content my-5">
        <div class="row flex-row justify-content-start gap-3">
            <?php include $_SERVER['DOCUMENT_ROOT'] . "/ajax/a_renderGallery_photos_main.php"; ?>
        </div>
    </div>
</section>