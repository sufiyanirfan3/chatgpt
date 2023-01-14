<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatGPT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</head>

<style>
*{
    font-family: 'Roboto';
    font-size: 20px;

}</style>

<body class="container">

    <?php
    if (isset($_POST['clear'])) {
        $fh = fopen('output.txt', 'w');
        fclose($fh);
        $fh1 = fopen('input.txt', 'w');
        fclose($fh1);
        echo '<script type="text/javascript">';
        echo ' alert("Success")';
        echo '</script>';
    }

    $apiKey = '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';
    ?>


    <form method="POST" autocomplete="off">
        <br><br>
        Description: <textarea class="form-control" rows="2" type="text" name="description"></textarea>
        <br><br>
        <button class="btn btn-dark" type="submit" name="generate">Submit</button>
        <button class="btn btn-danger" type="submit" name="clear">Clear</button>
    </form>

    <br>

    <?php

    function remove_empty_lines($string)
    {
        $lines = explode("\n", str_replace(array("\r\n", "\r"), "\n", $string));
        $lines = array_map('trim', $lines);
        $lines = array_filter($lines, function ($value) {
            return $value !== '';
        });
        return implode("\n", $lines);
    }

    if (isset($_POST['generate'])) {

        $prompt =   $_POST["description"];

        $model = 'text-davinci-003';

        $curl = curl_init();

        $request_body = [
            "prompt" => $prompt,
            "max_tokens" => 1024,
            "temperature" => 0.7,
            "top_p" => 1,
            "presence_penalty" => 0.75,
            "frequency_penalty" => 0.75,
            "best_of" => 1,
            "stream" => false,
            "model" => $model,
        ];

        $postfields = json_encode($request_body);

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => "https://api.openai.com/v1/completions",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $postfields,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Authorization: Bearer $apiKey"
                ),
            )
        );

        $response = curl_exec($curl);

        $response1 = json_decode($response, true);

        $myfile = fopen("output.txt", "w+");
        fwrite($myfile, $response1["choices"][0]["text"]);
        fclose($myfile);

        $myfile1 = fopen("input.txt", "w+");
        fwrite($myfile1, $prompt);
        fclose($myfile1);
        // $output=;
        curl_close($curl);
    }

    ?>


    <textarea class="form-control rounded-0" rows="10">

<?php

if (filesize("output.txt") && filesize("input.txt")) {

    $myfile1 = fopen("input.txt", "r");
    print_r('Question: ' . fread($myfile1, filesize("input.txt")));
    fclose($myfile1);

    echo "\n\n";

    $myfile = fopen("output.txt", "r");
    print_r('Response: ' . trim(fread($myfile, filesize("output.txt"))));
    fclose($myfile);
} else {

    echo "{{generated_text}}";
}
?>

</textarea>

</body>

</html>