<?php

function get_managers_emails() {
    return [
        'info@wildbags.ru',
        'opt@gingerbird.ru',
        'pokaccio@gmail.com',
        'sycoraxa@gmail.com',
    ];
}

function get_mailer_from() {
    return 'admin@gingerbird.ru'; //"info@gingerbird.ru";
}

function get_smtp_user() {
    return 'admin@gingerbird.ru';
}

function get_smtp_password() {
    return 'open1234';
}

function get_counter_value() {
    $path = rtrim(getcwd(), "\\/") . DIRECTORY_SEPARATOR . "mail_templates" . DIRECTORY_SEPARATOR . "counter.val";
    if(!file_exists($path)){
        file_put_contents($path, "0");
    }
    $file = fopen($path, "r+b");
    flock($file, LOCK_EX);
    $value = intval(fread($file, 1000));
    $value++;
    fseek($file, 0);
    fwrite($file, $value);
    fclose($file);
    flock($file, LOCK_UN);
    $rvalue="{$value}";
    while (mb_strlen($rvalue)<3){
        $rvalue="0{$rvalue}";
    }
    return $rvalue;
}

function swift_send_mail($message, $subject, $to_list) {
    $path = rtrim(getcwd(), "\\/") . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . 'SWIFT' . DIRECTORY_SEPARATOR . "swift_required.php";
    require_once $path;
    $mo = Swift_Message::newInstance();
    $mo->setTo(explode(",", $to_list));
    $mo->setFrom(get_mailer_from());
    $mo->setSubject($subject);
    $mo->setEncoder(new Swift_Mime_ContentEncoder_Base64ContentEncoder());
    $mo->setBody($message, 'text/html', 'UTF-8');
    $host = "smtp.mail.ru";
    $port = 465;
    if (!$host || !$port) {
        return;
    }
    $user = get_smtp_user();
    $password = get_smtp_password();
    $transport = Swift_SmtpTransport::newInstance($host, $port, 'ssl');
    $transport->setUsername($user);
    $transport->setPassword($password);
    $mailer = Swift_Mailer::newInstance($transport);
    try {
        $mailer->send($mo);
    } catch (Exception $e) {
        var_dump($e);
    }
}

function send_email_with_template($template, array $data, $subject, array &$od = null) {
    ob_start();
    if (array_key_exists("phone", $data)) {
        $data["phone_raw"] = "+" . preg_replace("/\D/i", "", $data['phone']);
    }
    $path = rtrim(getcwd(), "\\/") . DIRECTORY_SEPARATOR . "mail_templates" . DIRECTORY_SEPARATOR;
    $template_path = "{$path}{$template}.tpl";
    if (true || (file_exists($template_path) && is_file($template_path))) {
        global $smarty;
        $managers_emails = get_managers_emails();
        $mailer_from = get_mailer_from();
        $data["subject"] = $subject;
        $smarty->assign('mail_data', $data);
        $message = $smarty->fetch($template_path);
        $to = implode(",", $managers_emails);
        swift_send_mail($message, $subject, $to);
        if (false) {
            $from = $mailer_from;
            $subject = "{$subject}";
            $boundary = str_replace(" ", "", date('l jS \of F Y h i s A'));
            $newline = PHP_EOL;

            $headers = "From: $from$newline" .
                    "MIME-Version: 1.0$newline" .
                    "Content-Type: multipart/alternative;" .
                    "boundary = \"$boundary\"";
            $xmessage = "--$boundary$newline" .
                    "Content-Type: text/html; charset=utf-8$newline" .
                    "Content-Transfer-Encoding: base64{$newline}{$newline}" .
                    rtrim(chunk_split(base64_encode($message)));
            mail($to, $subject, $xmessage, $headers);
        }
    }
    $ee = ob_get_clean();
    $od ? $od['xxx'] = $ee : false;
}

