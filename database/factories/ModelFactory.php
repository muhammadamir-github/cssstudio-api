<?php

$factory->define(App\CardTransaction::class, function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->randomNumber(),
        'card_id' => $faker->randomNumber(),
        'payment_id' => $faker->randomNumber(),
        'id' => function () {
             return factory(App\User::class)->create()->id;
        },
    ];
});

$factory->define(App\Animation::class, function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->randomNumber(),
        'name' => $faker->name,
        'css' => $faker->word,
        'id' => function () {
             return factory(App\User::class)->create()->id;
        },
    ];
});

$factory->define(App\LoginHistory::class, function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->randomNumber(),
        'ip_address' => $faker->word,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'id' => function () {
             return factory(App\User::class)->create()->id;
        },
    ];
});

$factory->define(App\PaypalTransaction::class, function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->randomNumber(),
        'paypal_email' => $faker->word,
        'paypal_transaction_no' => $faker->randomNumber(),
        'payment_id' => $faker->randomNumber(),
        'id' => function () {
             return factory(App\User::class)->create()->id;
        },
    ];
});

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'email' => $faker->safeEmail,
        'username' => $faker->userName,
        'password' => bcrypt($faker->password),
        'type' => $faker->word,
        'ip_address' => $faker->word,
        'last_login' => $faker->date(),
        'expires_at' => $faker->date(),
    ];
});

$factory->define(App\UserPayment::class, function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->randomNumber(),
        'method' => $faker->randomNumber(),
        'method_transaction_id' => $faker->randomNumber(),
        'product' => $faker->word,
        'amount' => $faker->randomNumber(),
        'id' => function () {
             return factory(App\User::class)->create()->id;
        },
    ];
});

$factory->define(App\Card::class, function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->randomNumber(),
        'number' => $faker->randomNumber(),
        'sno' => $faker->randomNumber(),
        'name_on_card' => $faker->word,
        'type' => $faker->word,
        'total_transactions' => $faker->randomNumber(),
        'added_at' => $faker->date(),
        'id' => function () {
             return factory(App\User::class)->create()->id;
        },
    ];
});

$factory->define(App\Activity::class, function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->randomNumber(),
        'type' => $faker->word,
        'id' => function () {
             return factory(App\User::class)->create()->id;
        },
    ];
});

$factory->define(App\UserMetadata::class, function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->randomNumber(),
        'total_animaions' => $faker->randomNumber(),
        'total_elements' => $faker->randomNumber(),
        'total_payments' => $faker->randomNumber(),
        'total_logins' => $faker->randomNumber(),
        'total_spending' => $faker->randomNumber(),
        'id' => function () {
             return factory(App\User::class)->create()->id;
        },
    ];
});

$factory->define(App\Element::class, function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->randomNumber(),
        'name' => $faker->name,
        'css' => $faker->word,
        'id' => function () {
             return factory(App\User::class)->create()->id;
        },
    ];
});

$factory->define(App\UserPersonal::class, function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->randomNumber(),
        'phone' => $faker->phoneNumber,
        'country' => $faker->country,
        'state' => $faker->word,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'id' => function () {
             return factory(App\User::class)->create()->id;
        },
    ];
});

