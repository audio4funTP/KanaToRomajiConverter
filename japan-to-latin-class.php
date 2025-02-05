<?php
#Author: Pierre Tran from iVoicesoft.com ; Trieuphu05 from Truongca.com
#KanaToRomajiConverter class converts Japanese kana (hiragana/katakana) into Romaji for easier reading and pronunciation.
#include("japan-to-latin-class.php");
#$converter = new KanaToRomajiConverter();
#$echo $converter->convert('こんにちは'); // Output: konnichiwa

class KanaToRomajiConverter {
    private $kanaArray = [];
    private $useDiacriticsForLongVowels = false;
    private $singleKana = [
        ['あ','a'],['ア','a'],['い','i'],['イ','i'],['う','u'],['ウ','u'],['え','e'],['エ','e'],['お','o'],['オ','o'],
		['か','ka'],['カ','ka'],['き','ki'],['キ','ki'],['く','ku'],['ク','ku'],['け','ke'],['ケ','ke'],['こ','ko'],['コ','ko'],
		['さ','sa'],['サ','sa'],['し','shi'],['シ','shi'],['す','su'],['ス','su'],['せ','se'],['セ','se'],['そ','so'],['ソ','so'],
		['た','ta'],['タ','ta'],['ち','chi'],['チ','chi'],['つ','tsu'],['ツ','tsu'],['て','te'],['テ','te'],['と','to'],['ト','to'],
		['な','na'],['ナ','na'],['に','ni'],['ニ','ni'],['ぬ','nu'],['ヌ','nu'],['ね','ne'],['ネ','ne'],['の','no'],['ノ','no'],
		['は','ha'],['ハ','ha'],['ひ','hi'],['ヒ','hi'],['ふ','fu'],['フ','fu'],['へ','he'],['ヘ','he'],['ほ','ho'],['ホ','ho'],
		['ま','ma'],['マ','ma'],['み','mi'],['ミ','mi'],['む','mu'],['ム','mu'],['め','me'],['メ','me'],['も','mo'],['モ','mo'],
		['や','ya'],['ヤ','ya'],['ゆ','yu'],['ユ','yu'],['よ','yo'],['ヨ','yo'],
		['ら','ra'],['ラ','ra'],['り','ri'],['リ','ri'],['る','ru'],['ル','ru'],['れ','re'],['レ','re'],['ろ','ro'],['ロ','ro'],
		['わ','wa'],['ワ','wa'],['ゐ','i'],['ヰ','i'],['ゑ','e'],['ヱ','e'],['を','o'],['ヲ','o'],['ん','n'],['ン','n'],
		['が','ga'],['ガ','ga'],['ぎ','gi'],['ギ','gi'],['ぐ','gu'],['グ','gu'],['げ','ge'],['ゲ','ge'],['ご','go'],['ゴ','go'],
		['ざ','za'],['ザ','za'],['じ','ji'],['ジ','ji'],['ず','zu'],['ズ','zu'],['ぜ','ze'],['ゼ','ze'],['ぞ','zo'],['ゾ','zo'],
		['だ','da'],['ダ','da'],['ぢ','ji'],['ヂ','ji'],['づ','zu'],['ヅ','zu'],['で','de'],['デ','de'],['ど','do'],['ド','do'],
		['ば','ba'],['バ','ba'],['び','bi'],['ビ','bi'],['ぶ','bu'],['ブ','bu'],['べ','be'],['ベ','be'],['ぼ','bo'],['ボ','bo'],
		['ぱ','pa'],['パ','pa'],['ぴ','pi'],['ピ','pi'],['ぷ','pu'],['プ','pu'],['ぺ','pe'],['ペ','pe'],['ぽ','po'],['ポ','po'],
		['ヷ','va'],['ヸ','vi'],['ヴ','vu'],['ゔ','vu'],['ヹ','ve'],['ヺ','vo']
		];
    
