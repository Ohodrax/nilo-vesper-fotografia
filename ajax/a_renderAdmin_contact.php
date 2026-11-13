<?php
require_once dirname(__DIR__) . '/bin/_environment.php';
require_admin(false);

    echo "<h1 class='my-5 texto-destaque text-center text-md-start'><span>Contato</span></h1>";

?>

<br>

<div class="row my-5">
    <div class="col-12 mb-4">
        <h1 class="texto-destaque mb-4 text-center text-md-start"><span> MEIOS DE</span> CONTATO</h1>
    </div>
    
    <div class="col-12 col-lg-4 mb-4">
        <label for="input_whatsapp_number" class="custom-form-label">Número do Whatsapp</label>
        <input type="text" class="person-input-users" id="input_whatsapp_number" name="input_whatsapp_number" onfocusout="updateTextParam(this)" onkeydown="mask(this, event)" value="<?php echo h($input_whatsapp_number); ?>">
    </div>
    <div class="col-12 col-lg-4 mb-4">
        <label for="input_whatsapp_number_contact" class="custom-form-label">Número de Celular para Contato</label>
        <input type="text" class="person-input-users" id="input_whatsapp_number_contact" name="input_whatsapp_number_contact" onfocusout="updateTextParam(this)" onkeydown="mask(this, event)" value="<?php echo h($input_whatsapp_number_contact); ?>">
    </div>
    <div class="col-12 col-lg-4 mb-4">
        <label for="input_email" class="custom-form-label">E-mail para Contato</label>
        <input type="text" class="person-input-users" id="input_email" name="input_email" onfocusout="updateTextParam(this)" value="<?php echo h($input_email); ?>">
    </div>
</div>

<br>

<div class="row my-5">
    <div class="col-12 mb-4">
        <h1 class="texto-destaque mb-4 text-center text-md-start"><span> REDES</span> SOCIAIS</h1>
    </div>
    
    <div class="col-12 col-lg-4 mb-4">
        <label for="input_instagram" class="custom-form-label">Instagram</label>
        <input type="text" class="person-input-users" name="input_instagram" id="input_instagram" onfocusout="updateTextParam(this)" value="<?php echo h(safe_url($input_instagram)); ?>">
    </div>
    <div class="col-12 col-lg-4 mb-4">
        <label for="input_facebook" class="custom-form-label">Facebook</label>
        <input type="text" class="person-input-users" name="input_facebook" id="input_facebook" onfocusout="updateTextParam(this)" value="<?php echo h(safe_url($input_facebook)); ?>">
    </div>
    <div class="col-12 col-lg-4 mb-4">
        <label for="input_linkedin" class="custom-form-label">Linkedin</label>
        <input type="text" class="person-input-users" name="input_linkedin" id="input_linkedin" onfocusout="updateTextParam(this)" value="<?php echo h(safe_url($input_linkedin)); ?>">
    </div>
</div>