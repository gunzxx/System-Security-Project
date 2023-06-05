<!DOCTYPE html>
<html>

<head>
    <title>Keamanan Sistem - Kelompok 3</title>
    <link rel="stylesheet" href="style.css">
    <script src="jquery.min.js"></script>
    <script src="swal2.js"></script>
    <script src="script.js" defer></script>
</head>

<body>
    <main>

        <div class="container">
            <h2>Vigenere Cipher Encryption + LSB</h2>
            <form method="POST" class="form-container" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="plaintext">Plaintext :</label>
                    <input autofocus type="text" id="plaintext" name="plaintext" required>
                </div>
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
                    $imageFileType = explode('/', $_FILES['image']['type']);
                    $imageFileType = end($imageFileType);

                    // Periksa file unggah gambar
                    $allowedTypes = ['png'];
                    if (in_array($imageFileType, $allowedTypes)) {
                        $message = $ciphertext;
                        
                        $textLengthBin = str_pad(decbin(strlen($message)), 32, '0', STR_PAD_LEFT);

                        $image = imagecreatefrompng($_FILES['image']['tmp_name']);

                        $width = imagesx($image);
                        $height = imagesy($image);

                        // Menyembunyikan panjang teks ke dalam gambar (4 byte)
                        $x = 0;
                        $y = 0;
                        $textLength = strlen($message);

                        $textLengthBin = str_pad(decbin($textLength), 32, '0', STR_PAD_LEFT);
                        for ($i = 0; $i < 32; $i++) {
                            $rgb = imagecolorat($image, $x, $y);
                            $newR = ($rgb >> 16) & 0xFF;
                            $newG = ($rgb >> 8) & 0xFF;
                            $newB = ($rgb) & 0xFF;
                            $newR = ($newR & 0xFE) | (int)$textLengthBin[$i];
                            imagesetpixel($image, $x,
                                $y,
                                imagecolorallocate($image, $newR, $newG, $newB)
                            );
                            $x++;
                            if ($x >= $width) {
                                $x = 0;
                                $y++;
                            }
                        }

                        // Menyembunyikan teks ke dalam gambar
                        for ($i = 0; $i < $textLength; $i++) {
                            $char = $message[$i];
                            $charBin = str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT);
                            for ($j = 0; $j < 8; $j++) {
                                $rgb = imagecolorat($image, $x, $y);
                                $newR = ($rgb >> 16) & 0xFF;
                                $newG = ($rgb >> 8) & 0xFF;
                                $newB = ($rgb) & 0xFF;
                                $newR = ($newR & 0xFE) | (int)$charBin[$j];
                                imagesetpixel($image, $x, $y, imagecolorallocate($image, $newR, $newG, $newB));
                                $x++;
                                if ($x >= $width
                                ) {
                                    $x = 0;
                                    $y++;
                                }
                            }
                        }

                        // Simpan gambar dengan teks tersembunyi ke dalam file baru
                        imagepng($image, $targetDir.'encoded_'.basename($_FILES['image']['name']));
                        // unlink($targetDir.basename($_FILES['image']['name']));

                        echo '<div class="result">';
                        echo '<strong>Pesan telah berhasil disisipkan ke dalam gambar.</strong><br>';
                        echo '<a href="' . $targetDir . 'encoded_' . basename($_FILES['image']['name']) . '" download>Unduh Gambar Hasil Encoding</a><br>';
                        
                        echo "<br>";
                        echo '<strong>Plain text:</strong> ' . $plaintext . '<br>';
                        echo '<strong>Key:</strong> ' . $key . '<br>';
                        echo '<strong>Cipher text:</strong> ' . $ciphertext . '<br>';
                        echo '</div>';
                    } else {
                        echo '<div class="result">';
                        echo '<strong>Format gambar tidak valid. Hanya file PNG yang diperbolehkan.</strong>';
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