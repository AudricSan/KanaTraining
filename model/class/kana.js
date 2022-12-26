class kana {
    constructor() { }

    hiragana = [
        ["あ", "a", true],
        ["い", "i", true],
        ["う", "u", true],
        ["え", "e", true],
        ["お", "o", true],

        ["か", "ka", true],
        ["き", "ki", true],
        ["く", "ku", true],
        ["け", "ke", true],
        ["こ", "ko", true],

        ["さ", "sa", true],
        ["し", "shi", true],
        ["す", "su", true],
        ["せ", "se", true],
        ["そ", "so", true],

        ["た", "ta", true],
        ["ち", "chi-ti-tsi", true],
        ["つ", "tsu-tu", true],
        ["て", "te", true],
        ["と", "to", true],

        ["な", "na", true],
        ["に", "ni", true],
        ["ぬ", "nu", true],
        ["ね", "ne", true],
        ["の", "no", true],

        ["は", "ha", true],
        ["ひ", "hi", true],
        ["ふ", "fu-hu", true],
        ["へ", "he", true],
        ["ほ", "ho", true],

        ["ま", "ma", true],
        ["み", "mi", true],
        ["む", "mu", true],
        ["め", "me", true],
        ["も", "mo", true],

        ["や", "ya", true],
        ["ゆ", "yu", true],
        ["よ", "yo", true],

        ["ら", "ra", true],
        ["り", "ri", true],
        ["る", "ru", true],
        ["れ", "re", true],
        ["ろ", "ro", true],

        ["わ", "wa", true],
        ["ゐ", "wi", false],
        ["ゑ", "we", false],
        ["を", "wo", true],

        ["ん", "n-nn", true]
    ];

    hiraganaCombo = [
        ["きゃ", "kya", true],
        ["きゅ", "kyu", true],
        ["きょ", "kyo", true],

        ["しゃ", "sha", true],
        ["しゅ", "shu", true],
        ["しょ", "sho", true],

        ["ちゃ", "cha", true],
        ["ちゅ", "chu", true],
        ["ちょ", "cho", true],

        ["にゃ", "nya", true],
        ["にゅ", "nyu", true],
        ["にょ", "nyo", true],

        ["ひゃ", "hya", true],
        ["ひゅ", "hyu", true],
        ["ひょ", "hyo", true],

        ["みゃ", "mya", true],
        ["みゅ", "myu", true],
        ["みょ", "myo", true],

        ["りゃ", "rya", true],
        ["りゅ", "ryu", true],
        ["りょ", "ryo", true]
    ];

    hiraganaDakuon = [
        ["が", "ga", true],
        ["ぎ", "gi", true],
        ["ぐ", "gu", true],
        ["げ", "ge", true],
        ["ご", "go", true],

        ["ざ", "za", true],
        ["じ", "zi-ji", true],
        ["ず", "zu", true],
        ["ぜ", "ze", true],
        ["ぞ", "zo", true],

        ["だ", "da", true],
        ["ぢ", "ji", true],
        ["づ", "du-zu-dzu", true],
        ["で", "de", true],
        ["ど", "do", true],

        ["ば", "ba", true],
        ["び", "bi", true],
        ["ぶ", "bu", true],
        ["べ", "be", true],
        ["ぼ", "bo", true],

        ["ぱ", "pa", true],
        ["ぴ", "pi", true],
        ["ぷ", "pu", true],
        ["ぺ", "pe", true],
        ["ぽ", "po", true],

        ["ゔ", "vu", true]
    ];

    hiraganaDakuonCombo = [
        ["ぎゃ", "gya", true],
        ["ぎゅ", "gyu", true],
        ["ぎょ", "gyo", true],

        ["じゃ", "ja", true],
        ["じゅ", "ju", true],
        ["じょ", "jo", true],

        ["ぢゃ", "ja", false],
        ["ぢゅ", "ju", false],
        ["ぢょ", "jo", false],

        ["びゃ", "bya", true],
        ["びゅ", "byu", true],
        ["びょ", "byo", true],

        ["ぴゃ", "pya", true],
        ["ぴゅ", "pyu", true],
        ["ぴょ", "pyo", true]
   
    ];

    katakana = [
        ["ア", "a", true],
        ["イ", "i", true],
        ["ウ", "u", true],
        ["エ", "e", true],
        ["オ", "o", true],

        ["カ", "ka", true],
        ["キ", "ki", true],
        ["ク", "ku", true],
        ["ケ", "ke", true],
        ["コ", "ko", true],

        ["サ", "sa", true],
        ["シ", "shi", true],
        ["ス", "su", true],
        ["セ", "se", true],
        ["ソ", "so", true],

        ["タ", "ta", true],
        ["チ", "chi", true],
        ["ツ", "tsu", true],
        ["テ", "te", true],
        ["ト", "to", true],

        ["ナ", "na", true],
        ["ニ", "ni", true],
        ["ヌ", "nu", true],
        ["ネ", "ne", true],
        ["ノ", "no", true],

        ["ハ", "ha", true],
        ["ヒ", "hi", true],
        ["フ", "fu", true],
        ["ヘ", "he", true],
        ["ホ", "ho", true],

        ["マ", "ma", true],
        ["ミ", "mi", true],
        ["ム", "mu", true],
        ["メ", "me", true],
        ["モ", "mo", true],

        ["ヤ", "ya", true],
        ["ユ", "yu", true],
        ["ヨ", "yo", true],

        ["ラ", "ra", true],
        ["リ", "ri", true],
        ["ル", "ru", true],
        ["レ", "re", true],
        ["ロ", "ro", true],

        ["ワ", "wa", true],
        ["ヲ", "wo", true],

        ["ン", "n", true]
    ];

    katakanaCombo = [
        ["キャ", "kya", true],
        ["キュ", "kyu", true],
        ["キョ", "kyo", true],

        ["ギャ", "gya", true],
        ["ギュ", "gyu", true],
        ["ギョ", "gyo", true],

        ["シャ", "sha", true],
        ["シュ", "shu", true],
        ["ショ", "sho", true],

        ["チャ", "cha", true],
        ["チュ", "chu", true],
        ["チョ", "cho", true],

        ["ニャ", "nya", true],
        ["ニュ", "nyu", true],
        ["ニョ", "nyo", true],

        ["ヒャ", "hya", true],
        ["ヒュ", "hyu", true],
        ["ヒョ", "hyo", true],

        ["ミャ", "mya", true],
        ["ミュ", "myu", true],
        ["ミョ", "myo", true],

        ["リャ", "rya", true],
        ["リュ", "ryu", true],
        ["リョ", "ryo", true]
    ];

    katakanaDakuon = [
        ["ガ", "ga", true],
        ["ギ", "gi", true],
        ["グ", "gu", true],
        ["ゲ", "ge", true],
        ["ゴ", "go", true],

        ["ザ", "za", true],
        ["ジ", "zi-ji", true],
        ["ズ", "zu", true],
        ["ゼ", "ze", true],
        ["ゾ", "zo", true],

        ["ダ", "da", true],
        ["ヂ", "ji", true],
        ["ヅ", "du-zu-dzu", true],
        ["デ", "de", true],
        ["ド", "do", true],

        ["バ", "ba", true],
        ["ビ", "bi", true],
        ["ブ", "bu", true],
        ["ベ", "be", true],
        ["ボ", "bo", true],

        ["パ", "pa", true],
        ["ピ", "pi", true],
        ["プ", "pu", true],
        ["ペ", "pe", true],
        ["ポ", "po", true]
    ];

    katakanaDakuonCombo = [
        ["ビャ", "bya", true],
        ["ビュ", "byu", true],
        ["ビョ", "byo", true],

        ["ピャ", "pya", true],
        ["ピュ", "pyu", true],
        ["ピョ", "pyo", true],

        ["ジャ", "ja", true],
        ["ジュ", "ju", true],
        ["ジョ", "jo", true],
    ];
}