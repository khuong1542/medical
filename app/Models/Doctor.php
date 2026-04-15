<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id', 'facility_id', 'specialty_id', 'code', 'name', 'email', 'phone', 'experience_years', 'description', 'order', 'status', 'created_at', 'updated_at'])]

class Doctor extends Model
{
	public $incrementing = false;

	public $sortable = ['order'];

	public function facilities()
	{
		return $this->belongsTo(Facility::class);
	}

	public function specialty()
	{
		return $this->belongsTo(Specialty::class);
	}
}
