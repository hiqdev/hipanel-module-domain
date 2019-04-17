<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\tests\_support\Page;

use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;

class DomainIndexPage extends IndexPage
{
    /**
     * @param string $domainName
     * @throws \Codeception\Exception\ModuleException
     * @return string
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
