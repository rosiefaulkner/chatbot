<?php

namespace PetPro\Chatbot;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class ClinicConversation extends Conversation
{
    public function run()
    {
        $this->askProblem();
    }

    private function askProblem() : void
    {
        $question = Question::create('How can we help you today?')
            ->addButtons([
                Button::create('Schedule a Demo')->value('schedule_demo'),
                Button::create('Get support')->value('get_clinic_support'),
            ]);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $clicked = $answer->getValue();
                
                if ($clicked == 'schedule_demo') {
                    $this->say('Test1 is ' . $clicked);
                } elseif ($clicked == 'get_clinic_support') {
                    $this->say('Test2 is ' . $clicked);
                }
            }
        });
    }

}