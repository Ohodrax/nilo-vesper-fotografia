AOS.init();

var device;

window.onload = () => {
    if (document.getElementsByClassName("image-render-box").length > 1) {
        document.getElementsByClassName("image-render-box")[1].remove();
    }

    const ua = navigator.userAgent;

    if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
      device = "tablet";
    }
    
    if (/Mobile|iP(hone|od)|Android|BlackBerry|IEMobile|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua)) {
      device = "mobile";
    }

    device = "desktop";

    doSomethingElse();
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
    let data_post = `renderContent=${encodeURIComponent(refContent)}`;
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

function http_post_file(url, data) {

    return new Promise((response, reject) => {
        var http = new XMLHttpRequest();
        http.open('POST', url, true);
        http.setRequestHeader('X-CSRF-Token', document.querySelector('meta[name="csrf-token"]').content);
        http.timeout = 45000;
        http.onerror = () => reject(new Error('Falha de conexão'));
        http.ontimeout = () => reject(new Error('Tempo de espera excedido'));
    
        //Send the proper header information along with the request
        // http.setRequestHeader('Content-type', 'multipart/form-data');
    
        http.onreadystatechange = function() {//Call a function when the state changes.
            if (http.readyState === 4 && http.status !== 200) {
                let message = http.status === 413 ? 'A hospedagem recusou o tamanho do arquivo. Atualize a página ou revise os limites de envio no Plesk.' : 'Envio recusado (' + http.status + '). Atualize a página ou entre novamente.';
                try { const result = JSON.parse(http.responseText); if (typeof result.ref === 'string') message = result.ref.slice(0, 500); } catch {}
                const error = new Error(message); error.status = http.status; reject(error);
            }
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
    
    toast.innerHTML = icon;
    toast.appendChild(document.createTextNode(" " + String(msg)));
    
    toastBox.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 6000);
}

async function renderModalContent(o) {
    let url = "../../ajax/a_modalUser_renderContent.php";
    let data_post = `renderContent=${encodeURIComponent(o.dataset.renderContent)}`;

    if (o.dataset.targetId !== undefined) {
        data_post = data_post + `&targetId=${encodeURIComponent(o.dataset.targetId)}`;
    }

    try {
        await http_post(url, data_post).then(snapshot => {

            document.getElementById("modalAction_content").innerHTML = snapshot;

        }).catch(error => {
            console.log(error)
            showToast("Erro do sistema", false);
        })
    } catch (error) {
        console.log(error)
        showToast("Erro do sistema", false);
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

    if (o.dataset.targetId !== undefined) {
        data_post = `${data_post}&targetId=${encodeURIComponent(o.dataset.targetId)}`;
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

    o.innerHTML = oldInner;
    o.removeAttribute("disabled");
}

async function changeStatus(o) {
    let url = "../../control/c_adminUser_changeStatus.php";
    let data_post = `targetId=${encodeURIComponent(o.dataset.targetId)}&setStatus=${encodeURIComponent(o.checked)}`;

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
                showToast("Erro do sistema", false);
            }
        }).catch(error => {
            console.log(error);
            showToast("Erro do sistema", false);
        })
    } catch (error) {
        console.log(error);
        showToast("Erro do sistema", false);
    }
}

async function logout() {
    try {
        await http_post("/logout", "");
        window.location.href = "/login";
    } catch {
        showToast("Não foi possível sair. Tente novamente.", false);
    }
}

