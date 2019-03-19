<?php

namespace hipanel\modules\domain\tests\_support;

class Domain
{
    /** @var string */
    private $name = 'bladeroot.net';

    /** @var string */
    private $domainId;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $domainId
     */
    public function setDomainId(string $domainId): void
    {
        $this->domainId = $domainId;
    }

    public function getDomainId(): int
    {
        return $this->domainId;
    }
}
