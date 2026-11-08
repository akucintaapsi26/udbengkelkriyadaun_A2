<?php
header('Content-Type: text/plain; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  exit('Metode pengiriman tidak valid.');
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || strlen($name) > 150) {
  http_response_code(422);
  exit('Nama wajib diisi dan maksimal 150 karakter.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254) {
  http_response_code(422);
  exit('Alamat email tidak valid.');
}

if ($subject === '' || strlen($subject) > 200) {
  http_response_code(422);
  exit('Subjek wajib diisi dan maksimal 200 karakter.');
}

if ($message === '' || strlen($message) > 5000) {
  http_response_code(422);
  exit('Pesan wajib diisi dan maksimal 5000 karakter.');
}

$receiving_email_address = 'bengkelkriyadaun@gmail.com';
$mail_subject = '=?UTF-8?B?' . base64_encode('Pesan Website: ' . str_replace(["\r", "\n"], ' ', $subject)) . '?=';
$mail_body = "Nama: {$name}\nEmail: {$email}\n\nPesan:\n{$message}";
$mail_headers = [
  'MIME-Version: 1.0',
  'Content-Type: text/plain; charset=UTF-8',
  'From: UD Bengkel Kriya Daun <' . $receiving_email_address . '>',
  'Reply-To: ' . $email,
];

if (!mail($receiving_email_address, $mail_subject, $mail_body, implode("\r\n", $mail_headers))) {
  http_response_code(500);
  exit('Pesan belum dapat dikirim. Silakan coba lagi atau hubungi kami melalui WhatsApp.');
}

echo 'OK';
?>
