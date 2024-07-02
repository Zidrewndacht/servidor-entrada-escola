let modalWrapperLarge = document.getElementById("modal-wrapper-large");
let modalLarge = document.getElementById("modal-large");
let modalLargeContent = document.getElementById('modal-large-content');
let closeLarge = document.getElementById("modal-large-close");

let modalWrapperSmall = document.getElementById("modal-wrapper-small");
let modalSmall = document.getElementById("modal-small");
let modalSmallContent = document.getElementById('modal-small-content');
let closeSmall = document.getElementById("modal-small-close");

/** Comunicação assíncrona: */

function addFormListeners(form) {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        document.body.style.cursor = "progress"; //cursor de progresso
        let formData = new FormData(form);
        fetch(form.getAttribute('action'), {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                modalSmallContent.innerHTML = data;
                addTargetEvents();
                document.body.style.cursor = "default";
            })
            .catch((error) => {
                console.error('Error:', error);
            });
    });
    let selectElements = form.querySelectorAll('select[multiple]');
    selectElements.forEach(function (selectElement) {
        new Choices(selectElement, {
            removeItemButton: true,
            allowHTML: true,
            noChoicesText: 'Nenhuma opção restante',
        });
    });
}


function addTargetEvents() {    //Converte formulários em AJAX:
    let form = modalSmallContent.querySelector('form');
    if (form) addFormListeners(form);
}

/** Adiciona event listeners para botões de tabela: 
 *  clique em link abre modal em vez de página: pequeno, grande ou iframe;
 *  tabelas reordenáveis para campos 'sortable';
 *  barra de pesquisa instantânea;
 *  conversão de links em "botão" com células inteiras clicáveis e sem clique do meio. */
