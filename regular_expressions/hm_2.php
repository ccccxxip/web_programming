<?php

// расширение файла
function getExtension($filename) {
    if (preg_match('/\.([a-zA-Z0-9]+)$/', $filename, $m)) {
        return $m[1];
    }
    return null;
}


// тип файла
function checkFileType($filename) {

    $archive = '/\.(zip|rar|7z|tar|gz)$/i';
    $audio   = '/\.(mp3|wav|ogg|flac)$/i';
    $video   = '/\.(mp4|avi|mkv|mov)$/i';
    $image   = '/\.(jpg|jpeg|png|gif|webp)$/i';

    return [
        'archive' => preg_match($archive, $filename),
        'audio'   => preg_match($audio, $filename),
        'video'   => preg_match($video, $filename),
        'image'   => preg_match($image, $filename),
    ];
}


// Найти <title>
function getTitle($html) {
    if (preg_match('/<title>(.*?)<\/title>/is', $html, $m)) {
        return trim($m[1]);
    }
    return null;
}


// Все ссылки <a href="">
function getLinks($html) {
    preg_match_all('/<a\s+[^>]*href=["\'](.*?)["\']/i', $html, $m);
    return $m[1] ?? [];
}


// Все картинки <img src="">
function getImages($html) {
    preg_match_all('/<img\s+[^>]*src=["\'](.*?)["\']/i', $html, $m);
    return $m[1] ?? [];
}


// Подсветка строки <strong>
function highlightText($text, $word) {
    $pattern = '/' . preg_quote($word, '/') . '/i';
    return preg_replace($pattern, '<strong>$0</strong>', $text);
}


// Замена смайликов на картинки
function replaceSmiles($text) {

    $patterns = [
        '/:\)/' => '<img src="smile.png" alt=":)">',
        '/;\)/' => '<img src="wink.png" alt=";)">',
        '/:\(/' => '<img src="sad.png" alt=":(">',
    ];

    return preg_replace(array_keys($patterns), array_values($patterns), $text);
}


// Убрать лишние пробелы
function clearSpaces($text) {
    return preg_replace('/\s+/', ' ', trim($text));
}


// тесты

echo "extension test: ";
echo getExtension("picture.jpg");
echo "\n\n";

echo "file type test:\n";
print_r(checkFileType("movie.mp4"));
echo "\n\n";

echo "title test: ";
echo getTitle("<title>Test Page</title>");
echo "\n\n";

echo "links test:\n";
print_r(getLinks('<a href="https://google.com">Google</a>'));
echo "\n\n";

echo "images test:\n";
print_r(getImages('<img src="cat.jpg">'));
echo "\n\n";

echo "highlight test: ";
echo highlightText("Hello world", "world");
echo "\n\n";

echo "smile test: ";
echo replaceSmiles("Hello :) sad :( wink ;)");
echo "\n\n";

echo "clean spaces test: ";
echo clearSpaces("Hello     world   test   string");
echo "\n";

?>