<?php

namespace App\Models;

use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Orchestra\Tenanti\Tenantor;
use Orchid\Platform\Models\User as Authenticatable;

class User extends Authenticatable {
	use SoftDeletes, Notifiable;

	protected $company_name = '';

	/**
	 * Convert to tenantor.
	 *
	 * @return \Orchestra\Tenanti\Tenantor
	 */
	public function asTenantor(): Tenantor {
		return Tenantor::fromEloquent('user', $this);
	}

	/**
	 * Make a tenantor.
	 *
	 * @return \Orchestra\Tenanti\Tenantor
	 */
	public static function makeTenantor($key, $connection = null): Tenantor {
		return Tenantor::make(
			'user', $key, $connection ?: (new static())->getConnectionName()
		);
	}

	/**
	 * The "booting" method of the model.
	 */
	protected static function boot() {
		parent::boot();

		static::observe(new UserObserver);
	}

	/**
	 * Set the user's first name.
	 *
	 * @param  string  $value
	 * @return void
	 */
	public function getCompanyName() {
		return $this->company_name;
	}

	/**
	 * Set the user's first name.
	 *
	 * @param  string  $value
	 * @return void
	 */
	public function setCompanyName($value) {
		$this->company_name = $value;
	}

}
