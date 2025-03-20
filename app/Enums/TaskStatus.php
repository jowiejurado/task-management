<?php

namespace App\Enums;

enum TaskStatus: string
{
	case pending 			= 'pending';
	case in_progress 	= 'in progress';
	case completed 		= 'completed';
}
