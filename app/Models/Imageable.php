<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Imageable
 * 
 * @property int $id
 * @property string|null $url
 * @property int|null $imageable_id
 * @property string|null $imageable_type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @package App\Models
 */
class Imageable extends Model
{
	protected $table = 'imageable';
	public $timestamps = false;

	protected $casts = [
		'imageable_id' => 'int'
	];

	protected $fillable = [
		'url',
		'imageable_id',
		'imageable_type'
	];
	public function imageable()
    {
        return $this->morphTo();
    }
}
