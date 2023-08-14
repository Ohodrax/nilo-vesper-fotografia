<?php

    echo "<h1 class='my-5 texto-destaque text-center text-md-start'><span>Início</span></h1>";
?>

<br>

<section class="content my-5">
    <div class="row">
        <div class="col-12">
            <h1 class="texto-destaque mb-4 text-start"><span>MODO DE</span> EXIBIÇÃO: <span><?php echo $printMode; ?></span></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-3 d-flex justify-content-start align-items-center mb-3">
            <div class="new-image-box">

                <input type="file" oninput="uploadImage(this, 'home', 'carrossel_principal')" multiple accept="image/png, image/jpg, image/jpeg">

                <div class="new-image-content-box">
                    <i class="bi bi-camera"></i>
                    <span>Insira novas imagens aqui</span>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-9 mb-3">
            <div style="width: 100%;height: 100%;position: relative;">
                <div class="arrow arrow-left" onclick="arrowSlide(`left`)">
                    <i class="bi bi-chevron-left"></i>
                </div>
                
                <div class="image-render-box" id="image-render-box">
                    <?php

                        if ($numCarrosselImages == 0) {
                            echo "<h1 class='text-center'>Sem imagens</h1>";
                        } else {
                            for ($i=0; $i < count($ret_selecImages); $i++) { 
                                if ($ret_selecImages[$i]['localizacao'] == "carrossel_principal") {
                                    echo '<div class="single-image-box">';
                                    echo    '<img src="../../assets/home/' . $ret_selecImages[$i]['nome'] . '" alt="Image">';
                                    echo    '<span onclick="deleteImage('.$ret_selecImages[$i]['id_image'].')"><i class="bi bi-trash"></i></span>';
                                    echo '</div>';
                                }
                            }
                        }
                    
                    ?>
                </div>
                
                <div class="arrow arrow-right" onclick="arrowSlide(`right`)">
                    <i class="bi bi-chevron-right"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<br>
<br>

<section class="content my-5">
    <div class="row justify-content-between">
        <div class="col-12 col-lg-6">
            <h1 class="texto-destaque mb-4 text-end">VERDADE<br><span>&</span> PERFECCIONISMO</h1>
            <div class="row justify-content-lg-end">
                <div class="col-12 col-lg-10">
                    <textarea class="form-control person-form-control person-form-textarea mb-4 text-end" rows="10" name="textarea_capa" onfocusout="updateTextParam(this)"><?php echo $textareaCapa; ?></textarea>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-5">
            <div class="set-single-image">
                <input type="file" oninput="uploadImage(this, 'home', 'capa_principal')" accept="image/png, image/jpg, image/jpeg">

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
            <h1 class="texto-destaque mb-4 text-start"><span>BANNER</span> PARALLAX <span>1</span></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="set-parallax-image">

                <input type="file" oninput="uploadImage(this, 'home', 'banner_1')" accept="image/png, image/jpg, image/jpeg">

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
    <div class="row">
        <div class="col-12">
            <h1 class="texto-destaque mb-4 text-start"><span>BANNER</span> PARALLAX <span>2</span></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="set-parallax-image">

                <input type="file" oninput="uploadImage(this, 'home', 'banner_2')" accept="image/png, image/jpg, image/jpeg">

                <?php
                    echo "<img src='../../assets/".$banner2."' alt='Image'>";
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
