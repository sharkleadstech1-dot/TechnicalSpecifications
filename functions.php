<?php

declare(strict_types=1);

function render(string $template, array $data = []): void
{
    extract($data, EXTR_SKIP);

    ob_start();
    require TEMPLATES_PATH . '/' . $template . '.php';
    $content = (string) ob_get_clean();

    require TEMPLATES_PATH . '/layout.php';
}

function client_ip(): string
{
    foreach (['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'] as $key) {
        $value = $_SERVER[$key] ?? '';

        if (!is_string($value) || $value === '') {
            continue;
        }

        foreach (array_map('trim', explode(',', $value)) as $ip) {
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }

    return '127.0.0.1';
}

function landing_url(): string
{
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || ((int) ($_SERVER['SERVER_PORT'] ?? 0) === 443)
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

    return ($https ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
}

function api_request(string $endpoint, array $payload): array
{
    $curl = curl_init(API_URL . $endpoint);

    if ($curl === false) {
        throw new RuntimeException('Не удалось инициализировать cURL.');
    }

    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'token: ' . API_TOKEN,
            'Content-Type: application/json',
        ],
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_THROW_ON_ERROR),
        CURLOPT_TIMEOUT => 30,
    ]);

    $body = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    if ($body === false) {
        throw new RuntimeException('Ошибка API: ' . $error);
    }

    $decoded = json_decode($body, true);

    if (!is_array($decoded)) {
        throw new RuntimeException('Некорректный ответ API.');
    }

    return $decoded;
}

function validate_lead_form(array $data): array
{
    $errors = [];

    if ($data['firstName'] === '') {
        $errors['firstName'] = 'Укажите имя.';
    }

    if ($data['lastName'] === '') {
        $errors['lastName'] = 'Укажите фамилию.';
    }

    if ($data['phone'] === '') {
        $errors['phone'] = 'Укажите номер телефона.';
    }

    if ($data['email'] === '') {
        $errors['email'] = 'Укажите эл. почту.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Укажите корректную эл. почту.';
    }

    return $errors;
}