function send_email_with_template2($template, $to, array $data, $subject) {

    ob_start();
    if (array_key_exists("phone", $data)) {
        $data["phone_raw"] = "+" . preg_replace("/\D/i", "", $data['phone']);
    }
    $path = rtrim(getcwd(), "\\/") . DIRECTORY_SEPARATOR . "mail_templates" . DIRECTORY_SEPARATOR;
    $template_path = "{$path}{$template}.tpl";
    if (true || (file_exists($template_path) && is_file($template_path))) {
        global $smarty;
        $managers_emails = [$to];
        $mailer_from = get_mailer_from();
        $data["subject"] = $subject;
        $smarty->assign('mail_data', $data);
        $message = $smarty->fetch($template_path);
        $to = implode(",", $managers_emails);
        $from = $mailer_from;
        $subject = "{$subject}";
        swift_send_mail($message, $subject, $to);
        if (false) {
            $boundary = str_replace(" ", "", date('l jS \of F Y h i s A'));
            $newline = PHP_EOL;

            $headers = "From: $from$newline" .
                    "MIME-Version: 1.0$newline" .
                    "Content-Type: multipart/alternative;" .
                    "boundary = \"$boundary\"";
            $xmessage = "--$boundary$newline" .
                    "Content-Type: text/html; charset=utf-8$newline" .
                    "Content-Transfer-Encoding: base64{$newline}{$newline}" .
                    rtrim(chunk_split(base64_encode($message)));
            // $headers .= rtrim(chunk_split(base64_encode($message)));
            mail($to, $subject, $xmessage, $headers);
        }
    }
    $ee = ob_get_clean();
}

function get_user_id_by_email($email) {
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_HTTPGET => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_SSL_VERIFYHOST => FALSE,
        CURLOPT_SSL_VERIFYPEER => FALSE,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_MAXREDIRS => 50,
        CURLOPT_USERPWD => "Smm@wildbags:varfolomeeva",
        CURLOPT_URL => "https://online.moysklad.ru/api/remap/1.1/entity/counterparty?filter=" . urlencode("email={$email}"),
            // CURLOPT_URL => "https://online.moysklad.ru/api/remap/1.1/entity/counterparty",
    ]);
    $r = curl_exec($curl);
    curl_close($curl);
    if ($r) {
        $raw = json_decode($r, TRUE);
        //file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . "search_rq.txt", print_r($raw, true));
        if ($raw && is_array($raw) && array_key_exists("rows", $raw)) {
            if (is_array($raw['rows']) && count($raw['rows'])) {
                $one = $raw['rows'][0];
                if (is_array($one) && array_key_exists("id", $one)) {
                    return $one["id"];
                }
            }
        }
    }
    return null;
}

function get_user_id_by_phone($phone) {
    $phone = preg_replace("/\D/i", "", $phone);
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_HTTPGET => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_SSL_VERIFYHOST => FALSE,
        CURLOPT_SSL_VERIFYPEER => FALSE,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_MAXREDIRS => 50,
        CURLOPT_USERPWD => "Smm@wildbags:varfolomeeva",
        CURLOPT_URL => "https://online.moysklad.ru/api/remap/1.1/entity/counterparty?filter=" . urlencode("phone={$phone}"),
    ]);
    $r = curl_exec($curl);
    curl_close($curl);
    if ($r) {
        $raw = json_decode($r, TRUE);
        if ($raw && is_array($raw) && array_key_exists("rows", $raw)) {
            if (is_array($raw['rows']) && count($raw['rows'])) {
                $one = $raw['rows'][0];
                if (is_array($one) && array_key_exists("id", $one)) {
                    return $one["id"];
                }
            }
        }
    }
    return null;
}

