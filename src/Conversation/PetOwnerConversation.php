<?php

namespace PetPro\ChatBot\Conversation;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use PetPro\ChatBot\Conversation\SupportConversation;

class PetOwnerConversation extends Conversation
{
    public function run()
    {
        $this->askProblem();
    }

    /**
     * Ask Pet Owner Problem
     * 
     * @return void
     */
    private function askProblem() : void
    {
        $question = Question::create('How can we help you today?')
            ->callbackId('pet_owner_ask_problem')
            ->addButtons([
                Button::create('Get Support')->value('pet_owner_get_support'),
                Button::create('Report an issue')->value('pet_owner_report_issue'),
            ]);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $problem = $answer->getValue();
                if ($problem == 'pet_owner_get_support') {
                    $this->bot->startConversation(new SupportConversation());
                } elseif ($problem == 'pet_owner_report_issue') {
                    $this->askCreateTicket();
                }
            }
        });
    }

    /**
     * Ask Pet Owner Create Ticket
     * 
     * @return void
     */
    private function askCreateTicket() {
        $this->say('This feature is not yet available.');
        // Redirect to ticket page and create ticket from L1 support
        // Or ask information within chat to create form entries
    }
}