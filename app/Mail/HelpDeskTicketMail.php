<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Ticket;
use App\Models\User;
class HelpDeskTicketMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $ticket;
    public $employee;

    public function __construct(Ticket $ticket, User $employee)
    {
        $this->ticket = $ticket;
        $this->employee = $employee;
    }

    public function build()
    {
        return $this->subject("New Ticket: #{$this->ticket->id}")->view('emails.helpDeskTicket');

    }
}
