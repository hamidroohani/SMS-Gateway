<?php

namespace Database\Factories;

use App\Enums\MessageStatus;
use App\Enums\MessageType;
use App\Models\Client;
use App\Models\Provider;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $status = $this->faker->randomElement(MessageStatus::cases());
        $if_send = (in_array($status, [
            MessageStatus::DELIVERED,
            MessageStatus::SENT,
            MessageStatus::GETTING_DELIVERY,
            MessageStatus::UNKNOWN_ON_DELIVER,
            MessageStatus::FAILED_ON_DELIVER,
            MessageStatus::BLACK_LIST
        ]));
        return [
            'provider_id' => $this->faker->randomElement(Provider::query()->pluck("_id")->toArray()),
            'from' => $this->faker->phoneNumber(),
            'receiver_mobile' => $this->faker->phoneNumber(),
            'body' => $this->faker->text(),
            'status' => $status,
            'sent_at' => $if_send ? $this->faker->dateTime() : null,
            'ref_code' => $if_send ? $this->faker->numberBetween() : null,
            'err_msg' => ($status == 'failed_on_send') ? 'The api key is not set' : null,
        ];
    }

    public function dynamic_sent_at(Carbon $date): MessageFactory
    {
        return $this->state([
            'sent_at' => $date,
        ]);
    }
}
