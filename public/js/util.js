function seleccionarMenu (url) {
    $('ul.sidebar-menu a').filter(function () {
        return this.href == url;
    }).parent().addClass('active');

    $('ul.treeview-menu a').filter(function () {
        return this.href == url;
    }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');
}

function mensaje (locacion, tipo, mensaje, autocerrar = true) {
    let icono;
    switch (tipo) {
        case 'success':
            icono = 'glyphicon-ok';
            break;
        case 'danger':
            icono = 'glyphicon-remove';
            break;
        case 'warning':
            icono = 'glyphicon-warning-sign';
            break;
    }
    $(locacion).html(`
        <div class="alert alert-${tipo} fade in">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="glyphicon ${icono}"></span> ${mensaje}
        </div>`);

    if (autocerrar) {
        $(locacion).find('div.alert').fadeTo(10000, 500).slideUp(500, function () {
            $(this).alert('close');
        });
    }
}

function formatoNumero (numero, decimales, punto_decimal = ".", separador_miles = ",") {
    numero = (numero + '')
        .replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+numero) ? 0 : +numero,
        prec = !isFinite(+decimales) ? 0 : Math.abs(decimales),
        sep = (typeof separador_miles === 'undefined') ? ',' : separador_miles,
        dec = (typeof punto_decimal === 'undefined') ? '.' : punto_decimal,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + (Math.round(n * k) / k)
                .toFixed(prec);
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
        .split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '')
        .length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1)
            .join('0');
    }
    return s.join(dec);
}

function activarSoloDecimales () {
    $('body').on("keypress", ".decimal", function (event) {
        if (event.keyCode == 44 || event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 45 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
            (event.keyCode == 65 && event.ctrlKey === true)) {
            return;
        } else {
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57)) {
                event.preventDefault();
            }
        }
    });
}

function activarSoloEnteros () {
    $('body').on("keypress", ".entero", function (event) {
        if (event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
            (event.keyCode == 65 && event.ctrlKey === true)) {
            return;
        } else {
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57)) {
                event.preventDefault();
            }
        }
    });
}

//Requiere de la librería lobibox
function notify (tipo, mensaje) {
    Lobibox.notify(tipo, {
        size: 'mini',
        rounded: true,
        sound: false,
        delayIndicator: false,
        msg: mensaje
    });
}