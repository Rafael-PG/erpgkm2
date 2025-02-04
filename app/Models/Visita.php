<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Visita
 * 
 * @property int $idVisitas
 * @property string|null $nombre
 * @property Carbon|null $fecha_programada
 * @property Carbon|null $fecha_asignada
 * @property Carbon|null $fechas_desplazamiento
 * @property Carbon|null $fecha_llegada
 * @property Carbon|null $fecha_inicio
 * @property Carbon|null $fecha_final
 * @property bool|null $estado
 * @property int|null $idTickets
 * 
 * @property Ticket|null $ticket
 * @property Collection|AccionesVisita[] $acciones_visitas
 * @property AnexosVisita $anexos_visita
 *
 * @package App\Models
 */
class Visita extends Model
{
	protected $table = 'visitas';
	protected $primaryKey = 'idVisitas';
	public $timestamps = false;

	protected $casts = [
		'fecha_programada' => 'datetime',
		'fecha_asignada' => 'datetime',
		'fechas_desplazamiento' => 'datetime',
		'fecha_llegada' => 'datetime',
		'fecha_inicio' => 'datetime',
		'fecha_final' => 'datetime',
		'estado' => 'bool',
		'idTickets' => 'int'
	];

	protected $fillable = [
		'nombre',
		'fecha_programada',
		'fecha_asignada',
		'fechas_desplazamiento',
		'fecha_llegada',
		'fecha_inicio',
		'fecha_final',
		'estado',
		'idTickets'
	];

	public function ticket()
	{
		return $this->belongsTo(Ticket::class, 'idTickets');
	}

	public function acciones_visitas()
	{
		return $this->hasMany(AccionesVisita::class, 'idVisitas');
	}

	public function anexos_visita()
	{
		return $this->hasOne(AnexosVisita::class, 'idVisitas');
	}
}
