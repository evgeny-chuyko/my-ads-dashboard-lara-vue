<?php

namespace App\Enums;

enum AuditAction: string
{
    case Created = 'created';
    case Updated = 'updated';
    case Deleted = 'deleted';
    case Banned = 'banned';
    case Unbanned = 'unbanned';
    case Login = 'login';
    case Logout = 'logout';

    public function label(): string
    {
        return match($this) {
            self::Created => 'Created',
            self::Updated => 'Updated',
            self::Deleted => 'Deleted',
            self::Banned => 'Banned',
            self::Unbanned => 'Unbanned',
            self::Login => 'Login',
            self::Logout => 'Logout',
        };
    }
}
