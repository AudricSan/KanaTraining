let difficulty = []

// Filled by quiz.js from /api/characters (data now lives in the database)
let myKana = {}
let myKanji = {}

let character = document.querySelector('.character')
let kanjitype = document.querySelector('.kanjitype')

const toast = document.getElementById("toast")

let input = document.querySelector('.input')

const twitter = document.querySelector('.twitter-share')

const helpContainer = document.querySelector(".helpContainer")
const helpOpen = document.querySelector('.helpOpen')
const helpClose = document.querySelector('.helpClose')

var flipFlopState = false
const navbtn = document.querySelector('.navbtn')
const nav = document.querySelector('nav')
const blurbox = document.getElementById('blur')

const screenWidth = window.matchMedia('(max-width: 770px)')

const menuIcon = document.querySelector('.menuicon')