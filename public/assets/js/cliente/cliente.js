document.addEventListener("alpine:init", () => {
    Alpine.data("multipleTable", () => ({
        datatable1: null,
        clienteData: [], // Almacena los datos actuales de la tabla de clientes
        // pollInterval: 2000, // Intervalo de polling (en ms)

        init() {
            // console.log("Component initialized for Cliente");

            // Obtener datos iniciales e inicializar la tabla
            this.fetchDataAndInitTable();

            // Configurar polling para verificar actualizaciones
            // setInterval(() => {
            //     this.checkForUpdates();
            // }, this.pollInterval);
        },

        fetchDataAndInitTable() {
            fetch("/api/clientes") // Cambia la URL de la API a /api/clientes
                .then((response) => {
                    if (!response.ok) throw new Error(
                        "Error al obtener datos del servidor");
                    return response.json();
                })
                .then((data) => {
                    this.clienteData = data;

                    // Inicializar DataTable con las nuevas cabeceras
                    this.datatable1 = new simpleDatatables.DataTable("#myTable1", {
                        data: {
                            headings: ["Tipo Documento", "Documento", "Nombre",
                                "Teléfono", "Email", 
                                "Dirección", "Estado", "Acción"
                            ], // Nuevas cabeceras
                            data: this.formatDataForTable(
                                data
                            ), // Asegúrate de que esta función mapee los nuevos datos
                        },
                        searchable: true,
                        perPage: 10,
                        labels: {
                            placeholder: "Buscar...", // Placeholder de búsqueda
                            perPage: "{select} registros por página", // Selección de registros por página
                            noRows: "No se encontraron registros", // Mensaje cuando no hay registros
                            info: "", // Información de la tabla
                        },
                        layout: {
                            top: "{search}", // Posición del campo de búsqueda
                            bottom: "{info}{select}{pager}", // Posición de información, selector y paginador
                        },
                    });
                    // Centrando los encabezados manualmente
                    const headers = document.querySelectorAll("#myTable1 thead th");
                    headers.forEach((header) => {
                        header.style.textAlign = "center";
                        header.style.verticalAlign = "middle";
                    });
                })
                .catch((error) => {
                    console.error("Error al inicializar la tabla:", error);
                });
        },

        // Actualiza esta función para que incluya los nuevos datos de cliente
        formatDataForTable(data) {
            return data.map((cliente) => [
                `<div style="text-align: center;">${cliente.idTipoDocumento}</div>`, // Tipo de Documento
                `<div style="text-align: center;">${cliente.documento}</div>`,       // Documento
                `<div style="text-align: center;">${cliente.nombre}</div>`,          // Nombre
                `<div style="text-align: center;">${cliente.telefono}</div>`,        // Teléfono
                `<div style="text-align: center;">${cliente.email}</div>`,           // Email
                `<div style="text-align: center;">${cliente.direccion}</div>`,       // Dirección
                `<div style="text-align: center;">
                    ${cliente.estado === 'Activo' ?
                    `<span class="badge badge-outline-success">Activo</span>` :
                    `<span class="badge badge-outline-danger">Inactivo</span>`}
                </div>`, // Estado

                `<div style="text-align: center;" class="flex justify-center items-center">
            <a href="/cliente/${cliente.idCliente}/edit" class="ltr:mr-2 rtl:ml-2" x-tooltip="Editar">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                    <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                    <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5" />
                </svg>
            </a>
            <button type="button" x-tooltip="Eliminar" @click="deleteCliente(${cliente.idCliente})">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                    <path opacity="0.5" d="M9.17065 4C9.58249 2.83481 10.6937 2 11.9999 2C13.3062 2 14.4174 2.83481 14.8292 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    <path opacity="0.5" d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    <path opacity="0.5" d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </button>
        </div>`
            ]);
        },

        // checkForUpdates() {
        //     fetch("/api/clientes") // Cambiar la URL de la API a /api/clientes
        //         .then((response) => {
        //             if (!response.ok) throw new Error("Error al verificar actualizaciones");
        //             return response.json();
        //         })
        //         .then((data) => {
                    

        //             // Detectar nuevas filas
        //             const newData = data.filter(
        //                 (newCliente) =>
        //                     !this.clienteData.some(
        //                         (existingCliente) =>
        //                             existingCliente.idCliente === newCliente.idCliente
        //                     )
        //             );

        //             if (newData.length > 0) {

        //                 // Agregar filas nuevas a la tabla
        //                 this.datatable1.rows().add(this.formatDataForTable(newData));
        //                 this.clienteData.push(...newData); // Actualizar clienteData
        //             }
        //         })
        //         .catch((error) => {
        //             console.error("Error al verificar actualizaciones:", error);
        //         });
        // },

        deleteCliente(idCliente) {

            new window.Swal({
                icon: 'warning',
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esta acción!",
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                padding: '2em',
                customClass: 'sweet-alerts',
            }).then((result) => {
                if (result.value) {
                    // Hacer la solicitud de eliminación
                    fetch(`/api/clientes/${idCliente}`, {
                        method: "DELETE",
                    })
                        .then((response) => {
                            if (!response.ok) throw new Error("Error al eliminar cliente");
                            return response.json();
                        })
                        .then((data) => {

                            // Verificar que el cliente ha sido eliminado correctamente
                            if (data.message) {

                                // Actualizar la lista de clientes en el frontend
                                this.clienteData = this.clienteData.filter(
                                    (cliente) => cliente.idCliente !== idCliente
                                );

                                // Obtener todas las filas de la tabla
                                const rows = this.datatable1.rows();


                                // Iterar sobre las filas de la tabla
                                Array.from(rows).forEach((row, index) => {
                                    // Depurar el contenido de cada fila
                                    console.log(`Fila ${index}:`, row);

                                    if (row.cells[0].innerText == idCliente.toString()) {
                                        console.log(`Eliminando fila con ID ${idCliente}`);
                                        this.datatable1.rows().remove(index); // Eliminar la fila
                                    }
                                });

                                // Mostrar notificación de éxito
                                new window.Swal({
                                    title: '¡Eliminado!',
                                    text: 'El cliente ha sido eliminado con éxito.',
                                    icon: 'success',
                                    customClass: 'sweet-alerts',
                                }).then(() => {
                                    // Recargar la página después de la eliminación exitosa
                                    location.reload();
                                });
                            } else {
                                throw new Error('No se pudo eliminar el cliente.');
                            }
                        })
                        .catch((error) => {
                            console.error("Error al eliminar cliente:", error);

                            // Mostrar notificación de error
                            new window.Swal({
                                title: 'Error',
                                text: 'Ocurrió un error al eliminar el cliente.',
                                icon: 'error',
                                customClass: 'sweet-alerts',
                            });
                        });
                }
            });
        }





    }));
});
// Inicializar Select2
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.select2').forEach(function (select) {
        NiceSelect.bind(select, {
            searchable: true
        });
    });
});



