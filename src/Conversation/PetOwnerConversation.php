<?php

namespace PetPro\Chatbot\Conversation;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class PetOwnerConversation extends Conversation
{
    public function run()
    {
        $this->askProblem();
    }

    private function askProblem() : void
    {
        $question = Question::create('How can we help you today?')
            ->callbackId('pet_owner_ask_problem')
            ->addButtons([
                Button::create('Get Support')->value('get_support'),
                Button::create('Report an issue')->value('report_issue'),
            ]);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $problem = $answer->getValue();
                if ($problem == 'get_support') {
                    $this->askProblemDetails();
                } elseif (($problem == 'report_issue') {
                    $this->askCreateTicket();
                }
            }
        });
    }

    private function askProblemDetails() : void
    {
        $question = Question::create('What seems to be the issue?');
        $this->ask($question, function (Answer $answer) {
            $text = $answer->getText();
            $args = [
                'post_type' => 'post',
                'post_status' => 'publish',
                'category_name' => 'blog',
                's' => $text,
            ];

            $query = new \WP_Query($args);
            while ($query->have_posts()) {
                $query->the_post();
                $title = the_title('', '', false);
            }

            $this->say('You searched for ' . $text . '. Let me see if I can help. Check this out: ' . $title);
        });
    }

    private function askCreateTicket() {
        // Redirect to ticket page and create ticket from L1 support
        // Or ask information within chat to create form entries

    }
}