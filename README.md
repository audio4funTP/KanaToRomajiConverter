# KanaToRomajiConverter
KanaToRomajiConverter class converts Japanese kana (hiragana/katakana) into Romaji for easier reading and pronunciation.
# USE
include("japan-to-latin-class.php");

$converter = new KanaToRomajiConverter();

echo $converter->convert('こんにちは'); //example // Output: konnichiwa
