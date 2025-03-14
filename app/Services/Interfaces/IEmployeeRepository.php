<?php

namespace App\Services\Interfaces;

interface IEmployeeRepository
{
    public function findByEmailIgnoreDelFlag($email);
}
