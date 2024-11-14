<?php

namespace App\Enums;

enum Roles : string
{
    case Client = 'client';

    case Admin = 'admin';

    case SuperAdmin = 'super_admin';
}