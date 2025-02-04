<?php

use App\Http\Controllers\administracion\asociados\CastController;
use App\Http\Controllers\administracion\asociados\ClienteGeneralController;
use App\Http\Controllers\administracion\asociados\ClientesController;
use App\Http\Controllers\administracion\asociados\ProveedoresController;
use App\Http\Controllers\administracion\asociados\SubsidiarioController;
use App\Http\Controllers\administracion\asociados\TiendaController;
use App\Http\Controllers\administracion\cotizaciones\cotizacionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\dashboard\AdministracionController;
use App\Http\Controllers\dashboard\AlmacenController;
use App\Http\Controllers\dashboard\ComercialController;
use App\Http\Controllers\dashboard\TicketsController;
use App\Http\Controllers\administracion\UsuariosController;
use App\Http\Controllers\administracion\CompraController;
use App\Http\Controllers\administracion\asistencias\OficinaController;
use App\Http\Controllers\administracion\asistencias\TecnicoController;
use App\Http\Controllers\almacen\productos\ArticulosController;
use App\Http\Controllers\almacen\productos\ModelosController;
use App\Http\Controllers\almacen\productos\TipoArticuloController;
use App\Http\Controllers\almacen\productos\MarcaController;
use App\Http\Controllers\almacen\productos\CategoriasController;
use App\Http\Controllers\configuracion\ConfiguracionController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LockscreenController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Apps\ChatController;
use App\Http\Controllers\Apps\MailboxController;
use App\Http\Controllers\Apps\TodolistController;
use App\Http\Controllers\Apps\NotesController;
use App\Http\Controllers\Apps\ScrumboardController;
use App\Http\Controllers\Apps\ContactsController;
use App\Http\Controllers\Apps\CalendarController;
use App\Models\Clientegeneral;
use App\Models\Subsidiario;
use Illuminate\Http\Client\Request;
use App\Exports\ClientesGeneralExport;
use App\Exports\ClientesExport;
use App\Exports\TiendaExport;
use App\Exports\CastExport;
use App\Http\Controllers\almacen\productos\CategoriaController;
use App\Http\Controllers\tickets\OrdenesTrabajoController;
use App\Http\Controllers\almacen\kits\KitsController;
use App\Exports\ProveedoresExport;
use App\Exports\MarcasExport;
use App\Exports\CategoriaExport;
use App\Exports\ArticuloExport;
use App\Exports\ModeloExport;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UbigeoController;
use App\Http\Controllers\usuario\UsuarioController;
use Maatwebsite\Excel\Facades\Excel;
Auth::routes();

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Ruta para mostrar el formulario de login
// Route::get('/login', function () { return view('auth.login'); })->name('login');
// Ruta para manejar el envío del formulario de login
Route::post('/login', [LoginController::class, 'login'])->name('login');
// Ruta para cerrar sesión
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// Ruta protegida con middleware 'auth'
Route::post('/check-email', [AuthController::class, 'checkEmail']);
// Ruta para la pantalla de bloqueo
Route::get('/auth/cover-lockscreen', [LockscreenController::class, 'show'])->name('auth.lockscreen');
// Ruta para la pantalla de restablecimiento de contraseña
Route::get('/auth/cover-password-reset', [PasswordResetController::class, 'show'])->name('auth.password-reset');
Route::get('/', [AdministracionController::class, 'index'])->name('index')->middleware('auth');
Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion')->middleware('auth');
Route::post('/configuracion', [ConfiguracionController::class, 'store'])->name('configuracion.store')->middleware('auth');
Route::post('/configuracion/delete', [ConfiguracionController::class, 'delete'])->name('configuracion.delete');
// Ruta para el dashboard de almacén
Route::get('/almacen', [AlmacenController::class, 'index'])->name('almacen')->middleware('auth');
// Ruta para el dashboard comercial
Route::get('/comercial', [ComercialController::class, 'index'])->name('commercial')->middleware('auth');
// Ruta para el dashboard de tickets
Route::get('/tickets', [TicketsController::class, 'index'])->name('tickets')->middleware('auth');
// Ruta para Administración de Usuarios
Route::get('/administracion/usuarios', [UsuariosController::class, 'index'])->name('administracion.usuarios')->middleware('auth');
// Ruta para Administración de Compras
Route::get('/administracion/compras', [CompraController::class, 'index'])->name('administracion.compra')->middleware('auth');
//Rutas para Clientes Generales
Route::get('/cliente-general', [ClienteGeneralController::class, 'index'])->name('administracion.cliente-general')->middleware('auth');
Route::get('/cliente-general/{id}/edit', [ClienteGeneralController::class, 'edit'])->name('cliente-general.edit');
Route::get('/exportar-clientes-general', function () { return Excel::download(new ClientesGeneralExport, 'clientes_general.xlsx'); })->name('clientes-general.exportExcel');
Route::get('/clientes-general/export-pdf', [ClienteGeneralController::class, 'exportAllPDF']) ->name('clientes-general.exportPDF')->middleware('auth');

