<?php

declare(strict_types=1);

const API_URL = 'https://crm.belmar.pro/api/v1';
const API_TOKEN = 'ba67df6a-a17c-476f-8e95-bcdb75ed3958';

const BOX_ID = 28;
const OFFER_ID = 5;
const COUNTRY_CODE = 'GB';
const LANGUAGE = 'en';
const PASSWORD = 'qwerty12';

const STATUS_LIMIT = 100;
const STATUS_DAYS_DEFAULT = 30;
const STATUS_DAYS_MAX = 60;

const ROOT_PATH = __DIR__;
const TEMPLATES_PATH = ROOT_PATH . '/templates';
const STORAGE_PATH = ROOT_PATH . '/storage';
const LEADS_FILE = STORAGE_PATH . '/leads.json';
