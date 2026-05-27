<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\models;

use hipanel\modules\domain\Enum\Algorithm;
use hipanel\modules\domain\Enum\DigestType;
use Yii;

class Secdns extends \hipanel\base\Model
{
    const DNSKEY_FLAGS = 257;
    const DNSKEY_PROTOCOL = 3;

    use \hipanel\base\ModelTrait;

    /** {@inheritdoc} */
    public function rules()
    {
        return [
            [['id', 'domain_id', 'client_id', 'seller_id', 'key_tag', 'digest_alg', 'digest_type', 'key_alg', 'key_flags', 'key_protocol'], 'integer'],
            [['domain', 'client', 'seller', 'digest', 'pub_key'], 'safe'],
            [['id'], 'required', 'on' => ['delete']],
            [['domain_id', 'key_tag'], 'required', 'on' => ['create']],
            [['key_tag'], 'integer', 'min' => 1, 'max' => 65535],
            [['digest', 'pub_key'], 'filter', 'filter' => 'trim'],
            [['digest'], 'filter', 'filter' => 'strtoupper'],
            [['digest_alg', 'key_alg'], 'in', 'range' => array_keys(self::algorithmTypesWithLabels())],
            [['digest_type'], 'in', 'range' => array_keys(self::getDigestTypeLength())],
            [
                ['digest_alg', 'key_alg'],
                'in',
                'range' => self::deprecatedAlgorithms(),
                'not' => true,
                'message' => Yii::t('hipanel:domain', 'This DNSSEC algorithm is deprecated per RFC and MUST NOT be used for new records'),
                'on' => ['create'],
            ],
            [
                ['digest_type'],
                'in',
                'range' => self::deprecatedDigestTypes(),
                'not' => true,
                'message' => Yii::t('hipanel:domain', 'This digest type is deprecated per RFC and MUST NOT be used for new records'),
                'on' => ['create'],
            ],
            [
                ['key_alg', 'key_flags', 'key_protocol'],
                'required',
                'when' => function($model): bool {
                    return !empty($model->pub_key);
                },
                'whenClient' => "function (attribute, value) {
                    return $(this).parents('.item').find('input[id^=pub_key]').val() == '';
                }",
                'on' => ['create']
            ],
            [
                ['digest_alg', 'digest_type'],
                'required',
                'when' => function($model): bool {
                    return !empty($model->digest);
                },
                'whenClient' => "function (attribute, value) {
                    return $(this).parents('.item').find('input[id^=digest]').val() == '';
                }",
                'on' => ['create']
            ],
            [['digest'], 'match', 'pattern' => '/[^0-9A-Z][0-9A-Z]+/ui', 'not' => true],
            [['pub_key'], 'match', 'pattern' => '/[^\s][\s]+/ui', 'not' => true],
            [['digest'], 'string', 'on' => 'create'],
            [['digest'], 'validateDigestLength', 'on' => ['create']],
        ];
    }

    /** {@inheritdoc} */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'domain' => Yii::t('hipanel:domain', 'Domain'),
            'key_tag' => Yii::t('hipanel:domain', 'Key tag'),
            'digest' => Yii::t('hipanel:domain', 'Digest'),
            'digest_alg' => Yii::t('hipanel:domain', 'Digest algorithm'),
            'digest_type' => Yii::t('hipanel:domain', 'Digest type'),
            'key_alg' => Yii::t('hipanel:domain', 'Key algorithm'),
            'key_flags' => Yii::t('hipanel:domain', 'Key flag'),
            'key_protocol' => Yii::t('hipanel:domain', 'Key protocol'),
            'pub_key' => Yii::t('hipanel:domain', 'Public key'),
        ]);
    }

    public static function deprecatedAlgorithms(): array
    {
        return array_map(
            fn(Algorithm $a) => $a->value,
            array_filter(Algorithm::cases(), fn(Algorithm $a) => $a->isDeprecated())
        );
    }

    public static function deprecatedDigestTypes(): array
    {
        return array_map(
            fn(DigestType $t) => $t->value,
            array_filter(DigestType::cases(), fn(DigestType $t) => $t->isDeprecated())
        );
    }

    public static function algorithmTypesWithLabels(): array
    {
        return [
            Algorithm::RsaMd5->value          => Yii::t('hipanel:domain', 'RSA/MD5'),
            Algorithm::Dh->value              => Yii::t('hipanel:domain', 'Diffie-Hellman'),
            Algorithm::DsaSha1->value         => Yii::t('hipanel:domain', 'DSA/SHA1'),
            Algorithm::RsaSha1->value         => Yii::t('hipanel:domain', 'RSA/SHA1'),
            Algorithm::DsaNsec3Sha1->value    => Yii::t('hipanel:domain', 'DSA-NSEC3-SHA1'),
            Algorithm::Rsasha1Nsec3Sha1->value => Yii::t('hipanel:domain', 'RSASHA1-NSEC3-SHA1'),
            Algorithm::RsaSha256->value       => Yii::t('hipanel:domain', 'RSA/SHA-256'),
            Algorithm::RsaSha512->value       => Yii::t('hipanel:domain', 'RSA/SHA-512'),
            Algorithm::GostR->value           => Yii::t('hipanel:domain', 'GOST R 34.10-2001'),
            Algorithm::EcdsaP256Sha256->value => Yii::t('hipanel:domain', 'ECDSA Curve P-256 with SHA-256'),
            Algorithm::EcdsaP384Sha384->value => Yii::t('hipanel:domain', 'ECDSA Curve P-384 with SHA-384'),
            Algorithm::Ed25519->value         => Yii::t('hipanel:domain', 'ED25519'),
            Algorithm::Ed448->value           => Yii::t('hipanel:domain', 'ED448'),
        ];
    }

    public function getDigestAlgorithm(): string
    {
        return $this->getAlgorithmType('digest_alg');
    }

    public function getKeyAlgorithm(): string
    {
        return $this->getAlgorithmType('key_alg');
    }

    public function getAlgorithmType(string $attribute): string
    {
        $algs = self::algorithmTypesWithLabels();

        return (string) ($algs[$this->$attribute] ?? '');
    }

    public static function digestTypesWithLabels(): array
    {
        return [
            DigestType::Sha1->value   => Yii::t('hipanel:domain', 'SHA-1'),
            DigestType::Sha256->value => Yii::t('hipanel:domain', 'SHA-256'),
            DigestType::GostR->value  => Yii::t('hipanel:domain', 'GOST R 34.10-2001'),
            DigestType::Sha384->value => Yii::t('hipanel:domain', 'SHA-384'),
        ];
    }

    public function getDigestType(): string
    {
        $types = self::digestTypesWithLabels();

        return (string) ($types[$this->digest_type] ?? '');
    }

    public static function getDigestTypeLength(): array
    {
        return [
            DigestType::Sha1->value   => 32,
            DigestType::Sha256->value => 64,
            DigestType::GostR->value  => 32,
            DigestType::Sha384->value => 96,
        ];
    }

    public static function protocolTypesWithLabels(): array
    {
        return [
            self::DNSKEY_PROTOCOL => Yii::t('hipanel:domain', 'DNSSEC'),
        ];
    }

    public static function flagTypesWithLabels(): array
    {
        return [
            self::DNSKEY_FLAGS => Yii::t('hipanel:domain', 'KSK'),
        ];
    }

    public function validateDigestLength($attr, $value)
    {
        $length = self::getDigestTypeLength();

        if (strlen($this->$attr) === $length[$this->digest_type]) {
            return true;
        }

        $this->addError($attr, Yii::t("hipanel:domain", "Length of `$attr` should be {$length[$this->digest_type]}"));
        return false;
    }
}