// Actualizar los datos del cliente general
Route::put('administracion/{id}', [ClienteGeneralController::class, 'update'])->name('cliente-general.update');
Route::post('/cliente-general/store', [ClienteGeneralController::class, 'store'])->name('cliente-general.store');
Route::get('/ubigeo/provincias/{departamento_id}', [UbigeoController::class, 'getProvinciasByDepartamento']);
Route::get('/ubigeo/distritos/{provincia_id}', [UbigeoController::class, 'getDistritosByProvincia']);
Route::get('/get-provincia/{departamentoId}', [UbigeoController::class, 'getProvincias']);
Route::get('/get-distrito/{provinciaId}', [UbigeoController::class, 'getDistritos']);


//Rutas para Tiendas
Route::get('/tienda', [TiendaController::class, 'index'])->name('administracion.tienda')->middleware('auth');
Route::post('/tiendas', [TiendaController::class, 'store'])->name('tiendas.store');
Route::get('/tienda/{idTienda}/edit', [TiendaController::class, 'edit'])->name('tienda.edit');
Route::put('/tienda/{idTienda}', [TiendaController::class, 'update'])->name('tiendas.update');
Route::get('/tienda/create', [TiendaController::class, 'create'])->name('tienda.create')->middleware('auth');
Route::get('/exportar-tiendas', function () { return Excel::download(new TiendaExport, 'reporte_tiendas.xlsx'); })->name('tiendas.exportExcel');
Route::get('/reporte-tiendas', [TiendaController::class, 'exportAllPDF'])->name('reporte.tiendas');





// // Ruta para actualizar una tienda
// Route::put('/api/tiendas/{id}', [TiendaController::class, 'update']);


Route::post('/check-nombre', function (Request $request) {
    $nombre = $request->input('nombre');
    $exists = Clientegeneral::where('descripcion', $nombre)->exists();
    return response()->json(['unique' => !$exists]);
});
// Ruta para Administracion de tiendas

// Ruta para Administracion Subsidiario

//Ruta para Administracion Cast
Route::get('/cast', [CastController::class, 'index'])->name('administracion.cast')->middleware('auth');
Route::post('/cast/store', [CastController::class, 'store'])->name('cast.store');
Route::get('/cast/{idCast}/edit', [CastController::class, 'edit'])->name('cast.edit');
Route::put('/cast/{idCast}', [CastController::class, 'update'])->name('casts.update');
Route::get('/exportar-cast', function () { return Excel::download(new CastExport, 'cast.xlsx'); })->name('cast.exportExcel');
Route::get('/reporte-cast', [CastController::class, 'exportAllPDF'])->name('reporte.cast');

// Route::get('/casts', [CastController::class, 'getAll']);
// Ruta para Administracion Subsidiario
// Route::get('/sub-sidiario/create', [SubsidiarioController::class, 'create'])->name('administracion.create')->middleware('auth');
//Ruta para Administracion Clientes
Route::get('/clientes', [ClientesController::class, 'index'])->name('administracion.clientes');
Route::post('/cliente/store', [ClientesController::class, 'store'])->name('cliente.store');
Route::get('/cliente/{idCliente}/edit', [ClientesController::class, 'edit'])->name('cliente.edit');
Route::put('/clientes/{idCliente}', [ClientesController::class, 'update'])->name('clientes.update');
Route::get('/exportar-clientes', function () { return Excel::download(new ClientesExport, 'clientes.xlsx'); })->name('clientes.exportExcel');
Route::get('/reporte-clientes', [ClientesController::class, 'exportAllPDF']) ->name('reporte.clientes');


