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
        Mail::send('emails.participant_you_won', $data, function ($m) use ($winner) {
            $m->from('hola@tombofans.com', 'TomboFans');
            $m->to($winner->email, $winner->name)->subject('Has ganado una promoción!');
        });

        // mail to the promo owner
        Mail::send('emails.user_new_winner', $data, function ($m) use ($owner) {
            $m->from('hola@tombofans.com', 'TomboFans');
            $m->to($owner->email, $owner->name)->subject('Hay un nuevo ganador en tu promo!');
        });
    }

}