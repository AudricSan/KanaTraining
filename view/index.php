<?php
echo "
    <main>
        <section class='_1'>
            <nav class=''>";

$liveStatsHtml = '';

if (!empty($_SESSION['student_id'])) {
    echo "<a href='/student' class='fa-solid fa-person'></span> Mon profil </a>";
    echo "<a href='/classement' class='fa-solid fa-ranking-star'></span> Classement </a>";
    echo "<a href='/logout' class='fa-solid fa-right-from-bracket'></span> Déconnexion </a>";

    $env = new \Kanatraining\env();
    $studentDAO = new \Kanatraining\DAO\StudentDAO(\Kanatraining\Database::get($env));
    $student = $studentDAO->find((int) $_SESSION['student_id']);

    if ($student !== null) {
        $xp = (int) $student['student_GlobalXp'];
        $streak = (int) $student['student_StreakDays'];
        $liveStatsHtml = "<div class='live-stats' id='liveStats' aria-live='polite'>
            <span class='fa-solid fa-bolt'></span> <span id='liveXp'>{$xp}</span> XP
            <span>🔥</span> <span id='liveStreak'>{$streak}</span> j
        </div>";
    }
} else {
    echo "<a href='/login' class='fa-solid fa-person'></span> Connexion </a>";
    echo "<a href='/classement' class='fa-solid fa-ranking-star'></span> Classement </a>";
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
                        <input type='checkbox' class='switch' id='kanjiN5' name='cc' onchange='selectDificulty(this.id)' />
                        <label for='kanjiN5'>Kanji JLPT N5</label>
                    </form>

                    <form action=''>
                        <input type='checkbox' class='switch' id='kanjiN4' name='cc' onchange='selectDificulty(this.id)' />
                        <label for='kanjiN4'>Kanji JLPT N4</label>
                    </form>

                    <form action=''>
                        <input type='checkbox' class='switch' id='kanjiN3' name='cc' onchange='selectDificulty(this.id)' />
                        <label for='kanjiN3'>Kanji JLPT N3</label>
                    </form>

                    <form action=''>
                        <input type='checkbox' class='switch' id='kanjiN2' name='cc' onchange='selectDificulty(this.id)' />
                        <label for='kanjiN2'>Kanji JLPT N2</label>
                    </form>


                    <form action=''>
                        <input type='checkbox' class='switch' id='kanjiN1' name='cc' onchange='selectDificulty(this.id)' />
                        <label for='kanjiN1'>Kanji JLPT N1</label>
                    </form>
                </ul>
            </nav>
        </section>

        <section class='_2'>
            <h2 class='character' lang='ja' aria-live='polite'> あ </h2>
            <h3 class='kanjitype' aria-live='polite'> read </h3>

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

                {$liveStatsHtml}

                <div id='toast' role='status'>
                   <!--
                   <p>
                    <span>
                        coucou
                    </span>
                    </p>
                    -->
                </div>

                <label class='sr-only' for='input'>Réponse (romaji)</label>
                <input id='input' name='input' type='text' class='input' autofocus maxlength='3' />
            </form>
        </section>

        <section class='_3'>

            <div class='helpSpawner' id='helpSpawner' role='dialog' aria-label='Table des caractères'>
                <ul class='helpContainer'>
                  <!--  <li><p class='kana'>あ</p><p class='romanji'>a</p></li><li><p class='kana'>い</p><p class='romanji'>i</p></li><li><p class='kana'>う</p><p class='romanji'>u</p></li><li><p class='kana'>え</p><p class='romanji'>e</p></li><li><p class='kana'>お</p><p class='romanji'>o</p></li><li><p class='kana'>か</p><p class='romanji'>ka</p></li><li><p class='kana'>き</p><p class='romanji'>ki</p></li><li><p class='kana'>く</p><p class='romanji'>ku</p></li><li><p class='kana'>け</p><p class='romanji'>ke</p></li><li><p class='kana'>こ</p><p class='romanji'>ko</p></li><li><p class='kana'>さ</p><p class='romanji'>sa</p></li><li><p class='kana'>し</p><p class='romanji'>shi</p></li><li><p class='kana'>す</p><p class='romanji'>su</p></li><li><p class='kana'>せ</p><p class='romanji'>se</p></li><li><p class='kana'>そ</p><p class='romanji'>so</p></li><li><p class='kana'>た</p><p class='romanji'>ta</p></li><li><p class='kana'>ち</p><p class='romanji'>chi-ti-tsi</p></li><li><p class='kana'>つ</p><p class='romanji'>tsu-tu</p></li><li><p class='kana'>て</p><p class='romanji'>te</p></li><li><p class='kana'>と</p><p class='romanji'>to</p></li><li><p class='kana'>な</p><p class='romanji'>na</p></li><li><p class='kana'>に</p><p class='romanji'>ni</p></li><li><p class='kana'>ぬ</p><p class='romanji'>nu</p></li><li><p class='kana'>ね</p><p class='romanji'>ne</p></li><li><p class='kana'>の</p><p class='romanji'>no</p></li><li><p class='kana'>は</p><p class='romanji'>ha</p></li><li><p class='kana'>ひ</p><p class='romanji'>hi</p></li><li><p class='kana'>ふ</p><p class='romanji'>fu-hu</p></li><li><p class='kana'>へ</p><p class='romanji'>he</p></li><li><p class='kana'>ほ</p><p class='romanji'>ho</p></li><li><p class='kana'>ま</p><p class='romanji'>ma</p></li><li><p class='kana'>み</p><p class='romanji'>mi</p></li><li><p class='kana'>む</p><p class='romanji'>mu</p></li><li><p class='kana'>め</p><p class='romanji'>me</p></li><li><p class='kana'>も</p><p class='romanji'>mo</p></li><li><p class='kana'>や</p><p class='romanji'>ya</p></li><li><p class='kana'>ゆ</p><p class='romanji'>yu</p></li><li><p class='kana'>よ</p><p class='romanji'>yo</p></li><li><p class='kana'>ら</p><p class='romanji'>ra</p></li><li><p class='kana'>り</p><p class='romanji'>ri</p></li><li><p class='kana'>る</p><p class='romanji'>ru</p></li><li><p class='kana'>れ</p><p class='romanji'>re</p></li><li><p class='kana'>ろ</p><p class='romanji'>ro</p></li><li><p class='kana'>わ</p><p class='romanji'>wa</p></li><li><p class='kana'>ゐ</p><p class='romanji'>wi</p></li><li><p class='kana'>ゑ</p><p class='romanji'>we</p></li><li><p class='kana'>を</p><p class='romanji'>wo</p></li><li><p class='kana'>ん</p><p class='romanji'>n-nn</p></li></ul>
                -->
                    </ul>
            </div>

            <a href='#' class='helpOpen' aria-expanded='false' aria-controls='helpSpawner' aria-label=\"Afficher l'aide\"><span class='material-icons-round iconhelp'>info</span></a>
            <a href='#' class='helpClose hidden' aria-label=\"Fermer l'aide\"><span class='material-icons-round'>close</span></a>
        </section>
    </main>
";
