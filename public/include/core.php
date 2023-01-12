<div class='check'>

  <input type='checkbox' class='switch' id='hiragana' name='cc' onchange='selectDificulty(this.id)' />
  <label for='hiragana'>Hiragana</label>

  <br>

  <input type='checkbox' class='switch' id='hiraganaCombo' name='cc' onchange='selectDificulty(this.id)' />
  <label for='hiraganaCombo'>Hiragana Combo</label>

  <br>

  <input type='checkbox' class='switch' id='hiraganaDakuon' name='cc' onchange='selectDificulty(this.id)' />
  <label for='hiraganaDakuon'>Hiragana Dakuon</label>

  <br>

  <input type='checkbox' class='switch' id='hiraganaDakuonCombo' name='cc' onchange='selectDificulty(this.id)' />
  <label for='hiraganaDakuonCombo'>Hiragana Dakuon Combo </label>

  <p class='divider'>---------------------------------------</p>

  <input type='checkbox' class='switch' id='katakana' name='cc' onchange='selectDificulty(this.id)' />
  <label for='katakana'>Katakana</label>

  <br>

  <input type='checkbox' class='switch' id='katakanaCombo' name='cc' onchange='selectDificulty(this.id)' />
  <label for='katakanaCombo'>Katakana Combo</label>

  <br>

  <input type='checkbox' class='switch' id='katakanaDakuon' name='cc' onchange='selectDificulty(this.id)' />
  <label for='katakanaDakuon'>Katakana Dakuon</label>

  <br>

  <input type='checkbox' class='switch' id='katakanaDakuonCombo' name='cc' onchange='selectDificulty(this.id)' />
  <label for='katakanaDakuonCombo'>Katakana Dakuon Combo </label>
</div>
</ul>
</div>

<div class='center'>
  <div class='hiragana-symbol'>
    <!-- <p class='test-skills'> Test your kana's skill </p> -->
    <div class='character'></div>

    <span class='material-icons-round iconkeyboard'>keyboard_double_arrow_right</span>
    <span class='material-icons-round iconkeyboard'>keyboard</span>
    <span class='material-icons-round iconkeyboard'>keyboard_double_arrow_left</span>

    <form class='forms' action='#' onsubmit='checkAnswerd(); return false' autocomplete='off'>
      <input name='input' type='text' class='input' autofocus maxlength='3'>
      <input type='submit'>
    </form>
  </div>
</div>

<div class='center2'>
  <div id='notification'>
    <!--
      <div class='toast'>Nop bad answerd, the good one is <span>'A'</span> </div>
    -->
  </div>
</div>

<div class='right'>
  <a href='#' class='help'>HELP
    <span class='material-icons-round iconhelp'>info</span>
  </a>

  <a class='helpcloser hidden'><span class='material-icons-round'>close</span></a>
</div>

<div class='down'>
  <div>
    <span class='sc'>Your score</span>
    <span class='fa-solid fa-star'></span>
    <span class='good'>0</span>/<span class='total'>0</span>
  </div>

  <a href='' target='_blank' class='twitter-share'>
    <i class='fa fa-twitter'> </i>
    <span>Share on Twitter</span>
  </a>

  <div class='author'>
    <a href='https://twitter.com/AudricSan' target='_blank'>
      <span class='fa-solid fa-user'></span>
      by Audric_San
    </a>

    <p> | </p>

    <a href='https://github.com/AudricSan/KanaTraining' target='_blank' class='git'>
      The github project
      <span class='fab fa-github icongit'></span>
    </a>
  </div>
</div>
</div> <!-- End of content -->

<!-- HELP GENERATOR -->
<div id='help' class='hidden'>
  <div id='help-spawner'>
  </div>
</div>
<!-- END HELP -->