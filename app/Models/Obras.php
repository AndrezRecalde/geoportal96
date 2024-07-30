<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Obras
 * 
 * @property int $id
 * @property string|null $obra
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Obras extends Model
{
	protected $table = 'obras';
	public $timestamps = false;

	protected $fillable = [
		'obra'
	];
}
