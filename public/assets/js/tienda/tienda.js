document.addEventListener("alpine:init", () => {
    Alpine.data("multipleTable", () => ({
        datatable1: null,
        tiendaData: [], // Almacena los datos actuales de la tabla
        // pollInterval: 2000, // Intervalo de polling (en ms)

        init() {
            // console.log("Component initialized for Tienda");

            // Obtener datos iniciales e inicializar la tabla
            this.fetchDataAndInitTable();
            // Configurar polling para verificar actualizaciones
            // setInterval(() => {
            //     this.checkForUpdates();
            // }, this.pollInterval);
        },

        fetchDataAndInitTable() {
            fetch("/api/tiendas")
                .then((response) => {
                    if (!response.ok) throw new Error("Error al obtener datos del servidor");
                    return response.json();
                })
                .then((data) => {
                    this.tiendaData = data;

                    // Inicializar DataTable con las nuevas cabeceras
                    this.datatable1 = new simpleDatatables.DataTable("#myTable1", {
                        data: {
                            headings: ["Ruc", "Nombre", "Celular", "Email", "Dirección", "Referencia", "Acción"], // Nuevas cabeceras
                            data: this.formatDataForTable(data),
                        },
                        searchable: true,
                        perPage: 10,
                        labels: {
                            placeholder: "Buscar...",
                            perPage: "{select}",
                            noRows: "No se encontraron registros",
                            info: "Mostrando {start} a {end} de {rows} registros",
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


        // Actualiza esta función para que incluya los nuevos datos
        formatDataForTable(data) {
            return data.map((tienda) => [
                `<div style="text-align: center;">${tienda.ruc}</div>`,       // RUC de la tienda
                `<div style="text-align: center;">${tienda.nombre}</div>`,    // Nombre de la tienda
                `<div style="text-align: center;">${tienda.celular}</div>`,   // Celular de la tienda
                `<div style="text-align: center;">${tienda.email}</div>`,     // Email de la tienda
                `<div style="text-align: center;">${tienda.direccion}</div>`, // Dirección de la tienda
                `<div style="text-align: center;">${tienda.referencia}</div>`,  // Referencia de la tienda
                `<div style="text-align: center;" class="flex justify-center items-center">
             <a href="/tienda/${tienda.idTienda}/edit" class="ltr:mr-2 rtl:ml-2" x-tooltip="Editar">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5">
                    <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                    <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5" />
                </svg>
            </a>
            <button type="button" x-tooltip="Eliminar" @click="deleteTienda(${tienda.idTienda})">
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




        deleteTienda(idTienda) {
            // console.log(`Se ha iniciado la solicitud para eliminar la tienda con ID: ${idTienda}`);
        
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
                    // console.log(`Confirmada la eliminación de la tienda con ID: ${idTienda}`);
        
                    // Hacer la solicitud de eliminación
                    fetch(`/api/tiendas/${idTienda}`, {
                        method: "DELETE",
                    })
                        .then((response) => {
                            // console.log(`Respuesta del servidor para la tienda con ID: ${idTienda}: ${response.status}`);
                            
                            // Verificar si la respuesta fue un error 400
                            if (!response.ok) {
                                return response.json().then((data) => {
                                    throw new Error(data.error || "Error desconocido");
                                });
                            }
                            
                            return response.json();
                        })
                        .then(() => {
                            // console.log(`Tienda ${idTienda} eliminada con éxito`);
        
                            // Actualizar la tabla eliminando la fila
                            this.tiendaData = this.tiendaData.filter(
                                (tienda) => tienda.idTienda !== idTienda
                            );
                            this.datatable1.rows().remove(
                                (row) =>
                                    row.cells[0].innerHTML === idTienda.toString()
                            );
        
                            // Mostrar notificación de éxito
                            new window.Swal({
                                title: '¡Eliminado!',
                                text: 'La tienda ha sido eliminada con éxito.',
                                icon: 'success',
                                customClass: 'sweet-alerts',
                            }).then(() => {
                                // console.log('Recargando la página después de la eliminación exitosa.');
                                location.reload();
                            });
                        })
                        .catch((error) => {
                            // console.error("Error al eliminar tienda:", error);
        
                            // Mostrar notificación de error
                            new window.Swal({
                                title: 'Error',
                                text: error.message || 'Ocurrió un error al eliminar la tienda.',
                                icon: 'error',
                                customClass: 'sweet-alerts',
                            });
                        });
                } else {
                    // console.log(`La eliminación de la tienda con ID: ${idTienda} fue cancelada.`);
                }
            });
        }
        
        

    }));
});
