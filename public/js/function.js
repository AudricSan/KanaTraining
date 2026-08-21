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

  startGame()
}

function startGame() {
  const int = getRandomInteger(0, difficulty.length - 1)
  currentType = difficulty[int]
  switch (currentType) {
    case "hiragana":
      selectedCharacter = getRandom("hiragana")
      break

    case "hiraganaCombo":
      selectedCharacter = getRandom("hiraganaCombo")
      break

    case "hiraganaDakuon":
      selectedCharacter = getRandom("hiraganaDakuon")
      break

    case "hiraganaDakuonCombo":
      selectedCharacter = getRandom("hiraganaDakuonCombo")
      break

    case "katakana":
      selectedCharacter = getRandom("katakana")
      break

    case "katakanaCombo":
      selectedCharacter = getRandom("katakanaCombo")
      break

    case "katakanaDakuon":
      selectedCharacter = getRandom("katakanaDakuon")
      break

    case "katakanaDakuonCombo":
      selectedCharacter = getRandom("katakanaDakuonCombo")
      break

    case "kanjiN5":
      selectedCharacter = getRandomkanji("kanjiN5")
      break

    case "kanjiN4":
      selectedCharacter = getRandomkanji("kanjiN4")
      break

    case "kanjiN3":
      selectedCharacter = getRandomkanji("kanjiN3")
      break

    case "kanjiN2":
      selectedCharacter = getRandomkanji("kanjiN2")
      break

    case "kanjiN1":
      selectedCharacter = getRandomkanji("kanjiN1")
      break
  }

  character.innerHTML = selectedCharacter[0]
  kanjitype.innerHTML = ''

  if (selectedCharacter[2] == 'translate' || selectedCharacter[2] == 'read') {
    kanjitype.innerHTML = selectedCharacter[2]
    input.maxLength = 20
    kanjitype.classList.remove('hidden')
  }

  if (kanjitype.innerText === '') {
    kanjitype.classList.add('hidden')
  }

}

function getRandomInteger(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min
}

// Picks a random entry, favoring ones the student has answered wrong before
// (weakCharacters, injected server-side — empty/undefined for anonymous play,
// which keeps the pick uniform, same as before).
function weightedPick(list, keyFn, type) {
  const weights = (typeof weakCharacters !== "undefined" && weakCharacters[type]) || {}
  const pool = []

  list.forEach((entry) => {
    const weight = 1 + Math.min(weights[keyFn(entry)] || 0, 4)
    for (let i = 0; i < weight; i++) pool.push(entry)
  })

  return pool[getRandomInteger(0, pool.length - 1)]
}

function getRandom(type) {
  const list = myKana[type].filter((entry) => entry[2] === true)
  return weightedPick(list, (entry) => entry[0], type)
}

function getRandomkanji(type) {
  const entry = weightedPick(myKanji[type], (e) => e.kanji, type)
  const useReading = Math.random() < 0.5

  return [
    entry.kanji,
    useReading ? entry.read : entry.translate,
    useReading ? "read" : "translate",
  ]
}

// notification
function createNotification(txt, c) {
  const notif = document.createElement("p")
  const correct = document.createElement("span")

  notif.innerText = txt + " "

  if (c != undefined) {
    correct.innerText = '"' + c + '"'
  }

  toast.appendChild(notif)
  notif.appendChild(correct)

  input.setAttribute("disabled", "disabled")
  setTimeout(() => {
    notif.remove()
    input.removeAttribute("disabled", "disabled")
    input.focus()
  }, delay)
}

// Answerd checker
function checkAnswerd() {
  let correct
  const answeredCharacter = selectedCharacter[0]
  const answeredType = currentType

  if (document.getElementsByClassName("toast").length === 0) {
    const answer = input.value.toLowerCase()
    const validAnswers = selectedCharacter[1].split("-")
    correct = validAnswers.includes(answer)

    input.value = ""
    if (correct) {
      goodAnswerd()
    } else {
      badAnswerd()
    }
  } else {
    return
  }

  const good = parseInt(myPoint.innerText)
  const total = parseInt(totalPoint.innerText)

  const score = good + "/" + total
  save("score", score)
  createCookie("score", score, 1)

  const ratio = (good / total) * 100
  save("ratio", ratio)

  const best = localStorage.getItem("bestRatio")
  if (ratio >= best) {
    save("bestRatio", ratio)
    save("best", score)
    createCookie("best", score, 1)
  }

  if (isLoggedIn) {
    sendScoreUpdate(correct, good, total, answeredType, answeredCharacter)
  }
}

// Report one answer to the server so a logged-in student's XP/streak/score stay in sync,
// and which character it was so weak spots can be drilled more often next time.
function sendScoreUpdate(correct, good, total, type, character) {
  fetch("/api/score", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
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

// If is good
function goodAnswerd() {
  createNotification("good ! " + "+1")
  incrementGood()
}

// if is not!
function badAnswerd() {
  let a = selectedCharacter[1]
  createNotification("Nop bad answerd, the good one is", a)
  incrementTotal()
}

// increment point
function incrementGood() {
  let good = parseInt(myPoint.innerText)
  good += 1
  myPoint.innerText = good.toString()

  incrementTotal()
}

// increment total
function incrementTotal() {
  let total = parseInt(totalPoint.innerText)
  total += 1

  totalPoint.innerText = total.toString()

  setTimeout(() => {
    startGame()
  }, delay)
}

// Cookies
function createCookie(string, score, time) {
  var date = new Date()
  date.setDate(date.getDate() + 1)
  var dateString = date.toGMTString()
  document.cookie = string + "=" + score
}

//share to Twitter
twitter.addEventListener("click", (event) => {
  var b = twitter.getAttribute("href")

  var good = myPoint.textContent
  var total = totalPoint.textContent

  b =
    "https://twitter.com/intent/tweet?text=" +
    encodeURIComponent(
      "I just scored " +
      good +
      " out of " +
      total +
      " in my Hiragana Training. \nCan you do better? https://kana.audricrosier.be"
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
        helpRomanji.innerText = a[1]
      } else {
        const kanjiSpan = document.createElement("span")
        kanjiSpan.lang = 'ja'
        kanjiSpan.innerText = a['kanji']
        helpHiragana.appendChild(kanjiSpan)
        helpHiragana.appendChild(document.createTextNode(' || ' + a['translate']))
        helpRomanji.lang = 'ja'
        helpRomanji.innerText = a['read']
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

navbtn.addEventListener('click', event => {
  if (flipFlopState) {
    nav.classList.add('hidden')
    kanjitype.classList.remove('hidden')
    menuIcon.classList.remove('fa-caret-up')
    menuIcon.classList.add('fa-caret-down')
  } else {
    nav.classList.remove('hidden')
    kanjitype.classList.add('hidden')
    menuIcon.classList.remove('fa-caret-down')
    menuIcon.classList.add('fa-caret-up')
  }

  toggleFlipFlop()
})

function toggleFlipFlop() {
  flipFlopState = !flipFlopState
  return flipFlopState
}

function handleScreenWidthChange(screenWidth) {
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