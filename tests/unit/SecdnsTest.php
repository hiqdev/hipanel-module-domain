<?php

declare(strict_types=1);

namespace hipanel\modules\domain\tests\unit;

use hipanel\modules\domain\Enum\Algorithm;
use hipanel\modules\domain\Enum\DigestType;
use hipanel\modules\domain\models\Secdns;
use PHPUnit\Framework\TestCase;

class SecdnsTest extends TestCase
{
    public function testDeprecatedAlgorithmsContainsRfc8624Deprecated(): void
    {
        $deprecated = Secdns::deprecatedAlgorithms();

        $this->assertContains(Algorithm::RsaMd5->value, $deprecated, 'RSAMD5 must be deprecated');
        $this->assertContains(Algorithm::DsaSha1->value, $deprecated, 'DSA/SHA-1 must be deprecated');
        $this->assertContains(Algorithm::RsaSha1->value, $deprecated, 'RSA/SHA-1 must be deprecated');
        $this->assertContains(Algorithm::DsaNsec3Sha1->value, $deprecated, 'DSA-NSEC3-SHA1 must be deprecated');
        $this->assertContains(Algorithm::Rsasha1Nsec3Sha1->value, $deprecated, 'RSASHA1-NSEC3-SHA1 must be deprecated');
        $this->assertContains(Algorithm::GostR->value, $deprecated, 'GOST R must be deprecated');
    }

    public function testRecommendedAlgorithmsAreNotDeprecated(): void
    {
        $deprecated = Secdns::deprecatedAlgorithms();

        $this->assertNotContains(Algorithm::RsaSha256->value, $deprecated, 'RSA/SHA-256 must not be deprecated');
        $this->assertNotContains(Algorithm::EcdsaP256Sha256->value, $deprecated, 'ECDSA P-256 must not be deprecated');
        $this->assertNotContains(Algorithm::EcdsaP384Sha384->value, $deprecated, 'ECDSA P-384 must not be deprecated');
        $this->assertNotContains(Algorithm::Ed25519->value, $deprecated, 'Ed25519 must not be deprecated');
    }

    public function testDeprecatedDigestTypesContainsSha1AndGost(): void
    {
        $deprecated = Secdns::deprecatedDigestTypes();

        $this->assertContains(DigestType::Sha1->value, $deprecated, 'SHA-1 digest must be deprecated');
        $this->assertContains(DigestType::GostR->value, $deprecated, 'GOST R digest must be deprecated');
    }

    public function testSha256DigestIsNotDeprecated(): void
    {
        $deprecated = Secdns::deprecatedDigestTypes();

        $this->assertNotContains(DigestType::Sha256->value, $deprecated, 'SHA-256 is the required digest and must not be deprecated');
    }

    public function testDeprecatedAlgorithmsAreRemovedByArrayDiffKey(): void
    {
        $allAlgorithms = array_fill_keys(array_column(Algorithm::cases(), 'value'), '');

        $allowed = array_diff_key($allAlgorithms, array_flip(Secdns::deprecatedAlgorithms()));

        foreach (Secdns::deprecatedAlgorithms() as $alg) {
            $this->assertArrayNotHasKey($alg, $allowed, "Deprecated algorithm $alg must be filtered out");
        }
        $this->assertArrayHasKey(Algorithm::RsaSha256->value, $allowed);
        $this->assertArrayHasKey(Algorithm::EcdsaP256Sha256->value, $allowed);
        $this->assertArrayHasKey(Algorithm::EcdsaP384Sha384->value, $allowed);
        $this->assertArrayHasKey(Algorithm::Ed25519->value, $allowed);
    }

    public function testDeprecatedDigestTypesAreRemovedByArrayDiffKey(): void
    {
        $allDigestTypes = array_fill_keys(array_column(DigestType::cases(), 'value'), '');

        $allowed = array_diff_key($allDigestTypes, array_flip(Secdns::deprecatedDigestTypes()));

        foreach (Secdns::deprecatedDigestTypes() as $type) {
            $this->assertArrayNotHasKey($type, $allowed, "Deprecated digest type $type must be filtered out");
        }
        $this->assertArrayHasKey(DigestType::Sha256->value, $allowed);
        $this->assertArrayHasKey(DigestType::Sha384->value, $allowed);
    }
}
