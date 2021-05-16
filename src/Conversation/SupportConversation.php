<?php

namespace PetPro\Chatbot\Conversation;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;

class SupportConversation extends Conversation
{
    public function run()
    {
        $this->askProblem();
    }

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

            $this->say('You searched for ' . $text . '. Let me see if I can help.');
            if (empty($results)) {
                $this->say('Unfortunately I was unable to find any information about ' . $text . '.');
            } else {
                $msg = '';
                foreach ($results as $result) {
                    $msg .= '<br/><a href="' . $result['url'] . '" target="_blank">' . $result['title'] . '</a>';
                }
                $this->say('I found these related articles that might help:<br/>' . $msg);
            }
        });
    }

}