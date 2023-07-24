// add-Remove difficulty
function selectDificulty(id) {
  let ellement = document.getElementById(id);

  if (ellement.checked) {
    dificulty.push(ellement.id);
    save("dificulty", dificulty);
  } else {
    const index = dificulty.indexOf(ellement.id);

    if (index > -1) {
      dificulty.splice(index, 1);
    }
    save("dificulty", dificulty);
  }

  if (dificulty.length === 1) {
    let ellement = document.getElementById(dificulty[0]);
    ellement.setAttribute("disabled", "disabled");
  } else {
    let ellement = document.getElementById(dificulty[0]);
    ellement.removeAttribute("disabled", "disabled");
  }

  startGame();
}

function startGame() {
  const int = getRandomInteger(0, dificulty.length - 1);
  switch (dificulty[int]) {
    case "hiragana":
      selectedCharacter = getRandom("hiragana");
      break;

    case "hiraganaCombo":
      selectedCharacter = getRandom("hiraganaCombo");
      break;

    case "hiraganaDakuon":
      selectedCharacter = getRandom("hiraganaDakuon");
      break;

    case "hiraganaDakuonCombo":
      selectedCharacter = getRandom("hiraganaDakuonCombo");
      break;

    case "katakana":
      selectedCharacter = getRandom("katakana");
      break;

    case "katakanaCombo":
      selectedCharacter = getRandom("katakanaCombo");
      break;

    case "katakanaDakuon":
      selectedCharacter = getRandom("katakanaDakuon");
      break;

    case "katakanaDakuonCombo":
      selectedCharacter = getRandom("katakanaDakuonCombo");
      break;
  }

  character.innerHTML = selectedCharacter[0];
}

function getRandomInteger(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min;
}

function getRandom(type) {
  let selected;

  while (selected != "undefined") {
    let selected = Hiragana[type];
    let nb = getRandomInteger(0, selected.length - 1);

    if (selected != undefined) {
      selected = selected[nb];
      if (selected[2] == true) {
        return selected;
      }
    }
  }
}

// notification
function createNotification(txt, c) {
  const notif = document.createElement("p");
  const correct = document.createElement("span");

  notif.innerText = txt + " ";

  if (c != undefined) {
    correct.innerText = '"' + c + '"';
  }

  toast.appendChild(notif);
  notif.appendChild(correct);

  console.log(input);
  input.setAttribute("disabled", "disabled");
  setTimeout(() => {
    notif.remove();
    input.removeAttribute("disabled", "disabled");
    input.focus();
  }, delay);
}

// Answerd checker
function checkAnswerd() {
  if (document.getElementsByClassName("toast").length === 0) {
    let a = input.value.toLowerCase();
    let b = selectedCharacter[1];
    c = b.split("-");
    d = c.indexOf(a);

    if (d != -1) {
      e = c[d];
      if (a === e) {
        input.value = "";
        goodAnswerd();
      } else {
        input.value = "";
        badAnswerd();
      }
    } else {
      input.value = "";
      badAnswerd();
    }
  } else {
    return;
  }

  a = parseInt(myPoint.innerText);
  b = parseInt(totalPoint.innerText);

  score = a + "/" + b;
  save("score", score);
  createCookie("score", score, 1);

  pr = (a / b) * 100;
  save("ratio", pr);

  best = localStorage.getItem("bestRatio");
  if (pr >= best) {
    save("bestRatio", pr);
    save("best", score);
    createCookie("best", score, 1);
  }
}

// If is good
function goodAnswerd() {
  createNotification("good ! " + "+1");
  incrementGood();
}

// if is not!
function badAnswerd() {
  let a = selectedCharacter[1];
  createNotification("Nop bad answerd, the good one is", a);
  incrementTotal();
}

// increment point
function incrementGood() {
  a = parseInt(myPoint.innerText);
  a += 1;
  myPoint.innerText = a.toString();

  incrementTotal();
}

// increment total
function incrementTotal() {
  b = parseInt(totalPoint.innerText);
  b += 1;

  totalPoint.innerText = b.toString();

  setTimeout(() => {
    startGame();
  }, delay);

  a = myPoint.innerText;
}

// Cookies
function createCookie(string, score, time) {
  var date = new Date();
  date.setDate(date.getDate() + 1);
  var dateString = date.toGMTString();
  document.cookie = "best=" + score + "; expires=" + dateString;
}

//share to Twitter
twitter.addEventListener("click", (event) => {
  var b = twitter.getAttribute("href");

  var good = myPoint.textContent;
  var total = totalPoint.textContent;

  b =
    "https://twitter.com/intent/tweet?text=" +
    encodeURIComponent(
      "I just scored " +
        good +
        " out of " +
        total +
        " in my Hiragana Training. \nCan you do better? https://kana.audricrosier.be"
    );
  twitter.setAttribute("href", b);
});

// // Get all kana from the database to generate help
// function getAllKana() {
//     let helpTable = [];

//     dificulty.forEach(e => {
//         helpTable.push(hiragana[e]);
//     });

//     console.log(helpTable);
//     console.log(dificulty);

//     const ul = document.createElement('ul');
//     ul.classList.add("items");

//     helpspawner.appendChild(ul);

//     helpTable.forEach(e => {
//         e.forEach(a => {
//             const li = document.createElement('li');
//             const helpHiragana = document.createElement("div");
//             const helpRomanji = document.createElement('div');

//             helpHiragana.classList.add("kana");
//             helpRomanji.classList.add("romanji");

//             helpHiragana.innerText = a[0];
//             helpRomanji.innerText = a[1];

//             ul.appendChild(li);
//             li.appendChild(helpHiragana);
//             li.appendChild(helpRomanji);
//         });
//     });
// }

// //Open/Close help
// helpbtn.addEventListener('click', event => {
//     console.log("help Opened");

//     helpContainer.classList.remove('hidden');
//     closer.classList.remove('hidden');
//     helpbtn.classList.add('hidden');

//     getAllKana();
// })

// closer.addEventListener('click', event => {
//     console.log("help Closed");

//     helpContainer.classList.add('hidden');
//     closer.classList.add('hidden');
//     helpbtn.classList.remove('hidden');

//     todel = helpspawner.children
//     helpspawner.removeChild(todel[0]);
// })
