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
			'id' => [
				'type' => Type::int(),
				'description' => 'Description of the setting',
			],
			'name' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'so' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'do' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'date' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],

			'type' => [
				'type' => Type::int(),
				'description' => 'Description of the setting',
			],
			'store_id' => [
				'type' => Type::int(),
				'description' => 'Description of the setting',
			],
			'device_id' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'active' => [
				'type' => Type::int(),
				'description' => 'Description of the setting',
			],
			'code' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'fingerprint' => [
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
			'startDateTime' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'endDateTime' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'role' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'backoffice' => [
				'type' => Type::int(),
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

			'duration' => [
				'type' => Type::int(),
				'description' => 'The type of duration',
			],
			'contain' => [
				'type' => Type::listOf(Type::int()),
				'description' => 'The type of duration',
			],
			'rate' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'qty' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'cost' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'amount' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'price' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'opening' => [
				'type' => Type::float(),
				'description' => 'The type of setting',
			],
			'thumbnail' => [
				'type' => Type::string(),
				'description' => 'Description of the setting',
			],
			'variant' => [
				'type' => Type::listOf(Type::string()),
				'description' => 'Description of the setting',
			],
			'shareWith' => [
				'type' => Type::int(),
				'description' => 'A list of the item',

			],
			'duration' => [
				'type' => Type::int(),
				'description' => 'A list of the item',

			],
			'stocks' => [
				'type' => Type::listOf(GraphQL::type('variant_stock')),
				'description' => 'The type of setting',
			],
			'performBy' => [
				'type' => GraphQL::type('user'),
				'description' => 'A list of the servicesBy',
				'is_relation' => false,
			],

			'servicesBy' => [
				'type' => Type::listOf(GraphQL::type('services_by')),
				'description' => 'A list of the servicesBy',
				'is_relation' => false,
			],

		];
	}
	protected function resolveVariantField($root, $args) {

		return json_decode($root['variant']);

	}

}