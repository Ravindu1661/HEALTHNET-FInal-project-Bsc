<?php
// app/Models/Report.php
namespace App\Models;

class Report
{
    public string $name;
    public string $type;   // 'appointments', 'payments', etc.
    public array $filters;

    public function __construct(string $name, string $type, array $filters = [])
    {
        $this->name    = $name;
        $this->type    = $type;
        $this->filters = $filters;
    }
}

