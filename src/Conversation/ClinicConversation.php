<?php

namespace PetPro\ChatBot\Conversation;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use PetPro\ChatBot\Conversation\SupportConversation;

class ClinicConversation extends Conversation
{
    public function run()
    {
        $this->askProblem();
    }

    /**
     * Clinic Conversation Ask Problem
     * 
     * @return void
     */
    private function askProblem() : void
    {
        $question = Question::create('How can we help you today?')
            ->addButtons([
                Button::create('Schedule a Demo')->value('clinic_schedule_demo'),
                Button::create('Get support')->value('clinic_get_support'),
            ]);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {                
                $clicked = $answer->getValue();
                if ($clicked == 'clinic_schedule_demo') {
                    $this->say('<div id="scheduler"><div class="scheduler-x">X</div></div>');
                    $this->askAnythingElse();
                } elseif ($clicked == 'clinic_get_support') {
                    $this->bot->startConversation(new SupportConversation());
                }
            }
        });
    }

    /**
     * Clinic Conversation Ask Anything Else
     * Chatbot Q6
     * 
     * @return void
     */
    private function askAnythingElse() : void
    {
        $question = Question::create('Great! Is there anything else we can help you with today?')
            ->addButtons([
                Button::create('Yes')->value('clinic_anything_else'),
                Button::create('No')->value('clinic_nothing_else'),
            ]);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $btnValue = $answer->getValue();
                if ($btnValue == 'clinic_anything_else') {
                    // $this->bot->stopsConversation();
                    $this->bot->startConversation(new static);
                } elseif ($btnValue == 'clinic_nothing_else') {
                    $this->say('Thank you for the opportunity to serve you.');
                    $this->say('Have a great day!');
                }
            }
        });
    }
}
