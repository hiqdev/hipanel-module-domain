<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\bootstrap;

use DateTimeImmutable;
use DateTimeZone;
use Exception;
use hipanel\modules\client\models\Client;
use hipanel\modules\domain\widgets\ServiceTerminationNotice;
use hipanel\modules\finance\models\Tariff;
use hiqdev\hiart\ResponseErrorException;
use yii\base\BootstrapInterface;
use yii\web\View;
use Yii;

class ServiceTerminationNoticeBootstrap implements BootstrapInterface
{
    private const int TARIFF_CHECK_CACHE_DURATION = 3600;

    public function bootstrap($app)
    {
        // Accepts string values from env as well: "false" must disable the notice.
        if (!filter_var(Yii::$app->params['service-termination-notice.enabled'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            return;
        }

        if (!$this->hasStarted()) {
            return;
        }

        $user = Yii::$app->user;
        if ($user->isGuest || $user->identity->type !== Client::TYPE_CLIENT) {
            return;
        }

        $sellers = $this->getListParam('service-termination-notice.sellers');
        if (!in_array($user->identity->seller, $sellers, true)) {
            return;
        }

        $tariffIds = $this->getListParam('service-termination-notice.tariff_ids');
        if ($tariffIds !== [] && !$this->isAnyDomainTariffAvailable($tariffIds)) {
            return;
        }

        $startDate = (string) (Yii::$app->params['service-termination-notice.start_date'] ?? '');
        Yii::$app->view->on(View::EVENT_END_BODY, function () use ($startDate) {
            echo ServiceTerminationNotice::widget(['startDate' => $startDate ?: null]);
        });
    }

    /**
     * The notice is shown from `service-termination-notice.start_date` (UTC) on; no date means no restriction.
     */
    private function hasStarted(): bool
    {
        $startDate = (string) (Yii::$app->params['service-termination-notice.start_date'] ?? '');
        if ($startDate === '') {
            return true;
        }

        try {
            $start = new DateTimeImmutable($startDate, new DateTimeZone('UTC'));
        } catch (Exception $e) {
            Yii::error("Invalid service termination notice start date \"$startDate\": {$e->getMessage()}", __METHOD__);

            return false;
        }

        return new DateTimeImmutable('now', new DateTimeZone('UTC')) >= $start;
    }

    private function getListParam(string $name): array
    {
        $value = Yii::$app->params[$name] ?? [];

        return is_array($value) ? $value : [];
    }

    /**
     * Runs on every page, so the API answer is cached per client.
     */
    private function isAnyDomainTariffAvailable(array $tariffIds): bool
    {
        $key = [__METHOD__, Yii::$app->user->id, $tariffIds];

        // Stored as int: a cached `false` is indistinguishable from a cache miss.
        return (bool) Yii::$app->cache->getOrSet($key, function () use ($tariffIds): int {
            return (int) $this->fetchIsAnyDomainTariffAvailable($tariffIds);
        }, self::TARIFF_CHECK_CACHE_DURATION);
    }

    private function fetchIsAnyDomainTariffAvailable(array $tariffIds): bool
    {
        try {
            $tariffs = Tariff::find()
                ->action('get-available')
                ->andWhere(['type' => 'domain', 'ids' => implode(',', $tariffIds)])
                ->all();
        } catch (ResponseErrorException $e) {
            // The notice is informational: an API failure must not break every page.
            Yii::error("Failed to check available domain tariffs: {$e->getMessage()}", __METHOD__);

            return false;
        }

        return !empty($tariffs);
    }
}
