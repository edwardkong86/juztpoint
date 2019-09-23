<?php
namespace App\GraphQL\Mutation;
use App\Models\Document;
use DB;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class NewReceiptMutation extends Mutation {
	protected $attributes = [
		'name' => 'NewReceipt',
	];
	public function type(): Type {
		return GraphQL::type('receipt');
	}
	public function args(): array{
		return [
			'reference' => [
				'name' => 'reference',
				'type' => Type::nonNull(Type::string()),
			],
			'terminal_id' => [
				'name' => 'terminal_id',
				'type' => Type::nonNull(Type::int()),
			],
			'account_id' => [
				'name' => 'account_id',
				'type' => Type::string(),
			],
			'transact_by' => [
				'name' => 'transact_by',
				'type' => Type::nonNull(Type::int()),
			],
			'type' => [
				'name' => 'type',
				'type' => Type::nonNull(Type::string()),
			],
			'status' => [
				'name' => 'status',
				'type' => Type::nonNull(Type::string()),
			],
			'date' => [
				'name' => 'date',
				'type' => Type::nonNull(Type::string()),
			],
			'discount' => [
				'name' => 'discount',
				'type' => Type::nonNull(Type::string()),
			],
			'discount_amount' => [
				'name' => 'discount_amount',
				'type' => Type::float(),
			],
			'service_charge' => [
				'name' => 'service_charge',
				'type' => Type::float(),
			],
			'tax_amount' => [
				'name' => 'tax_amount',
				'type' => Type::float(),
			],
			'charge' => [
				'name' => 'charge',
				'type' => Type::float(),
			],
			'received' => [
				'name' => 'received',
				'type' => Type::float(),
			],
			'rounding' => [
				'name' => 'rounding',
				'type' => Type::float(),
			],
			'change' => [
				'name' => 'change',
				'type' => Type::float(),
			],
			'refund' => [
				'name' => 'refund',
				'type' => Type::float(),
			],
			'note' => [
				'name' => 'note',
				'type' => Type::string(),
			],
			'items' => [
				'name' => 'items',
				'type' => Type::listOf(GraphQL::type('ItemInput')),
			],
			'payments' => [
				'name' => 'payments',
				'type' => Type::listOf(GraphQL::type('ItemInput')),
			],
			'commissions' => [
				'name' => 'commissions',
				'type' => Type::listOf(GraphQL::type('ItemInput')),
			],
			'properties' => [
				'name' => 'properties',
				'type' => Type::string(),
			],

		];
	}
	public function resolve($root, $args) {

		$success = false;
		$error = null;

		$commissions = [];

		if ($args['type'] === 'receipt') {

			foreach ($args['items'] as $item) {
				$amount = 0.00;
				$commission = $item['commission']['properties'];

				$amount = $this->calcCommission($commission);

				if ($item['properties']['share']) {
					$amount = $amount / 2;
					$commissions[] = $this->row($item, $commission, $item['sharedBy'], $amount);
				}

				if ($item['properties']['services']) {
					foreach ($item['properties']['services'] as $service) {
						$service_item = Item::with(['commission'])->find($service['id']);
						$service_amount = $this->calcCommission($commission);
						$commissions[] = $this->row($service_item, $commission, $service['user_id'], $service_amount);
						$amount -= $service_amount;
					}
				}

				$commissions[] = $this->row($item, $commission, $item['user_id'], $amount);

			}
		}
		DB::beginTransaction();
		try {

			$document = Document::create($args);

			$document->items()->createMany($args['items']);
			$document->payments()->createMany($args['payments']);
			$document->commissions()->createMany($commissions);

			// $document->items()->save($args->items);
			// $document->payments()->save($args->payments);
			// $document->commissions()->save($args->commissions);

			DB::commit();
			$success = true;
		} catch (\Exception $e) {
			$success = false;
			$error = $e;
			DB::rollback();
		}

		if (!$success) {
			return $error;
		}
		return $document;
	}

	protected function row($item, $commission, $user, $amount) {
		return [
			'line' => $item['line'],
			'item_id' => $commission['id'],
			'discount' => '{}',
			'discount_amount' => 0.00,
			'tax_id' => 1,
			'qty' => 1,
			'refund_qty' => 0.00,
			'refund_amount' => 0.00,
			'tax_amount' => 0.00,
			'user_id' => $user,
			'total_amount' => $amount,
			'note' => '',
		];
	}

	protected function calcCommission($commission) {
		if ($commission['type'] === 'fix') {
			return $commission['rate'];
		} else {
			return $commission['rate'] * $commission['rate'] / 100;
		}
	}
}
