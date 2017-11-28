<?php

namespace hipanel\modules\domain\models;

trait PaidFeatureForwardingTrait
{
    public function setFeature()
    {
        if ($this->isNewRecord) {
            $data = [
                $this->domain_id => [1 => $this->attributes],
            ];
        } else {
            $data = [
                $this->domain_id => [$this->id => $this->attributes],
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

    private function buildAction($opperation)
    {
        $forwardObj = str_replace('fw', '', strtolower((new \ReflectionClass($this))->getShortName()));

        return "$opperation-$forwardObj-forwarding";
    }
}
