let dificulty = []

const myKana = new kana()
const myKanji = new kanji()

let character = document.querySelector('.character')
let kanjitype = document.querySelector('.kanjitype')

const toast = document.getElementById("toast")
const delay = 1000

const form = document.querySelector('form');
let input = document.querySelector('.input')
let myPoint = document.querySelector('.good')
let totalPoint = document.querySelector('.total')

const twitter = document.querySelector('.twitter-share')

const helpContainer = document.querySelector(".helpContainer")
const helpOpen = document.querySelector('.helpOpen')
const helpClose = document.querySelector('.helpClose')

var flipFlopState = false
const navbtn = document.querySelector('.navbtn')
const nav = document.querySelector('nav')

const screenWidth = window.matchMedia('(max-width: 770px)')
// screenWidth.addListener(handleScreenWidthChange)

const menuIcon = document.querySelector('.menuicon')