<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Order;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    protected $order;
    protected $filePath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, $filePath)
    {
        $this->order = $order;
        $this->filePath = $filePath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->view('view.name');

        //成功发送!!  纯文本与附件[多个]  详情见: https://xueyuanjun.com/post/19513#bkmrk-%E5%8F%91%E9%80%81%E9%82%AE%E4%BB%B6
        return $this->from(env('MAIL_FROM_ADDRESS'))//example@example.com
            ->view('emails.orders.shipped')
            ->with([
                'orderName' => $this->order->name,
                'orderPrice' => $this->order->price,
                ])
            ->attach($this->filePath, [
                'as' => 'file_name.txt',
                'mime' => 'text/plain',//pdf是图片格式: application/pdf  普通文本: text/plain  html: text/html
            ])->attach('G:\phpstudy_pro\WWW\test.png', [
                'as' => 'file_name.png',
                'mime' => 'image/png',//pdf是图片格式: application/pdf  普通文本: text/plain  html: text/html
            ]);

        //可以知道多人抄送  问题: cc与bcc的区别？
        /*Mail::to($request->user())
            ->cc($moreUsers)
            ->bcc($evenMoreUsers)
            ->send(new OrderShipped($order));*/
    }
}
