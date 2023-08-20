<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/control/c_renderSite_gellery_getImages.php";
    
    if (count($ret_selectImages) > 0) {
        echo '<div class="gallery-grid">';
        
        if (count($ret_selectImages) >= 1) {
            echo '<section class="w-100 my-5">';
            echo    '<div class="row justify-content-between w-100 mx-0">';
            
            if (isset($ret_selectImages[0])) {
                echo '<div class="col-12 col-md-4 image-retrato-box image-box-full mb-3 aos-init aos-animate" data-aos="fade-up-right" data-aos-delay="300">';
                echo    '<img src="'.ROOT_URL."/assets/".$reference."/".$ret_selectImages[0]['nome'].'" alt="Retrato linha 2 - 1" class="image-retrato">';
                echo '</div>';
            }
            if (isset($ret_selectImages[1])) {
                echo '<div class="col-12 col-md-7 image-retrato-box image-box-full mb-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">';
                echo    '<img src="'.ROOT_URL."/assets/".$reference."/".$ret_selectImages[1]['nome'].'" alt="Retrato linha 2 - 2" class="image-retrato">';
                echo '</div>';
            }
            
            echo    '</div>';
            echo '</section>';

            echo '<br>';
            echo '<br>';
        }

        if (count($ret_selectImages) > 2) {
            echo '<section class="w-100 my-5">';
            echo    '<div class="row justify-content-between w-100 mx-0">';
                            
            if (isset($ret_selectImages[2])) {
                echo '<div class="col-12 col-md-3 image-retrato-box image-box-3 mb-3 aos-init aos-animate" data-aos="zoom-in-up" data-aos-delay="300">';
                echo    '<img src="'.ROOT_URL."/assets/".$reference."/".$ret_selectImages[2]['nome'].'" alt="Retrato linha 3 - 1" class="image-retrato">';
                echo '</div>';
            }
            if (isset($ret_selectImages[3])) {
                echo '<div class="col-12 col-md-3 image-retrato-box image-box-3 mb-3 aos-init aos-animate" data-aos="zoom-in-up" data-aos-delay="300">';
                echo    '<img src="'.ROOT_URL."/assets/".$reference."/".$ret_selectImages[3]['nome'].'" alt="Retrato linha 3 - 2" class="image-retrato">';
                echo '</div>';
            }
            if (isset($ret_selectImages[4])) {
                echo '<div class="col-12 col-md-3 image-retrato-box image-box-3 mb-3 aos-init aos-animate" data-aos="zoom-in-up" data-aos-delay="300">';
                echo    '<img src="'.ROOT_URL."/assets/".$reference."/".$ret_selectImages[4]['nome'].'" alt="Retrato linha 3 - 3" class="image-retrato">';
                echo '</div>';
            }

            echo    '</div>';
            echo '</section>';

            echo '<br>';
            echo '<br>';
        }

        if (count($ret_selectImages) > 5) {
            echo '<section class="w-100 my-5">';
            echo    '<div class="row justify-content-between w-100 mx-0">';
            
            if (isset($ret_selectImages[5])) {
                echo '<div class="col-12 image-retrato-box image-box-full mb-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">';
                echo    '<img src="'.ROOT_URL."/assets/".$reference."/".$ret_selectImages[5]['nome'].'" alt="Retrato linha 4 - 1" class="image-retrato">';
                echo '</div>';
            }

            echo    '</div>';
            echo '</section>';

            echo '<br>';
            echo '<br>';
        }

        if (count($ret_selectImages) > 6) {
            echo '<section class="w-100 my-5">';
            echo    '<div class="row justify-content-between w-100 mx-0">';

            if (isset($ret_selectImages[6])) {
                echo '<div class="col-12 col-md-3 image-retrato-box image-box-2 mb-3 aos-init aos-animate" data-aos="fade-up-right" data-aos-delay="300">';
                echo    '<img src="'.ROOT_URL."/assets/".$reference."/".$ret_selectImages[6]['nome'].'" alt="Retrato linha 5 - 1" class="image-retrato">';
                echo '</div>';
            }
            if (isset($ret_selectImages[7])) {
                echo '<div class="col-12 col-md-8 image-retrato-box image-full-line-5 mb-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">';
                echo    '<img src="'.ROOT_URL."/assets/".$reference."/".$ret_selectImages[7]['nome'].'" alt="Retrato linha 5 - 2" class="image-retrato">';
                echo '</div>';
            }
            
            echo    '</div>';
            echo '</section>';

            echo '<br>';
            echo '<br>';
        }

        if (count($ret_selectImages) == 8) {
            echo '<section class="w-100">';
            echo    '<div class="row mx-0 justify-content-center">';
            echo        '<div class="col-10 div col-md-6 col-lg-3 aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">';
            echo            '<button class="button-enviar w-100" onclick="getMoreImages(this)">VER MAIS</button>';
            echo        '</div>';
            echo    '</div>';
            echo '</section>';

            echo '<br>';
            echo '<br>';
        }

        echo "</div>";

    } else {
        if ($setFrom == 0) {
            echo '<section class="w-100 my-5">';
            echo    '<div class="row justify-content-between w-100 mx-0">';
            echo        '<div class="col-12 aos-init aos-animate" data-aos="fade-up" data-aos-delay="300">';
            echo            '<h1 class="text-center">Nenhuma imagem adicionada</h1>';
            echo        '</div>';
            echo    '</div>';
            echo '</section>';
        }
    }
?>
