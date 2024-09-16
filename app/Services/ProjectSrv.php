<?php

namespace App\Services;

class ProjectSrv
{
    public function HashGen()
    {
        $randomBytes = random_bytes(32);
        $randomString = bin2hex($randomBytes);
        return hash('ripemd160', $randomString);
    }
}
