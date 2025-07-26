<?php

namespace App;

enum UserStatus:string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case Rejected = 'rejected';
    case Pending = 'pending';
}
