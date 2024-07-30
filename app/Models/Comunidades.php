<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Comunidades
 * 
 * @property int $id
 * @property string|null $comunidad
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Comunidades extends Model
{
	protected $table = 'comunidades';
	public $timestamps = false;

	protected $fillable = [
		'comunidad'
	];
}
