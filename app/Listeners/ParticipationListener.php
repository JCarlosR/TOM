<?php

namespace App\Listeners;

use App\Participation;
use App\Promotion;
use App\User;
use Illuminate\Support\Facades\Mail;

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

        if ($participation->is_winner) {
            // if the participant won, send two mails (to the next users)
            $winner = $participation->user;
            $owner = $promotion->fanPage->user;
            $this->deliverNewWinnerMails($winner, $owner, $promotion);
        }

        // $participation->save(); is not required in "creating" event
    }

    public function deliverNewWinnerMails(User $winner, User $owner, Promotion $promotion)
    {
        // data
        $data['winner'] = $winner;
        $data['owner'] = $owner;
        $data['promotion'] = $promotion;

        // mail to the winner user
        $subject = "Felicidades, has ganado $promotion->description!";
        Mail::send('emails.participant_you_won', $data, function ($m) use ($winner, $subject) {
            $m->to($winner->email, $winner->name)->subject($subject);
        });

        // mail to the promo owner
        $subject = "Felicidades, un nuevo prospecto ganó en tu promoción $promotion->description!";
        Mail::send('emails.user_new_winner', $data, function ($m) use ($owner, $subject) {
            $m->to($owner->email, $owner->name)->subject($subject);
        });
    }

}