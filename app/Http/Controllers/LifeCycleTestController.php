<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LifeCycleTestController extends Controller
{
    public function showServiceProviderTest() {
        $encrypt = app()->make('encrypter');
        $password = $encrypt->encrypt('password');

        $sample = app()->make('serviceProviderTest');

        dd($sample, $password, $encrypt->decrypt($password));
    }

    public function showServiceContainerTest() {
        // register
        app()->bind('lifeCycleTest', function() {
            return 'life cycle test';
        });

        // call
        $test = app()->make('lifeCycleTest');

        // 서비스 컨테이너 없음 패턴
        // 의존하는 두개의 클래스를 각각 인스턴스화 해서 실행
        $message = new Message();
        $sample = new Sample($message);
        $sample->run();

        // 서비스 컨테이너 app() 패턴
        // bind로 등록할 경우 관련된 클래스(Message)도 같이 인스턴스화 해준다
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