    private $doubleKana = [
        ['きゃ','kya'],['キャ','kya'],['きゅ','kyu'],['キュ','kyu'],['きょ','kyo'],['キョ','kyo'],
		['しゃ','sha'],['シャ','sha'],['しゅ','shu'],['シュ','shu'],['しょ','sho'],['ショ','sho'],
		['ちゃ','cha'],['チャ','cha'],['ちゅ','chu'],['チュ','chu'],['ちょ','cho'],['チョ','cho'],
		['にゃ','nya'],['ニャ','nya'],['にゅ','nyu'],['ニュ','nyu'],['にょ','nyo'],['ニョ','nyo'],
		['ひゃ','hya'],['ヒャ','hya'],['ひゅ','hyu'],['ヒュ','hyu'],['ひょ','hyo'],['ヒョ','hyo'],
		['みゃ','mya'],['ミャ','mya'],['みゅ','myu'],['ミュ','myu'],['みょ','myo'],['ミョ','myo'],
		['りゃ','rya'],['リャ','rya'],['りゅ','ryu'],['リュ','ryu'],['りょ','ryo'],['リョ','ryo'],
		['ぎゃ','gya'],['ギャ','gya'],['ぎゅ','gyu'],['ギュ','gyu'],['ぎょ','gyo'],['ギョ','gyo'],
		['じゃ','ja'],['ジャ','ja'],['じゅ','ju'],['ジュ','ju'],['じょ','jo'],['ジョ','jo'],
		['ぢゃ','ja'],['ヂャ','ja'],['ぢゅ','ju'],['ヂュ','ju'],['ぢょ','jo'],['ヂョ','jo'],
		['びゃ','bya'],['ビャ','bya'],['びゅ','byu'],['ビュ','byu'],['びょ','byo'],['ビョ','byo'],
		['ぴゃ','pya'],['ピャ','pya'],['ぴゅ','pyu'],['ピュ','pyu'],['ぴょ','pyo'],['ピョ','pyo'],
		['イィ','yi'],['イェ','ye'],['ウァ','wa'],['ウィ','wi'],['ウゥ','wu'],['ウェ','we'],['ウォ','wo'],
		['ウュ','wyu'],
		['ヴァ','va'],['ヴィ','vi'],['ヴ','vu'],['ヴェ','ve'],['ヴォ','vo'],
		['ヴャ','vya'],['ヴュ','vyu'],['ヴョ','vyo'],
		['キェ','kye'],['ギェ','gye'],
		['クァ','kwa'],['クィ','kwi'],['クェ','kwe'],['クォ','kwo'],
		['クヮ','kwa'],['グァ','gwa'],['グヮ','gwa'],['グィ','gwi'],['グェ','gwe'],['グォ','gwo'],
		['シェ','she'],['ジェ','je'],
		['スィ','si'],['ズィ','zi'],
		['チェ','che'],
		['ツァ','tsa'],['ツィ','tsi'],['ツェ','tse'],['ツォ','tso'],['ツュ','tsyu'],
		['ティ','ti'],['トゥ','tu'],['テュ','tyu'],['ディ','di'],['ドゥ','du'],['デュ','dyu'],
		['ニェ','nye'],['ヒェ','hye'],['ビェ','bye'],['ピェ','pye'],
		['ファ','fa'],['フィ','fi'],['フェ','fe'],['フォ','fo'],
		['フャ','fya'],['フュ','fyu'],['フョ','fyo'],
		['ホゥ','hu'],
		['ミェ','mye'],
		['リェ','rye'],
		['ラ゜','la'],['リ゜','li'],['ル゜','lu'],['レ゜','le'],['ロ゜','lo']
    ];
    
    private $tripleKana = [
        ['リ゜ャ', 'lya'], ['リ゜ュ', 'lyu'], ['リ゜ェ', 'lye'], ['リ゜ョ', 'lyo'], ['リ゜ュウ', 'lyu'], ['リ゜ョウ', 'lyo'],
        ['フィェ', 'fye']
    ];
    
    public function convert($kanaString) {
        foreach ($this->tripleKana as $mapping) {
            $kanaString = str_replace($mapping[0], $mapping[1], $kanaString);
        }
        foreach ($this->doubleKana as $mapping) {
            $kanaString = str_replace($mapping[0], $mapping[1], $kanaString);
        }
        foreach ($this->singleKana as $mapping) {
            $kanaString = str_replace($mapping[0], $mapping[1], $kanaString);
        }
        return $kanaString;
    }
}
?>
