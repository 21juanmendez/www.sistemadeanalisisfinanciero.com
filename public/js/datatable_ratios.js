
$(function () {
    $("#example4").DataTable({
        "pageLength": 15,
        "language": {
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Ratios",
            "infoEmpty": "Mostrando 0 a 0 de 0 Ratios",
            "infoFiltered": "(Filtrado de _MAX_ total Ratios)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Ratios",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscador:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        buttons: [{
            extend: "collection",
            text: "Reportes",
            orientation: "landscape",
            buttons: [{
                text: "Copiar",
                extend: "copy"
            },
            {
                extend: "pdf"
            },
            {
                extend: "csv"
            },
            {
                extend: "excel"
            },
            {
                text: "Imprimir",
                extend: "print"
            }
            ]
        },
        {
            extend: "colvis",
            text: "Visor de columnas",
            /* collectionLayout: "fixed three-column" */
        }
        ],
    }).buttons().container().appendTo("#example4_wrapper .col-md-6:eq(0)");
});
