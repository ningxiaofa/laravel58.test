<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Post;

class SendPostEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $post;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        //
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $data= [
            'title'=> $this->post->title,
            'body'=> $this->post->body,
        ];
        // emails.post 对应的视图文件模板
        Mail::send('emails.post', $data, function($message){
            //$message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));//可省略默认使用的config/mail.php:58 from
            $message->to('15237181025@163.com')->subject('There is a new post');
        });

        if(count(Mail::failures()) < 1){
            echo '发送邮件成功，请查收！';
        }else{
            echo '发送邮件失败，请重试！';
        }
        //dd(Mail::failures());
    }
}
