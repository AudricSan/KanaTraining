// add-Remove difficulty
function selectDificulty(id) {
  let ellement = document.getElementById(id)

  if (ellement.checked) {
    dificulty.push(ellement.id)
    save("dificulty", dificulty)
  } else {
    const index = dificulty.indexOf(ellement.id)

    if (index > -1) {
      dificulty.splice(index, 1)
    }
    save("dificulty", dificulty)
  }

  if (dificulty.length === 1) {
    let ellement = document.getElementById(dificulty[0])
    ellement.setAttribute("disabled", "disabled")
  } else {
    let ellement = document.getElementById(dificulty[0])
    ellement.removeAttribute("disabled", "disabled")
  }

  startGame()
}

function startGame() {
  const int = getRandomInteger(0, dificulty.length - 1)
  switch (dificulty[int]) {
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

function getRandom(type) {
  let selected

  while (selected != "undefined") {
    let selected = myKana[type]
    let nb = getRandomInteger(0, selected.length - 1)

    if (selected != undefined) {
      selected = selected[nb]
      if (selected[2] == true) {
        return selected
      }
    }
  }
}

function getRandomkanji(type) {
  let selected

  while (selected != "undefined") {
    let selected = myKanji[type]
    let nb = getRandomInteger(0, selected.length - 1)
    selected = selected[nb]

    selected[0] = selected["kanji"]

    var randomValue = Math.random();
    if (randomValue < 0.5) {
      selected[1] = selected["read"]
      selected[2] = "read"
    } else {
      selected[1] = selected["translate"]
      selected[2] = "translate"
    }

    return selected
  }
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
  if (document.getElementsByClassName("toast").length === 0) {
    let a = input.value.toLowerCase()
    let b = selectedCharacter[1]
    c = b.split("-")
    d = c.indexOf(a)

    if (d != -1) {
      e = c[d]
      if (a === e) {
        input.value = ""
        goodAnswerd()
      } else {
        input.value = ""
        badAnswerd()
      }
    } else {
      input.value = ""
      badAnswerd()
    }
  } else {
    return
  }

  a = parseInt(myPoint.innerText)
  b = parseInt(totalPoint.innerText)

  score = a + "/" + b
  save("score", score)
  createCookie("score", score, 1)

  pr = (a / b) * 100
  save("ratio", pr)

  best = localStorage.getItem("bestRatio")
  if (pr >= best) {
    save("bestRatio", pr)
    save("best", score)
    createCookie("best", score, 1)
  }
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
  a = parseInt(myPoint.innerText)
  a += 1
  myPoint.innerText = a.toString()

  incrementTotal()
}

// increment total
function incrementTotal() {
  b = parseInt(totalPoint.innerText)
  b += 1

  totalPoint.innerText = b.toString()

  setTimeout(() => {
    startGame()
  }, delay)

  a = myPoint.innerText
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
  let helpTable = []

  dificulty.forEach((e) => {
    helpTable.push(myKana[e])
    helpTable.push(myKanji[e])

    newhelpTable = helpTable.filter(function (element) {
      return element !== undefined;
    });
  })

  helpContainer.removeChild

  newhelpTable.forEach((e) => {
    e.forEach((a) => {
      const li = document.createElement("li")
      const helpHiragana = document.createElement("p")
      const helpRomanji = document.createElement("p")

      helpHiragana.classList.add("kana")
      helpRomanji.classList.add("romanji")

      if (a[2] === true || a[2] === false) {
        helpHiragana.innerText = a[0]
        helpRomanji.innerText = a[1]
      } else {
        texta = a['kanji'] + ' || ' + a['translate']
        helpHiragana.innerText = texta
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
  // console.log("help Opened")

  helpClose.classList.remove('hidden')
  helpOpen.classList.add('hidden')

  // blurbox.classList.add('blur')

  input.setAttribute("disabled", "disabled")
  helpGenerator()
})

helpClose.addEventListener('click', event => {
  // console.log("help Closed")

  helpClose.classList.add('hidden')
  helpOpen.classList.remove('hidden')

  // blurbox.classList.remove('blur')

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
    nav.classList.add('hidden')
    nav.classList.remove('hidden')
  } else {
    nav.classList.remove('hidden')
  }
}

handleScreenWidthChange(screenWidth)
screenWidth.addListener(handleScreenWidthChange);