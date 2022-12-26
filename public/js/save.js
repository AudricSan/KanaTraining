function saveInArray() {
    if (localStorage !== null) {
        const ls = Object.entries(localStorage);
        return ls;
    }

    return false;
}

function deleteAllCookies() {
    localStorage.clear();
}

function save(name, value) {
    localStorage.setItem(name, value);
}

function getSave() {
    let save = saveInArray();

    if (save) {
        save.forEach(element => {
            // console.log(element);

            switch (element[0]) {
                case "dificulty":
                    element = element[1];

                    if (element != '') {
                        let split = element.split(',');
                        difTable = split

                        difTable.forEach(element => {
                            document.getElementById(element).checked = true
                            selectDificulty(element);
                        });

                    }else{
                        document.getElementById('hiragana').checked = true
                        selectDificulty('hiragana');                    }
                    break;

                case "theme":
                    NextTheme = element[1];
                    selectTheme(NextTheme);
                    break;
                    
                case "score":
                    // console.log('score');
                break;
                    
                case "best":
                    // console.log('best');
                    break;

                default:
                    // console.log('default');
                    break;
            }
        });
    }
}

// Function to create the cookie
function createCookie(name, value, days) {
    var expires;
      
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else {
        expires = "";
    }
      
    document.cookie = escape(name) + "=" + 
        escape(value) + expires + "; path=/";
}