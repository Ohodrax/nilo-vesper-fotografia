AOS.init();

function mask(o, f) {
    setTimeout(function() {
        var v = maskCelular(o.value);

        if (v != o.value) {
            o.value = v;
        }
    }, 1);
}

function maskCelular(v) {
    var r = v.replace(/\D/g, "");
    r = r.replace(/^0/, "");

    if (r.length > 10) {
        r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
    } else if (r.length > 5) {
        r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
    } else if (r.length > 2) {
        r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
    } else {
        r = r.replace(/^(\d*)/, "($1");
    }

    return r;
}

function setLinkActive(o) {
    let navLinks = document.getElementsByClassName("nav-link");

    // remove todos os links ativos
    for (let index = 0; index < navLinks.length; index++) {
        navLinks[index].style.fontWeight = 400;
    }

    // define um novo link ativo
    o.style.fontWeight = 500;
}

async function renderPage(o) {
    let refContent = o.innerHTML;
    let data_post = `renderContent=${refContent}`;
    let url = "../../ajax/a_renderAdmin_main.php";

    setLinkActive(o);

    try {

        await http_post(url, data_post).then(snapshot => {

            try {

                document.getElementById("root").innerHTML = snapshot;
                
            } catch (error) {
                console.log(error)
                showToast("Ocorreu um erro", false);
            }

        }).catch(error => {
            console.log(error)
            showToast("Ocorreu um erro", false);
        })
        
    } catch (error) {
        console.log(error)
        showToast("Ocorreu um erro", false);
    }
}

function http_post(url, data) {

    return new Promise(response => {
        var http = new XMLHttpRequest();
        http.open('POST', url, true);
    
        //Send the proper header information along with the request
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    
        http.onreadystatechange = function() {//Call a function when the state changes.
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
        icon = `<i class='bx bxs-error-circle'></i>`;
        toast.classList.add("toast-info");
    } else {
        if (ret) {
            icon = `<i class='bx bxs-check-circle'></i>`;
            toast.classList.add("toast-success");
        } else {
            toast.classList.add("toast-error");
            icon = `<i class='bx bxs-x-circle'></i>`;
        }
    }

    setTimeout(() => {
        toast.classList.add("toast-out");
    }, 5000);
    
    toast.innerHTML = `${icon} ${msg}`;
    
    toastBox.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 6000);
}