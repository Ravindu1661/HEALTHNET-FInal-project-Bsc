<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

class TestMailController extends Controller
{
    public function send()
    {
        Mail::raw('Test Email Body from HEALTHNET Laravel setup.', function($msg) {
            $msg->to('ravindudinal599@gmail.com');
            $msg->subject('Test subject from HealthNet Laravel');
        });

        return "Mail Sent! Check your inbox.";
    }
}

