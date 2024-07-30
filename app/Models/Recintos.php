<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Recintos
 * 
 * @property int $id
 * @property string|null $nombre
 * @property int|null $id_parroquia
 * @property bool|null $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Recintos extends Model
{
	protected $table = 'recintos';
	public $timestamps = false;

	protected $casts = [
		'id_parroquia' => 'int',
		'activo' => 'bool'
	];

	protected $fillable = [
		'nombre',
		'id_parroquia',
		'activo'
	];
}
