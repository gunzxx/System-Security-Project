<!DOCTYPE html>
<html>

<head>
    <title>Vigenere Cipher Decryption</title>
    <link rel="stylesheet" href="style.css">
    <script src="jquery.min.js"></script>
    <script src="swal2.js"></script>
    <script src="script.js" defer></script>
</head>

<body>
    <main>
        <div class="container">
            <h2>Vigenere Cipher Decryption</h2>
            <form method="POST" class="form-container" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="plaintext">Masukkan gambar :</label>
                    <input type="file" id="image" accept="image/png" name="image" required>
                </div>
                <div class="form-group">
                    <label for="key">Key hanya huruf dan tidak <a href="https://www.wiblogger.com/2017/12/case-sensitive-dan-case-insensitive.html" target="_blank">Case Sensitive</a> :</label>
                    <input type="text" id="key" name="key" required>
                </div>
                <div class="button-container">
                    <div class="form-group">
                        <button type="submit">Decrypt</button>
                    </div>
                    <a href="index.php">Encrypt</a>
                </div>
            </form>

            <?php
            function decrypt_vigenere($ciphertext, $key)
            {
                $decrypted_text = "";
                $key_length = strlen($key);
                $key = strtoupper($key);
                $index = 0;

                for ($i = 0; $i < strlen($ciphertext); $i++) {
                    $char = $ciphertext[$i];
                    if (ctype_alpha($char)) {
                        // Ubah karakter kunci sesuai dengan indeksnya
                        $key_shift = ord($key[$index % $key_length]) - ord('A');

                        if (ctype_upper($char)) {
                            $decrypted_char = chr((ord($char) - ord('A') - $key_shift + 26) % 26 + ord('A'));
                        } else {
                            $decrypted_char = chr((ord($char) - ord('a') - $key_shift + 26) % 26 + ord('a'));
                        }

                        $index++;
                    } else {
                        $decrypted_char = $char;
                    }

                    $decrypted_text .= $decrypted_char;
                }

                return $decrypted_text;
            }


            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $key = $_POST['key'];

                    $targetDir = 'encrypted/';
                    $imageFileType = explode('/', $_FILES['image']['type']);
                    $imageFileType = end($imageFileType);

                    // unggah gambar
                    $allowedTypes = ['png'];
                    if (in_array($imageFileType, $allowedTypes)) {
                        $image = imagecreatefrompng($_FILES['image']['tmp_name']);

                        // Membaca panjang teks dari 2 byte pertama gambar
                        $lengthBytes = "";
                        $lengthBytes .= chr(imagecolorat($image, 0, 0));
                        $lengthBytes .= chr(imagecolorat($image, 1, 0));
                        $textLength = unpack("n", $lengthBytes)[1];
                        // echo $textLength;

                        // Mengambil tiap karakter teks dari bit LSB (Least Significant Bit) nilai pixel gambar
                        $text = "";
                        $charIndex = 0;
                        $width = imagesx($image);
                        $height = imagesy($image);
                        for ($y = 0; $y < $height; $y++) {
                            for ($x = 2; $x < $width; $x++) {
                                if ($charIndex < $textLength) {
                                    $rgb = imagecolorat($image, $x, $y);
                                    echo $rgb;
                                    $r = ($rgb >> 16) & 0xFF;
                                    $g = ($rgb >> 8) & 0xFF;
                                    $b = $rgb & 0xFF;

                                    // Mendapatkan bit LSB dari nilai RGB
                                    $charCode = (($r & 0x01) << 7) | (($g & 0x01) << 6) | (($b & 0x01) << 5);
                                    $char = chr($charCode);
                                    // echo $charCode."<br>";
                                    $text .= $char;

                                    $charIndex++;
                                } else {
                                    break 2; // Menghentikan perulangan jika semua karakter telah diambil
                                }
                            }
                        }

                        // Menghapus gambar dari memori
                        imagedestroy($image);
                        
                        // $plaintext = decrypt_vigenere($extractedText, $key);
                        $plaintext = '';
                        
                        echo '<div class="result">';
                        echo '<strong>Pesan berhasil diekstrak:</strong><br>';
                        echo '<p>' . $text . '</p>';
                        echo '<strong>Plaintext:</strong> ' . $plaintext . '<br>';
                        echo '<strong>Key:</strong> ' . $key . '<br>';
                        echo '<strong>Ciphertext:</strong> ' . $text . '<br>';
                        echo '</div>';
                        // if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                        // } else {
                        //     echo '<div class="result">';
                        //     echo '<strong>Terjadi kesalahan saat mengunggah gambar.</strong>';
                        //     echo '</div>';
                        // }
                    } else {
                        echo '<div class="result">';
                        echo '<strong>Format gambar tidak valid. Hanya file JPG, JPEG, dan PNG yang diperbolehkan.</strong>';
                        echo '</div>';
                        exit;
                    }
                }
            }
            ?>
        </div>
    </main>
</body>

</html>