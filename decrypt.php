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
            <form method="POST" class="form-container">
                <div class="form-group">
                    <label for="plaintext">Plaintext:</label>
                    <input type="text" id="plaintext" name="plaintext" required>
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
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $plaintext = $_POST['plaintext'];
                $key = $_POST['key'];
    
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
    
    
    
                $ciphertext = decrypt_vigenere($plaintext, $key);
    
                echo '<div class="result">';
                echo '<strong>Plaintext:</strong> ' . $plaintext . '<br>';
                echo '<strong>Key:</strong> ' . $key . '<br>';
                echo '<strong>Ciphertext:</strong> ' . $ciphertext . '<br>';
                echo '</div>';
            }
            ?>
        </div>
    </main>
</body>

</html>