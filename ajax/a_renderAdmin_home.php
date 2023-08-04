<?php

    echo "<h1 class='my-5'>Início</h1>";

?>

<section class="content my-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-3">Modo de exibição: Carrossel de imagens</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-3 d-flex justify-content-start mb-3">
            <div class="new-image-box">

                <input type="file" id="fileInputCarrossel1">
                <label for="fileInputCarrossel1"></label>

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

                    <div class="single-image-box">
                        <img src="https://images.pexels.com/photos/36717/amazing-animal-beautiful-beautifull.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Image">
                        <span><i class="bi bi-trash"></i></span>
                    </div>
                    <div class="single-image-box">
                        <img src="https://images.pexels.com/photos/36717/amazing-animal-beautiful-beautifull.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Image">
                        <span><i class="bi bi-trash"></i></span>
                    </div>
                    <div class="single-image-box">
                        <img src="https://images.pexels.com/photos/36717/amazing-animal-beautiful-beautifull.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Image">
                        <span><i class="bi bi-trash"></i></span>
                    </div>
                    <div class="single-image-box">
                        <img src="https://images.pexels.com/photos/36717/amazing-animal-beautiful-beautifull.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Image">
                        <span><i class="bi bi-trash"></i></span>
                    </div>
                    <div class="single-image-box">
                        <img src="https://images.pexels.com/photos/36717/amazing-animal-beautiful-beautifull.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Image">
                        <span><i class="bi bi-trash"></i></span>
                    </div>
                    <div class="single-image-box">
                        <img src="https://images.pexels.com/photos/36717/amazing-animal-beautiful-beautifull.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Image">
                        <span><i class="bi bi-trash"></i></span>
                    </div>

                </div>
                
                <div class="arrow arrow-right" onclick="arrowSlide(`right`)">
                    <i class="bi bi-chevron-right"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<br>

<section class="content my-5">
    <div class="row justify-content-between">
        <div class="col-12 col-lg-6">
            <h1 class="texto-destaque mb-4 text-end">VERDADE<br><span>&</span> PERFECCIONISMO</h1>
            <div class="row justify-content-lg-end">
                <div class="col-12 col-lg-10">
                    <textarea class="form-control person-form-control person-form-textarea mb-4" rows="10">30 ANOS DE CARREIRA
                    DEDICADOS A ARTE</textarea>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-5">
            <div class="set-single-image">
                <input type="file" id="fileInputBanner">
                <label for="fileInputBanner"></label>

                <img src="https://images.pexels.com/photos/36717/amazing-animal-beautiful-beautifull.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Image">

                <div class="new-banner-box">
                    <i class="bi bi-camera"></i>
                    <span>Nova imagem</span>
                </div>
            </div>
        </div>
    </div>
</section>

<br>
    
<section class="content my-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-3">Banner Parallax 1</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="set-parallax-image">

                <input type="file" id="fileInputBanner">
                <label for="fileInputBanner"></label>

                <img src="https://images.pexels.com/photos/36717/amazing-animal-beautiful-beautifull.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Image">

                <div class="new-banner-box">
                    <i class="bi bi-camera"></i>
                    <span>Nova imagem</span>
                </div>
            </div>
        </div>
    </div>
</section>

<br>

<section class="content my-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-3">Banner Parallax 2</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="set-parallax-image">

                <input type="file" id="fileInputBanner">
                <label for="fileInputBanner"></label>

                <img src="https://images.pexels.com/photos/36717/amazing-animal-beautiful-beautifull.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Image">

                <div class="new-banner-box">
                    <i class="bi bi-camera"></i>
                    <span>Nova imagem</span>
                </div>
            </div>
        </div>
    </div>
</section>

<br>
