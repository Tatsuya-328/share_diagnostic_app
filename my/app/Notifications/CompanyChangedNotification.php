<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Company;
use App\Models\CompanyChangeRequest;
use App\Notifications\AppNotification;

class CompanyChangedNotification extends AppNotification
{
    use Queueable;
    protected $companyChangeRequest;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(CompanyChangeRequest $companyChangeRequest)
    {
        $this->companyChangeRequest = $companyChangeRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    private function getView()
    {
        $requestType = $this->companyChangeRequest->request_type;
        $authorizeStatus = $this->companyChangeRequest->authorize_status;
        $view = "";
        if ($authorizeStatus == CompanyChangeRequest::AUTHORIZE_STATUS_AUTHORIZE) {
            if ($requestType == CompanyChangeRequest::REQUEST_TYPE_SIGNUP) {
                $view = "partner.emails.companies.authorize.signup";
            } elseif ($requestType == CompanyChangeRequest::REQUEST_TYPE_UPDATE) {
                $view = "partner.emails.companies.authorize.update";
            } elseif ($requestType == CompanyChangeRequest::REQUEST_TYPE_DELETE) {
                $view = "partner.emails.companies.authorize.delete";
            }
        } elseif ($authorizeStatus == CompanyChangeRequest::AUTHORIZE_STATUS_REJECT) {
            if ($requestType == CompanyChangeRequest::REQUEST_TYPE_SIGNUP) {
                $view = "partner.emails.companies.reject.signup";
            } elseif ($requestType == CompanyChangeRequest::REQUEST_TYPE_UPDATE) {
                $view = "partner.emails.companies.reject.update";
            } elseif ($requestType == CompanyChangeRequest::REQUEST_TYPE_DELETE) {
                $view = "partner.emails.companies.reject.delete";
            }
        }
        return $view;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $params['user'] = $notifiable;
        $params["company"] = $this->companyChangeRequest->company;
        // 削除承認以外は、urlへのリンクも載せる
        if ($this->companyChangeRequest->request_type != CompanyChangeRequest::REQUEST_TYPE_DELETE || $this->companyChangeRequest->authorize_status == CompanyChangeRequest::AUTHORIZE_STATUS_REJECT) {
            $params["url"] = getFrontUrl("companies/{$params["company"]->id}");
        }

        if ($this->companyChangeRequest->authorize_status == CompanyChangeRequest::AUTHORIZE_STATUS_REJECT) {
            $params["rejectMessage"] = $this->companyChangeRequest->message;
        }

        parent::toMail($notifiable);
        return $this->toMailFormat($this->getView(), $params);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
