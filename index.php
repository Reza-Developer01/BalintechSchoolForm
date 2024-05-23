<?php
// مسیر فایل CSV
$file = 'registrations.csv';

// اطلاعات فرم ارسال شده
$name = isset($_POST['firstname']) ? htmlspecialchars($_POST['firstname']) : '';
$family = isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : '';
$nationalCode = isset($_POST['nationalcode']) ? htmlspecialchars($_POST['nationalcode']) : '';
$adjusted = isset($_POST['adjusted']) ? htmlspecialchars($_POST['adjusted']) : '';
$dateOfBirth = isset($_POST['dateofbirth']) ? htmlspecialchars($_POST['dateofbirth']) : '';
$phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
$previousSchool = isset($_POST['previousSchool']) ? htmlspecialchars($_POST['previousSchool']) : '';

// بررسی ارسال فرم و ذخیره داده‌ها
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // اگر تمام فیلدها خالی نباشند، داده‌ها را در فایل CSV ذخیره کن
    if (!empty($name) && !empty($family) && !empty($nationalCode) && !empty($adjusted) && !empty($dateOfBirth) && !empty($phone) && !empty($previousSchool)) {
        // تلاش برای باز کردن فایل با حداکثر 5 بار تلاش
        $attempts = 0;
        $maxAttempts = 5;
        $handle = false;

        while ($attempts < $maxAttempts && !$handle) {
            $handle = @fopen($file, 'a'); // استفاده از @ برای جلوگیری از نمایش هشدارهای PHP
            if (!$handle) {
                $attempts++;
                usleep(100000); // صبر به مدت 100 میلی‌ثانیه قبل از تلاش مجدد
            }
        }

        if ($handle) {
            // قفل گذاری روی فایل
            if (flock($handle, LOCK_EX)) {
                // افزودن داده‌های کاربر به فایل
                if (fputcsv($handle, [$name, $family, $nationalCode, $adjusted, $dateOfBirth, $phone, $previousSchool]) === false) {
                    error_log("Error writing data to the CSV file.");
                    http_response_code(500);
                    echo "مشکلی در ثبت نام رخ داده است. لطفاً دوباره امتحان کنید.";
                } else {
                    echo "ثبت نام با موفقیت انجام شد!";
                }

                // باز کردن قفل
                flock($handle, LOCK_UN);

                // بستن فایل
                fclose($handle);
            } else {
                fclose($handle);
                error_log("Could not lock the file.");
                http_response_code(500);
                echo "فایل در حال استفاده است. لطفاً فایل Excel را ببندید و دوباره امتحان کنید.";
            }
        } else {
            error_log("Failed to open the file after multiple attempts.");
            http_response_code(500);
            echo "فایل در حال استفاده است. لطفاً فایل Excel را ببندید و دوباره امتحان کنید.";
        }
    } else {
        echo "لطفا برگردید و تمام اطلاعات مورد نیاز را وارد کنید.";
    }
}
