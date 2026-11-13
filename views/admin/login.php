<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta name="csrf-token" content="<?php echo h(csrf_token()); ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap 5 reference - CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <!-- Bootstrap 5 reference - JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>

    <!-- AOS Animated - CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- AOS Animated - JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Icons Ref -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="./assets/favicon.svg">
    
    <title>Login | Nilo Vesper Fotografia</title>

    <link rel="stylesheet" href="../../css/admin.css">
</head>
<body>
    <div class="carousel slide carousel-fade carrossel-background" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="5000">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../../assets/bg-image-1.webp" alt="Background Image 1" class="background-image">
            </div>
            <div class="carousel-item">
                <img src="../../assets/bg-image-2.jpg" alt="Background Image 2" class="background-image">
            </div>
            <div class="carousel-item">
                <img src="../../assets/bg-image-3.jpg" alt="Background Image 3" class="background-image">
            </div>
            <div class="carousel-item">
                <img src="../../assets/bg-image-4.jpg" alt="Background Image 4" class="background-image">
            </div>
            <div class="carousel-item">
                <img src="../../assets/bg-image-5.jpg" alt="Background Image 5" class="background-image">
            </div>
        </div>
    </div>

    <div class="form-box">
        <div class="wrapper">
            <form action="">
                <h1 data-aos="fade-up">Login</h1>
                <div class="input-box" data-aos="fade-up" data-aos-delay="200">
                    <input type="text" placeholder="Usuário" required name="username" id="username" inputmode="email">
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box" data-aos="fade-up" data-aos-delay="300">
                    <input type="password" placeholder="Senha" required name="userpassword" id="userpassword">

                    <input type="checkbox" name="showPassword" id="showPassword" class="d-none" onchange="changeInput(this)">
                    <label for="showPassword" id="lock-icon">
                        <i class='bx bxs-lock-alt'></i>
                        <i class='bx bxs-lock-open-alt'></i>
                    </label>
                </div>
    
                <div class="remember-forgot" data-aos="fade-up" data-aos-delay="400">
                    <label for="rememberMe">
                        <input type="checkbox" name="rememberMe" id="rememberMe">
                        Lembrar usuário
                    </label>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalResetarSenha">Esqueceu sua senha?</a>
                </div>
    
                <div data-aos="fade-up" data-aos-delay="500">
                    <button type="submit" class="btn" onclick="login(event, this)">Entrar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalResetarSenha" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalResetarSenhaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="staticBackdropLabel">Recuperar senha</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-box input-box-modal" data-aos="fade-up" data-aos-delay="300">
                        <input type="email" placeholder="E-mail" required name="emailsendrecovery" id="emailsendrecovery" inputmode="email">
                        <i class='bx bxs-envelope'></i>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Voltar</button>
                    <button type="button" class="btn btn-outline-dark" onclick="recuperarSenha(this)">Recuperar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="toastBox"></div>

    <script src="../scripts/_admin_login.js"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=6LflwJsnAAAAAK-pZDvzNjOwHAkGxpAzbhe-Ar6l"></script>
    <?php
        // exibe a mensagem de logout
        if ($showMessage) {
            echo "<script>function doSomethingElse() { showToast(" . js_value($message['ref']) . ", " . js_value(filter_var($message['return'], FILTER_VALIDATE_BOOLEAN)) . ");}</script>";
        } else {
            echo "<script>function doSomethingElse() { return null; }</script>";
        }
    ?>
</body>
</html>
