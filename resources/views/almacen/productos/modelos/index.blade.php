<x-layout.default>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .panel {
            overflow: visible !important;
            /* Asegura que el modal no restrinja contenido */
        }
    </style>
    <div x-data="multipleTable">
        <div>
            <ul class="flex space-x-2 rtl:space-x-reverse">
                <li>
                    <a href="javascript:;" class="text-primary hover:underline">Almacen</a>
                </li>
                <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                    <span>Modelos</span>
                </li>
            </ul>
        </div>
        <div class="panel mt-6">
            <div class="md:absolute md:top-5 ltr:md:left-5 rtl:md:right-5">
                <div class="flex flex-wrap items-center justify-center gap-2 mb-5 sm:justify-start md:flex-nowrap">
                    <!-- Botón Exportar a Excel -->
                    <button type="button" class="btn btn-success btn-sm flex items-center gap-2"
                    onclick="window.location.href='{{ route('modelos.exportExcel') }}'">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <path
                                d="M4 3H20C21.1046 3 22 3.89543 22 5V19C22 20.1046 21.1046 21 20 21H4C2.89543 21 2 20.1046 2 19V5C2 3.89543 2 3 4 3Z"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M16 10L8 14M8 10L16 14" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span>Excel</span>
                    </button>

                    <!-- Botón Exportar a PDF -->
                    <button type="button" class="btn btn-danger btn-sm flex items-center gap-2"
                    onclick="window.location.href='{{ route('modelos.export.pdf') }}'">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                            <path
                                d="M2 5H22M2 5H22C22 6.10457 21.1046 7 20 7H4C2.89543 7 2 6.10457 2 5ZM2 5V19C2 20.1046 2.89543 21 4 21H20C21.1046 21 22 20.1046 22 19V5M9 14L15 14"
                                stroke="currentColor" stroke-width="1.5" />
                            <path d="M12 11L12 17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        <span>PDF</span>
                    </button>
                    <!-- Botón Agregar -->
                    <button type="button" class="btn btn-primary btn-sm flex items-center gap-2"
                        @click="$dispatch('toggle-modal')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                            fill="none">
                            <path
                                d="M6 3H18C19.1046 3 20 3.89543 20 5V19C20 20.1046 19.1046 21 18 21H6C4.89543 21 4 20.1046 4 19V5C4 3.89543 4.89543 3 6 3Z"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M8 7H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M8 12H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M8 17H12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>

                        <span>Agregar</span>
                    </button>
                </div>
            </div>

            <table id="myTable1" class="table whitespace-nowrap"></table>
        </div>
    </div>

    <!-- Modal -->
    <div x-data="{ open: false }" class="mb-5" @toggle-modal.window="open = !open">
        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="open && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="open = false">
                <div x-show="open" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8 animate__animated animate__zoomInUp">
                    <!-- Header del Modal -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Agregar Modelo</h5>
                        <button type="button" class="text-white-dark hover:text-dark" @click="open = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    <!-- Formulario -->
                    <div class="modal-scroll">
                        <form class="p-5 space-y-4" id="modeloForm" enctype="multipart/form-data" method="post">
                            @csrf <!-- Asegúrate de incluir el token CSRF -->
                            <!-- Nombre -->
                            <div>
                                <label for="nombre" class="block text-sm font-medium">Nombre</label>
                                <input id="nombre" name="nombre" class="form-input w-full" placeholder="Ingrese el nombre del modelo" required>
                            </div>
                            <!-- Marca -->
                            <div>
                                <label for="idMarca" class="block text-sm font-medium">Marca</label>
                                <select id="idMarca" name="idMarca" class="select2 w-full" style="display:none" required>
                                    <option value="" disabled selected>Seleccione la Marca</option>
                                    @foreach ($marcas as $marca)
                                        <option value="{{ $marca->idMarca }}">{{ $marca->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Categoría -->
                            <div>
                                <label for="idCategoria" class="block text-sm font-medium">Categoria</label>
                                <select id="idCategoria" name="idCategoria" class="select2 w-full" style="display:none" required>
                                    <option value="" disabled selected>Seleccione la Categoría</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->idCategoria }}">{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Botones -->
                            <div class="flex justify-end items-center mt-4">
                                <button type="button" class="btn btn-outline-danger" @click="open = false">Cancelar</button>
                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
  
        // Inicializar Select2
        document.addEventListener("DOMContentLoaded", function() {
            // Inicializar todos los select con la clase "select2"
            document.querySelectorAll('.select2').forEach(function(select) {
                NiceSelect.bind(select, {
                    searchable: true
                });
            });
        });
        document.addEventListener("alpine:init", () => {
            Alpine.data("form", () => ({
                date1: '',
                init() {
                    flatpickr(document.getElementById('fechaRegistro'), {
                        dateFormat: 'Y-m-d',
                        defaultDate: this.date1,
                        onChange: (selectedDates, dateStr) => {
                            this.date1 = dateStr; // Sincroniza el valor con Alpine.js
                        }
                    });
                }
            }));
        });
    </script>

    <script src="{{ asset('assets/js/Modelos/modelos.js') }}"></script>
    <script src="{{ asset('assets/js/Modelos/modeloStore.js') }}"></script>
    <script src="{{ asset('assets/js/Modelos/modeloValidaciones.js') }}"></script>

    <script src="/assets/js/simple-datatables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>

</x-layout.default>
