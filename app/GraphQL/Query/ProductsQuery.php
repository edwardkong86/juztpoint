<?php
namespace App\GraphQL\Query;

use App\Models\Product;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ProductsQuery extends Query {
	protected $attributes = [
		'name' => 'products',
		'description' => 'A query of products',
	];

	public function type(): Type {
		return GraphQL::paginate('products');
	}
	// arguments to filter query
	public function args(): array{
		return [
			'id' => [
				'name' => 'id',
				'type' => Type::int(),
			],
			'name' => [
				'name' => 'name',
				'type' => Type::string(),
			],
			'type' => [
				'name' => 'type',
				'type' => Type::string(),
			],
			'status' => [
				'name' => 'status',
				'type' => Type::string(),
			],
			'sku' => [
				'type' => Type::string(),
				'name' => 'sku',
			],
			'limit' => [
				'type' => Type::int(),
			],
			'sort' => [
				'type' => Type::string(),
			],
			'desc' => [
				'type' => Type::string(),
			],
			'search' => [
				'name' => 'search',
				'type' => Type::string(),
			],
			'page' => [
				'type' => Type::int(),
			],
		];
	}
	public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields) {
		$where = function ($query) use ($args) {

			if (isset($args['id'])) {
				$query->where('id', $args['id']);
			}
			if (isset($args['name'])) {
				$query->where('name', 'like', '%' . $args['name'] . '%');
			}
			if (isset($args['type'])) {
				$query->where('type', 'like', $args['type']);
			}
			if (isset($args['status'])) {
				$query->where('status', $args['status']);
			}
			if (isset($args['sku'])) {
				$query->where('sku', $args['sku']);
			}
			if (isset($args['search'])) {

				$query->where(function ($query) use ($args) {
					$query->orWhere('name', 'LIKE', '%' . $args['search'] . '%');
					$query->orWhere('sku', 'LIKE', '%' . $args['search'] . '%');
				});

			}
		};

		$fields = $getSelectFields();

		$selectedFields = $fields->getSelect();

		$q = Product::with(array_keys($fields->getRelations()))
			->where($where)
			->select($selectedFields);

		if ($args['limit'] > 0) {
			$results = $q->paginate($args['limit'], ['*'], 'page', $args['page']);
		} else {
			$count = $q->count();
			$results = $q->paginate($count, ['*'], 'page', 1);
		}

		return $results;
	}
}