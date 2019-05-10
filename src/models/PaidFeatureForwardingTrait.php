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

trait PaidFeatureForwardingTrait
{
    public static function tableName()
    {
        return 'domain';
    }

    public function setFeature()
    {
        if ($this->getFeatureModelName() !== 'parking') {
            if ($this->isNewRecord) {
                $data = [
                    $this->domain_id => [1 => $this->attributes],
                ];
            } else {
                $data = [
                    $this->domain_id => [$this->id => $this->attributes],
                ];
            }
        } else {
            $data = [
                $this->domain_id => $this->attributes,
            ];
        }

        return self::batchPerform($this->buildSetAction(), $data);
    }

    public function loadFeatureByDomainId($domainId, $featureId)
    {
        $apiData = self::perform($this->buildGetAction(), ['id' => $domainId]);
        $this->attributes = $apiData['forwarding'][$featureId];
    }

    private function buildSetAction()
    {
        return $this->buildAction('set');
    }

    private function buildGetAction()
    {
        return $this->buildAction('get');
    }

    public function getFeatureModelName()
    {
        return strtolower((new \ReflectionClass($this))->getShortName());
    }

    private function buildAction($opperation)
    {
        $objName = $this->getFeatureModelName();
        if (strstr($objName, 'fw') !== false) {
            $command = str_replace('fw', '', $objName) . '-forwarding';
        } else {
            $command = $objName;
        }

        return "$opperation-$command";
    }
}
