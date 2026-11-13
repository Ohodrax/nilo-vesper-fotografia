AOS.init();

function changeInput(o) {
    let iconLock     = `<i class='bx bxs-lock-alt'></i>`;
    let iconLockOpen = `<i class='bx bxs-lock-open-alt'></i>`;
    let labelShowIcon;
    let inputPassword;

    if (o.id == "showPassword") {
        labelShowIcon = document.getElementById("lock-icon-new");
        inputPassword = document.getElementById("formNewPassword");
    }

    if (o.id == "showConfirmPassword") {
        labelShowIcon = document.getElementById("lock-icon-confirm");
        inputPassword = document.getElementById("formConfirmPassword");
    }

    if (labelShowIcon != undefined && inputPassword != undefined) {
        if (o.checked) {
            inputPassword.type = "text";
            labelShowIcon.innerHTML = iconLockOpen;
        } else {
            inputPassword.type = "password";
            labelShowIcon.innerHTML = iconLock;
        }        
    } else {
        return;
    }

    return;
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

async function defineNewPassword(o) {
    let doingActionRef = `Alterando... <div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>`;
    o.innerHTML = doingActionRef;
    o.setAttribute("disabled", true);

    let url = "../../control/c_recoveryPassword_update.php";
    let proccess = true;
    let emptyRef = "";
    let data_post;
    let tags = ['input', 'select'];

    for (let indexTag = 0; indexTag < tags.length; indexTag++) {
        let tag = tags[indexTag];

        for (let index = 0; index < document.getElementsByTagName(tag).length; index++) {
            const element = document.getElementsByTagName(tag)[index];

            if (element.required && element.value == "") {
                proccess = false;
                emptyRef = emptyRef == "" ? element.name : `${emptyRef}, ${element.name}`;
            } else if (element.id.includes("form")) {
                data_post = data_post == undefined ? `${encodeURIComponent(element.id)}=${encodeURIComponent(element.value)}` : `${data_post}&${encodeURIComponent(element.id)}=${encodeURIComponent(element.value)}`;
            }        
        }
    }

    if (proccess) {

        try {
            await http_post(url, data_post).then(snapshot => {

                console.log(snapshot);

                try {
                    let ret = JSON.parse(snapshot);
    
                    showToast(ret.ref, ret.return);
                    
                    if (ret.return) {
                        window.location.href = "/login";
                    }
    
                } catch (error) {
                    console.log(error)
                    showToast("Erro do sistema", false);
                }
    
            }).catch(error => {
                console.log(error)
                showToast("Erro do sistema", false);
            })
            
        } catch (error) {
            console.log(error)
            showToast("Erro do sistema", false);
        }
        
    } else {
        showToast("Campo(s) vazio(s): " + emptyRef, false);
    }

    o.innerHTML = "Alterar";
    o.removeAttribute("disabled");

}