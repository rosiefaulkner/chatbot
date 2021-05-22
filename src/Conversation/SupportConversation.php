<?php

namespace PetPro\ChatBot\Conversation;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class SupportConversation extends Conversation
{
    public function run()
    {
        $this->askProblem();
    }

    /**
     * Support Conversation Ask Problem
     * Chatbot Q4
     * 
     * @return void
     */
    private function askProblem() : void
    {
        $question = Question::create('What seems to be the issue?');
        $this->ask($question, function (Answer $answer) {
            $text = $answer->getText();
            $args = [
                'post_type' => 'post',
                'post_status' => 'publish',
                'category_name' => 'blog',
                's' => $text,
                'posts_per_page' => 5,
                'orderby' => 'relevance',
            ];

            $results = [];
            $query = new \WP_Query($args);
            while ($query->have_posts()) {
                $query->the_post();
                $results[] = [
                    'title' => the_title('', '', false),
                    'url' => get_permalink(),
                ];
            }

            $this->say('Sorry to hear you\'re having trouble with that. Here are some articles that might help:');
            if (empty($results)) {
                $this->say('Unfortunately I was unable to find any information about ' . $text . '. Please try to rephrase the issue you\'re experiencing.');
                $this->askProblem();
            } else {
                $msg = '<ul>';
                foreach ($results as $result) {
                    $msg .= '<li><a href="' . $result['url'] . '" target="_blank">' . $result['title'] . '</a></li>';
                }
                $msg .= '</ul>';
                $this->say('I found these related articles that might help:<br/>' . $msg);
                $this->askProblemSolved();
            }
        });
    }

    /**
     * Support Conversation Ask Problem Solved
     * Chatbot Q5
     * 
     * @return void
     */
    private function askProblemSolved() : void
    {
        $question = Question::create('Did any of those resources answer your question?')
            ->addButtons([
                Button::create('Yes')->value('support_problem_solved'),
                Button::create('No')->value('support_problem_not_solved'),
            ]);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $btnValue = $answer->getValue();
                if ($btnValue == 'support_problem_solved') {
                    $this->askAnythingElse();
                } elseif ($btnValue == 'support_problem_not_solved') {
                    $this->askTicketForm();
                }
            }
        });
    }

    /**
     * Support Conversation Ask Anything Else
     * Chatbot Q6
     * 
     * @return void
     */
    private function askAnythingElse() : void
    {
        $question = Question::create('Great! Is there anything else we can help you with today?')
            ->addButtons([
                Button::create('Yes')->value('support_anything_else'),
                Button::create('No')->value('support_nothing_else'),
            ]);

        $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $btnValue = $answer->getValue();
                if ($btnValue == 'support_anything_else') {
                    $this->askProblem();
                } elseif ($btnValue == 'support_nothing_else') {
                    $this->say('Thank you for the opportunity to serve you.');
                    $this->say('Have a great day!');
                }
            }
        });
    }

    /**
     * Support Conversation Ask Ticket Form
     * Chatbot Q6
     * 
     * @return void
     */
    private function askTicketForm() : void
    {
        $this->say('Sorry to hear that :(. Our support center is currently closed, but if you fill out this brief form, an agent will contact you on the next business day to help resolve your issue:');
        $this->say('Support form feature is not yet available.');
    }
}
