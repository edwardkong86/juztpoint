<?php
namespace App\GraphQL\Query;

use App\Models\Item;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class AppointmentsQuery extends Query {
	protected $attributes = [
		'name' => 'appointments',
		'description' => 'A query of appointments',
	];

	public function type(): Type {

		return GraphQL::paginate('items');
	}
	// arguments to filter query
	public function args(): array{
		return [
			'id' => [
				'name' => 'id',
				'type' => Type::int(),
			],
			'trxn_id' => [
				'name' => 'trxn_id',
				'type' => Type::string(),
			],
			'limit' => [
				'type' => Type::int(),
			],
			'page' => [
				'type' => Type::int(),
			],
			'sort' => [
				'type' => Type::string(),
			],
			'sort_desc' => [
				'type' => Type::string(),
			],

		];
	}
	public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields) {
		$where = function ($query) use ($args) {
			if (isset($args['id'])) {
				$query->where('id', $args['id']);
			}
			if (isset($args['trxn_id'])) {
				$query->where('account_id', $args['account_id']);
			}
		};

		$fields = $getSelectFields();
		$q = Item::with(array_keys($fields->getRelations()))
			->where('type', 'appointment')
			->where($where)
			->select($fields->getSelect());

		if ($args['limit'] > 0) {
			$results = $q->paginate($args['limit'], ['*'], 'page', $args['page']);
		} else {
			$count = $q->count();
			$results = $q->paginate($count, ['*'], 'page', 1);
		}

		return $results;
	}
}