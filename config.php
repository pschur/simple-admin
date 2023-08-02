<?php
defined('MAIN') ?: die('Forbidden');

$config = [
    'root' => __DIR__,
    'base_url' => 'https://admin.test',
    'dev' => false,
    'database' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'admin',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'collation' => 'utf8mb4_general_ci',
        'prefix' => '',
    ]
];

const ROOT = $config['root'];
const BASE_URL = $config['base_url'];

if ($config['dev']){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

require __DIR__ . '/vendor/autoload.php';

// DATABASE

class DB extends \Illuminate\Database\Capsule\Manager {}

$db = new DB;
$db->addConnection($config['databse');
$db->setAsGlobal();

// helper
function redirect(string $to){
    header("Location: {$to}");
    exit;
}

// AUTH
function auth():object{
    return new class {
        public function check(){
            return request()->session()->has('user') && request()->session('user', null) != null;
        }

        public function check_redirect(){
            if (!$this->check()) {
                redirect(BASE_URL.'/login.php?back='.urlencode(request()->fullUrl()));
            }
        }

        public function id():int|null{
            return $_SESSION['user'] ?? null;
        }

        public function user(){
            return DB::table('users')->where('id', $this->id())->first();
        }

        public function loginWithCredentials(string $username, string $password){
            $user = DB::table('users')->where('username', $username)->first();

            if ($user && password_verify($password, $user->password)) {
                request()->session()->set('user', $user->id);
                return true;
            }

            return false;
        }

        public function logout(){
            request()->session()->remove('user');
            request()->session()->destroy();
            return true;
        }
    };
}

// REQUEST
function request():object{
    return new class {
        public function get(string $key, $default = null){
            return $_GET[$key] ?? $default;
        }

        public function post(string $key, $default = null){
            return $_POST[$key] ?? $default;
        }

        public function put(string $key, $default = null){
            return $_PUT[$key] ?? $default;
        }

        public function delete(string $key, $default = null){
            return $_DELETE[$key] ?? $default;
        }

        public function files(string $key, $default = null){
            return $_FILES[$key] ?? $default;
        }

        public function all(){
            return $_REQUEST;
        }

        public function session(string $key = null, $default = null){
            $session = new class {
                public function get(string $key, $default = null){
                    return $_SESSION[$key] ?? $default;
                }

                public function set(string $key, $value){
                    $_SESSION[$key] = $value;
                }

                public function has(string $key){
                    return isset($_SESSION[$key]);
                }

                public function remove(string $key){
                    unset($_SESSION[$key]);
                }

                public function all(){
                    return $_SESSION;
                }

                public function intialize(){
                    session_start();
                }

                public function destroy(){
                    session_destroy();
                }

                public function id(){
                    return session_id();
                }
            };

            if ($key == null) return $session;

            return $session->get($key, $default);
        }

        public function isGet(){
            return $_SERVER['REQUEST_METHOD'] == 'GET';
        }

        public function isPost(){
            return $_SERVER['REQUEST_METHOD'] == 'POST';
        }

        public function isPut(){
            return $_SERVER['REQUEST_METHOD'] == 'PUT';
        }

        public function isDelete(){
            return $_SERVER['REQUEST_METHOD'] == 'DELETE';
        }

        public function fullUrl(){
            return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

        public function validation(array $rules = []){
            return validation()->validate($this->all(), $rules);
        }

        public function access_deny() {
            http_response_code(403);
            exit();
        }
    };
}

function validation():object{
    return new class {
        private $validation;

        public function __construct(){
            $this->validation = [
                'string' => function($value){
                    return is_string($value);
                },
                'min' => function($value, $min){
                    return strlen($value) >= $min;
                },
                'max' => function($value, $max){
                    return strlen($value) <= $max;
                },
                'email' => function($value){
                    return filter_var($value, FILTER_VALIDATE_EMAIL);
                },
                'integer' => function($value){
                    return filter_var($value, FILTER_VALIDATE_INT);
                },
            ];
        }

        public function validate(array $data, array $config = []): array
        {
            $return = [];

            foreach ($config as $key => $rules) {
                if (is_string($rules)){
                    $rules = explode('|', $rules);
                }

                foreach ($rules as $rule => $value) {
                    if (!is_string($rule)) {
                        $value = explode(':', $value);
                        $rule = $value[0];
                        $value = $value[1] ?? '';
                    }

                    if (!isset($this->validation[$rule])) {
                        throw new Exception("Validation rule {$rule} not found");
                    }

                    if (!isset($data[$key])) {
                        throw new Exception("Data key {$key} not found");
                    }

                    if ($this->validation[$rule]($data[$key], $value)) {
                        $return[$key] = $data[$key];
                    } else {
                        throw new \Exception("Validation failed for {$key} with rule {$rule}");
                    }
                }
            }

            return $return;
        }
    };
}

function resource_field(string $label, string $type = 'string', bool $required = true, bool $table = true, bool $readonly = false, string $default = null, array $validation = [], bool $hidden = false){
    return compact('label', 'type', 'required', 'readonly', 'default', 'validation', 'table', 'hidden');
}

// SESSION

request()->session()->intialize();
