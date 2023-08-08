AOS.init();

window.onload = () => {
    if (document.getElementsByClassName("image-render-box").length > 1) {
        document.getElementsByClassName("image-render-box")[1].remove();
    }
}

var device;

window.onload = () => {
    const ua = navigator.userAgent;

    if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
      device = "tablet";
    }
    
    if (/Mobile|iP(hone|od)|Android|BlackBerry|IEMobile|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua)) {
      device = "mobile";
    }

    device = "desktop";
};

function gallery_functions() {
    const slider = document.querySelector('.image-render-box');
    let isDown = false;
    let startX;
    let scrollLeft;

    slider.addEventListener('mousedown', (e) => {
        isDown = true;
        slider.classList.add('active');
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });
    slider.addEventListener('mouseleave', () => {
        isDown = false;
        slider.classList.remove('active');
    });
    slider.addEventListener('mouseup', () => {
        isDown = false;
        slider.classList.remove('active');
    });
    slider.addEventListener('mousemove', (e) => {
        if(!isDown) return;
    
        e.preventDefault();
        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 2; //scroll-fast
        slider.scrollLeft = scrollLeft - walk;
        // console.log(walk);
    });
    
}

function arrowSlide(to) {
    const slider = document.querySelector('.image-render-box');
    let scrollTime = 0;

    let timerSlide = setInterval(() => {
        scrollTime++;

        if (scrollTime > 25) {
            clearInterval(timerSlide);
        } else {
            
            slider.scrollLeft = to == "right" ? slider.scrollLeft + scrollTime : slider.scrollLeft - scrollTime;
        }
    }, 10);
}

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

    if (o !== undefined) {
        // define um novo link ativo
        o.style.fontWeight = 500;
    }
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

                switch (refContent) {
                    case "Início":
                        // document.getElementsByClassName("image-render-box")[1].remove();

                        gallery_functions();

                        if (device == "tablet" || device == "mobile") {
                            document.getElementsByClassName("image-render-box")[0].style.overflowX = "scroll";
                        }
                    break;
                
                    default:
                    break;
                }

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
        icon = `<i class="bi bi-info-circle text-warning"></i>`;
        toast.classList.add("toast-info");
    } else {
        if (ret) {
            icon = `<i class="bi bi-check2-circle text-success"></i>`;
            toast.classList.add("toast-success");
        } else {
            toast.classList.add("toast-error");
            icon = `<i class="bi bi-x-circle text-danger"></i>`;
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

async function renderModalContent(o) {
    let url = "../../ajax/a_modalUser_renderContent.php";
    let data_post = `renderContent=${o.dataset.renderContent}`;

    if (o.dataset.targetId !== undefined) {
        data_post = data_post + `&targetId=${o.dataset.targetId}`;
    }

    try {
        await http_post(url, data_post).then(snapshot => {

            document.getElementById("modalAction_content").innerHTML = snapshot;

        }).catch(error => {
            console.log(error)
            showToast("Ocorreu um erro", false);
        })
    } catch (error) {
        console.log(error)
        showToast("Ocorreu um erro", false);
    }
}

async function performAction(o) {
    let refAction = {
        "Adicionar": "Adicionando",
        "Editar"   : "Editando",
        "Excluir"  : "Excluindo"
    };
    let oldInner = o.innerHTML;
    let doingActionRef = `${refAction[o.innerHTML]} <div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>`;
    o.innerHTML = doingActionRef;
    o.setAttribute("disabled", true);

    let url      = "../../control/c_adminUser_CRUD.php";
    let proccess = true;
    let emptyRef = "";
    let data_post;

    for (let index = 0; index < document.getElementsByTagName("input").length; index++) {
        const element = document.getElementsByTagName("input")[index];

        if (element.required && element.value == "") {
            proccess = false;
            emptyRef = emptyRef == "" ? element.name : `${emptyRef}, ${element.name}`;
        } else if (element.id.includes("form")) {
            data_post = data_post == undefined ? `${element.id}=${element.value}` : `${data_post}&${element.id}=${element.value}`;
        }        
    }

    if (o.dataset.targetId !== undefined) {
        data_post = `${data_post}&targetId=${o.dataset.targetId}`;
    }

    if (proccess) {

        try {
            await http_post(url, data_post).then(snapshot => {

                try {
                    let ret = JSON.parse(snapshot);
    
                    showToast(ret.ref, ret.return);
                    
                    if (ret.return) {
                        
                        for (let index = 0; index < document.getElementsByTagName("input").length; index++) {
                            const element = document.getElementsByTagName("input")[index];
                    
                            if (element.id.includes("form")) {
                                element.value = "";
                            }
                        }

                        bootstrap.Modal.getInstance("#modalAction").hide();

                        renderPage(document.getElementsByClassName("navbar-brand")[0]);
                    }
    
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
        
    } else {
        showToast("Campo(s) vazio(s): " + emptyRef, false);
    }

    o.innerHTML = oldInner;
    o.removeAttribute("disabled");
}

async function changeStatus(o) {
    let url = "../../control/c_adminUser_changeStatus.php";
    let data_post = `targetId=${o.dataset.targetId}&setStatus=${o.checked}`;

    try {
        
        await http_post(url, data_post).then(snapshot => {

            try {
             
                let ret = JSON.parse(snapshot);

                showToast(ret.ref, ret.return);

                if (ret.return) {
                    document.getElementById("checkboxLabel_" + o.dataset.targetId).innerHTML = o.checked ? "Ativo" : "Inativo";
                } else {
                    o.checked = !o.checked;
                }

                
            } catch (error) {
                console.log(error);
                showToast("Ocorreu um erro", false);
            }
        }).catch(error => {
            console.log(error);
            showToast("Ocorreu um erro", false);
        })
    } catch (error) {
        console.log(error);
        showToast("Ocorreu um erro", false);
    }
}