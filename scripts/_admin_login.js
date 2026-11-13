AOS.init();

window.onload = () => {
    let rememberUser = localStorage.getItem("savedUser");

    if (rememberUser != null) {
        rememberUser = JSON.parse(rememberUser);

        document.getElementById("username").value = rememberUser.user;
        document.getElementById("rememberMe").checked = true;
    }

    doSomethingElse();
}

function http_post(url, data) {

    return new Promise((response, reject) => {
        var http = new XMLHttpRequest();
        http.open('POST', url, true);
        http.setRequestHeader('X-CSRF-Token', document.querySelector('meta[name="csrf-token"]').content);
        http.timeout = 45000;
        http.onerror = () => reject(new Error('Falha de conexão'));
        http.ontimeout = () => reject(new Error('Tempo de espera excedido'));
    
        //Send the proper header information along with the request
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    
        http.onreadystatechange = function() {//Call a function when the state changes.
            if (http.readyState === 4 && http.status !== 200) { reject(new Error("Requisição recusada (" + http.status + "). Atualize a página ou entre novamente.")); }
            if(http.readyState == 4 && http.status == 200) {
                response(http.responseText);
            }
        }
    
        http.send(data);
    });
}

function showToast(msg, ret) {
    let toastBox = document.getElementById("toastBox");
    let toast = document.createElement("div");
    let icon;
    toast.classList.add("toast");

    if (ret === undefined) {
        icon = `<i class='bx bx-info-circle'></i>`;
        toast.classList.add("toast-info");
    } else {
        if (ret) {
            icon = `<i class='bx bx-check-circle'></i>`;
            toast.classList.add("toast-success");
        } else {
            toast.classList.add("toast-error");
            icon = `<i class='bx bx-x-circle'></i>`;
        }
    }

    setTimeout(() => {
        toast.classList.add("toast-out");
    }, 5000);
    
    toast.innerHTML = icon;
    toast.appendChild(document.createTextNode(" " + String(msg)));
    
    toastBox.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 6000);
}

async function processLogin(o) {
    let url = "../../control/c_loginValidation.php";
    let username = document.getElementById("username").value;
    let userpassword = document.getElementById("userpassword").value;

    o.innerHTML = `Carregando... <div class="spinner-border" style="width: 2rem; height: 2rem;" role="status"><span class="visually-hidden">Loading...</span></div>`;
    o.setAttribute("disabled", true);

    if (username == "" || username == undefined || username == null) {
        showToast("Campo de usuário inválido", false);
        
        o.innerHTML = "Entrar";
        o.removeAttribute("disabled");

        return false;
    }
    if (userpassword == "" || userpassword == undefined || userpassword == null) {
        showToast("Campo de senha inválido", false);

        o.innerHTML = "Entrar";
        o.removeAttribute("disabled");

        return false;
    }

    let data_post = `username=${encodeURIComponent(username)}&userpassword=${encodeURIComponent(userpassword)}`;

    try {
        await http_post(url, data_post).then(snapshot => {
            let ret = JSON.parse(snapshot);

            showToast(ret.ref, ret.return);

            if (ret.return) {

                if (document.getElementById("rememberMe").checked) {
                    let jsonSave = JSON.stringify({
                        "user" : username
                    })
                    localStorage.setItem("savedUser", jsonSave);
                } else {
                    localStorage.clear("savedUser");
                }

                window.location.href = "/admin";

            } else {
                o.innerHTML = "Entrar";
                o.removeAttribute("disabled");
            }

        }).catch(error => {
            console.log(error);
            showToast("Erro do sistema", false);
            
            o.innerHTML = "Entrar";
            o.removeAttribute("disabled");
        })        
    } catch (error) {
        console.log(error);
        showToast("Erro do sistema", false);

        o.innerHTML = "Entrar";
        o.removeAttribute("disabled");
    }
}

async function login(e, o) {
    e.preventDefault();

    grecaptcha.ready(function() {
        grecaptcha.execute('6LflwJsnAAAAAK-pZDvzNjOwHAkGxpAzbhe-Ar6l', {action: 'submit'}).then(function(token) {
            processLogin(o);
        });
    });
}

async function runRecuperarSenha(o) {
    let url = "../../control/c_recoveryPassword_sendLink.php";
    let emailsendrecovery = document.getElementById("emailsendrecovery").value;

    o.innerHTML = `Carregando... <div class="spinner-border" style="width: 2rem; height: 2rem;" role="status"><span class="visually-hidden">Loading...</span></div>`;
    o.setAttribute("disabled", true);

    if (emailsendrecovery == "" || emailsendrecovery == undefined || emailsendrecovery == null) {
        showToast("Campo de e-mail inválido", false);
        
        o.innerHTML = "Recuperar";
        o.removeAttribute("disabled");

        return false;
    }

    let data_post = `emailsendrecovery=${encodeURIComponent(emailsendrecovery)}`;

    try {
        await http_post(url, data_post).then(snapshot => {
            let ret = JSON.parse(snapshot);

            showToast(ret.ref, ret.return);

            if (ret.return) {
                bootstrap.Modal.getInstance("#modalResetarSenha").hide();

                // limpar campo de email
                emailsendrecovery = "";

                o.innerHTML = "Recuperar";
                o.removeAttribute("disabled");
            } else {
                o.innerHTML = "Recuperar";
                o.removeAttribute("disabled");
            }

        }).catch(error => {
            console.log(error);
            showToast("Erro do sistema", false);
            
            o.innerHTML = "Recuperar";
            o.removeAttribute("disabled");
        })        
    } catch (error) {
        console.log(error);
        showToast("Erro do sistema", false);

        o.innerHTML = "Recuperar";
        o.removeAttribute("disabled");
    }
}

async function recuperarSenha(o) {
    grecaptcha.ready(function() {
        grecaptcha.execute('6LflwJsnAAAAAK-pZDvzNjOwHAkGxpAzbhe-Ar6l', {action: 'submit'}).then(function(token) {
            runRecuperarSenha(o);
        });
    });
}

function changeInput(o) {
    let iconLock     = `<i class='bx bxs-lock-alt'></i>`;
    let iconLockOpen = `<i class='bx bxs-lock-open-alt'></i>`;
    let labelShowIcon = document.getElementById("lock-icon");
    let inputPassword = document.getElementById("userpassword");

    if (o.checked) {
        inputPassword.type = "text";
        labelShowIcon.innerHTML = iconLockOpen;
    } else {
        inputPassword.type = "password";
        labelShowIcon.innerHTML = iconLock;
    }

    return;
}