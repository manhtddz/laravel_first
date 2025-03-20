<?php

namespace App\Services\Interfaces;

interface IEmployeeRepository
{
    public function findNotActiveEmployeeByEmail($email);
    public function findActiveEmployeeByEmail($email);
    public function findAllEmployeeId();

}