//Ruta para Administracion Proveedores
Route::get('/proveedores', [ProveedoresController::class, 'index'])->name('administracion.proveedores')->middleware('auth');
Route::post('/proveedores/store', [ProveedoresController::class, 'store'])->name('proveedor.store');
Route::get('/proveedores/{idProveedor}/edit', [ProveedoresController::class, 'edit'])->name('proveedor.edit');
Route::put('/proveedores/{idProveedor}', [ProveedoresController::class, 'update'])->name('proveedores.update');
Route::get('/exportar-proveedores', function () { return Excel::download(new ProveedoresExport, 'proveedores.xlsx'); })->name('proveedores.exportExcel');
Route::get('/reporte-proveedores', [ProveedoresController::class, 'exportAllPDF'])->name('proveedores.pdf');


//Ruta para administracion cotizaciones
Route::get('/cotizaciones/crear-cotizacion', [cotizacionController::class, 'index'])->name('cotizaciones.crear-cotizacion')->middleware('auth');



Route::get('/apps/chat', [ChatController::class, 'index'])->name('apps.chat');
Route::get('/apps/mailbox', [MailboxController::class, 'index'])->name('apps.mailbox');
Route::get('/apps/todolist', [TodolistController::class, 'index'])->name('apps.todolist');
Route::get('/apps/notes', [NotesController::class, 'index'])->name('apps.notes');
Route::get('/apps/scrumboard', [ScrumboardController::class, 'index'])->name('apps.scrumboard');
Route::get('/apps/contacts', [ContactsController::class, 'index'])->name('apps.contacts');
Route::get('/apps/calendar', [CalendarController::class, 'index'])->name('apps.calendar');

// Route::view('/apps/chat', 'apps.chat');
// Route::view('/apps/mailbox', 'apps.mailbox');
// Route::view('/apps/todolist', 'apps.todolist');
// Route::view('/apps/notes', 'apps.notes');
// Route::view('/apps/scrumboard', 'apps.scrumboard');
// Route::view('/apps/contacts', 'apps.contacts');
// Route::view('/apps/calendar', 'apps.calendar');


/// MODULO DE ALMACEN /// 

