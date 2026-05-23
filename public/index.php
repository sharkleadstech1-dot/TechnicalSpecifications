<?php

declare(strict_types=1);

require dirname(__DIR__) . '/config.php';
require dirname(__DIR__) . '/functions.php';

$page = $_GET['page'] ?? 'home';

if ($page === 'statuses') {
    $result = fetch_lead_statuses($_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET);

    render('lead-statuses', [
        'page' => 'lead-statuses',
        'title' => 'Статусы лидов',
        'result' => $result,
    ]);
    exit;
}

if ($page === 'thank-you') {
    render('thank-you', [
        'page' => 'thank-you',
        'title' => 'Спасибо за заявку',
        'lead_id' => trim((string) ($_GET['id'] ?? '')),
    ]);
    exit;
}

$result = [
    'success' => false,
    'errors' => [],
    'values' => ['firstName' => '', 'lastName' => '', 'phone' => '', 'email' => ''],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = submit_lead($_POST);

    if ($result['success']) {
        $query = http_build_query(array_filter([
            'page' => 'thank-you',
            'id' => $result['lead_id'] ?? null,
        ]));

        header('Location: /?' . $query);
        exit;
    }
}

render('lead-form', [
    'page' => 'lead-form',
    'title' => 'Ваш персональный брокер',
    'result' => $result,
]);
