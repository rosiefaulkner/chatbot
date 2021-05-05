<?php

namespace PetPro\Chatbot;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class OnboardingConversation extends Conversation
{
    protected $firstname;
    protected $email;

    public function askFirstname()
    {
        $this->ask('Hello! What is your firstname?', function (Answer $answer) {
            // Save result
            $this->firstname = $answer->getText();

            $this->say('Nice to meet you ' . ucfirst(strtolower($this->firstname)));
            $this->askEmail();
        });
    }

    public function askEmail()
    {
        $this->ask('One more thing - what is your email?', function (Answer $answer) {
            // Save result
            $this->email = $answer->getText();

            $this->say('Great - that is all we need, ' . $this->firstname);
        });
    }

    public function run()
    {
        // This will be called immediately
        $this->askForDemo();
    }

    // ...inside the conversation object...
    public function askForDemo()
    {
        $question0 = Question::create('Please tell me a little bit about what you are trying to find')
            ->fallback('Unable to find something relevant to your search')
            ->callbackId('query_user');

        $this->ask($question0, function (Answer $answer0) {
            // Detect if button was clicked:
            $selectedText = $answer0->getText(); // will be either 'Schedule me' or 'I need help!'
            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
				'category_name' => 'blog', // change to constant
				's'                      => $selectedText,
			);
				// The Query
				$query = new \WP_Query( $args );
				while($query->have_posts()){
					$query->the_post(); 
					$title = the_title('', '', false);
				}
				
            $this->say('You searched for ' . $selectedText . '. Let me see if I can help. Check this out: ' . $title );
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
