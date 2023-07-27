<footer id="contato" class="container" style="min-height: 85vh;margin-top: 120px;overflow: hidden;">
    <div class="row justify-content-center gap-5">
        <div class="col-12 col-md-8 col-lg-5" data-aos="fade-up-right" data-aos-delay="300">
            <h1 class="texto-destaque-formulario mb-5"><span>VAMOS</span> CONVERSAR?</h1>

            <div class="mt-2">
                <input type="text" class="form-control person-form-control" id="form_inputNome" placeholder="SEU NOME" required>
            </div>
            
            <br>
            
            <div class="mt-2">
                <input type="email" class="form-control person-form-control" id="form_inputEmail" placeholder="SEU E-MAIL" inputmode="email" required>
            </div>
            
            <br>
            
            <div class="mt-2">
                <input type="text" class="form-control person-form-control" onkeyup="mask(this, event)" id="form_inputTelefone" placeholder="SEU TELEFONE" inputmode="numeric" maxlength="15" required>
            </div>
            
            <br>

            <div class="mt-2">
                <textarea class="form-control person-form-control person-form-textarea" rows="5" id="form_inputMensagem" placeholder="MENSAGEM" required></textarea>
            </div>
            
            <br>
            
            <div class="mt-2 w-100 d-flex align-items-center" style="position: relative;">
                <div class="col-12 col-md-auto">
                    <button class="button-enviar mb-3" onclick="enviarContato(this)">ENVIAR</button>
                </div>

                <!-- Success Message -->
                <div class="col-12 col-md-auto">
                    <div id="success-message" class="d-flex flex-row gap-2 ms-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="#198754" class="bi bi-check2" viewBox="0 0 16 16">
                            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                        </svg>
                        <p class="fs-3 text-success">Enviado!</p>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <div class="col-12 col-md-auto">
                <div id="error-message" class="d-flex flex-row gap-2" style="opacity: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="#dc3545" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                    </svg>
                    <p class="fs-3 text-danger">Verifique os campos e tente novamente</p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8 col-lg-5 d-flex align-items-end">
            <div class="row text-end">
                <div class="col-12 mb-5" data-aos="fade-up" data-aos-delay="300">
                    <h2 class="footer-titulo">PAULO<span>BARETA</span>FOTOGRAFIA</h2>
                </div>
                <div class="col-12 my-3" data-aos="fade-up" data-aos-delay="400">
                    <p class="footer-texto-contato">
                        <a class="link-default" href="tel:+5511991386363">(11) 99138-6363</a>
                        <br>
                        <a class="link-default" href="mailto:paulobareta@hotmail.com">paulobareta@hotmail.com</a>
                        <br>
                        SÃO PAULO, BRASIL
                    </p>
                </div>
                <div class="col-12 mb-5" data-aos="fade-up" data-aos-delay="500">
                    <div class="footer-icones-contato d-flex align-items-center justify-content-end" style="min-height: 115px;">

                        <!-- instagram -->
                        <a href="https://www.google.com.br" target="_blank" rel="noopener noreferrer">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" class="bi bi-instagram" viewBox="0 0 16 16">
                                <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                            </svg>
                        </a>

                        <!-- facebook -->
                        <a href="https://www.facebook.com/paulobareta.bareta" target="_blank" rel="noopener noreferrer">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" class="bi bi-facebook" viewBox="0 0 16 16">
                                <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                            </svg>
                        </a>

                        <!-- linkedin -->
                        <a href="https://www.linkedin.com/in/paulo-bareta-bb47523b/" target="_blank" rel="noopener noreferrer">
                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" class="bi bi-linkedin" viewBox="0 0 16 16">
                                <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<a href="https://wa.me/5511991386363" class="btn-whatsapp" target="_blank" <?php echo $_SERVER['REQUEST_URI'] == '/contato' ? '' : 'data-aos="zoom-in-up" data-aos-delay="300"'; ?>>
    <img src="./assets/whatsapp-icon.png" alt="Botao whatssapp">
    <span class="tooltip-text">Envie uma mensagem</span>
</a>