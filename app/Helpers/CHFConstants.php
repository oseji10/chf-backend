<?php

namespace App\Helpers;

class CHFConstants
{

    public static $ACTIVE = 'active';
    public static $INACTIVE = 'inactive';
    public static $PENDING = 'pending';
    public static $APPROVED = 'approved';
    public static $PATIENTS = 'patients';
    public static $DECLINED = 'declined';
    public static $ASSIGNED = 'assigned';
    public static $OPEN = 'open';
    public static $CLOSED = 'closed';
    public static $CMD_APPROVED = 'cmd approved';
    public static $CMD_DECLINED = 'cmd declined';
    public static $CHF_ADMIN = 'CHF Admin';
    public static $COE_ADMIN = 'COE Admin';
    public static $CMD = 'CMD';
    public static $MDT = "MDT";
    public static $FULFILLED = 'FULFILLED';
    public static $DEFAULT_PAGINATION_OFFSET = 0;
    public static $DEFAULT_DATA_PER_PAGE = 20;
    public static $DEFAULT_SORT_KEY = 'created_at';
    public static $ALLOWED_SORT_VALUES = ['asc', 'desc'];

    public static $PAYMENT_INITIATED = 'payment initiated';
    public static $PAYMENT_RECOMMENDED = 'payment recommended';
    public static $PAYMENT_APPROVED = 'payment approved';
    public static $DFA_APPROVED = 'dfa approved';
    public static $PERMSEC_APPROVED = 'permsec approved';
    public static $PAID = 'paid';
    public static $INITIATED = 'initiated';
    public static $CREDITED = 'credited';

    public static $DEFAULT_TIMEZONE = 'Africa/Lagos';
}
