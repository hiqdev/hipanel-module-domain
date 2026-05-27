<?php

namespace hipanel\modules\domain\Enum;

enum DigestType: int
{
    case Sha1   = 1;
    case Sha256 = 2;
    case GostR  = 3;
    case Sha384 = 4;

    public function isDeprecated(): bool
    {
        return match($this) {
            self::Sha1, self::GostR => true,
            default                 => false,
        };
    }
}
