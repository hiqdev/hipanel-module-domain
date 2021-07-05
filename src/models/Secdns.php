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

use Exception;
use hipanel\helpers\ArrayHelper;
use hipanel\helpers\StringHelper;
use hipanel\validators\DomainValidator;
use hiqdev\hiart\ResponseErrorException;
use Yii;

class Secdns extends \hipanel\base\Model
{
    /**
     * Cryptographic algorythm
     */
    const ALGORITHM_RSA_MD5 = 1;
    const ALGORITHM_DH = 2;
    const ALGORITHM_DSA_SHA1 = 3;
    const ALGORITHM_RSA_SHA1 = 5;
    const ALGORITHM_DSA_NSEC3_SHA1 = 6;
    const ALGORITHM_RSASHA1_NSEC3_SHA1 = 7;
    const ALGORITHM_RSA_SHA256 = 8;
    const ALGORITHM_RSA_SHA512 = 10;
    const ALGORITHM_GOST_R = 12;
    const ALGORITHM_ECDSA_P256_SHA256 = 13;
    const ALGORITHM_ECDSA_P384_SHA384 = 14;
    const ALGORITHM_ED25519 = 15;
    const ALGORITHM_ED448 = 16;

    /**
     * Digest types
     */
    const DIGEST_TYPE_SHA1 = 1;
    const DIGEST_TYPE_SHA256 = 2;
    const DIGEST_TYPE_GOST_R = 3;
    const DIGEST_TYPE_SHA384 = 4;

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
            [['digest'], 'filter', 'filter' => function($value) {
                return mb_strtoupper($value);
            }],
            [['digest_alg', 'key_alg'], 'in', 'range' => array_keys(self::algorithmTypesWithLabels())],
            [['digest_type'], 'in', 'range' => array_keys(self::getDigestTypeLength())],
            [
                ['key_alg', 'key_flags', 'key_protocol'],
                'required',
                'when' => function($model) {
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
                'when' => function($model) {
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

    /**
     * SecDNS Algorithms with labels
     *
     * @return array
     */
    public static function algorithmTypesWithLabels() : array
    {
        return [
            self::ALGORITHM_RSA_MD5 => Yii::t('hipanel:domain', 'RSA/MD5'),
            self::ALGORITHM_DH => Yii::t('hipanel:domain', 'Diffie-Hellman'),
            self::ALGORITHM_DSA_SHA1 => Yii::t('hipanel:domain', 'DSA/SHA1'),
            self::ALGORITHM_RSA_SHA1 => Yii::t('hipanel:domain', 'RSA/SHA1'),
            self::ALGORITHM_DSA_NSEC3_SHA1 => Yii::t('hipanel:domain', 'DSA-NSEC3-SHA1'),
            self::ALGORITHM_RSASHA1_NSEC3_SHA1 => Yii::t('hipanel:domain', 'RSASHA1-NSEC3-SHA1'),
            self::ALGORITHM_RSA_SHA256 => Yii::t('hipanel:domain', 'RSA/SHA-256'),
            self::ALGORITHM_RSA_SHA512 => Yii::t('hipanel:domain', 'RSA/SHA-512'),
            self::ALGORITHM_GOST_R => Yii::t('hipanel:domain', 'GOST R 34.10-2001'),
            self::ALGORITHM_ECDSA_P256_SHA256 => Yii::t('hipanel:domain', 'ECDSA Curve P-256 with SHA-256'),
            self::ALGORITHM_ECDSA_P384_SHA384 => Yii::t('hipanel:domain', 'ECDSA Curve P-384 with SHA-384'),
            self::ALGORITHM_ED25519 => Yii::t('hipanel:domain', 'ED25519'),
            self::ALGORITHM_ED448 => Yii::t('hipanel:domain', 'ED448'),
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

    /**
     * Digest types with labels
     *
     * @return array
     */
    public static function digestTypesWithLabels() : array
    {
        return [
            self::DIGEST_TYPE_SHA1 => Yii::t('hipanel:domain', 'SHA-1'),
            self::DIGEST_TYPE_SHA256 => Yii::t('hipanel:domain', 'SHA-256'),
            self::DIGEST_TYPE_GOST_R => Yii::t('hipanel:domain', 'GOST R 34.10-2001'),
            self::DIGEST_TYPE_SHA384 => Yii::t('hipanel:domain', 'SHA-384'),
        ];
    }

    public function getDigestType(): string
    {
        $types = self::digestTypesWithLabels();

        return (string) ($types[$this->digest_type] ?? '');
    }

    /**
     * Length of digest for digest types
     *
     * @return array
     */
    public static function getDigestTypeLength() : array
    {
        return [
            self::DIGEST_TYPE_SHA1 => 32,
            self::DIGEST_TYPE_SHA256 => 64,
            self::DIGEST_TYPE_GOST_R => 32,
            self::DIGEST_TYPE_SHA384 => 96,
        ];
    }

    public static function protocolTypesWithLabels() : array
    {
        return [
            self::DNSKEY_PROTOCOL => Yii::t('hipanel:domain', 'DNSSEC'),
        ];
    }

    public static function flagTypesWithLabels() : array
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
