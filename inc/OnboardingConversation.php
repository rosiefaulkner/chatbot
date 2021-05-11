<?php

namespace PetPro\Chatbot;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

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

    public function run()
    {
        // This will be called immediately
        $this->askForDemo();
    }
    private function askWhatsTheIssue()
    {
        $whatsTheIssue = Question::create('What seems to be the issue?');
        $this->ask($whatsTheIssue, function (Answer $issueAnswer) {
            $selectedText = $issueAnswer->getText(); // will be either 'Schedule me' or 'I need help!'
            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'category_name' => 'blog', // change to constant
                's'                      => $selectedText,
            );
            // The Query
            $query = new \WP_Query($args);
            while ($query->have_posts()) {
                $query->the_post();
                $title = the_title('', '', false);
            }

            $this->say('You searched for ' . $selectedText . '. Let me see if I can help. Check this out: ' . $title);
        });
    }

    private function askHowCanIHelp()
    {
        $howCanIHelpYou = Question::create('How can we help you today?')
            ->addButtons([
                Button::create('Get Support')->value('get_support'),
                Button::create('Report an issue')->value('report_issue'),
            ]);

        $this->ask($howCanIHelpYou, function (Answer $helpAnswer) {
            if ($helpAnswer->isInteractiveMessageReply()) {
                $help = $helpAnswer->getValue(); // will be either 'get_support' or 'report_issue'
                // Detect if button was clicked:
                if ($help == 'get_support') {
                    $this->askWhatsTheIssue();
                }else{
                    $this->askCreateTicket();
                }
            }
        });
    }

    private function askCreateTicket() {
        // Redirect to ticket page and create ticket from L1 support
        // Or ask information within chat to create form entries

    }

    private function askHowCanIHelpClinic()
    {
        $howCanIHelpYouClinic = Question::create('How can we help you today?')
            ->addButtons([
                Button::create('Schedule a Demo')->value('schedule_demo'),
                Button::create('Get support')->value('get_clinic_support'),
            ]);

        $this->ask($howCanIHelpYouClinic, function (Answer $helpAnswerClinic) {
            if ($helpAnswerClinic->isInteractiveMessageReply()) {
                $helpClinic = $helpAnswerClinic->getValue(); // will be either 'get_support' or 'invite_provider'
                // Detect if button was clicked:
                if ($helpClinic == 'get_clinic_support') {
                    // $this->askWhatsTheIssue();
                }
            }
        });
    }

    public function askForDemo()
    {
        $whichOneAreYou = Question::create('Hi, ' . $this->firstName . '. Which of the following best describes you?')
            ->addButtons([
                Button::create('Pet Owner')->value('pet_owner'),
                Button::create('Clinic')->value('clinic'),
            ]);

        $this->ask($whichOneAreYou, function (Answer $personaAnswer) {
            if ($personaAnswer->isInteractiveMessageReply()) {
                $persona = $personaAnswer->getValue(); // will be either 'pet owner' or 'clinic'
                // Detect if button was clicked:
                if ($persona == 'pet_owner') {
                    $this->askHowCanIHelp();
                } else {
                    // When user is pet clinic
                    $this->askHowCanIHelpClinic();
                }
            }
        });


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
}
