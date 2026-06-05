<?php
// إعدادات الاتصال بقاعدة البيانات الجديدة د الجروب
$host = 'localhost';
$dbname = 'medflow_db'; // الاسم الجديد اللي تفاهمتوا عليه
$username = 'root';
$password = '';

try {
    // فتح الاتصال في سطر واحد مباشر
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // تفعيل التنبيه بالأخطاء باش يلا غلطتي ف شي كود SQL يعاونك السيرفر ديريكت
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // يلا وقع مشكل ف الاتصال كيطبع رسالة واضحة
    die("Erreur de connexion : " . $e->getMessage());
}