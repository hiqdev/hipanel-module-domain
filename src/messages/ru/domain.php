<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

return [
    'Domains' => 'Домены',
    'Domain name' => 'Имя домена',
    'Domain' => 'Домен',
    'Renew domain' => 'Продлить домен',
    'Registration' => 'Регистрация',

    'Domains in «ok» state' => 'В состоянии «ОК»',
    'Incoming transfer domains' => 'Входящий трансфер',
    'Outgoing transfer domains' => 'Исходящий трансфер',
    'Expired domains' => 'Просроченные домены',

    'The domain will be autorenewed for one year in a week before it expires if you have enough credit on your account' => 'Домен будет продлён на один год автоматически за неделю до окончания срока при наличии средств на счету',
    'This operation pushes the domain to another user irrevocably. You can not bring it back.' => 'Данная операция передаёт домен другому пользователю необратимо. Вы не сможете отменить эту операцию.',

    'Transfer confirmation email was sent to:' => 'Письмо для подтверждения трансфера было отправлено по адресу:',
    'transfer_attention_1' => 'В соответствии с требованиями ICANN трансфер домена требует обязательного подтверждения',
    'transfer_attention_2' => 'Трансфер не начнется пока вы не подтвердите запрос',
    'transfer_attention_3' => 'Для продолжения трансфера вы должны подтвердить трансфер перейдя по ссылке полученной в письме отправленном на e-mail Владельца или Административного контакта этого домена указанного в базе данных WHOIS',
    'transfer_attention_4' => 'Если вы являетесь владельцем домена, но не получаете от нас письмо о трансфере, пожалуйста, проверьте WHOIS информацию соответствующего домена и при необходимости актуализируйте контактные данные либо снимите WHOIS-protect',
    'transfer_attention_5' => 'Более подробную информацию о процессе трансфера доменов вы можете получить на <a href="http://www.icann.org/ru/resources/registrars/transfers">сайте ICANN</a> и особенно в соответствующем документе: "<a href="http://www.icann.org/ru/resources/registrars/transfers/policy">Политика передачи регистраций между регистраторами</a>"',

    'Domain check' => 'Проверка доступности доменов',
    'Status' => 'Статус',
    'Special' => 'Special',
    'All' => 'Все',
    'Available' => 'Доступные',
    'Unavailable' => 'Занятые',
    'Popular Domains' => 'Популярные Домены',
    'Promotion' => 'Акционные',
    'Categories' => 'Категории',

    'Starting the transfer procedure for the following domains' => 'Начинаем процедуру трансфера для следующих доменов',
    'Return to transfer form' => 'Вернуться к форме трансфера',

    'Domain is payed up to' => 'Домен оплачен до',
    'Domain was successfully pushed' => 'Домен был передан успешно',
    'Failed to push the domain' => 'Не удалось передать домен',

    'Up to 13 IPv4 or IPv6 addresses separated with comma' => 'До 13 IPv4 или IPv6 адресов, разделенных запятыми',

    'Name server' => 'Сервер имен',
    'Sync contacts' => 'Синхронизировать контакты',
    'Renew' => 'Продлить',
    'Renewal' => 'Продление',
    'In cart' => 'В корзине',

    'Enable Hold' => 'Прекратить делегирование',
    'Disable Hold' => 'Восстановить делегирование',
    'Enable WHOIS protect' => 'Вкл. защиту WHOIS',
    'Disable WHOIS protect' => 'Откл. защиту WHOIS',
    'Enable Lock' => 'Вкл. защиту от трансфера',
    'Disable Lock' => 'Откл. защиту от трансфера',
    'Enable autorenew' => 'Вкл. автопродление',
    'Disable autorenew' => 'Откл. автопродление',
    'Push' => 'Передать',
    'Push domain' => 'Передать домен',
    'Set notes' => 'Установить заметки',
    'Set NS' => 'Установить сервера имен',
    'Change contacts' => 'Сменить контакты',
    'Registered range' => 'Период регистрации',
    'Go to site ' => 'Перейти на сайт ',
    'Manage DNS' => 'Управлять DNS',
    'Adding' => 'Добавляем',
    '{0, plural, one{# year} other{# years}}' => '{0, plural, one{# год} few{# года} other{# лет}}',
    'Domains in zone {zone} could be renewed only in last {min, plural, one{# day} other{# days}} before the expiration date. You are able to renew domain {domain} only after {date} (in {days, one{# day} other{# days}})' => 'Домен в зоне {zone} возможно продлить только в {min, plural, one{последний день} few{последние # дня} other{последние # дней}} перед окончанием срока регистрации. Домен {domain} вы сможете продлить после {date} (через {days, plural, one{# день} few{# дня} other{# дней}})'
];
