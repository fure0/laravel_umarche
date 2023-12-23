<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LifeCycleTestController extends Controller
{
    public function showServiceContainerTest() {
        // register
        app()->bind('lifeCycleTest', function() {
            return 'life cycle test';
        });

        // call
        $test = app()->make('lifeCycleTest');

        // 서비스 컨테이너 없음 패턴
        $message = new Message();
        $sample = new Sample($message);
        $sample->run();

        // 서비스 컨테이너 app() 패턴
        app()->bind('sample', Sample::class);
        $sample = app()->make('sample');
        $sample->run();

        dd($test, app());
    }
}

class Sample
{
    public $message;
    public function __construct(Message $message) {
        $this->message = $message;
    }
    public function run() {
        $this->message->send();
    }
}

class Message
{
    public function send() {
        echo('show message');
    }
}
