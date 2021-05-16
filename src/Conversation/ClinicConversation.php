<?php

namespace PetPro\Chatbot\Conversation;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use PetPro\Chatbot\Conversation\SupportConversation;

class ClinicConversation extends Conversation
{
    public function run()
    {
        $this->askProblem();
    }

    private function askProblem() : void
    {
        $question = Question::create('How can we help you today?')
            ->callbackId('clinic_ask_problem')
            ->addButtons([
                Button::create('Schedule a Demo')->value('clinic_schedule_demo'),
                Button::create('Get support')->value('clinic_get_support'),
            ]);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {                
                $clicked = $answer->getValue();
                if ($clicked == 'clinic_schedule_demo') {
                    $this->say(
                        '<a href="https://calendly.com/petpro-team/website" target="_blank">Click here</a> to schedule a demo'
                    );
                } elseif ($clicked == 'clinic_get_support') {
                    $this->bot->startConversation(new SupportConversation());
                }
            }
        });
    }

}