AOS.init();

const toastElList = document.querySelectorAll('.toast')
const toastList = [...toastElList].map(toastEl => new bootstrap.Toast(toastEl, {}))

const myToast = document.getElementById('toastNotification');

myToastEl.addEventListener('hidden.bs.toast', () => {
  console.log("salkjdoasj")
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

function renderPage(o) {
    let refContent = o.innerHTML;
    let data_post = `renderContent=${refContent}`;
    let url = "../ajax/a_renderAdmin_main.php";

    try {
        
    } catch (error) {
        
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