function create_user_with_params($name, $phone = null, $mail = null, $city = null) {
    $data = ["name" => $name];
    $mail ? $data["email"] = $mail : false;
    $phone ? $data["phone"] = $phone : false;
    $city ? $data["actualAddress"] = $city : false;
    $data['state'] = [
        "meta" => [
            "href" => "https://online.moysklad.ru/api/remap/1.1/entity/counterparty/metadata/states/9466c8c8-69ec-11e9-9ff4-3150001d4d4b",
            "metadataHref" => "https://online.moysklad.ru/api/remap/1.1/entity/counterparty/metadata",
            "type" => "state",
            "mediaType" => "application/json"
        ],
    ];
    $curl = curl_init();
    $data_enc = json_encode($data);
    curl_setopt_array($curl, [
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_SSL_VERIFYHOST => FALSE,
        CURLOPT_SSL_VERIFYPEER => FALSE,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_MAXREDIRS => 50,
        CURLOPT_USERPWD => "Smm@wildbags:varfolomeeva",
        CURLOPT_URL => "https://online.moysklad.ru/api/remap/1.1/entity/counterparty",
        CURLOPT_POSTFIELDS => $data_enc,
        CURLOPT_HTTPHEADER => [
            "Content-Length: " . mb_strlen($data_enc, 'UTF-8'),
            "Content-Type: application/json"
        ]
    ]);
    $new_ca = curl_exec($curl);
    curl_close($curl);
    if ($new_ca) {
        $nc = json_decode($new_ca, true);
        //file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . "new_ca_resp.txt", print_r($nc, true));
        $nc_id = $nc['id'];
        return $nc_id;
    }
    return null;
}

function create_fake_order($user_id, $status, $name, $comment) {
    $order_data = [
        "name" => "CORP_" . get_counter_value(),
        "organization" => [
            "meta" => [
                "href" => "https://online.moysklad.ru/api/remap/1.1/entity/organization/ffd090ec-b3d2-11e5-7a69-9711002bb206",
                "type" => "organization",
                "mediaType" => "application/json"
            ]
        ],
        "agent" => [
            "meta" => [
                "href" => "https://online.moysklad.ru/api/remap/1.1/entity/counterparty/{$user_id}",
                "type" => "counterparty",
                "mediaType" => "application/json"
            ]
        ],
        "state" => [
            "meta" => [
                "href" => "https://online.moysklad.ru/api/remap/1.1/entity/customerorder/metadata/states/c7283a99-8e67-11e8-9107-50480018f29c",
                "type" => "state",
                "mediaType" => "application/json"
            ]
        ],
        "description" => $comment
    ];
    $data_post = json_encode($order_data);
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_POST => TRUE,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_SSL_VERIFYHOST => FALSE,
        CURLOPT_SSL_VERIFYPEER => FALSE,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_MAXREDIRS => 50,
        CURLOPT_USERPWD => "Smm@wildbags:varfolomeeva",
        CURLOPT_URL => "https://online.moysklad.ru/api/remap/1.1/entity/customerorder",
        CURLOPT_POSTFIELDS => $data_post,
        CURLOPT_HTTPHEADER => [
            "Content-Length: " . mb_strlen($data_post, 'UTF-8'),
            "Content-Type: application/json"
        ]
    ]);
    $new_order = curl_exec($curl);
    curl_close($curl);
}

