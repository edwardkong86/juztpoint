<?php
namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PropertyType extends GraphQLType {
	protected $attributes = [
		'name' => 'Property',
		'description' => 'JSON of object property',

	];
	// define field of type
	public function fields(): array{
		return [
			'name' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'code' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'start' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'end' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'role' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'color' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'address' => [
				'type' => Type::string(),
				'description' => 'The id of the user',
			],
			'contact' => [
				'type' => Type::string(),
				'description' => 'The name or value of the setting',
			],
			'mobile' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'phone' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],
			'email' => [
				'type' => Type::string(),
				'description' => 'The type of setting',
			],
			'timezone' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'currency' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'type' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'duration' => [
				'type' => Type::nonNull(Type::int()),
				'description' => 'The type of duration',
			],
			'rate' => [
				'type' => Type::nonNull(Type::float()),
				'description' => 'The type of setting',
			],
			'qty' => [
				'type' => Type::nonNull(Type::float()),
				'description' => 'The type of setting',
			],
			'cost' => [
				'type' => Type::nonNull(Type::float()),
				'description' => 'The type of setting',
			],
			'price' => [
				'type' => Type::nonNull(Type::float()),
				'description' => 'The type of setting',
			],
			'thumbnail' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
		];
	}

}