<?php

    echo "<h1 class='my-5 texto-destaque text-center text-md-start'><span>Sobre</span></h1>";

?>

<br>

<section class="content my-5">
    <div class="row justify-content-between">
        <div class="col-12 col-lg-6">
            <h1 class="texto-destaque mb-4 text-center text-md-end">POR TRÁS<br><span> DAS LENTES</span></h1>
            <div class="row justify-content-lg-end">
                <div class="col-12 col-lg-10">
                    <textarea class="form-control person-form-control person-form-textarea mb-4 text-end" rows="10" name="textarea_about_capa" onfocusout="updateTextParam(this)"><?php echo $textareaCapa; ?></textarea>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-5">
            <div class="set-single-image">
                <input type="file" oninput="uploadImage(this, 'about', 'capa_principal')" accept="image/png, image/jpg, image/jpeg">
                
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
    
<section class="content my-5">
    <div class="row">
        <div class="col-12">
            <h1 class="texto-destaque mb-4 text-center text-md-start"><span>BANNER</span> PARALLAX <span>1</span></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="set-parallax-image">
                <input type="file" oninput="uploadImage(this, 'about', 'banner_1')" accept="image/png, image/jpg, image/jpeg">
                
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