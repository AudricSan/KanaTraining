<?php
$link = $_SESSION['TLink'];

echo "
    <main>
        <section class='_1'>
            <nav>
                <ul>";

if (!empty($_SESSION['email'])) {
    echo "<a href='/student' class='fa-solid fa-person'></span> My student Page </a>";
} else {
    // echo "<a href='$link' class='fa-solid fa-person'></span> Connection </a>";
    echo "<a href='JavaScript:createNotification(\"in as future update\")' class='fa-solid fa-person'></span> Connection </a>";
}

echo "

                    <p class='divider'>---------------------------------------</p>

                    <form action=''>
                        <input type='checkbox' class='switch theme' id='theme' name='cc' onchange='selectTheme()' />
                        <label for='theme'>theme-color</label>
                    </form>

                    <p class='divider'>---------------------------------------</p>

                    <form action=''>
                        <input type='checkbox' class='switch' id='hiragana' name='cc' onchange='selectDificulty(this.id)' checked/>
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
                    </form>
                </ul>
            </nav>
        </section>

        <section class='_2'>
            <h2 class='character'> あ </h2>

            <div>
                <span class='material-icons-round iconkeyboard'>keyboard_double_arrow_right</span>
                <span class='material-icons-round iconkeyboard'>keyboard</span>
                <span class='material-icons-round iconkeyboard'>keyboard_double_arrow_left</span>
            </div>

            <form class='forms' action='#' onsubmit='checkAnswerd(); return false' autocomplete='off'>

                <div>
                    <span>Votre score</span>
                    <span class='fa-solid fa-star'></span>
                    <span class='good'>0</span>/<span class='total'>0</span>
                </div>

                <div id='toast'>
                </div>

                <input name='input' type='text' class='input' autofocus maxlength='3' />
            </form>
        </section>

        <section class='_3'>

            <div class='helpSpawner'>
                <ul class='helpContainer'>
                </ul>
            </div>

            <a href='#' class='helpOpen'><span class='material-icons-round iconhelp'>info</span></a>
            <a href='#' class='helpClose hidden'><span class='material-icons-round'>close</span></a>
        </section>
    </main>
";
