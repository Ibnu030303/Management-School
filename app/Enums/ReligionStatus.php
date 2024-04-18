<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ReligionStatus: string implements HasLabel
{
    case Islam = 'Islam';
    case Kristen = 'Kristen';
    case Hindu = 'Hindu';
    case Budha = 'Budha';
    case Konghucu = 'Konghucu';
    case Protestan = 'Protestan';

    public function getLabel(): ?string
    {
        return $this->name;

        // or

        return match ($this) {
            self::Islam => 'Islam',
            self::Kristen => 'Kristen',
            self::Hindu => 'Hindu',
            self::Budha => 'Budha',
            self::Protestan => 'Protestan',
            self::Konghucu => 'Konghucu',
        };
    }
}