// INICIO CATEGORIA /// 
Route::prefix('categoria')->name('categorias.')->group(function () {
    Route::get('/', [CategoriaController::class, 'index'])->name('index'); // Mostrar la vista principal
    Route::post('/store', [CategoriaController::class, 'store'])->name('store'); // Guardar una nueva categoría
    Route::get('/{id}/edit', [CategoriaController::class, 'edit'])->name('edit');// Editar una categoría
    Route::put('/update/{id}', [CategoriaController::class, 'update'])->name('update'); // Actualizar una categoría
    Route::delete('/{id}', [CategoriaController::class, 'destroy'])->name('destroy'); // Eliminar una categoría
    Route::get('/reporte-categorias', [CategoriaController::class, 'exportAllPDF'])->name('categorias.pdf'); // Exportar todas las categorías a PDF
    Route::get('/get-all', [CategoriaController::class, 'getAll'])->name('getAll'); // Obtener todas las categorías en formato JSON
    Route::post('/check-nombre', [CategoriaController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::get('/exportar-excel', function () {
        return Excel::download(new CategoriaExport, 'categorias.xlsx');
    })->name('exportExcel');
    
});

/// FIN CATEGORIA ///

/// INICIO MARCA ///

Route::prefix('marcas')->name('marcas.')->group(function () {
    Route::get('/', [MarcaController::class, 'index'])->name('index'); // Mostrar la vista principal
    Route::post('/store', [MarcaController::class, 'store'])->name('store'); // Guardar una nueva marca
    Route::get('/{id}/edit', [MarcaController::class, 'edit'])->name('edit'); // Editar una marca
    Route::put('/update/{id}', [MarcaController::class, 'update'])->name('update'); // Actualizar una marca
    Route::delete('/{id}', [MarcaController::class, 'destroy'])->name('destroy'); // Eliminar una marca
    Route::get('/reporte-marcas', [MarcaController::class, 'exportAllPDF'])->name('marcas.pdf'); // Exportar todas las marcas a PDF
    Route::get('/get-all', [MarcaController::class, 'getAll'])->name('getAll'); // Obtener todas las marcas en formato JSON
    Route::post('/check-nombre', [MarcaController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::get('/exportar-excel', function () {
        return Excel::download(new MarcasExport, 'marcas.xlsx');
    })->name('exportExcel');
    
});

/// FIN MARCA ///

/// INICIO MODELO ///
Route::prefix('modelos')->name('modelos.')->group(function () {
    Route::get('/', [ModelosController::class, 'index'])->name('index'); // Mostrar la vista principal
    Route::post('/store', [ModelosController::class, 'store'])->name('store'); // Guardar un nuevo modelo
    Route::get('/{id}/edit', [ModelosController::class, 'edit'])->name('edit'); // Editar un modelo
    Route::put('/update/{id}', [ModelosController::class, 'update'])->name('update'); // Actualizar un modelo
    Route::delete('/{id}', [ModelosController::class, 'destroy'])->name('destroy'); // Eliminar un modelo
    Route::get('/export-pdf', [ModelosController::class, 'exportAllPDF'])->name('export.pdf'); // Exportar todos los modelos a PDF
    Route::get('/get-all', [ModelosController::class, 'getAll'])->name('getAll'); // Obtener todos los modelos en formato JSON
    Route::post('/check-nombre', [ModelosController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::get('/exportar-excel', function () {
        return Excel::download(new ModeloExport, 'modelos.xlsx');
    })->name('exportExcel');
});

/// FIN MODELO ///


/// INICIO ARTICULOS ///
Route::prefix('articulos')->name('articulos.')->group(function () {
    Route::get('/', [ArticulosController::class, 'index'])->name('index'); // Mostrar la vista principal
    Route::post('/store', [ArticulosController::class, 'store'])->name('store'); // Guardar un nuevo artículo
    Route::get('/{id}/edit', [ArticulosController::class, 'edit'])->name('edit'); // Editar un artículo
    Route::put('/update/{id}', [ArticulosController::class, 'update'])->name('update'); // Actualizar un artículo
    Route::delete('/{id}', [ArticulosController::class, 'destroy'])->name('destroy'); // Eliminar un artículo
    Route::get('/export-pdf', [ArticulosController::class, 'exportAllPDF'])->name('export.pdf'); // Exportar todos los artículos a PDF
    Route::get('/get-all', [ArticulosController::class, 'getAll'])->name('getAll'); // Obtener todos los artículos en formato JSON
    Route::post('/check-nombre', [ArticulosController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
    Route::get('/exportar-excel', function () {
        return Excel::download(new ArticuloExport, 'articulos.xlsx');
    })->name('exportExcel');
});

/// FIN ARTICULO ///

/// KITS DE ARTICULOS ///

Route::prefix('kits')->name('almacen.kits.')->group(function () {
    Route::get('/', [KitsController::class, 'index'])->name('index'); // Mostrar todos los kits
    Route::get('/create', [KitsController::class, 'create'])->name('create'); // Crear un nuevo kit
    Route::post('/store', [KitsController::class, 'store'])->name('store'); // Guardar un nuevo kit
    Route::get('/{id}/edit', [KitsController::class, 'edit'])->name('edit'); // Editar un kit
    Route::put('/update/{id}', [KitsController::class, 'update'])->name('update'); // Actualizar un kit
    Route::delete('/{id}', [KitsController::class, 'destroy'])->name('destroy'); // Eliminar un kit
    Route::get('/export-pdf', [KitsController::class, 'exportAllPDF'])->name('export.pdf'); // Exportar a PDF
    Route::get('/get-all', [KitsController::class, 'getAll'])->name('getAll'); // Obtener datos en JSON
});


//INICIO TICKETS///

/// INICIO ÓRDENES DE TRABAJO ///
Route::prefix('ordenes')->name('ordenes.')->group(function () {
    Route::get('/', [OrdenesTrabajoController::class, 'index'])->name('index'); // Mostrar la vista principal
    Route::get('/smart', [OrdenesTrabajoController::class, 'smart'])->name('smart');
    Route::get('/helpdesk', [OrdenesTrabajoController::class, 'helpdesk'])->name('helpdesk');
    Route::get('/create-smart/', [OrdenesTrabajoController::class, 'createsmart'])->name('createsmart');
    Route::get('/create-helpdesk', [OrdenesTrabajoController::class, 'createhelpdesk'])->name('createhelpdesk')->middleware('auth'); // Crear una nueva orden de trabajo


    Route::post('/storehelpdesk', [OrdenesTrabajoController::class, 'storehelpdesk'])->name('storehelpdesk'); // Guardar una nueva orden de trabajo
    Route::post('/storesmart', [OrdenesTrabajoController::class, 'storesmart'])->name('storesmart'); // Guardar una nueva orden de trabajo
    Route::get('/{id}/edit', [OrdenesTrabajoController::class, 'edit'])->name('edit'); // Editar una orden de trabajo
    Route::put('/update/{id}', [OrdenesTrabajoController::class, 'update'])->name('update'); // Actualizar una orden de trabajo
    Route::delete('/{id}', [OrdenesTrabajoController::class, 'destroy'])->name('destroy'); // Eliminar una orden de trabajo

    // Exportar y Reportes
    Route::get('/export-pdf', [OrdenesTrabajoController::class, 'exportAllPDF'])->name('export.pdf'); // Exportar todas las órdenes de trabajo a PDF
    Route::get('/get-all', [OrdenesTrabajoController::class, 'getAll'])->name('getAll'); // Obtener todas las órdenes de trabajo en formato JSON

    // Validaciones
    Route::post('/check-nombre', [OrdenesTrabajoController::class, 'checkNombre'])->name('checkNombre'); // Validar si un nombre ya existe
});

// Rutas para obtener modelos por marca
Route::get('/modelos/{idMarca}', [OrdenesTrabajoController::class, 'obtenerModelosPorMarca']);
Route::get('/perfil', [UsuarioController::class, 'perfil'])->name('perfil');
Route::get('/usuario', [UsuarioController::class, 'index'])->name('usuario');

Route::get('/create/usuario', [UsuarioController::class, 'create'])->name('usuario.create');
Route::get('/usuario/{usuario}/edit', [UsuarioController::class, 'edit'])->name('usuario.edit');
Route::post('/usuario/store', [UsuarioController::class, 'store'])->name('usuarios.store');
Route::put('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
Route::put('config/{id}', [UsuarioController::class, 'config'])->name('usuario.config');
Route::put('direccion/{id}', [UsuarioController::class, 'direccion'])->name('usuario.direccion');
Route::put('/usuario/firma/{idUsuario}', [UsuarioController::class, 'actualizarFirma'])->name('usuario.firma');


// Route::get('/informe-pdf/{idTickets}', [OrdenesTrabajoController::class, 'generarInformePdf'])->name('informe.pdf');
Route::get('/ver-informe-pdf/{idTickets}', [OrdenesTrabajoController::class, 'verInforme']);
Route::get('/ver-hoja-entrega-pdf/{idTickets}', [OrdenesTrabajoController::class, 'verHojaEntrega']);
//Validaciones de tienda
Route::post('/validar/ructienda', [TiendaController::class, 'validarRuc'])->name('validar.ruc');
Route::post('/validar/emailtienda', [TiendaController::class, 'validarEmail'])->name('validar.email');
Route::post('/validar/celulartienda', [TiendaController::class, 'validarCelular'])->name('validar.celular');
Route::post('/validar/nombretienda', [TiendaController::class, 'validarNombre'])->name('validar.nombre');
//Validaciones de cast
Route::get('/validar/ruccast', [CastController::class, 'validarRucCast'])->name('validar.ruccast');
Route::get('/validar/emailcast', [CastController::class, 'validarEmailCast'])->name('validar.emailcast');
Route::get('/validar/celularcast', [CastController::class, 'validarTelefonoCast'])->name('validar.telefonocast');
Route::get('/validar/nombrecast', [CastController::class, 'validarNombreCast'])->name('validar.nombrecast');
//Validaciones de proveedores
Route::post('/validar/rucproveedores', [ProveedoresController::class, 'validarnumeroDocumentoProveedores'])->name('validar.numerodocumentoproveedores');
Route::post('/validar/emailproveedores', [ProveedoresController::class, 'validarEmailProveedores'])->name('validar.emailproveedores');
Route::post('/validar/celularproveedores', [ProveedoresController::class, 'validarTelefonoProveedores'])->name('validar.telefonoproveedores');
Route::post('/validar/nombreproveedores', [ProveedoresController::class, 'validarNombreProveedores'])->name('validar.nombreproveedores');


Route::view('/apps/invoice/list', 'apps.invoice.list');
Route::view('/apps/invoice/preview', 'apps.invoice.preview');
Route::view('/apps/invoice/add', 'apps.invoice.add');
Route::view('/apps/invoice/edit', 'apps.invoice.edit');



Route::view('/components/tabs', 'ui-components.tabs');
Route::view('/components/accordions', 'ui-components.accordions');
Route::view('/components/modals', 'ui-components.modals');
Route::view('/components/cards', 'ui-components.cards');
Route::view('/components/carousel', 'ui-components.carousel');
Route::view('/components/countdown', 'ui-components.countdown');
Route::view('/components/counter', 'ui-components.counter');
Route::view('/components/sweetalert', 'ui-components.sweetalert');
Route::view('/components/timeline', 'ui-components.timeline');
Route::view('/components/notifications', 'ui-components.notifications');
Route::view('/components/media-object', 'ui-components.media-object');
Route::view('/components/list-group', 'ui-components.list-group');
Route::view('/components/pricing-table', 'ui-components.pricing-table');
Route::view('/components/lightbox', 'ui-components.lightbox');

Route::view('/elements/alerts', 'elements.alerts');
Route::view('/elements/avatar', 'elements.avatar');
Route::view('/elements/badges', 'elements.badges');
Route::view('/elements/breadcrumbs', 'elements.breadcrumbs');
Route::view('/elements/buttons', 'elements.buttons');
Route::view('/elements/buttons-group', 'elements.buttons-group');
Route::view('/elements/color-library', 'elements.color-library');
Route::view('/elements/dropdown', 'elements.dropdown');
Route::view('/elements/infobox', 'elements.infobox');
Route::view('/elements/jumbotron', 'elements.jumbotron');
Route::view('/elements/loader', 'elements.loader');
Route::view('/elements/pagination', 'elements.pagination');
Route::view('/elements/popovers', 'elements.popovers');
Route::view('/elements/progress-bar', 'elements.progress-bar');
Route::view('/elements/search', 'elements.search');
Route::view('/elements/tooltips', 'elements.tooltips');
Route::view('/elements/treeview', 'elements.treeview');
Route::view('/elements/typography', 'elements.typography');

Route::view('/charts', 'charts');
Route::view('/widgets', 'widgets');
Route::view('/font-icons', 'font-icons');
Route::view('/dragndrop', 'dragndrop');

Route::view('/tables', 'tables');

Route::view('/datatables/advanced', 'datatables.advanced');
Route::view('/datatables/alt-pagination', 'datatables.alt-pagination');
Route::view('/datatables/basic', 'datatables.basic');
Route::view('/datatables/checkbox', 'datatables.checkbox');
Route::view('/datatables/clone-header', 'datatables.clone-header');
Route::view('/datatables/column-chooser', 'datatables.column-chooser');
Route::view('/datatables/export', 'datatables.export');
Route::view('/datatables/multi-column', 'datatables.multi-column');
Route::view('/datatables/multiple-tables', 'datatables.multiple-tables');
Route::view('/datatables/order-sorting', 'datatables.order-sorting');
Route::view('/datatables/range-search', 'datatables.range-search');
Route::view('/datatables/skin', 'datatables.skin');
Route::view('/datatables/sticky-header', 'datatables.sticky-header');

Route::view('/forms/basic', 'forms.basic');
Route::view('/forms/input-group', 'forms.input-group');
Route::view('/forms/layouts', 'forms.layouts');
Route::view('/forms/validation', 'forms.validation');
Route::view('/forms/input-mask', 'forms.input-mask');
Route::view('/forms/select2', 'forms.select2');
Route::view('/forms/touchspin', 'forms.touchspin');
Route::view('/forms/checkbox-radio', 'forms.checkbox-radio');
Route::view('/forms/switches', 'forms.switches');
Route::view('/forms/wizards', 'forms.wizards');
Route::view('/forms/file-upload', 'forms.file-upload');
Route::view('/forms/quill-editor', 'forms.quill-editor');
Route::view('/forms/markdown-editor', 'forms.markdown-editor');
Route::view('/forms/date-picker', 'forms.date-picker');
Route::view('/forms/clipboard', 'forms.clipboard');


Route::view('/users/user-account-settings', 'users.user-account-settings');

Route::view('/pages/knowledge-base', 'pages.knowledge-base');
Route::view('/pages/contact-us-boxed', 'pages.contact-us-boxed');
Route::view('/pages/contact-us-cover', 'pages.contact-us-cover');
Route::view('/pages/faq', 'pages.faq');
Route::view('/pages/coming-soon-boxed', 'pages.coming-soon-boxed');
Route::view('/pages/coming-soon-cover', 'pages.coming-soon-cover');
Route::view('/pages/error404', 'pages.error404');
Route::view('/pages/error500', 'pages.error500');
Route::view('/pages/error503', 'pages.error503');
Route::view('/pages/maintenence', 'pages.maintenence');


