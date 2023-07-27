AOS.init();

window.addEventListener('scroll', () => {
    let urls = ['/', '/retratos', '/personalidades', '/moda', '/corporativo'];

    if (!urls.includes(window.location.pathname)) {
        return false;
    }
    if (window.scrollY > 50) {
        document.getElementsByClassName('offcanvas-header-nav')[0].classList.add('offcanvas-header-nav-scrolled');
        document.getElementsByClassName('menu-icon')[0].classList.add('menu-icon-scrolled');
        document.getElementsByClassName('nav-content')[0].classList.add('nav-content-scrolled');
    } else {
        document.getElementsByClassName('offcanvas-header-nav')[0].classList.remove('offcanvas-header-nav-scrolled');
        document.getElementsByClassName('menu-icon')[0].classList.remove('menu-icon-scrolled');
        document.getElementsByClassName('nav-content')[0].classList.remove('nav-content-scrolled');
    }
})

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

async function enviarContato(o){
    let url          = "../control/c_proccess_new_contact.php";
    let tags_name    = ['input', 'textarea'];
    let data_post    = "";
    let data_invalid = [];
    let proccessForm = true;
    let value        = "";

    o.setAttribute("disabled", true);
    o.innerHTML = `ENVIANDO... <div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>`;

    for (let tagsIndex = 0; tagsIndex < tags_name.length; tagsIndex++) {
        const tags_length =  document.getElementsByTagName(tags_name[tagsIndex]);

        for (let tagIndex = 0; tagIndex < tags_length.length; tagIndex++) {
            const element = tags_length[tagIndex];

            data_post = data_post === "" ? "" : data_post + "&";

            if (element.type == "checkbox") {
                value = element.checked ? 1 : 0;
            } else {
                value = element.value;
            }

            if (element.required && value === "" || value === 0) {
                proccessForm = false;
                data_invalid.push(element.name);

                element.classList.remove('is-valid');
                element.classList.add('is-invalid');
            } else if (element.required) {
                element.classList.remove('is-invalid');
                element.classList.add('is-valid');
            }
            
            data_post = data_post + element.id + "=" + value;
        }
    }

    if (proccessForm) {
        let ret = await http_post_form(url, data_post);

        try {
            ret = JSON.parse(ret);
            showMessage(ret.return);

            if (ret.return) {
                clearInputs();
            } else {
                showMessage(ret.return)

                o.removeAttribute("disabled");
                o.innerHTML = "ENVIAR";
            }
        } catch (error) {
            console.log(error)
            showMessage(false);

            o.removeAttribute("disabled");
            o.innerHTML = "ENVIAR";
        }

    } else {
        showMessage(false);
        
        o.removeAttribute("disabled");
        o.innerHTML = "ENVIAR";
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

function showMessage(ret) {
    const successMessage = document.getElementById("success-message");
    const errorMessage = document.getElementById("error-message");

    if (ret) {
        successMessage.style.opacity = 1;
        errorMessage.style.opacity = 0;
    } else {
        successMessage.style.opacity = 0;
        errorMessage.style.opacity = 1;
    }

    setTimeout(() => {
        successMessage.style.opacity = 0;
        errorMessage.style.opacity = 0;
    }, 4000);
}