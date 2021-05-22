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
            ->addButtons([
                Button::create('Get Support')->value('pet_owner_get_support'),
                Button::create('Invite a Care Provider')->value('pet_owner_invite_provider'),
            ]);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $problem = $answer->getValue();
                if ($problem == 'pet_owner_get_support') {
                    $this->bot->startConversation(new SupportConversation());
                } elseif ($problem == 'pet_owner_invite_provider') {
                    $this->askInviteProvider();
                }
            }
        });
    }

    /**
     * Ask Pet Owner Invite Provider
     * 
     * @return void
     */
    private function askInviteProvider() {
        $this->say('Sure thing! First we need just a little information:');
        $this->say('Care provider invite form feature is not yet available.');
    }
}