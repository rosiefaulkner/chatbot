<?php

namespace PetPro\ChatBot\Conversation;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use PetPro\ChatBot\Conversation\PetOwnerConversation;
use PetPro\ChatBot\Conversation\ClinicConversation;

class OnboardingConversation extends Conversation
{

    /**
     * @var String
     */
    private $firstName;

    /**
     * Capture user's name for the conversation
     * 
     * @param $name String
     * @return void
     */
    private function setName(string $name): void
    {
        $this->firstName = ucfirst(strtolower($name));
    }

    /**
     * Entry point - called by botman
     * 
     * @return void
     */
    public function run()
    {
        $this->askName();
    }

    /**
     * Ask user's name
     * 
     * @return void
     */
    private function askName() : void
    {
        $this->ask(
            'Hey there, and welcome to our site. What\'s your name?',
            function(Answer $answer)
            {
                $this->setName($answer->getText());
                $this->askPersona();
            }
        );
    }

    private function askPersona() : void
    {
        $text = 'Hi, ' . $this->firstName . '. Which of the following best describes you?';
        $question = Question::create($text)
            ->addButtons([
                Button::create('Pet Owner')->value('pet_owner'),
                Button::create('Clinic')->value('clinic'),
            ]);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $persona = $answer->getValue();
                if ($persona == 'pet_owner') {                    
                    $this->bot->startConversation(new PetOwnerConversation());
                } elseif ($persona == 'clinic') {
                    $this->bot->startConversation(new ClinicConversation());
                }
            }
        });
    }
}