$od = ["status" => "ok"];
try {
    $action = array_key_exists("action", $_GET) ? trim(strip_tags($_GET["action"])) : null;
    $post_action = array_key_exists("c", $_POST) ? trim(strip_tags($_POST["c"])) : null;
    if ($post_action && $post_action === $action) {
        $name = array_key_exists("name", $_POST) ? trim(strip_tags($_POST["name"])) : null;
        $name = $name && mb_strlen($name, "UTF-8") ? $name : null;
        $phone = array_key_exists("phone", $_POST) ? trim(strip_tags($_POST["phone"])) : null;
        $phone = $phone && mb_strlen($phone, "UTF-8") ? $phone : null;
        $qty = array_key_exists("qty", $_POST) ? trim(strip_tags($_POST["qty"])) : null;
        $qty = $qty && mb_strlen($qty, "UTF-8") ? $qty : null;
        $money = array_key_exists("money", $_POST) ? trim(strip_tags($_POST["money"])) : null;
        $money = $money && mb_strlen($money, "UTF-8") ? $money : null;
        $email = array_key_exists("email", $_POST) ? trim(strip_tags($_POST["email"])) : null;
        $email = $email && mb_strlen($email, "UTF-8") ? $email : null;
        $city = array_key_exists("city", $_POST) ? trim(strip_tags($_POST["city"])) : null;
        $city = $city && mb_strlen($city, "UTF-8") ? $city : null;
        $email = $email && preg_match("/^[^@]{1,}@[^@]{1,}\.[^@\.]{1,}$/i", trim($email)) ? $email : null;
        $m = [];
        if ($phone && preg_match("/^(?P<cc>\d{1,})(?P<ci>\d{3})(?P<o1>\d{3})(?P<o2>\d{2})(?P<o3>\d{2})$/", preg_replace("/\D/i", "", trim($phone)), $m)) {
            $phone = sprintf("+%s (%s) %s-%s-%s", $m['cc'], $m["ci"], $m["o1"], $m["o2"], $m["o3"]);
        } else {
            $phone = null;
        }
        if ($action === "form1") {

            if (!($name && $phone)) {
                throw new \Exception(" name and phone are required");
            }
            try {
                $user_id = get_user_id_by_phone($phone);
                if (!$user_id) {
                    $user_id = create_user_with_params($name, $phone, NULL, null);
                }
                if ($user_id) {
                    create_fake_order($user_id, "ffdea2ff-b3d2-11e5-7a69-9711002bb220", $name, "Запрос обратного звонка");
                }
            } catch (\Exception $ee) {
                
            }
            send_email_with_template("callback", compact('name', 'phone'), "Корпоративный:Запрос обратного звонка", $od);
        } else if ($action === "form2") {
            if (!($name && $phone && $email && $city && $money && $qty)) {
                throw new \Exception(" all fields are required");
            }
            try {
                $user_id = get_user_id_by_email($email);
                if (!$user_id) {
                    $user_id = get_user_id_by_phone($phone);
                }
                if (!$user_id) {
                    $user_id = create_user_with_params($name, $phone, $email, $city);
                }
                if ($user_id) {
                    create_fake_order($user_id, "ffdea2ff-b3d2-11e5-7a69-9711002bb220", $name, "Запрос расчета:{$qty} рюкзаков по {$money} рублей");
                }
            } catch (\Exception $ee) {
                
            }
            send_email_with_template("calculate", compact('name', 'phone', 'qty', 'email', 'city', 'money'), "Корпоративный:Запрос расчета");
        } else if ($action === 'form3') {
            if (!($name && $phone && $email && $city)) {
                throw new \Exception(" all fields are required");
            }
            try {
                $user_id = get_user_id_by_email($email);
                if (!$user_id) {
                    $user_id = get_user_id_by_phone($phone);
                }
                if (!$user_id) {
                    $user_id = create_user_with_params($name, $phone, $email, $city);
                }
                if ($user_id) {
                    create_fake_order($user_id, "ffdea2ff-b3d2-11e5-7a69-9711002bb220", $name, "Запрос каталога");
                }
            } catch (\Exception $ee) {
                
            }
            send_email_with_template("catalog", compact('name', 'phone', 'email', 'city'), "Корпоративный:Запрос каталога");
            send_email_with_template2("catalog_user", $email, compact('name', 'phone', 'email', 'city'), "Запрос каталога");
        }
    }
} catch (\Exception $ee) {
    $od['status'] = "error";
    $od['error'] = $ee->getMessage();
}
while (ob_get_level()) {
    ob_end_clean();
}
if (!headers_sent()) {
    header("Content-Type: application/json", true);
}
die(json_encode($od));
