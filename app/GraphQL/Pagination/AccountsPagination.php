<?php
namespace App\GraphQL\Pagination;

use App\Models\Account;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as Pagination;

class AccountsPagination extends Pagination {

	protected $attributes = [
		'name' => 'accounts',
		'description' => 'The collection of all Accounts',
		'model' => Account::class, // define model for users type
	];
	// define field of type
	public function fields(): array{
		return [
			'id' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'The id of the user',
			],
			'uid' => [
				'type' => Type::string(),
				'description' => 'The name or value of the setting',
			],
			'name' => [
				'type' => Type::string(),
				'description' => 'The name or value of the setting',
			],
			'description' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'type' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],
			'status' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],
			'properties' => [
				'type' => GraphQL::type('property'),
				'description' => 'A list of the property',
				'is_relation' => false,
			],

		];
	}

}