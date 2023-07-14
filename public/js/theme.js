//Theme Variable
const head = document.getElementsByTagName("HEAD")[0];
var link = document.createElement("link");

link.rel = "stylesheet";
link.type = "text/css";
link.href = "css/darktheme.css";

//Change Theme
function selectTheme(NextTheme) {
  switch (NextTheme) {
    case "light":
      // console.log('in light');
      if (head.contains(link)) {
        head.removeChild(link);
      }
      break;

    case "dark":
      // console.log('in dark');
      head.appendChild(link);

      document.getElementById("theme").checked = true;
      break;

    default:
      // console.log('default (Flip/Flop)');
      if (head.contains(link)) {
        head.removeChild(link);
        NextTheme = "light";
      } else {
        head.appendChild(link);
        NextTheme = "dark";

        document.getElementById("theme").checked = true;
      }

      // console.log(NextTheme);
      save("theme", NextTheme);
      break;
  }
}
