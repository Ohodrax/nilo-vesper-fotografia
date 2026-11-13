<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/control/c_getInformations_recoveryPassword.php"; ?>

<!DOCTYPE html>
<html lang="en">
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
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="./assets/favicon.ico">

    <!-- Icons Ref -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <title>Recuperar Senha</title>

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
                <h1 data-aos="fade-up">Recuperar Senha</h1>
                
                <div class="input-box" data-aos="fade-up" data-aos-delay="200">
                    <input type="password" placeholder="Senha" required name="Senha" id="formNewPassword">

                    <input type="checkbox" name="showPassword" id="showPassword" class="d-none" onchange="changeInput(this)">
                    <label for="showPassword" id="lock-icon-new" class="lock-icon">
                        <i class='bx bxs-lock-alt'></i>
                        <i class='bx bxs-lock-open-alt'></i>
                    </label>
                </div>
                
                <div class="input-box" data-aos="fade-up" data-aos-delay="300">
                    <input type="password" placeholder="Confirmar senha" required name="Confirmar senha" id="formConfirmPassword">

                    <input type="checkbox" name="showPassword" id="showConfirmPassword" class="d-none" onchange="changeInput(this)">
                    <label for="showConfirmPassword" id="lock-icon-confirm" class="lock-icon">
                        <i class='bx bxs-lock-alt'></i>
                        <i class='bx bxs-lock-open-alt'></i>
                    </label>
                </div>

                <input type="hidden" name="raid" value="<?php echo h($raid); ?>" id="formRaid">
    
                <div data-aos="fade-up" data-aos-delay="500">
                    <button type="submit" class="btn" onclick="defineNewPassword(this)">Alterar</button>
                </div>
            </form>
        </div>
    </div>

    <div id="toastBox"></div>

    <script src="../scripts/_admin_recovery.js"></script>
</body>
</html>