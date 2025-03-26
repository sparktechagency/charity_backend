<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class VolunteerStatusUpdated extends Mailable
{
    public $volunteer;
    public $status;

    public function __construct($volunteer, $status)
    {
        $this->volunteer = $volunteer;
        $this->status = $status;
    }

    public function build()
    {
        return $this->subject('Your Volunteer Status Has Been Updated')
                    ->view('volunteerStatusUpdated')
                    ->with([
                        'volunteer' => $this->volunteer,
                        'status' => $this->status,
                    ]);
    }
}
