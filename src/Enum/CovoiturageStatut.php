<?php

namespace App\Enum;

enum CovoiturageStatut: string
{
    case DRAFT = 'DRAFT';
    case PUBLISHED = 'PUBLISHED';
    case CANCELLED = 'CANCELLED';
    case TERMINE = 'TERMINE';
}
