<?php

namespace App;

use App\Libraries\Helpers;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use MathieuTu\JsonSyncer\Contracts\JsonExportable;
use MathieuTu\JsonSyncer\Contracts\JsonImportable;
use MathieuTu\JsonSyncer\Traits\JsonExporter;
use MathieuTu\JsonSyncer\Traits\JsonImporter;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

// Just add the JsonExportable and/or JsonImportable interfaces and JsonExporter and/or JsonImporter traits to your models.

class User extends Authenticatable implements HasMedia, JsonExportable, JsonImportable
{
  use Notifiable;
  use HasMediaTrait;
  use HasSlug;
  use SoftDeletes;
  use JsonExporter;
  use JsonImporter;
  protected $dates = ['deleted_at'];

  /**
   * Get the options for generating the slug.
   */
  public function getSlugOptions() : SlugOptions
  {
      return SlugOptions::create()
          ->generateSlugsFrom('name')
          ->saveSlugsTo('slug');
  }
  public function registerMediaCollections()
  {
    $this->addMediaCollection('avatar')->singleFile();
  }

  // relationship
  //
  public function bazars()
  {
    return $this->hasMany(Bazar::class);
  }

  public function meals()
  {
    return $this->hasMany(Meal::class);
  }

  public function debits()
  {
    return $this->hasMany(Debitcredit::class, 'debit_to');
  }
  public function credits()
  {
    return $this->hasMany(Debitcredit::class, 'credit_to');
  }

  // mine function

  public function isAdmin()
  {
    return $this->role->id == 1;
  }
  public function isMederator()
  {
    return $this->role->id == 2;
  }
  public function isMember()
  {
    return $this->role->id == 3;
  }

  public static function get_active_users($year_month) {
      $month = $year_month->month;
      $year = $year_month->year;
      $user_month = UserMonth::whereYear('year_month', $year)
                ->whereMonth('year_month', $month)->first();
      if ($user_month) {
        // get users_ids and return users collection
        $ids = json_decode( $user_month->user_ids );
        return User::whereIn('id', $ids)->get();
      } else {
        // get active users store inside  and return users
        // get enable users id
        $users = User::where('enable', 1)->get();
        $user_ids = $users->pluck('id');
        $year_month = Helpers::generating_year_month($year_month);
        $user_month = new UserMonth;
        $user_month->year_month = $year_month;
        $user_month->user_ids = $user_ids;
        $user_month->save();
        return $users;
      }

  }
  public static function get_active_user_ids ($year_month) {
    $users = self::get_active_users($year_month);
    if ($users) {
      return $users->pluck('id');
    }else {
      return [];
    }
  }



  public function hasRole($role)
  {
    return $this->role->slug === $role;
  }

  public function hasRoles($roles)
  {
    $hasRole = false;
    foreach ($roles as $role) {
      $hasRole = $this->hasRole($role);
    }
    return $hasRole;
  }

  public function hasAnyRoles($roles)
  {
    foreach ($roles as $role) {
      if ($this->hasRole($role)) {
        return true;
      }

    }
    return false;
  }



  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name',
      'email',
      'password',
      'provider',
      'provider_id',
      'slug',
      'avatar',
      'address',
      'city',
      'others',
      'role_id',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
      'password', 'remember_token',
  ];
  public function role()
  {
    return $this->belongsTo(Role::class);
  }

  public function is_editable($user_id)
  {
    return $this->id == $user_id;
    return $this->isAdmin() || $this->id == $user_id;
  }





  public function setNameAttribute($value)
  {
      $this->attributes['name'] = ucfirst($value);
  }


}
