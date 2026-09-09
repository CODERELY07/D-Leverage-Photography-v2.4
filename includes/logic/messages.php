<?php

require_once __DIR__ . '/../db/messages.php';

if (!function_exists('validate_contact_submission')) {

    function validate_contact_submission(array $post): array {
        $required = ['fullname', 'email', 'phonenumber', 'date', 'location', 'session', 'services', 'message'];
        $errors = [];
        foreach ($required as $field) {
            $value = isset($post[$field]) ? htmlspecialchars($post[$field]) : false;
            if ($value == false) {
                $errors[$field] = 'This field is required.';
            }
        }
        return $errors;
    }
}

if (!function_exists('submit_contact_message')) {

    function submit_contact_message(mysqli $db, array $post) {
        if (!empty(validate_contact_submission($post))) {
            return 'missing_fields';
        }

        $email = htmlspecialchars($post['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'invalid_email';
        }

        if (message_email_exists($db, $email)) {
            return 'duplicate_email';
        }

        $data = [
            'fullname'    => htmlspecialchars($post['fullname']),
            'email'       => $email,
            'phonenumber' => htmlspecialchars($post['phonenumber']),
            'date'        => htmlspecialchars($post['date']),
            'location'    => htmlspecialchars($post['location']),
            'session'     => htmlspecialchars($post['session']),
            'services'    => htmlspecialchars($post['services']),
            'message'     => htmlspecialchars($post['message']),
        ];

        $result = insert_message($db, $data);

        return $result === true ? 'success' : $result;
    }
}
