<?php
namespace App\GraphQL\Query\Reports;

use App\Helpers\TenantTable;
use App\Models\Item;
use Closure;
use DB;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ReportProductSalesPerformance {

	public function run($args, Closure $getSelectFields) {
		$documents = TenantTable::parse('documents');
		$items = TenantTable::parse('items');
		$products = TenantTable::parse('products');
		$settings = TenantTable::parse('settings');
		$accounts = TenantTable::parse('accounts');

		$where = function ($query) use ($args, $documents) {
			if (isset($args['from']) && isset($args['to'])) {
				if ($args['from'] !== "" && $args['to'] !== "") {
					$from = $args['from'] . ' 00:00:00';
					$to = $args['to'] . ' 23:59:59';
					$query->whereBetween($documents . '.date', [$from, $to]);
				}
			}
			if (isset($args['store'])) {
				$query->where($documents . '.store_id', $args['store']);
			}
			if (isset($args['terminal'])) {
				$query->where($documents . '.terminal_id', $args['terminal']);
			}
			if (isset($args['user'])) {
				$query->where($documents . '.transact_by', $args['user']);
			}

		};

		$item = Item::join($documents, $documents . '.id', '=', $items . '.trxn_id')
			->join(DB::raw($settings . ' as store'), 'store.id', '=', $items . '.store_id')
			->join(DB::raw($products . ' as products'), 'products.id', '=', $items . '.item_id')
			->join(DB::raw($settings . ' as category'), 'category.id', '=', 'products.cat_id')
			->leftJoin(DB::raw($accounts . ' as accounts'), $documents . '.account_id', '=', 'accounts.uid')
			->where($items . '.type', 'item')
			->where('products.type', 'product')
			->where($where);

		$sum = $item->sum($items . '.total_amount');
		$count = $item->count('item_id');

		$query = $item->selectRaw('products.name as item_name, category.name as cat_name, SUM(ABS(' . $items . '.qty)) as qty,  SUM(' . $items . '.total_amount) as total_amount')
			->groupBy('products.name')
			->groupBy('category.name');

		if (isset($args['sort']) && isset($args['desc'])) {

			if (isset($args['desc']) && $args['desc'] === 'desc') {
				$query->orderBy($args['sort'], 'desc');
			} else {
				if ($args['sort'] !== '') {
					$query->orderBy($args['sort']);
				} else {
					$query->orderBy(DB::raw('JSON_EXTRACT(JSON_UNQUOTE(' . $items . '.discount), "$.type")'));
				}

			}

		}
		$results = $query->paginate($args['limit'], ['*'], 'page', $args['page']);
		return ['summary' => ['count' => $count, 'sum' => $sum], 'data' => $results];

	}
}
