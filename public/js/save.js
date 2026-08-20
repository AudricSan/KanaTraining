// transform Local Storage into array
function saveInArray() {
//   console.log("saveInArray");
  if (localStorage !== null) {
    const ls = Object.entries(localStorage);
    return ls;
  }

  return false;
}

// Delete all cookies
function deleteAllCookies() {
  localStorage.clear();
}

// Create a backup in the local storage
function save(name, value) {
  localStorage.setItem(name, value);
}

// Retrieve the backup in the local storage
function getSave() {
  const savedEntries = saveInArray();

  if (savedEntries) {
    if (savedEntries.length === 0) {
      document.getElementById("hiragana").checked = true;
      selectDificulty("hiragana");
    } else {
      savedEntries.forEach((element) => {
        switch (element[0]) {
          case "difficulty":
            const value = element[1];

            if (value !== "") {
              const difTable = value.split(",");

              difTable.forEach((id) => {
                const checkbox = document.getElementById(id);
                if (checkbox) {
                  checkbox.checked = true;
                  selectDificulty(id);
                }
              });
            } else {
              document.getElementById("hiragana").checked = true;
              selectDificulty("hiragana");
            }
            break;

          case "theme":
            const nextTheme = element[1];
            selectTheme(nextTheme);
            break;
        }
      });
    }
  }
}
