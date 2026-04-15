<?php

namespace Modules\Api\Enums;

enum FacilityEnum: int
{
	case HOSPITAL = 1;
	case CLINIC = 2;

	public function name(): string
	{
		return match ($this) {
			self::HOSPITAL => 'Hospital',
			self::CLINIC => 'Clinic',
		};
	}
}
