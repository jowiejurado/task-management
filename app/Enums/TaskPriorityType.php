<?php

namespace App\Enums;

enum TaskPriorityType: string
{
	case low 		= 'low';
	case medium = 'medium';
	case high 	= 'high';
}
