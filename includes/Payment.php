<?php

class Payment
{
    private static function config(): array
    {
        return require __DIR__ . '/../config/payment.php';
    }

    public static function generateRef(int $subscriptionId): string
    {
        return 'MK-' . $subscriptionId . '-' . time();
    }

    public static function isPaid(int $subscriptionId): bool
    {
        $db = Database::getConnection();
        $stmt = $db->prepare('SELECT payment_status FROM subscriptions WHERE id = ?');
        $stmt->execute([$subscriptionId]);
        $row = $stmt->fetch();

        return $row && ($row['payment_status'] ?? '') === 'paid';
    }

    public static function markPaid(int $subscriptionId, string $method, string $transactionId, string $paymentRef): bool
    {
        try {
            if (self::isPaid($subscriptionId)) {
                return true;
            }

            $db = Database::getConnection();
            $stmt = $db->prepare(
                'UPDATE subscriptions SET
                    payment_status = ?, payment_method = ?, transaction_id = ?, payment_ref = ?,
                    paid_at = NOW(), status = ?
                 WHERE id = ? AND payment_status = ?'
            );
            $stmt->execute(['paid', $method, $transactionId, $paymentRef, 'confirmed', $subscriptionId, 'unpaid']);

            return $stmt->rowCount() > 0 || self::isPaid($subscriptionId);
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }

    public static function markFailed(int $subscriptionId): void
    {
        $db = Database::getConnection();
        $stmt = $db->prepare('UPDATE subscriptions SET payment_status = ? WHERE id = ?');
        $stmt->execute(['failed', $subscriptionId]);
    }

    public static function setPaymentRef(int $subscriptionId, string $ref): void
    {
        $db = Database::getConnection();
        $stmt = $db->prepare('UPDATE subscriptions SET payment_ref = ? WHERE id = ?');
        $stmt->execute([$ref, $subscriptionId]);
    }

    private static function validateExpiry(string $input, string $expected): bool
    {
        // Normalize both: remove spaces and slashes, extract MM and YY
        $input = preg_replace('/[\s\/]/', '', $input);
        $expected = preg_replace('/[\s\/]/', '', $expected);

        // Extract month and year from both (assuming MMYY format after normalization)
        if (strlen($input) < 4) {
            return false;
        }
        $inputMonth = substr($input, 0, 2);
        $inputYear = substr($input, 2, 2);
        $expectedMonth = substr($expected, 0, 2);
        $expectedYear = substr($expected, 2, 2);

        // Compare month and year
        return $inputMonth === $expectedMonth && $inputYear === $expectedYear;
    }

    public static function validateLocalCard(array $input): array
    {
        $test = self::config()['sandbox_test'];
        $card = preg_replace('/\s+/', '', $input['card_number'] ?? '');
        $cvv  = trim($input['cvv'] ?? '');
        $expiry = trim($input['expiry'] ?? '');
        $pin  = trim($input['pin'] ?? '');
        $otp  = trim($input['otp'] ?? '');

        if ($card !== $test['card_number']) {
            return ['success' => false, 'message' => 'Invalid card number. Use the test card shown on this page.'];
        }
        if ($cvv !== $test['cvv']) {
            return ['success' => false, 'message' => 'Invalid CVV. Your CVV is ' . $test['cvv']];
        }
        if (!self::validateExpiry($expiry, $test['expiry'])) {
            return ['success' => false, 'message' => 'Invalid expiry. Use ' . $test['expiry']];
        }
        if ($pin !== $test['pin']) {
            return ['success' => false, 'message' => 'Invalid PIN. Your PIN is ' . $test['pin']];
        }
        if ($otp !== $test['otp']) {
            return ['success' => false, 'message' => 'Invalid OTP. Your OTP is ' . $test['otp']];
        }

        return ['success' => true, 'method' => 'card', 'transaction_id' => 'SBX-CARD-' . uniqid()];
    }

    public static function validateLocalMobile(array $input): array
    {
        $test = self::config()['sandbox_test'];
        $phone = preg_replace('/\s+/', '', $input['phone'] ?? '');
        $otp   = trim($input['mobile_otp'] ?? '');

        if (strlen($phone) < 9) {
            return ['success' => false, 'message' => 'Enter a valid Tanzanian phone number (e.g. 0712345678).'];
        }
        if ($otp !== $test['mobile_otp']) {
            return ['success' => false, 'message' => 'Invalid OTP. Your OTP is ' . $test['mobile_otp']];
        }

        return ['success' => true, 'method' => 'mobile_money', 'transaction_id' => 'SBX-MOMO-' . uniqid()];
    }

    public static function initFlutterwave(array $subscription, array $user, string $paymentRef): array
    {
        $config = self::config();
        $secret = $config['flutterwave']['secret_key'] ?? '';

        if (strpos($secret, 'xxxx') !== false || $secret === '') {
            return ['success' => false, 'message' => 'Flutterwave test keys not configured in config/payment.php'];
        }

        $appConfig = require __DIR__ . '/../config/app.php';
        $payload = [
            'tx_ref'          => $paymentRef,
            'amount'          => (float) $subscription['total_amount'],
            'currency'        => $appConfig['currency'],
            'redirect_url'    => absoluteUrl('payment-callback.php'),
            'payment_options' => 'card,mobilemoneytanzania',
            'customer'        => [
                'email'        => $user['email'],
                'phonenumber'  => $user['phone'] ?? '',
                'name'         => $user['full_name'] ?? $user['name'] ?? 'Customer',
            ],
            'customizations'  => [
                'title'       => $appConfig['name'],
                'description' => 'Weekly meal subscription #' . $subscription['id'],
                'logo'        => absoluteUrl('assets/images/placeholder-meal.svg'),
            ],
            'meta' => [
                'subscription_id' => $subscription['id'],
            ],
        ];

        $response = self::curlPost('https://api.flutterwave.com/v3/payments', $payload, $secret);

        if (!$response || ($response['status'] ?? '') !== 'success') {
            $msg = $response['message'] ?? 'Could not start Flutterwave payment.';
            return ['success' => false, 'message' => $msg];
        }

        return [
            'success'      => true,
            'payment_link' => $response['data']['link'] ?? '',
        ];
    }

    public static function verifyFlutterwave(string $transactionId): array
    {
        $secret = self::config()['flutterwave']['secret_key'];
        $url = 'https://api.flutterwave.com/v3/transactions/' . urlencode($transactionId) . '/verify';
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $secret,
                'Content-Type: application/json',
            ],
        ]);
        $body = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($body, true);
        if (!$data || ($data['status'] ?? '') !== 'success') {
            return ['success' => false, 'message' => 'Payment verification failed.'];
        }
        $tx = $data['data'] ?? [];
        if (($tx['status'] ?? '') !== 'successful') {
            return ['success' => false, 'message' => 'Payment was not successful.'];
        }
        return [
            'success'        => true,
            'transaction_id' => (string) ($tx['id'] ?? $transactionId),
            'payment_ref'    => $tx['tx_ref'] ?? '',
            'method'         => $tx['payment_type'] ?? 'flutterwave',
        ];
    }
    private static function curlPost(string $url, array $payload, string $secretKey): ?array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $secretKey,
                'Content-Type: application/json',
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
        ]);
        $body = curl_exec($ch);
        curl_close($ch);
        return json_decode($body, true);
    }
}