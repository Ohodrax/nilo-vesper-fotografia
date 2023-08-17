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

async function processarEnvio(o) {
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

            if (element.id.includes("form_")) {
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
    }

    if (proccessForm) {
        
        await http_post(url, data_post).then(snapshot => {

            try {
                let ret = JSON.parse(snapshot);
                showMessage(ret.return);
    
                if (ret.return) {
                    clearInputs();
                } else {
                    console.log(ret.ref)
                }
    
                o.removeAttribute("disabled");
                o.innerHTML = "ENVIAR";
    
            } catch (error) {
                console.log(error)
                showMessage(false);
    
                o.removeAttribute("disabled");
                o.innerHTML = "ENVIAR";
            }

        }).catch(error => {
            console.log(error)
            showMessage(false);

            o.removeAttribute("disabled");
            o.innerHTML = "ENVIAR";
        })

    } else {
        showMessage(false);
        
        o.removeAttribute("disabled");
        o.innerHTML = "ENVIAR";
    }
}

async function enviarContato(o){
    grecaptcha.ready(function() {
        grecaptcha.execute('6LflwJsnAAAAAK-pZDvzNjOwHAkGxpAzbhe-Ar6l', {action: 'submit'}).then(function(token) {
            processarEnvio(o);
        });
    });
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

async function getMoreImages(o) {
    let url = "../ajax/a_renderSite_galleryImages.php";
    let reference = document.getElementById("referenceRef").value;
    let data_post = `renderedRef=${document.getElementsByClassName("gallery-grid").length}&referenceRef=${reference}`;

    o.remove();

    await http_post(url, data_post).then(snapshot => {

        document.getElementById("gallery-box").innerHTML = document.getElementById("gallery-box").innerHTML + snapshot;
        
        AOS.init();

    }).catch(error => {
        console.log(error);
    })
}

async function clearInputs() {
    let tags_name = ['input', 'textarea'];

    for (let tagsIndex = 0; tagsIndex < tags_name.length; tagsIndex++) {
        const tags_length =  document.getElementsByTagName(tags_name[tagsIndex]);

        for (let tagIndex = 0; tagIndex < tags_length.length; tagIndex++) {
            const element = tags_length[tagIndex];

            if (element.id.includes("form_")) {
                element.value = "";
                
                element.classList.remove('is-valid');
                element.classList.remove('is-invalid');
            }
        }
    }
}