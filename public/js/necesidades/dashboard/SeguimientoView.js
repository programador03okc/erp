class SeguimientoView {

    constructor(model) {
        this.model = model;

    }

    listar = (page) => {
        let fun = this;

        $("#tablaSeguimiento").LoadingOverlay("show", {
            imageAutoResize: true,
            progress: true,
            imageColor: "#3c8dbc"
        });

        this.model.listar(page).then((respuesta) => {
            fun.listarSeguimiento(respuesta);
        }).fail((respuesta) => {
            console.log(respuesta);
        }).always(() => {
            $("#tablaSeguimiento").LoadingOverlay("hide", true);
        });

    };


    listarSeguimiento = (respuesta) =>{
        console.log(respuesta);
        
        let fun = this;
        fun.construirPaginador(respuesta);
        fun.limpiarTabla('tablaSeguimiento');
        fun.construirTabla(respuesta);
        
    }

    limpiarTabla=(idElement)=>{
        let nodeTbody = document.querySelector("table[id='" + idElement + "'] tbody");
        if (nodeTbody != null) {
            while (nodeTbody.children.length > 0) {
                nodeTbody.removeChild(nodeTbody.lastChild);
            }

        }
    }

    construirPaginador =(data)=>{

        document.querySelector("ul[id='paginadorSeguimiento']").innerHTML='';

        let estructuraPaginador= '';
        if(data.hasOwnProperty('links')){
            // let paginaActual= data.current_page;
            (data.links).forEach(element => {
                estructuraPaginador +=`
                <li class="${element.active?'active':''}">
                    <a href="#" data-action="cargar-pagina" data-request-url="${element.url??''}" aria-label="${element.label}">
                        ${element.label??''}
                    </a>
                </li>`;
            });
        }

        document.querySelector("ul[id='paginadorSeguimiento']").insertAdjacentHTML('beforeend', estructuraPaginador)

    }

    cargarPagina=(e)=>{
        console.log(e.currentTarget);
    }

    construirTabla =(data)=>{
        let htmlTabla= '';

        if(data.hasOwnProperty('data')){
            (data.data).forEach(element => {
                htmlTabla+=`
                <tr>
                <td class="text-center">${element.codigo_requerimiento}</td>
                <td class="text-center">${element.codigo_oportunidad}</td>
                <td class="text-center">${element.dias_para_entrega}</td>
                <td class="text-center">${element.comercial}</td>
                <td class="text-center">${element.compras}</td>
                <td class="text-center">${element.almacen}</td>
                <td class="text-center">${element.cas}</td>
                <td class="text-center"></td>
            </tr>
                `;
            });
        }
        document.querySelector("table[id='tablaSeguimiento'] tbody").insertAdjacentHTML('beforeend', htmlTabla)

    }

    eventos = () => {
        let fun = this;
        $(document).on('click','a[data-action="cargar-pagina"]',function (e) {

            let requestUrl = $(e.currentTarget).attr('data-request-url');
            let page = requestUrl.split('=');
            fun.listar(page[1]);
        });
        // $(document).on('click','button[data-action="mostar-mas"]',function (e) {

        //     let href = $(e.currentTarget).attr('data-href');
        //     let page = href.split('=');
        //     fun.listarODI(page[1]);

        // });
    }

    
}