<?php

namespace PetPro\Chatbot\Conversation;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use PetPro\Chatbot\Conversation\PetOwnerConversation;
use PetPro\Chatbot\Conversation\ClinicConversation;

class OnboardingConversation extends Conversation
{
    protected $firstName;
    protected $email;

    public function setName(string $name): void
    {
        $this->firstName = ucfirst(strtolower($name));
    }

    public function askEmail()
    {
        $this->ask('One more thing - what is your email?', function (Answer $answer) {
            // Save result
            $this->email = $answer->getText();

            $this->say('Great - that is all we need, ' . $this->firstName);
        });
    }

    /**
     * Entry point - called by botman
     * 
     * @return void
     */
    public function run()
    {
        $this->askPersona();
    }

    /**
     * Ask user's name
     * 
     * @return void
     * @deprecated No longer in use since the introduction asks for the user's name
     */
    private function askName() : void
    {
        $this->ask('What is your name?', function(Answer $answer) {
            $this->setName($answer->getText());
            $this->say('Nice to meet you '. $this->firstName);
            $this->askPersona();
        });
    }

    private function askPersona() : void
    {
        $text = 'Hi, ' . $this->firstName . '. Which of the following best describes you?';
        $question = Question::create($text)
            ->callbackId('onboarding_ask_persona')
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



        //         $question1 = Question::create('Would you like a demo?')
        //             ->fallback('Unable to schedule a demo')
        //             ->callbackId('create_demo')
        //             ->addButtons([
        //                 Button::create('Schedule me')->value('yes'),
        //                 Button::create('I need help!')->value('no'),
        //             ]);

        //         $question2 = Question::create('Please select a date and time that works for you')
        //             ->fallback('Unable to schedule a demo')
        //             ->callbackId('schedule_demo')
        //             ->addButtons([
        //                 Button::create('Select a date')->value('yes')
        //             ]);

        //         $this->ask($question1, function (Answer $answer1) use ($question2) {
        //             // Detect if button was clicked:
        //             if ($answer1->isInteractiveMessageReply()) {
        //                 $selectedValue = $answer1->getValue(); // will be either 'yes' or 'no'
        //                 $selectedText = $answer1->getText(); // will be either 'Schedule me' or 'I need help!'
        //                 if ($selectedValue == 'yes') {
        //                     $this->ask($question2, function (Answer $answer2) {
        //                         if ($answer2->isInteractiveMessageReply()) {
        //                             $this->say('Ok your answer is ' . $answer2->getValue());
        //                         }
        //                     });
        //                 }
        //             }
        //         });

}
