<?php

namespace App\Models;

use App\Traits\tUUID;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'first_name',
        'last_name',
        'other_names',
        'phone_number',
        'gender',
        'date_of_birth',
        'email',
        'password',
        'email_verified_at',
        'coe_id',
        'profession',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function age()
    {
        return Carbon::parse($this->attributes['date_of_birth'])->age;
    }


    #A user that has coe_id is a staff
    public function coe()
    {
        return $this->belongsTo(COE::class);
    }

    public function patient()
    {
        return $this->hasOne(Patient::class, 'user_id', 'id');
    }

    public function physicianPatients()
    {
        return $this->hasMany(Patient::class, 'primary_physician', 'id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function customPermissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user', 'user_id', 'permission_id');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function emailVerifications()
    {
        return $this->hasMany(UserVerification::class, 'channel', 'email');
    }

    public function applicationReview()
    {
        return $this->hasOne(ApplicationReview::class, 'user_id', 'id');
    }

    public function permissions()
    {
        /* 
        *   MERGE ALL CUSTOM PERMISSIONS AND PERMISSIONS ASSIGNED BY ROLES
         */
        return array_merge(
            $this->customPermissions->pluck('permission')->unique()->toArray(),
            $this->roles->map->permissions->flatten()->pluck('permission')->unique()->toArray()
        );
    }

    public function transactionComments()
    {
        return $this->hasManyThrough(Comment::class, Transaction::class, 'user_id', 'transaction_id', 'id', 'transaction_id');
    }

    public function hasReviewBy($user_id)
    {
        return $this->applicationReview->committeeApprovals()->where('committee_member_id', $user_id)->first();
    }

    public function patientAppointmentsCreated()
    {
        return $this->hasMany(PatientAppointment::class, 'staff_id');
    }

    public function userVerifications()
    {
        return $this->hasMany(UserVerification::class, 'channel', 'email');
    }

    public function patientTransferRequests()
    {
        return $this->hasMany(PatientTransferRequest::class, 'requesting_physician_id');
    }

    public function fundRetrievals()
    {
        return $this->hasMany(FundRetrieval::class, 'user_id', 'id');
    }
}
