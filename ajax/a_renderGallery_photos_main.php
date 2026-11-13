<?php
require_once dirname(__DIR__) . '/bin/_environment.php';
require_admin(false);

    require_once $_SERVER['DOCUMENT_ROOT'] . "/control/c_renderGallery_photos_main.php";

    if (count($ret_selecGalleryImages) == 0) {
        echo "<div class='col-12'>";
        echo    "<h1 class='text-center my-5'>Nenhuma imagem nesta galeria</h1>";
        echo "</div";
    } else {
        foreach ($ret_selecGalleryImages as $key => $value) {
            $destaqueIcon = boolval($value['destaque_carrossel']) ? "bi-star-fill" : "bi-star";


            echo '<div class="col-12 col-md-auto mb-3">';
            echo    '<div class="gallery-image-box">';
            echo        '<img src="../../assets/'.$reference.'/'.h($value['nome']).'" alt="'.h($value['localizacao'])."_".h($value['nome']).'">';
            echo        '<div class="gallery-image-options-box">';
            echo            '<span onclick="setDestaque('.$value['id_image'].', '."'".$reference."'".')"><i class="bi '.$destaqueIcon.'"></i></span>';
            echo            '<span onclick="deleteImage('.$value['id_image'].', '."'".$reference."'".')"><i class="bi bi-trash"></i></span>';
            echo        '</div>';
            echo    '</div>';
            echo '</div>';
        }
    }

?>