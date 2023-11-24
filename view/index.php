<?php
$link = $_SESSION['TLink'];
// $_SESSION['email'] = "audricrosier@gmail.com";

echo "
    <main>
        <section class='_1'>
            <nav class=''>";

if (!empty($_SESSION['email'])) {
    echo "<a href='/student' class='fa-solid fa-person'></span> My student Page </a>";
} else {
    echo "<!-- <a href='$link' class='fa-solid fa-person'></span> Connection </a> -->";
    echo "<a href='JavaScript:createNotification(\"in as future update\")' class='fa-solid fa-person'></span> Connection </a>";
}

echo "
                <ul>
                    <p class='divider'>---------------------------------------</p>

                    <form action=''>
                        <input type='checkbox' class='switch theme' id='theme' name='cc' onchange='selectTheme()' />
                        <label for='theme'>theme-color</label>
                    </form>

                    <p class='divider'>---------------------------------------</p>

                    <form action=''>
                        <input type='checkbox' class='switch' id='hiragana' name='cc' onchange='selectDificulty(this.id)'/>
                        <label for='hiragana'>Hiragana</label>
                    </form>

                    <form action=''>
                        <input type='checkbox' class='switch' id='hiraganaCombo' name='cc' onchange='selectDificulty(this.id)' />
                        <label for='hiraganaCombo'>Hiragana Combo</label>
                    </form>

                    <form action=''>
                        <input type='checkbox' class='switch' id='hiraganaDakuon' name='cc' onchange='selectDificulty(this.id)' />
                        <label for='hiraganaDakuon'>Hiragana Dakuon</label>
                    </form>

                    <form action=''>
                        <input type='checkbox' class='switch' id='hiraganaDakuonCombo' name='cc' onchange='selectDificulty(this.id)' />
                        <label for='hiraganaDakuonCombo'>Hiragana Dakuon Combo </label>
                    </form>

                    <p class='divider'>---------------------------------------</p>

                    <form action=''>
                        <input type='checkbox' class='switch' id='katakana' name='cc' onchange='selectDificulty(this.id)' />
                        <label for='katakana'>Katakana</label>
                    </form>

                    <form action=''>
                        <input type='checkbox' class='switch' id='katakanaCombo' name='cc' onchange='selectDificulty(this.id)' />
                        <label for='katakanaCombo'>Katakana Combo</label>
                    </form>

                    <form action=''>
                        <input type='checkbox' class='switch' id='katakanaDakuon' name='cc' onchange='selectDificulty(this.id)' />
                        <label for='katakanaDakuon'>Katakana Dakuon</label>
                    </form>


                    <form action=''>
                        <input type='checkbox' class='switch' id='katakanaDakuonCombo' name='cc' onchange='selectDificulty(this.id)' />
                        <label for='katakanaDakuonCombo'>Katakana Dakuon Combo </label>

                    <p class='divider'>---------------------------------------</p>

                    <form action=''>
                        <input type='checkbox' class='switch' id='kanjin5' name='cc' onchange='selectDificulty(this.id)' />
                        <label for='kanjin5'>Kanji JLPT N5</label>
                    </form>

                    <form action=''>
                        <input type='checkbox' class='switch' id='kanjin4' name='cc' onchange='selectDificulty(this.id)' disabled/>
                        <label for='kanjin4'>Kanji JLPT N4</label>
                    </form>

                    <form action=''>
                        <input type='checkbox' class='switch' id='kanjin3' name='cc' onchange='selectDificulty(this.id)' disabled/>
                        <label for='kanjin3'>Kanji JLPT N3</label>
                    </form>

                    <form action=''>
                        <input type='checkbox' class='switch' id='kanjin2' name='cc' onchange='selectDificulty(this.id)' disabled/>
                        <label for='kanjin2'>Kanji JLPT N2</label>
                    </form>


                    <form action=''>
                        <input type='checkbox' class='switch' id='kanjin1' name='cc' onchange='selectDificulty(this.id)' />
                        <label for='kanjin1'>Kanji JLPT N1</label>
                    </form>
                </ul>
            </nav>
        </section>

        <section class='_2'>
            <h2 class='character'> あ </h2>
            <h3 class='kanjitype'> read </h3>

            <div class='keyboard'>
                <span class='material-icons-round iconkeyboard'>keyboard_double_arrow_right</span>
                <span class='material-icons-round iconkeyboard'>keyboard</span>
                <span class='material-icons-round iconkeyboard'>keyboard_double_arrow_left</span>
            </div>

            <form class='forms' action='#' onsubmit='checkAnswerd(); return false' autocomplete='off'>

                <div class='score'>
                    <span>Votre score</span>
                    <span class='fa-solid fa-star'></span>
                    <span class='good'>0</span>/<span class='total'>0</span>
                </div>

                <div id='toast'>
                   <!--
                   <p>
                    <span>
                        coucou
                    </span>
                    </p>
                    -->
                </div>

                <input name='input' type='text' class='input' autofocus maxlength='3' />
            </form>
        </section>

        <section class='_3'>

            <div class='helpSpawner'>
                <ul class='helpContainer'>
                  <!--  <li><p class='kana'>あ</p><p class='romanji'>a</p></li><li><p class='kana'>い</p><p class='romanji'>i</p></li><li><p class='kana'>う</p><p class='romanji'>u</p></li><li><p class='kana'>え</p><p class='romanji'>e</p></li><li><p class='kana'>お</p><p class='romanji'>o</p></li><li><p class='kana'>か</p><p class='romanji'>ka</p></li><li><p class='kana'>き</p><p class='romanji'>ki</p></li><li><p class='kana'>く</p><p class='romanji'>ku</p></li><li><p class='kana'>け</p><p class='romanji'>ke</p></li><li><p class='kana'>こ</p><p class='romanji'>ko</p></li><li><p class='kana'>さ</p><p class='romanji'>sa</p></li><li><p class='kana'>し</p><p class='romanji'>shi</p></li><li><p class='kana'>す</p><p class='romanji'>su</p></li><li><p class='kana'>せ</p><p class='romanji'>se</p></li><li><p class='kana'>そ</p><p class='romanji'>so</p></li><li><p class='kana'>た</p><p class='romanji'>ta</p></li><li><p class='kana'>ち</p><p class='romanji'>chi-ti-tsi</p></li><li><p class='kana'>つ</p><p class='romanji'>tsu-tu</p></li><li><p class='kana'>て</p><p class='romanji'>te</p></li><li><p class='kana'>と</p><p class='romanji'>to</p></li><li><p class='kana'>な</p><p class='romanji'>na</p></li><li><p class='kana'>に</p><p class='romanji'>ni</p></li><li><p class='kana'>ぬ</p><p class='romanji'>nu</p></li><li><p class='kana'>ね</p><p class='romanji'>ne</p></li><li><p class='kana'>の</p><p class='romanji'>no</p></li><li><p class='kana'>は</p><p class='romanji'>ha</p></li><li><p class='kana'>ひ</p><p class='romanji'>hi</p></li><li><p class='kana'>ふ</p><p class='romanji'>fu-hu</p></li><li><p class='kana'>へ</p><p class='romanji'>he</p></li><li><p class='kana'>ほ</p><p class='romanji'>ho</p></li><li><p class='kana'>ま</p><p class='romanji'>ma</p></li><li><p class='kana'>み</p><p class='romanji'>mi</p></li><li><p class='kana'>む</p><p class='romanji'>mu</p></li><li><p class='kana'>め</p><p class='romanji'>me</p></li><li><p class='kana'>も</p><p class='romanji'>mo</p></li><li><p class='kana'>や</p><p class='romanji'>ya</p></li><li><p class='kana'>ゆ</p><p class='romanji'>yu</p></li><li><p class='kana'>よ</p><p class='romanji'>yo</p></li><li><p class='kana'>ら</p><p class='romanji'>ra</p></li><li><p class='kana'>り</p><p class='romanji'>ri</p></li><li><p class='kana'>る</p><p class='romanji'>ru</p></li><li><p class='kana'>れ</p><p class='romanji'>re</p></li><li><p class='kana'>ろ</p><p class='romanji'>ro</p></li><li><p class='kana'>わ</p><p class='romanji'>wa</p></li><li><p class='kana'>ゐ</p><p class='romanji'>wi</p></li><li><p class='kana'>ゑ</p><p class='romanji'>we</p></li><li><p class='kana'>を</p><p class='romanji'>wo</p></li><li><p class='kana'>ん</p><p class='romanji'>n-nn</p></li></ul>
                -->
                    </ul>
            </div>

            <a href='#' class='helpOpen'><span class='material-icons-round iconhelp'>info</span></a>
            <a href='#' class='helpClose hidden'><span class='material-icons-round'>close</span></a>
        </section>
    </main>
";
