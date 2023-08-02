# Simple Admin
## Description
This is a simple admin panel for managing users and other resources, that you can define.

## Installation
I'm currently working on an installer, but for now you can just clone the repo and create the user table manually:
```mysql
CREATE TABLE `users` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`email` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`username` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	`password` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_0900_ai_ci',
	PRIMARY KEY (`id`) USING BTREE,
	UNIQUE INDEX `username` (`username`) USING BTREE
)
COLLATE='utf8mb4_0900_ai_ci'
ENGINE=InnoDB
AUTO_INCREMENT=4
;
```

## Usage
You can define your own resources in the `resources` folder. The `users` resource is already defined as an example.
The routes will be automatically generated based on the resource name. For example, the `users` resource will have the following route: `/resources/users.php`

Here is an example of a resource file:
```php
<?php
const MAIN = true;

$GLOBALS['config'] = [
    'key' => 'users',
    'table' => 'users',
    'name' => 'User',
    'plural_name' => 'Users',
    'fields' => [
        'name' => [
            'type' => 'text',
            'label' => 'Name',
            'required' => true,
            'readonly' => false,
            'default' => null,
            'validation' => [
                'string' => true,
                'min' => 3,
                'max' => 255
            ],
            'table' => true,
            'hidden' => false
        ],
		'email' => resource_field('Email, 'email', false, validation: [
			'string' => true,
			'email' => true,
			'min' => 3,
			'max' => 255
	    ])
    ],
    'permissions' => [
        'index' => true,
        'create' => true,
        'read' => true,
        'update' => true,
        'delete' => function($item){
            return auth()->user()->id != $item->id;
        },
    ]
];

require __DIR__.'/_provider.php';
?>
```
```php
resource_field(string $label, string $type = 'string', bool $required = true, bool $table = true, bool $readonly = false, string $default = null, array $validation = [], bool $hidden = false);
```

I think the most is self explaining, but here is a quick overview of the config:
- `key`: The key of the resource must be the filename. The resource file `/resources/users.php` has the key `users`.
- `table`: The table name in the database.
- `name`: The name of the resource.
- `plural_name`: The plural name of the resource.
- `fields`: An array of fields that will be displayed in the resource. The key of the field must be the column name in the database.
    - `type`: The type of the field. The following types are available:
        - `text`: A simple text input.
        - `textarea`: A textarea.
        - `select`: A select input. The options must be defined in the `options` key.
        - `checkbox`: A checkbox input.
        - `password`: A password input.
        - `file`: A file input.
        - `image`: An image input. The image will be displayed in the list view.
        - `date`: A date input.
        - `datetime`: A datetime input.
        - `time`: A time input.
        - `number`: A number input.
        - `email`: An email input.
        - `url`: An url input.
        - `color`: A color input.
        - `range`: A range input.
        - `label`: The label of the field.
    - `required`: A boolean that indicates if the field is required.
    - `readonly`: A boolean that indicates if the field is readonly. It will be ignored in the create and edit view.
    - `default`: The default value of the field.
    - `validation`: An array of validation rules. The key must be the name of the rule. The value can be a boolean (if nothing has to pass) or a string/number. #
    - `table`: A boolean that indicates if the field should be displayed in the list view.
    - `hidden`: A boolean that indicates if the field should be hidden in the show and edit view.
- `permissions`: An array of permissions for the resource. The key must be the name of the permission. The value can be a boolean or a function. If it's a function, it will be called with the item as the first parameter. The function must return a boolean.

## Validation
The following validation rules are available:
- `string`: The value must be a string.
- `min`: The value must be at least the given value.
- `max`: The value must be at most the given value.
- `email`: The value must be a valid email address.

I will extend this list in the future, or add a validation package.