let imageUploadInProgress = false;
async function uploadImage(o, ref, loc) {
    if (!o || !o.files || !ref || !loc) { showToast('Selecione uma imagem e seu destino.', false); return; }
    if (imageUploadInProgress) { showToast('Aguarde o envio em andamento.'); o.value = ''; return; }
    const files = Array.from(o.files);
    if (!files.length) return;
    imageUploadInProgress = true;
    o.disabled = true;
    let saved = 0;
    const status = document.getElementById('image-upload-status');
    try {
        for (let index = 0; index < files.length; index++) {
            try {
                if (status) status.textContent = 'Preparando foto ' + (index + 1) + ' de ' + files.length + '…';
                const prepared = await PhotoUpload.prepare(files[index], window.imageUploadLimits);
                const data = new FormData();
                data.append('reference', ref);
                data.append('localization', loc);
                data.append(0, prepared.file);
                if (status) status.textContent = 'Enviando foto ' + (index + 1) + ' de ' + files.length + '…';
                const response = await http_post_file('../../control/c_uploadFile_save.php', data);
                let result;
                try { result = JSON.parse(response); }
                catch { throw new Error('O servidor retornou uma resposta inesperada. Atualize a página e tente novamente.'); }
                if (result.return !== true) throw new Error(typeof result.ref === 'string' ? result.ref : 'O servidor não confirmou o envio.');
                saved++;
            } catch (error) {
                showToast(files[index].name + ': ' + (error.message || 'Não foi possível enviar a imagem.'), false);
                if ([401, 403, 413, 429].includes(error.status)) break;
            }
        }
        if (saved) {
            showToast(saved === 1 ? 'Imagem salva com sucesso.' : saved + ' imagens salvas com sucesso.', true);
            await renderPage(document.getElementById('nav-menu-' + ref));
        }
    } finally {
        imageUploadInProgress = false;
        o.disabled = false;
        o.value = '';
        if (status) status.textContent = saved + ' de ' + files.length + ' imagens salvas.';
    }
}

async function deleteImage(id, reloadRef) {
    let url = "../../control/c_uploadFile_delete.php";
    let data_post = `targetId=${encodeURIComponent(id)}`;

    if (id === undefined || +id === 0) {
        showToast("Ausência de parametro", false);
        
        return false;
    }

    if (reloadRef === undefined) {
        reloadRef = "home";
    }

    await http_post(url, data_post).then(snapshot => {
        try {

            let ret = JSON.parse(snapshot);

            showToast(ret.ref, ret.return);

            if (ret.return) {
                renderPage(document.getElementById(`nav-menu-${reloadRef}`));
            }
            
        } catch (error) {
            console.log(error);
            showToast("Erro do sistema", false);
        }
    })
}

async function updateTextParam(o) {
    let url = "../../control/c_adminSystem_updateTextParam.php";
    let data_post = `refParamName=${encodeURIComponent(o.name)}&refParamValue=${encodeURIComponent(o.value)}`;

    if (typeof o === undefined) {
        showToast("Erro do sistema", false);
        console.log("Referencia não é um objeto válido");

        return false;
    }

    if (o.name === undefined || o.value === undefined) {
        showToast("Erro do sistema", false);
        console.log("Referencias do objeto são inválidas");

        return false;
    }

    await http_post(url, data_post).then(snapshot => {
        try {

            let ret = JSON.parse(snapshot);

            showToast(ret.ref, ret.return);
            
        } catch (error) {
            console.log(error);
            showToast("Erro do sistema", false);

            return false;
        }
    }).catch(error => {
        console.log(error);
        showToast("Erro do sistema", false);

        return false;
    })
}

async function setDestaque(id, reloadRef) {
    let url = "../../control/c_galleryManager_setDestaque.php";
    let data_post = `targetId=${encodeURIComponent(id)}`;

    try {
        
        await http_post(url, data_post).then(snapshot => {

            try {
             
                let ret = JSON.parse(snapshot);

                showToast(ret.ref, ret.return);

                if (ret.return) {
                    renderPage(document.getElementById(`nav-menu-${reloadRef}`));
                }
                
            } catch (error) {
                console.log(error);
                showToast("Erro do sistema", false);
            }
        }).catch(error => {
            console.log(error);
            showToast("Erro do sistema", false);
        })
    } catch (error) {
        console.log(error);
        showToast("Erro do sistema", false);
    }
}
