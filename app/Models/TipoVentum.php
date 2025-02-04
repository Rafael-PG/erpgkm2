<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoVentum
 * 
 * @property int $idTipoVenta
 * @property string|null $nombre
 * 
 * @property Collection|Credito[] $creditos
 *
 * @package App\Models
 */
class TipoVentum extends Model
{
	protected $table = 'tipo_venta';
	protected $primaryKey = 'idTipoVenta';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function creditos()
	{
		return $this->hasMany(Credito::class, 'idTipoVenta');
	}
}