function submit_lead(array $input): array
{
    $values = [
        'firstName' => trim($input['firstName'] ?? ''),
        'lastName' => trim($input['lastName'] ?? ''),
        'phone' => trim($input['phone'] ?? ''),
        'email' => trim($input['email'] ?? ''),
    ];

    $errors = validate_lead_form($values);

    if ($errors !== []) {
        return ['success' => false, 'errors' => $errors, 'lead_id' => null, 'values' => $values];
    }

    try {
        $response = api_request('/addlead', [
            'firstName' => $values['firstName'],
            'lastName' => $values['lastName'],
            'phone' => $values['phone'],
            'email' => $values['email'],
            'countryCode' => COUNTRY_CODE,
            'box_id' => BOX_ID,
            'offer_id' => OFFER_ID,
            'landingUrl' => landing_url(),
            'ip' => client_ip(),
            'password' => PASSWORD,
            'language' => LANGUAGE,
        ]);
    } catch (Throwable) {
        return [
            'success' => false,
            'errors' => ['form' => 'Не удалось отправить заявку. Попробуйте позже.'],
            'lead_id' => null,
            'values' => $values,
        ];
    }

    if (($response['status'] ?? false) === true || ($response['status'] ?? '') === 'true') {
        $leadId = isset($response['id']) ? (string) $response['id'] : null;

        save_local_lead([
            'id' => $leadId ?? (string) time(),
            'email' => $values['email'],
            'firstName' => $values['firstName'],
            'lastName' => $values['lastName'],
            'phone' => $values['phone'],
            'status' => 'new',
            'ftd' => '0',
            'landing_url' => landing_url(),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return [
            'success' => true,
            'errors' => [],
            'lead_id' => $leadId,
            'values' => ['firstName' => '', 'lastName' => '', 'phone' => '', 'email' => ''],
        ];
    }

    $message = is_string($response['error'] ?? null)
        ? translate_api_error($response['error'])
        : 'Не удалось зарегистрировать лид.';

    return ['success' => false, 'errors' => ['form' => $message], 'lead_id' => null, 'values' => $values];
}

function parse_status_dates(array $input): array
{
    $errors = [];
    $now = new DateTimeImmutable('now');
    $defaultFrom = $now->modify('-' . STATUS_DAYS_DEFAULT . ' days')->setTime(0, 0, 0);
    $maxFrom = $now->modify('-' . STATUS_DAYS_MAX . ' days')->setTime(0, 0, 0);

    $fromInput = trim($input['date_from'] ?? '');
    $toInput = trim($input['date_to'] ?? '');

    $from = parse_date($fromInput, $defaultFrom, true);
    $to = parse_date($toInput, $now, false);

    if ($fromInput !== '' && DateTimeImmutable::createFromFormat('Y-m-d', $fromInput) === false) {
        $errors['date_from'] = 'Некорректная дата начала.';
    }

    if ($toInput !== '' && DateTimeImmutable::createFromFormat('Y-m-d', $toInput) === false) {
        $errors['date_to'] = 'Некорректная дата окончания.';
    }

    if ($from > $to) {
        $errors['date_from'] = 'Дата начала не может быть позже даты окончания.';
    }

    if ($from < $maxFrom) {
        $errors['date_from'] = 'Дата начала не может быть более 60 дней назад.';
    }

    return [
        'errors' => $errors,
        'date_from' => $from->format('Y-m-d H:i:s'),
        'date_to' => $to->format('Y-m-d H:i:s'),
        'date_from_input' => $fromInput !== '' ? $fromInput : $from->format('Y-m-d'),
        'date_to_input' => $toInput !== '' ? $toInput : $to->format('Y-m-d'),
    ];
}

function parse_date(string $value, DateTimeImmutable $fallback, bool $isStart): DateTimeImmutable
{
    if ($value === '') {
        return $fallback;
    }

    $date = DateTimeImmutable::createFromFormat('Y-m-d', $value);

    if ($date === false) {
        return $fallback;
    }

    return $isStart ? $date->setTime(0, 0, 0) : $date->setTime(23, 59, 59);
}

function fetch_lead_statuses(array $input): array
{
    $dates = parse_status_dates($input);

    if ($dates['errors'] !== []) {
        return [
            'success' => false,
            'errors' => $dates['errors'],
            'leads' => [],
            'date_from' => $dates['date_from_input'],
            'date_to' => $dates['date_to_input'],
        ];
    }

    try {
        $response = api_request('/getstatuses', [
            'date_from' => $dates['date_from'],
            'date_to' => $dates['date_to'],
            'page' => 0,
            'limit' => STATUS_LIMIT,
        ]);
    } catch (Throwable) {
        return [
            'success' => false,
            'errors' => ['form' => 'Не удалось загрузить статусы лидов. Попробуйте позже.'],
            'leads' => [],
            'date_from' => $dates['date_from_input'],
            'date_to' => $dates['date_to_input'],
        ];
    }

    if (($response['status'] ?? false) !== true && ($response['status'] ?? '') !== 'true') {
        $message = is_string($response['error'] ?? null)
            ? translate_api_error($response['error'])
            : 'Не удалось загрузить статусы лидов.';

        return [
            'success' => false,
            'errors' => ['form' => $message],
            'leads' => [],
            'date_from' => $dates['date_from_input'],
            'date_to' => $dates['date_to_input'],
        ];
    }

    $apiLeads = normalize_leads($response['data'] ?? []);
    $localLeads = load_local_leads($dates['date_from'], $dates['date_to']);
    $source = ($input['source'] ?? '') === 'site' ? 'site' : 'all';

    $leads = $source === 'site'
        ? enrich_local_leads_with_api($localLeads, $apiLeads)
        : merge_leads($apiLeads, $localLeads);

    return [
        'success' => true,
        'errors' => [],
        'leads' => $leads,
        'date_from' => $dates['date_from_input'],
        'date_to' => $dates['date_to_input'],
        'source' => $source,
    ];
}

function save_local_lead(array $lead): void
{
    if (!is_dir(STORAGE_PATH)) {
        mkdir(STORAGE_PATH, 0755, true);
    }

    $leads = load_all_local_leads();
    $leads[] = $lead;

    file_put_contents(
        LEADS_FILE,
        json_encode($leads, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    );
}

function load_all_local_leads(): array
{
    if (!is_file(LEADS_FILE)) {
        return [];
    }

    $data = json_decode((string) file_get_contents(LEADS_FILE), true);

    return is_array($data) ? $data : [];
}

function load_local_leads(string $dateFrom, string $dateTo): array
{
    $from = new DateTimeImmutable($dateFrom);
    $to = new DateTimeImmutable($dateTo);
    $leads = [];

    foreach (load_all_local_leads() as $item) {
        if (!is_array($item)) {
            continue;
        }

        $createdAt = $item['created_at'] ?? '';

        if ($createdAt === '') {
            continue;
        }

        $created = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $createdAt);

        if ($created === false || $created < $from || $created > $to) {
            continue;
        }

        $leads[] = [
            'id' => (string) ($item['id'] ?? ''),
            'email' => (string) ($item['email'] ?? ''),
            'status' => (string) ($item['status'] ?? 'new'),
            'ftd' => (string) ($item['ftd'] ?? '0'),
            'from_site' => true,
            'created_at' => $createdAt,
        ];
    }

    return $leads;
}

function enrich_local_leads_with_api(array $localLeads, array $apiLeads): array
{
    $byId = [];
    $byEmail = [];

    foreach ($apiLeads as $apiLead) {
        if ($apiLead['id'] !== '') {
            $byId[$apiLead['id']] = $apiLead;
        }

        if ($apiLead['email'] !== '') {
            $byEmail[strtolower($apiLead['email'])] = $apiLead;
        }
    }

    $leads = [];

    foreach ($localLeads as $lead) {
        $apiLead = $byId[$lead['id']] ?? $byEmail[strtolower($lead['email'])] ?? null;

        if ($apiLead !== null) {
            $lead['status'] = $apiLead['status'] !== '' ? $apiLead['status'] : $lead['status'];
            $lead['ftd'] = $apiLead['ftd'];
            $lead['id'] = $apiLead['id'] !== '' ? $apiLead['id'] : $lead['id'];
        }

        $lead['from_site'] = true;
        $leads[] = $lead;
    }

    usort($leads, static function (array $a, array $b): int {
        return (int) $b['id'] <=> (int) $a['id'];
    });

    return $leads;
}

function merge_leads(array $apiLeads, array $localLeads): array
{
    $merged = [];

    foreach ($apiLeads as $lead) {
        $lead['from_site'] = false;
        $merged[$lead['id']] = $lead;
    }

    foreach ($localLeads as $lead) {
        $id = $lead['id'];

        if ($id !== '' && isset($merged[$id])) {
            $merged[$id]['from_site'] = true;

            if ($merged[$id]['status'] === '') {
                $merged[$id]['status'] = $lead['status'];
            }

            continue;
        }

        if ($id !== '') {
            $merged[$id] = $lead;
            continue;
        }

        $merged['local-' . $lead['email'] . '-' . ($lead['created_at'] ?? '')] = $lead;
    }

    $leads = array_values($merged);

    usort($leads, static function (array $a, array $b): int {
        return (int) $b['id'] <=> (int) $a['id'];
    });

    return $leads;
}

function normalize_leads(mixed $raw): array
{
    if (is_string($raw)) {
        $raw = json_decode($raw, true);
    }

    if (!is_array($raw)) {
        return [];
    }

    $leads = [];

    foreach ($raw as $item) {
        if (!is_array($item)) {
            continue;
        }

        $leads[] = [
            'id' => (string) ($item['id'] ?? ''),
            'email' => (string) ($item['email'] ?? ''),
            'status' => (string) ($item['status'] ?? ''),
            'ftd' => (string) ($item['ftd'] ?? '0'),
        ];
    }

    return $leads;
}

function translate_status(string $status): string
{
    $map = [
        'new' => 'Новый',
        'approved' => 'Одобрен',
        'converted' => 'Конвертирован',
        'rejected' => 'Отклонён',
        'failed' => 'Ошибка',
        'pending' => 'В обработке',
        'processing' => 'Обрабатывается',
    ];

    $key = strtolower(trim($status));

    if ($key === '') {
        return 'Не указан';
    }

    return $map[$key] ?? $status;
}

function translate_api_error(string $error): string
{
    $map = [
        'token does not exist' => 'Неверный токен API.',
        'invalid token' => 'Неверный токен API.',
        'the lead is blacklisted' => 'Заявка отклонена: контакт в чёрном списке.',
        'Failed to register a lead' => 'Не удалось зарегистрировать лид.',
        'invalid data' => 'Некорректные данные.',
    ];

    $key = strtolower(trim($error));

    foreach ($map as $english => $russian) {
        if (strtolower($english) === $key) {
            return $russian;
        }
    }

    return $error;
}
