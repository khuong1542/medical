<?php

namespace App\Enums;

enum FacilityType: int
{
	case HOSPITAL = 1;
	case CLINIC = 2;
	case PHARMACY = 3;

	public function label(): string
	{
		return match ($this) {
			self::HOSPITAL => 'Hospital',
			self::CLINIC => 'Clinic',
			self::PHARMACY => 'Pharmacy',
		};
	}
}
