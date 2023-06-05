<!DOCTYPE html>
<html>

<head>
    <title>Vigenere Cipher Encryption</title>
    <link rel="stylesheet" href="style.css">
    <script src="jquery.min.js"></script>
    <script src="swal2.js"></script>
    <script src="script.js" defer></script>
</head>

<body>
    <main>

        <div class="container">
            <h2>Vigenere Cipher Encryption</h2>
            <form method="POST" class="form-container" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="plaintext">Plaintext :</label>
                    <input autofocus type="text" id="plaintext" name="plaintext" required>
                </div>
                <div class="form-group">
                    <label for="plaintext">Masukkan gambar :</label>
                    <input autofocus type="file" id="image" accept="image/png" name="image" required>
                </div>
                <div class="form-group">
                    <label for="key">Key hanya huruf dan tidak <a href="https://www.wiblogger.com/2017/12/case-sensitive-dan-case-insensitive.html" target="_blank">Case Sensitive</a> :</label>
                    <input type="text" id="key" name="key" required>
                </div>
                <div class="button-container">
                    <div class="form-group">
                        <button type="submit">Encrypt</button>
                    </div>
                    <a href="decrypt.php">Decrypt</a>
                </div>
            </form>

            <?php
            function encrypt_vigenere($plaintext, $key)
            {
                $encrypted_text = "";
                $key_length = strlen($key);
                $key = strtoupper($key);
                $index = 0;

                for ($i = 0; $i < strlen($plaintext); $i++) {
                    $plainchar = $plaintext[$i];
                    if (ctype_alpha($plainchar)) {
                        $key_shift = ord($key[$index % $key_length]) - 65;

                        if (ctype_upper($plainchar)) {
                            $encrypted_char = chr(((ord($plainchar) - 65) + $key_shift) % 26 + 65);
                        } else {
                            $encrypted_char = chr(((ord($plainchar) - 97) + $key_shift) % 26 + 97);
                        }

                        $index++;
                    } else {
                        $encrypted_char = $plainchar;
                    }

                    $encrypted_text .= $encrypted_char;
                }

                return $encrypted_text;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $plaintext = $_POST['plaintext'];
                    $key = $_POST['key'];

                    $ciphertext = encrypt_vigenere($plaintext, $key);


                    $targetDir = 'encrypted/';
                    $targetFile = $targetDir.basename($_FILES['image']['name']);
                    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                    // Periksa apakah file yang diunggah adalah gambar
                    $allowedTypes = ['jpg', 'jpeg', 'png'];
                    if (in_array($imageFileType, $allowedTypes)) {
                        
                        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                            $message = $ciphertext;

                            embedMessage($targetFile, $message);

                            echo '<div class="result">';
                            echo 'Pesan telah berhasil disisipkan ke dalam gambar.';
                            echo '<strong>Plaintext:</strong> ' . $plaintext . '<br>';
                            echo '<strong>Key:</strong> ' . $key . '<br>';
                            echo '<strong>Ciphertext:</strong> ' . $ciphertext . '<br>';
                            echo '</div>';
                        } else {
                            echo 'Terjadi kesalahan saat mengunggah gambar.';
                        }
                    } else {
                        echo 'Format gambar tidak valid. Hanya file JPG, JPEG, dan PNG yang diperbolehkan.';
                        exit;
                    }
                }
            }
            function embedMessage($coverImage, $message)
            {
                $outputImage = imagecreatefromjpeg($coverImage);
                $messageBin = str_pad(decbin(strlen($message)), 8, '0', STR_PAD_LEFT) . $message;
                $messageLength = strlen($messageBin);

                $index = 0;
                for ($x = 0; $x < imagesx($outputImage); $x++) {
                    for ($y = 0; $y < imagesy($outputImage); $y++) {
                        $rgb = imagecolorat($outputImage, $x, $y);

                        $r = ($rgb >> 16) & 0xFF;
                        $g = ($rgb >> 8) & 0xFF;
                        $b = $rgb & 0xFF;

                        if ($index < $messageLength) {
                            $r = ($r & 0xFE) | $messageBin[$index];
                            $index++;
                        }
                        if ($index < $messageLength) {
                            $g = ($g & 0xFE) | $messageBin[$index];
                            $index++;
                        }
                        if ($index < $messageLength) {
                            $b = ($b & 0xFE) | $messageBin[$index];
                            $index++;
                        }

                        $color = imagecolorallocate($outputImage, $r, $g, $b);
                        imagesetpixel($outputImage, $x, $y, $color);
                    }
                }

                imagejpeg($outputImage, "output_image.jpg");
                imagedestroy($outputImage);
            }
            ?>
        </div>
    </main>
</body>

</html>