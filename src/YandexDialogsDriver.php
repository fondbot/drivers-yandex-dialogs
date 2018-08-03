<?php

declare(strict_types=1);

namespace FondBot\Drivers\YandexDialogs;

use FondBot\Channels\Chat;
use FondBot\Channels\User;
use FondBot\Channels\Driver;
use FondBot\Contracts\Event;
use Illuminate\Http\Request;
use FondBot\Contracts\Template;
use FondBot\Templates\Attachment;
use FondBot\Events\MessageReceived;
use FondBot\Contracts\Channels\WebhookVerification;

class YandexDialogsDriver extends Driver implements WebhookVerification
{
    public const VERSION = '1.0';

    private $session;

    /**
     * Get gateway display name.
     *
     * This can be used for various system where human-friendly name is required.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Yandex Dialogs';
    }

    public function getShortName(): string
    {
        return 'yandex-dialogs';
    }

    /**
     * Define driver default parameters.
     *
     * Example: ['token' => '', 'apiVersion' => '1.0']
     *
     * @return array
     */
    public function getDefaultParameters(): array
    {
        return [];
    }

    /**
     * Get API client.
     *
     * @return mixed
     */
    public function getClient()
    {
        return new YandexDialogsClient();
    }

    /**
     * Create event based on incoming request.
     *
     * @param Request $request
     *
     * @return Event
     */
    public function createEvent(Request $request): Event
    {
        $this->session = $request->input('session');

        $chat = new Chat($request->input('session.session_id'));
        $user = new User($request->input('session.user_id'));

        return new MessageReceived($chat, $user, $request->input('request.command', ''));
    }

    /**
     * Send message.
     *
     * @param Chat $chat
     * @param User $recipient
     * @param string $text
     * @param Template|null $template
     */
    public function sendMessage(Chat $chat, User $recipient, string $text, Template $template = null): void
    {
        response([
            'response' => [
                'text' => $text,
                'tts' => $text,
                'end_session' => false,
            ],
            'session' => $this->session,
            'version' => self::VERSION,
        ])
            ->send();
    }

    /**
     * Send attachment.
     *
     * @param Chat $chat
     * @param User $recipient
     * @param Attachment $attachment
     */
    public function sendAttachment(Chat $chat, User $recipient, Attachment $attachment): void
    {
        // TODO: Implement sendAttachment() method.
    }

    /**
     * Send low-level request.
     *
     * @param Chat $chat
     * @param User $recipient
     * @param string $endpoint
     * @param array $parameters
     */
    public function sendRequest(Chat $chat, User $recipient, string $endpoint, array $parameters = []): void
    {
        // TODO: Implement sendRequest() method.
    }

    public function isVerificationRequest(Request $request): bool
    {
        return $request->input('request.command') === 'test';
    }

    public function verifyWebhook(Request $request)
    {
        return [
            'version' => self::VERSION,
            'session' => $request->input('session'),
            'response' => [
                'text' => 'FondBot',
            ],
        ];
    }
}
