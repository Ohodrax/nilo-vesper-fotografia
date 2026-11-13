<?php
require_once dirname(__DIR__) . '/bin/_environment.php';
require_admin(false);

    echo "<h1 class='my-5 texto-destaque text-center text-md-start'><span>Clientes</span></h1>";

?>

<br>

<section class="content my-5">
    <div class="row justify-content-between">
        <div class="col-12 col-lg-6">
            <h1 class="texto-destaque mb-4 text-center text-md-end">PARCEIROS<br><span> DE HISTÓRIA</span></h1>
            <div class="row justify-content-lg-end">
                <div class="col-12 col-lg-10 mb-5">
                    <textarea class="form-control person-form-control person-form-textarea mb-4 text-end" rows="10" name="textarea_clients_capa" onfocusout="updateTextParam(this)"><?php echo h($textareaCapa); ?></textarea>
                </div>
            </div>
            <div class="d-flex flex-row justify-content-center w-100 mt-3 mb-5">
                <img src="../../assets/parceiros.png" alt="Image Parceiros" class="img-fluid">
            </div>
        </div>
        <div class="col-12 col-lg-5">
            <div class="set-single-image">
                <input type="file" oninput="uploadImage(this, 'clients', 'capa_principal')" accept="image/png, image/jpg, image/jpeg">

                <?php
                    echo "<img src='../../assets/".h($capaImage)."' alt='Image'>";
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
                <input type="file" oninput="uploadImage(this, 'clients', 'banner_1')" accept="image/png, image/jpg, image/jpeg">

                <?php
                    echo "<img src='../../assets/".h($banner1)."' alt='Image'>";
                ?>

                <div class="new-banner-box">
                    <i class="bi bi-camera"></i>
                    <span>Nova imagem</span>
                </div>
            </div>
        </div>
    </div>
</section>