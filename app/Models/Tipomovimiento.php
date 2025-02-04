<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tipomovimiento
 * 
 * @property int $idTipoMovimiento
 * @property string|null $nombre
 * 
 * @property Collection|Movimientoarticulo[] $movimientoarticulos
 *
 * @package App\Models
 */
class Tipomovimiento extends Model
{
	protected $table = 'tipomovimiento';
	protected $primaryKey = 'idTipoMovimiento';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function movimientoarticulos()
	{
		return $this->hasMany(Movimientoarticulo::class, 'idTipoMovimiento');
	}
}
