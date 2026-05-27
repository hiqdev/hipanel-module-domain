<?php

namespace hipanel\modules\domain\Enum;

enum Algorithm: int
{
    case RsaMd5             = 1;
    case Dh                 = 2;
    case DsaSha1            = 3;
    case RsaSha1            = 5;
    case DsaNsec3Sha1       = 6;
    case Rsasha1Nsec3Sha1   = 7;
    case RsaSha256          = 8;
    case RsaSha512          = 10;
    case GostR              = 12;
    case EcdsaP256Sha256    = 13;
    case EcdsaP384Sha384    = 14;
    case Ed25519            = 15;
    case Ed448              = 16;

    public function isDeprecated(): bool
    {
        return match($this) {
            self::RsaMd5,
            self::DsaSha1,
            self::RsaSha1,
            self::DsaNsec3Sha1,
            self::Rsasha1Nsec3Sha1,
            self::GostR => true,
            default     => false,
        };
    }
}
