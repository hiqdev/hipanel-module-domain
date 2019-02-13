<?php

namespace hipanel\modules\domain\tests\_support\Page;

use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;

class DomainIndexPage extends IndexPage
{
    /**
     * @param string $domainName
     * @return string
     * @throws \Codeception\Exception\ModuleException
     */
    public function getDomainId(string $domainName): string
    {
        $this->filterBy(
            Input::asTableFilter($this->tester, 'Domain name'), $domainName
        );
        $this->containsAmountOfRows(1);

        return $this->getRowDataKeyByNumber(1);
    }
}
