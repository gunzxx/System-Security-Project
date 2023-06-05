<!DOCTYPE html>
<html>

<head>
    <title>Vigenere Cipher Encryption</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Vigenere Cipher Encryption</h2>
        <!-- <?= ord("asu"[1]) ?> -->
        <form method="POST">
            <div class="form-group">
                <label for="plaintext">Plaintext:</label>
                <input type="text" id="plaintext" name="plaintext" required>
            </div>
            <div class="form-group">
                <label for="key">Key (hanya huruf dan tidak <a href="https://www.wiblogger.com/2017/12/case-sensitive-dan-case-insensitive.html" target="_blank">Case Sensitive</a>):</label>
                <input type="text" id="key" name="key" required>
            </div>
            <div class="form-group">
                <button type="submit">Encrypt</button>
            </div>
            <a href="decrypt.php">Decrypt</a>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $plaintext = $_POST['plaintext'];
            $key = $_POST['key'];

            function vigenereEncrypt($plaintext, $key)
            {
                $plaintext = strtoupper($plaintext);
                $key = strtoupper($key);
                $ciphertext = '';

                $plaintextLength = strlen($plaintext);
                $keyLength = strlen($key);

                for ($i = 0; $i < $plaintextLength; $i++) {
                    $plainChar = ord($plaintext[$i]);
                    $keyChar = ord($key[$i % $keyLength]);

                    // plain kapital
                    if ($plainChar >= 65 && $plainChar <= 90) {
                        $plainChar -= 65;
                        $keyChar -= 65;
                        $encryptedChar = ($plainChar + $keyChar) % 26;
                        $ciphertext .= chr($encryptedChar + 65);
                    }
                    // plain non-kapital
                    elseif ($plainChar >= 97 && $plainChar <= 122) {
                        $plainChar -= 97;
                        $keyChar -= 65;
                        $encryptedChar = ($plainChar + $keyChar) % 26;
                        $ciphertext .= chr($encryptedChar + 97);
                    }
                    // plain angka
                    elseif ($plainChar >= 48 && $plainChar <= 57) {
                        $plainChar -= 48;
                        $keyChar -= 65;
                        $encryptedChar = ($plainChar + $keyChar) % 26;
                        $ciphertext .= chr($encryptedChar + 97);
                    } else {
                        $ciphertext .= $plaintext[$i];
                    }
                }

                return $ciphertext;
            }

            $ciphertext = vigenereEncrypt($plaintext, $key);

            echo '<div class="result">';
            echo '<strong>Plaintext:</strong> ' . $plaintext . '<br>';
            echo '<strong>Key:</strong> ' . $key . '<br>';
            echo '<strong>Ciphertext:</strong> ' . $ciphertext . '<br>';
            echo '</div>';
        }
        ?>
    </div>
</body>

</html>