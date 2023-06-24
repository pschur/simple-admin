<?php
defined('MAIN') ?: die('Forbidden');

require __DIR__.'/../config.php';

auth()->check_redirect();

// Check required variables
if (!isset($GLOBALS['config'])){
    throw new Exception('Config variable not found');
}
$config = $GLOBALS['config'];

$required_variables = ['key', 'table', 'fields', 'name', 'plural_name'];
foreach ($required_variables as $variable) {
    if (!isset($config[$variable])) {
        throw new Exception('Config variable $config['.$variable.'] not found');
    }
}

function prepareDataForValidationRules(array $fields, bool $without_hidden = false):array{
    $rules = [];

    foreach ($fields as $field_name => $field) {
        if (isset($field['validation'])) {
            $rules[$field_name] = [];

            foreach ($field['validation'] as $rule => $value) {
                if ($value === true) {
                    $rules[$field_name][] = $rule;
                } else {
                    $rules[$field_name][] = "{$rule}:{$value}";
                }
            }

            $rules[$field_name] = implode('|', $rules[$field_name]);

            if ($without_hidden) {
                if ($field['hidden'] ?? false) {
                    unset($rules[$field_name]);
                }
            }
        }
    }

    return $rules;
}

function permission(string $key, $item = null) {
    global $config;

    if (!isset($config['permissions'][$key])) {
        return request()->access_deny();
    }

    if (is_callable($config['permissions'][$key])) {
        return call_user_func($config['permissions'][$key], $item) ?: request()->access_deny();
    }

    return $config['permissions'][$key] ?: request()->access_deny();
}



$action = request()->get('action', 'index');

if ($action == 'index'){
    $search_field = request()->get('search_field', 'id');
    $search = request()->get('search', '');
    $order = request()->get('order', 'id');
    $order_direction = request()->get('order_direction', 'asc');

    $rows = DB::table($config['table'])->where($search_field, 'LIKE', "%{$search}%")->orderBy($order, $order_direction)->get();

    $view = __DIR__.'/views/index.php';
} elseif ($action == 'create') {
    $view = __DIR__.'/views/create.php';
} elseif ($action == 'store' && request()->isPost()) {
    $data = request()->validation(prepareDataForValidationRules($config['fields']));

    foreach ($config['fields'] as $field_name => $field) {
        if ($field['type'] == 'password') {
            $data[$field_name] = password_hash($data[$field_name], PASSWORD_DEFAULT);
        }

        if ($field['type'] == 'secret') {
            $symmetric = new \MiladRahimi\PhpCrypt\Symmetric();
            $data[$field_name] = $symmetric->encrypt($data[$field_name]);
        }
    }

    $id = DB::table($config['table'])->insertGetId($data);

    redirect(BASE_URL."/resources/{$config['key']}.php?action=show&id={$id}");
} elseif ($action == 'show'){
    $item = DB::table($config['table'])->find(request()->get('id'));

    if (!$item) {
        http_response_code(404);
        exit();
    }

    $view = __DIR__.'/views/show.php';
} elseif ($action == 'edit'){
    $item = DB::table($config['table'])->find(request()->get('id'));

    if (!$item) {
        http_response_code(404);
        exit();
    }

    $view = __DIR__.'/views/edit.php';
} elseif ($action == 'update' && request()->isPost()){
    $data = request()->validation(prepareDataForValidationRules($config['fields'], true));

    DB::table($config['table'])->where('id', request()->get('id'))->update($data);

    redirect(BASE_URL."/resources/{$config['key']}.php?action=show&id=".request()->get('id'));
} elseif ($action == 'destroy' || $action == 'delete'){
    permission('delete', DB::table($config['table'])->find(request()->get('id')));
    if (request()->isPost()) {
        DB::table($config['table'])->where('id', request()->get('id'))->delete();

        redirect(BASE_URL."/resources/{$config['key']}.php");
    }

    $view = __DIR__.'/views/delete.php';
} else {
    http_response_code(404);
    exit();
}

if (isset($view)){
    require __DIR__.'/views/layout.php';
}