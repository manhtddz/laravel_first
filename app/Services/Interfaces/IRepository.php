<?php

namespace App\Services\Interfaces;

interface IRepository
{
    public function findById($id);
    public function findAllPaging($amount);
    public function create(array $requestData);
    public function update($id, array $requestData);
    public function delete($id);
    public function searchPaging($amount, array $requestData);
}
