<?php

namespace App\Listeners;

use App\Participation;

class ParticipationListener
{

    public function creating(Participation $participation)
    {
        $promotion = $participation->promotion;
        $participationsCount = $promotion->last_ticket;
        $attemptsToWin = $promotion->attempts;

        // Set the corresponding ticket number and check if it is a winning participation
        $ticketNumber = $participationsCount +1;
        $participation->ticket = $ticketNumber;
        $participation->is_winner = ($ticketNumber % $attemptsToWin == 0);

        // $participation->save(); not required in creating event
    }

}