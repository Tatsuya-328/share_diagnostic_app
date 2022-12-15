<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\CompanyChangeRequest;

class CompanyStatusChangeEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $companyChangeRequest;
    public $deleteImageIds = [];

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CompanyChangeRequest $companyChangeRequest, $deleteImageIds=[])
    {
        $this->companyChangeRequest = $companyChangeRequest;
        $this->deleteImageIds = $deleteImageIds;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
