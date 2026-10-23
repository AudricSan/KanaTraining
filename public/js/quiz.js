// Loads the kana / kanji of every set from the server, then restores the
// saved difficulties and theme (getSave) and starts the first question.
window.addEventListener('load', () => {
  fetch(charactersUrl, { headers: { Accept: 'application/json' } })
    .then((response) => response.json())
    .then((data) => {
      myKana = data.kana
      myKanji = data.kanji

      getSave()

      // Nothing selected yet (first visit, or only the theme was saved): default to Hiragana
      if (difficulty.length === 0) {
        document.getElementById('hiragana').checked = true
        selectDificulty('hiragana')
      }
    })
})
