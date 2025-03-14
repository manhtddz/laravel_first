<?php

namespace App\Jobs;

use App\Mail\EmployeeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendEmployeeEmailJob implements ShouldQueue
{
    use Queueable;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $employee;

    public function __construct($employee)
    {
        $this->employee = $employee;
    }

    public function handle()
    {
        Mail::to($this->employee['email'])->send(new EmployeeMail($this->employee));
    }
}
