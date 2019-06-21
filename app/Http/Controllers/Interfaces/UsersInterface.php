<?php

namespace App\Http\Controllers\Interfaces;

interface UsersInterface
{
    public function index();
    public function show($id);
    public function store(array $data);
    public function destroy($id);
    public function multiple(array $data);
}