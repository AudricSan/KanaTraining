<?php
$liveStatsHtml = '';

if (!empty($_SESSION['student_id'])) {
    $env = new \Kanatraining\env();
    $studentDAO = new \Kanatraining\DAO\StudentDAO(\Kanatraining\Database::get($env));
    $student = $studentDAO->find((int) $_SESSION['student_id']);

    if ($student !== null) {
        $xp = (int) $student['student_GlobalXp'];
        $streak = (int) $student['student_StreakDays'];
        $liveStatsHtml = "<div class='live-stats' id='liveStats' aria-live='polite'>
            <span class='material-icons-round'>bolt</span> <span id='liveXp'>{$xp}</span> XP
            <span>🔥</span> <span id='liveStreak'>{$streak}</span> j
        </div>";
    }
}

echo "
    <main>
        <section class='_1'>
            <nav class=''>
                <details class='menu-group' open>
                    <summary>Hiragana<span class='menu-group-badge hidden' aria-hidden='true'></span></summary>
                    <div class='menu-group-body'>
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
                    </div>
                </details>

                <details class='menu-group'>
                    <summary>Katakana<span class='menu-group-badge hidden' aria-hidden='true'></span></summary>
                    <div class='menu-group-body'>
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
                        </form>
                    </div>
                </details>

                <details class='menu-group'>
                    <summary>Kanji JLPT<span class='menu-group-badge hidden' aria-hidden='true'></span></summary>
                    <div class='menu-group-body'>
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
                    </div>
                </details>
            </nav>
        </section>

        <section class='_2'>
            <h2 class='character' lang='ja' aria-live='polite'> あ </h2>
            <h3 class='kanjitype' aria-live='polite'> read </h3>

            <form class='forms' action='#' onsubmit='checkAnswerd(); return false' autocomplete='off'>

                <label class='sr-only' for='input'>Réponse (romaji)</label>
                <input id='input' name='input' type='text' class='input' autofocus maxlength='3' />

                <div id='toast' role='status'></div>

                <div class='stats-row'>
                    <div class='score'>
                        <span>Votre score</span>
                        <span class='material-icons-round'>star</span>
                        <span class='good'>0</span>/<span class='total'>0</span>
                    </div>

                    {$liveStatsHtml}
                </div>
            </form>
        </section>

        <section class='_3'>

            <div class='helpSpawner' id='helpSpawner' role='dialog' aria-label='Table des caractères'>
                <ul class='helpContainer'>
                    </ul>
            </div>

            <a href='#' class='helpOpen' aria-expanded='false' aria-controls='helpSpawner' aria-label=\"Afficher l'aide\"><span class='material-icons-round iconhelp'>info</span></a>
            <a href='#' class='helpClose hidden' aria-label=\"Fermer l'aide\"><span class='material-icons-round'>close</span></a>
        </section>
    </main>
";
