<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/bin/_environment.php";

    switch ($referencePage) {
        case 'Retratos':
            $reference = "pictures";
        break;
        
        case 'Personalidades':
            $reference = "personalities";
        break;
        
        case 'Moda':
            $reference = "fashion";
        break;
        
        case 'Corporativo':
            $reference = "corporate";
        break;
    }

    $filters = array(
        "id_image > 0",
        "referencia = '".$reference."'",
        "localizacao = 'gallery'",
    );

    $orderby = "id_image desc";

    $selecGalleryImages = new db_crud();
    $selecGalleryImages->table   = "tb_image";
    $selecGalleryImages->filters = $filters;
    $selecGalleryImages->orderby = $orderby;
    $ret_selecGalleryImages = $selecGalleryImages->default_select_data();

    
?>