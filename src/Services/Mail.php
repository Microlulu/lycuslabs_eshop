<?php

namespace App\Services;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private string $api_key ='3c7b6426c2aa6fb09a6c7fb1f9df0160';
    private string $api_key_secret = 'bea9908accd38f35ed97da9b8128815d';
// TEMPLATE DE MAIL POUR REGISTRATION
    public function send($to_email, $to_name, $subject, $content){
        $mj = new Client($this->api_key, $this->api_key_secret ,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "support@lycuslabs.com",
                        'Name' => "Lycuslabs"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 3926224,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success($response->getData());
    }
// TEMPLATE DE MAIL POUR MESSAGE PROFIL ET POUR PAGE CONTACT
    public function sendSupport($to_email, $to_name, $subject, $content){
        $mj = new Client($this->api_key, $this->api_key_secret ,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "support@lycuslabs.com",
                        'Name' => "Lycuslabs"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 3929100,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success($response->getData());
    }

// TEMPLATE DE MAIL POUR CONFIRMATION DE COMMANDE
    public function sendConfirmOrder($to_email, $to_name, $subject, $content){
        $mj = new Client($this->api_key, $this->api_key_secret ,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "support@lycuslabs.com",
                        'Name' => "Lycuslabs"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 3935031,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success($response->getData());
    }


}