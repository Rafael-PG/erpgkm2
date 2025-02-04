<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ticketapoyo
 * 
 * @property int $idTicketApoyo
 * @property int|null $idTecnico
 * @property int|null $idTicket
 * 
 * @property Usuario|null $usuario
 * @property Ticket|null $ticket
 *
 * @package App\Models
 */
class Ticketapoyo extends Model
{
	protected $table = 'ticketapoyo';
	protected $primaryKey = 'idTicketApoyo';
	public $timestamps = false;

	protected $casts = [
		'idTecnico' => 'int',
		'idTicket' => 'int'
	];

	protected $fillable = [
		'idTecnico',
		'idTicket'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'idTecnico');
	}

	public function ticket()
	{
		return $this->belongsTo(Ticket::class, 'idTicket');
	}
}
