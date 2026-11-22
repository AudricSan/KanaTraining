// add-Remove difficulty
function selectDificulty(id) {
  let ellement = document.getElementById(id)

  if (ellement.checked) {
    difficulty.push(ellement.id)
    save("difficulty", difficulty)
  } else {
    const index = difficulty.indexOf(ellement.id)

    if (index > -1) {
      difficulty.splice(index, 1)
    }
    save("difficulty", difficulty)
  }

  if (difficulty.length === 1) {
    let ellement = document.getElementById(difficulty[0])
    ellement.setAttribute("disabled", "disabled")
  } else {
    let ellement = document.getElementById(difficulty[0])
    ellement.removeAttribute("disabled", "disabled")
  }

  updateGroupIndicators()
  startGame()
}

// Affiche, sur les groupes repliés (Hiragana/Katakana/Kanji JLPT), combien
// de toggles y sont actifs, avec une tooltip listant lesquels.
function updateGroupIndicators() {
  document.querySelectorAll('.menu-group').forEach((group) => {
    const checked = group.querySelectorAll('input.switch:checked')
    const badge = group.querySelector('.menu-group-badge')
    const summary = group.querySelector('summary')

    if (!badge || !summary) return

    if (checked.length > 0) {
      const labels = Array.from(checked).map((cb) => {
        const label = group.querySelector(`label[for="${cb.id}"]`)
        return label ? label.textContent.trim() : cb.id
      })

      badge.textContent = checked.length
      badge.classList.remove('hidden')
      summary.title = 'Actif : ' + labels.join(', ')
    } else {
      badge.textContent = ''
      badge.classList.add('hidden')
      summary.removeAttribute('title')
    }
  })
}

// Short message under the question (unlocked achievements)
function createNotification(txt) {
  const notif = document.createElement("p")
  notif.innerText = txt

  toast.appendChild(notif)
  setTimeout(() => notif.remove(), 3000)
}

// Report one answer to the server so a logged-in student's XP/streak/score stay in sync,
// and which character it was so weak spots can be drilled more often next time.
function sendScoreUpdate(correct, good, total, type, character) {
  fetch(scoreUrl, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
    },
    body: JSON.stringify({ correct, good, total, type, character }),
  })
    .then((response) => (response.ok ? response.json() : null))
    .then((data) => {
      if (!data) return

      const liveXp = document.getElementById("liveXp")
      const liveStreak = document.getElementById("liveStreak")
      if (liveXp) liveXp.innerText = data.globalXp
      if (liveStreak) liveStreak.innerText = data.streakDays

      if (data.newAchievements) {
        data.newAchievements.forEach((a) => {
          createNotification(a.icon + " Succès débloqué : " + a.name)
        })
      }
    })
    .catch(() => {})
}

//share to Twitter
twitter.addEventListener("click", (event) => {
  var b = twitter.getAttribute("href")

  var good = exercise.good
  var total = exercise.asked

  b =
    "https://twitter.com/intent/tweet?text=" +
    encodeURIComponent(
      "J'ai eu " +
      good +
      " bonnes réponses sur " +
      total +
      " sur KanaTraining. \nTu fais mieux ? https://kana.audricrosier.be"
    )
  twitter.setAttribute("href", b)
})

// Get all kana from the database to generate help
function helpGenerator() {
  const helpTable = []

  difficulty.forEach((e) => {
    helpTable.push(myKana[e])
    helpTable.push(myKanji[e])
  })

  const newhelpTable = helpTable.filter((element) => element !== undefined)

  newhelpTable.forEach((e) => {
    e.forEach((a) => {
      const li = document.createElement("li")
      const helpHiragana = document.createElement("p")
      const helpRomanji = document.createElement("p")

      helpHiragana.classList.add("kana")
      helpRomanji.classList.add("romanji")

      if (a[2] === true || a[2] === false) {
        helpHiragana.lang = 'ja'
        helpHiragana.innerText = a[0]
        helpRomanji.innerText = a[1].split('-').join(' / ')
      } else {
        const kanjiSpan = document.createElement("span")
        kanjiSpan.lang = 'ja'
        kanjiSpan.innerText = a['kanji']
        helpHiragana.appendChild(kanjiSpan)
        helpHiragana.appendChild(document.createTextNode(' : ' + a['translate'].split('-').join(', ')))
        helpRomanji.lang = 'ja'
        helpRomanji.innerText = a['read'].split('-').join('、')
      }

      helpContainer.appendChild(li)
      li.appendChild(helpHiragana)
      li.appendChild(helpRomanji)
    })
  })
}

//Open/Close help
helpOpen.addEventListener('click', event => {
  helpClose.classList.remove('hidden')
  helpOpen.classList.add('hidden')
  helpOpen.setAttribute('aria-expanded', 'true')

  blurbox.classList.add('blur')

  input.setAttribute("disabled", "disabled")
  helpGenerator()
})

helpClose.addEventListener('click', event => {
  helpClose.classList.add('hidden')
  helpOpen.classList.remove('hidden')
  helpOpen.setAttribute('aria-expanded', 'false')

  blurbox.classList.remove('blur')

  input.removeAttribute("disabled", "disabled")
  input.focus()

  helpContainer.innerHTML = ''
})

// Mobile only: shows / hides the exercise settings above the question
navbtn.addEventListener('click', event => {
  if (!nav) return

  const open = toggleFlipFlop()
  nav.classList.toggle('hidden', !open)
  navbtn.setAttribute('aria-expanded', open ? 'true' : 'false')
  menuIcon.textContent = open ? 'expand_less' : 'expand_more'
})

function toggleFlipFlop() {
  flipFlopState = !flipFlopState
  return flipFlopState
}

function handleScreenWidthChange(screenWidth) {
  if (!nav) return

  if (screenWidth.matches) {
    nav.classList.add('hidden');
  } else {
    nav.classList.remove('hidden');
  }
}
// Détection et application automatique du thème système
function applySystemTheme() {
  const themeSwitch = document.getElementById('theme');
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  document.body.classList.toggle('dark-theme', prefersDark);
  if (themeSwitch) themeSwitch.checked = prefersDark;
}
// Appliquer au chargement
applySystemTheme();
// Écouter les changements système
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', applySystemTheme);

handleScreenWidthChange(screenWidth);
screenWidth.addEventListener('change', handleScreenWidthChange);