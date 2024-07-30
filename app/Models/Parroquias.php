<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Parroquias
 * 
 * @property int $id
 * @property string|null $nombre
 * @property int|null $id_canton
 * @property bool|null $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Parroquias extends Model
{
	protected $table = 'parroquias';
	public $timestamps = false;

	protected $casts = [
		'id_canton' => 'int',
		'activo' => 'bool'
	];

	protected $fillable = [
		'nombre',
		'id_canton',
		'activo'
	];
}
