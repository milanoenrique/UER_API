<?php

namespace App\Jobs;
use Illuminate\Support\Facades\Mail;
use App\Mail\RestorePassword;
class RestorePasswordJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->data['user'])->send(new RestorePassword($this->data['password']));
    }
}