function addTablesListeners() {
    //usa modal grande   
    document.querySelectorAll(".status-resp-btn").forEach(function (btn) {
        btn.onclick = function (event) {
            event.preventDefault();
            animateModalLargeOpen();    //exibe imediatamente a janela grande, antes de receber dados

            fetch(this.getAttribute('href'))
                .then(response => response.text())
                .then(data => {
                    modalLargeContent.innerHTML = data;
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        };
    });

    //usa modal pequeno:   
    document.querySelectorAll(".modal-small").forEach(function (btn) {
        btn.onclick = function (event) {
            event.preventDefault();
            event.stopPropagation();
            animateModalWrapperOpen();  //cursor de carregamento e bloqueio de interface ocorrem imediatamente
            fetch(this.getAttribute('href'))
                .then(response => response.text())
                .then(data => {
                    document.body.style.cursor = "default";
                    modalSmallContent.innerHTML = data;
                    addTargetEvents();        //apenas para modal pequeno, não há formulários em modal grande;
                    animateModalSmallOpen();
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
    });

    //usa modal com iframe
    document.querySelectorAll(".iframe-small").forEach(function (btn) {
        btn.onclick = function (event) {
            event.preventDefault();
            animateModalWrapperOpen();
            modalSmallContent.innerHTML = `<iframe title="mqtt-write-view" src="${this.href}" width="640" height="420" frameBorder="0" allowTransparency="true"></iframe>`;
            animateModalSmallOpen();
        }
    });

    //sortir tabelas com clique no cabeçalho:
    document.querySelectorAll('th.sortable').forEach(th => th.onclick = function () {
        const table = th.closest('table');
        const allHeaders = Array.from(table.querySelectorAll('thead th.sortable'));
        const index = allHeaders.indexOf(th) + 1;
        sortTable(table.id, index);
    });

    //barra de pesquisa instantânea:
    document.querySelectorAll('.tabela-pesquisa-campo').forEach(input => {
        input.onkeyup = function () {
            let tableId = this.dataset.tableId;
            let table = document.getElementById(tableId);
            searchTable(table, this.value);
        };
    });

    //transforma células inteiras em clicáveis:
    let cells = document.getElementsByTagName('td');
    for (let i = 0; i < cells.length; i++) {
        cells[i].onclick = function () {
            let link = this.getElementsByTagName('a')[0];
            if (link) {
                link.click();
            }
        };
    }

    //previne clique com botão do meio:
    let links = document.getElementsByTagName('a');
    for (let i = 0; i < links.length; i++) {
        links[i].onauxclick = function (event) {
            event.preventDefault();
        };
        links[i].oncontextmenu = function (event) {
            event.preventDefault();
        };
        //o que é isso?
        // links[i].on = function (event) {
        //     event.preventDefault();
        // };
    }
}

function firstTag(event, id) {
    event.preventDefault();
    console.log(id);
    animateModalSmallClose().then(() => {   //aguarda animação de fechamento antes de abrir novamente:
        animateModalWrapperOpen();
        animateModalSmallOpen();
        modalSmallContent.innerHTML = `<iframe src="/admin/write_tag/${id}" width="640" height="420" frameBorder="0" allowTransparency="true"></iframe>`;
    });
}
function fetchAlunos() {
    return fetch("/admin/tabela_alunos")
        .then(response => response.text())
        .then(data => new Promise(resolve => {  //para Promise.all em window.onload
            document.getElementById('tabela-alunos-wrapper').innerHTML = data;
            addTablesListeners();
            resolve();
        }))
        .catch((error) => {
            console.error('Error:', error);
        });
}

function fetchResp() {
    return fetch("/admin/tabela_resp")
        .then(response => response.text())
        .then(data => new Promise(resolve => {  //para Promise.all em window.onload
            document.getElementById('tabela-resp-wrapper').innerHTML = data;
            addTablesListeners();
            resolve();
        }))
        .catch((error) => {
            console.error('Error:', error);
        });
}

function fetchEvt() {
    return fetch("/admin/tabela_evt")
        .then(response => response.text())
        .then(data => new Promise(resolve => {  //para Promise.all em window.onload
            document.getElementById('tabela-evt-wrapper').innerHTML = data;
            resolve();
        }))
        .catch((error) => {
            console.error('Error:', error);
        });
}





/** Animações: */
function animateModalWrapperOpen() {
    document.body.style.cursor = "progress";
    // modalWrapperSmall.style.willChange = 'opacity';
    modalWrapperSmall.style.transition = 'none';
    modalWrapperSmall.style.zIndex = "2";
    setTimeout(() => {
        modalWrapperSmall.style.transition = "0.2s all ease-in";
        modalWrapperSmall.style.opacity = "1";
    }, 20);  // necessário 10ms para evitar otimização fora-de-ordem.
}
function animateModalSmallOpen() {    //modalWrapperSmall é animado no instante de atualização do cursor em vez de aqui.
    // modalSmall.style.willChange = 'opacity, transform, z-index';
    modalSmall.style.transition = "0.33s all ease-out";
    modalSmall.style.opacity = "1";
    modalSmall.style.transform = "scale(1)";
    modalSmall.style.zIndex = "2";
}

function animateModalSmallClose() {
    // modalWrapperSmall.style.willChange = 'opacity, z-index';
    // modalSmall.style.willChange = 'opacity, transform, z-index';
    modalWrapperSmall.style.transition = "0.5s all ease";
    modalWrapperSmall.style.opacity = "0";
    modalSmall.style.opacity = "0";
    modalSmall.style.transform = "scale(0.9)";
    modalSmall.style.zIndex = "-1";

    fetchAlunos();  //atualiza tabelas possivelmente relevantes ao fechar modal;
    fetchResp();    //atualiza tabelas possivelmente relevantes ao fechar modal;
    document.body.style.cursor = "default";
    
    // Use setTimeout to delay the zIndex change
    setTimeout(() => {
        modalWrapperSmall.style.zIndex = "-1";
    }, 500);  // Change '500' to your desired delay in milliseconds

    return new Promise(resolve => {     //promessa necessária para timing de fechamento e reabertura de modal
        setTimeout(resolve, 510);       //resolve a promessa após animação
    });
}

function animateModalLargeOpen() {
    document.getElementById('modal-large-content').innerHTML = ' <h1 id="modal-body">Carregando...</h1>';
    // modalWrapperLarge.style.willChange = 'opacity, z-index';
    // modalLarge.style.willChange = 'opacity, transform, z-index';
    modalWrapperLarge.style.opacity = "1";
    modalWrapperLarge.style.transition = "0.1s all ease";
    modalWrapperLarge.style.zIndex = "2";
    modalLarge.style.transition = "0.33s all ease-out";
    modalLarge.style.opacity = "1";
    modalLarge.style.transform = "scale(1)";
    modalLarge.style.zIndex = "2";
}

function animateModalLargeClose() {
    // modalWrapperLarge.style.willChange = 'opacity, z-index';
    // modalLarge.style.willChange = 'opacity, transform, z-index';
    modalWrapperLarge.style.transition = "0.5s all ease";
    modalWrapperLarge.style.opacity = "0";
    modalLarge.style.opacity = "0";
    modalLarge.style.transform = "scale(0.9)";
    modalLarge.style.zIndex = "-1";

    // Use setTimeout to delay the zIndex change
    setTimeout(() => {
        modalWrapperLarge.style.zIndex = "-1";
    }, 510);  // Change '500' to your desired delay in milliseconds
}

closeLarge.onclick = function () { animateModalLargeClose(); }
closeSmall.onclick = function () { animateModalSmallClose(); }

document.addEventListener('keyup', function (event) {
    if (event.key === "Escape") {
        animateModalSmallClose();
        animateModalLargeClose();
    }
});






/** Pesquisa e reordenamento de tabelas: */

function sortTable(tableId, n) {
    let table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById(tableId);
    switching = true;
    dir = "asc";
    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            if (x && y) {
                if (dir == "asc") {
                    if (x.textContent.toLowerCase().trim() > y.textContent.toLowerCase().trim()) {
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (x.textContent.toLowerCase().trim() < y.textContent.toLowerCase().trim()) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchcount++;
        } else {
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}
function searchTable(table, query) {
    let filter, tbody, tr, td, i, j, txtValue;
    filter = query.toUpperCase();
    tbody = table.getElementsByTagName("tbody")[0];
    tr = tbody.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        tr[i].style.display = "none";  // Start by hiding the row
        td = tr[i].getElementsByTagName("td");
        for (j = 0; j < td.length; j++) {
            if (td[j]) {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";  // If a match is found, display the row
                    break;  // No need to check other cells; move on to the next row
                }
            }
        }
    }
}
function scrollToEvt(mode) {
    if(mode == "up"){
        let element = document.getElementById('admin-logo');
        element.scrollIntoView({behavior: "smooth"});
        document.getElementById("evt-link-btn").href="javascript:scrollToEvt('down')";
        document.getElementById("evt-link-btn").innerHTML = "Ver eventos recentes";
    } else {
        let element = document.getElementById('tabela-evt-wrapper');
        element.scrollIntoView({behavior: "smooth"});
        document.getElementById("evt-link-btn").href="javascript:scrollToEvt('up')";
        document.getElementById("evt-link-btn").innerHTML = "↑<br>Voltar";
    }
}

window.onload = function () {
    Promise.all([fetchAlunos(), fetchEvt(), fetchResp()])
        .then(() => {
            document.body.style.cursor = "default";
            document.getElementById("loading-placeholder").style.display = "none";
            document.getElementById("admin-wrapper").style.opacity = "1";
            document.getElementById("evt-link-btn").style.display = "unset";
            document.body.style.height="unset";
        })
        .catch((error) => {
            console.error('Error:', error);
        });

    animateModalLargeClose();
    animateModalSmallClose();
}
